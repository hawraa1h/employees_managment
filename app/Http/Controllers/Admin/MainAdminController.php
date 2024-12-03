<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Policy;
use App\Models\Setting;
use App\Traits\UploadFile;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DB;
use Illuminate\Cache\RateLimiter;
use Illuminate\Support\Facades\RateLimiter as RateLimiterFacade;
use Illuminate\Support\Str;

class MainAdminController extends Controller
{
    use UploadFile;
    public function home(Request $request)
    {
        // Get counts for employees, roles, permissions, and departments
        $employeeCount = \App\Models\Employee::count();
        $roleCount = \App\Models\Role::count();
        $permissionCount = \App\Models\Permission::count();
        $departmentCount = Department::count();

        // Get departments with employee counts for chart
        $departmentsWithEmployeeCount = Department::withCount('employees')->get();

        // Prepare chart data for departments with employee counts
        $departmentChartData = $departmentsWithEmployeeCount->pluck('employees_count', 'name');


        $employee = auth()->user();
        if ($employee->id == 1 || hasRole('admin')) {
            $tasks = Policy::with(['department', 'reviewer', 'auditor'])->get();
        } else {
            $tasks = Policy::with(['department', 'reviewer', 'auditor'])
                ->whereHas('employees', function ($query) use ($employee) {
                    $query->where('employee_id', $employee->id);
                })
                ->get();
        }
        if (hasRole('normal')) {
            $unreviewedCount = Policy::whereNull('notes_by_employee')->count();
        } else {
            $unreviewedCount = $tasks->whereNull('reviewed_at')->count();
        }


        return view('admin.index', [
            'employeeCount' => $employeeCount,
            'roleCount' => $roleCount,
            'permissionCount' => $permissionCount,
            'departmentCount' => $departmentCount,
            'departmentChartData' => $departmentChartData,
            'tasks' => $tasks,
            'unreviewedCount' => $unreviewedCount,

        ]);
    }

    private function getMonthlyCreationData($model)
    {
        return \DB::table($model)
            ->select(\DB::raw('count(id) as count, DATE_FORMAT(created_at, "%Y-%m") as month'))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->pluck('count', 'month')
            ->all();
    }


    public function getFormLogin()
    {
        return view('admin.login');
    }

    public function postLogin(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);
        // Check if login is with email or employee number
        $credentials = filter_var($request->email, FILTER_VALIDATE_EMAIL)
            ? ['email' => $request->email, 'password' => $request->password]
            : ['emp_number' => $request->email, 'password' => $request->password];
        // Handle rate limiting
        if (RateLimiterFacade::tooManyAttempts($this->throttleKey($request), 3)) {
            $seconds = RateLimiterFacade::availableIn($this->throttleKey($request));
            toastr()->error(__('لقد تجاوزت عدد المحاولات المسموح بها. يرجى المحاولة بعد :seconds ثانية.', ['seconds' => $seconds]));
            return back()->withInput();
        }
        if (Auth::guard()->attempt($credentials)) {
            $employee = Auth::guard()->user();
            $setting = Setting::first();
           if ($setting && $employee->updated_password_at) {
               $passwordExpirationDate = $employee->updated_password_at->addDays($setting->password_period);
               if (now()->greaterThan($passwordExpirationDate)) {
                   toastr()->warning(__('انتهت صلاحية كلمة المرور الخاصة بك. يرجى تحديثها.'));
                   return redirect()->route('main_admin.password_update');
               }
           }
            RateLimiterFacade::clear($this->throttleKey($request));
            return redirect()->route('main_admin.home');
        }
        RateLimiterFacade::hit($this->throttleKey($request), 60); // Add failed attempt with a 60-second decay
        \Log::info('Rate Limiter Check After Failure:', [
            'attempts' => RateLimiterFacade::attempts($this->throttleKey($request)),
            'tooManyAttempts' => RateLimiterFacade::tooManyAttempts($this->throttleKey($request), 3),
            'availableIn' => RateLimiterFacade::availableIn($this->throttleKey($request))
        ]);

        toastr()->error(__('auth.failed'));
        return back()->withInput();
    }



    public function logout()
    {
        Session::flush();
        Auth::guard()->logout();
        return redirect()->route('main_admin.login');
    }

    public function getMyProfile()
    {
        $profile = Auth::guard()->user();
        return view('admin.my_profile', compact('profile'));
    }

    public function updateMyProfile(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $adminData = $request->all();

        if ($request->filled('password')) {
            $adminData['password'] = Hash::make($request->password);
        } else {
            unset($adminData['password']);
        }

        $profile = Auth::guard()->user();
        $profile->update($adminData);
        toastr()->success(__('Profile Updated Successfully'));
        return back();
    }

    public function getMonthlySalesDataByEmployee($employeeId)
    {
        $employee = Employee::with(['reservations.bill'])->findOrFail($employeeId);

        $data = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => $employee->name,
                    'data' => [],
                ],
            ],
        ];

        $salesData = $employee->reservations->groupBy(function ($date) {
            return Carbon::parse($date->res_Date)->format('F');
        })->map(function ($month) {
            return $month->sum('bill.payment_Amount');
        });

        $data['datasets'][0]['data'] = $salesData->values()->all();
        $data['labels'] = array_keys($salesData->toArray());

        return $data;
    }

    public function getChartDataByStatus($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        $reservations = $employee->reservations()
            ->select('res_Status', DB::raw('count(*) as count'))
            ->groupBy('res_Status')
            ->get();

        $chartData = [];

        foreach ($reservations as $reservation) {
            $data = [
                'value' => $reservation->count,
                'label' => $reservation->res_Status,
                'color' => $reservation->res_Status == 'Complete' ? '#46BFBD' : '#F7464A',
                'highlight' => $reservation->res_Status == 'Complete' ? '#5AD3D1' : '#FF5A5E',
            ];

            $chartData[] = $data;
        }

        return $chartData;
    }


    // Function to display the settings form
    public function getSettings()
    {
        $settings = Setting::first(); // Assuming there's only one row of settings
        return view('admin.settings', compact('settings'));
    }

    // Function to handle the update of settings
    public function updateSettings(Request $request)
    {
        $this->validate($request, [
            'website_name' => 'required|max:255',
            'session_period' => 'required|integer',
            'password_period' => 'required|integer',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $logoPath = null;
        $existingSettings = Setting::first();
        if ($request->hasFile('logo')) {
            $logoPath = $this->upload($request->logo);
        }
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'website_name' => $request->website_name,
                'session_period' => $request->session_period,
                'password_period' => $request->password_period,
                'logo' => $logoPath ?? ($existingSettings->logo ?? null)
            ]
        );
        toastr()->success(__('Settings Updated Successfully'));
        return back();
    }



    public function showPasswordUpdateForm()
    {
        return view('admin.password_update');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:10|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{10,}$/',
        ], [
            'new_password.regex' => __('كلمة المرور يجب أن تحتوي على حرف كبير وحرف صغير ورقم وحرف خاص.')
        ]);
        $employee = Auth::user();
        if (!Hash::check($request->current_password, $employee->password)) {
            return back()->withErrors(['current_password' => __('كلمة المرور الحالية غير صحيحة.')]);
        }
        $employee->update([
            'password' => Hash::make($request->new_password),
            'updated_password_at' => now(), // Update the password change timestamp
        ]);
        toastr()->success(__('تم تحديث كلمة المرور بنجاح.'));
        return redirect()->route('main_admin.home');
    }


    // Generate a throttle key based on the request
    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }
}
