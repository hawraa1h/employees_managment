<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Policy;
use App\Models\Department;
use App\Traits\UploadFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PolicyController extends Controller
{
    use UploadFile;
    // Display a listing of the policies
    public function index()
    {
        $employee = auth()->user(); // Get the authenticated employee
        if ($employee->id == 1 || $employee->role->name === 'admin' || $employee->role->name === 'normal' ) {
            $policies = Policy::where('type', 'policy')->with(['department', 'reviewer', 'auditor'])->get();
        } else {
            $policies = Policy::where('type', 'policy')->with(['department', 'reviewer', 'auditor'])
                ->whereHas('employees', function ($query) use ($employee) {
                    $query->where('employee_id', $employee->id);
                })
                ->get();
        }
        return view('admin.policies.index', compact('policies'));
    }


    // Show the form for creating a new policy
    // Show the form for creating a new policy
    public function create()
    {
        $departments = Department::all();
        $employees = Employee::all();
        return view('admin.policies.create', compact('departments', 'employees'));
    }


    // Store a newly created policy in storage
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department_ids' => 'required|array',
            'department_ids.*' => 'exists:departments,id',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'expected_review_date' => 'required|date',
            'expected_audit_date' => 'required|date',
            'file_path' => 'required',
        ]);
        $filePath = $this->upload($request->file_path);
        $policy = Policy::create([
            'title' => $request->input('title'),
            'file_path' => $filePath,
            'expected_review_date' => $request->input('expected_review_date'),
            'expected_audit_date' => $request->input('expected_audit_date'),
            'status' => 'pending',
        ]);
        // Attach departments and employees
        $policy->departments()->attach($request->input('department_ids'));
        $policy->employees()->attach($request->input('employee_ids'));
        return redirect()->route('main_admin.policies.index')->with('success', 'تم إضافة السياسة بنجاح');
    }

    // Show the form for editing the specified policy
    public function edit(Policy $policy)
    {
        $departments = Department::all();
        $employees = Employee::all();
        return view('admin.policies.update', compact('policy', 'departments', 'employees'));
    }

    // Update the specified policy in storage
    // Update the specified policy in storage
    public function update(Request $request, Policy $policy)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'department_ids' => 'required|array',
            'department_ids.*' => 'exists:departments,id',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id',
            'expected_review_date' => 'required|date',
            'expected_audit_date' => 'required|date',
            'file_path' => 'nullable',
        ]);
        $filePath = $policy->file_path;
        if ($request->hasFile('file_path')) {
            $filePath = $this->upload($request->file_path);
        }
        $policy->update([
            'file_path' => $filePath,
            'title' => $request->input('title'),
            'expected_review_date' => $request->input('expected_review_date'),
            'expected_audit_date' => $request->input('expected_audit_date'),
        ]);
        $policy->departments()->sync($request->input('department_ids'));
        $policy->employees()->sync($request->input('employee_ids'));
        return redirect()->route('main_admin.policies.index')->with('success', 'تم تحديث السياسة بنجاح');
    }

    // Remove the specified policy from storage
    public function destroy(Policy $policy)
    {
        $policy->delete();
        return redirect()->route('main_admin.policies.index')->with('success', 'تم حذف السياسة بنجاح');
    }


    public function review(Request $request, Policy $policy)
    {
        $request->validate([
            'review_notes' => 'required|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Optional file re-upload
        ]);

        // Check if a new file has been uploaded and replace the existing one
        if ($request->hasFile('file_path')) {
            $filePath = $this->upload($request->file_path); // Upload new file
            $policy->update([
                'file_path' => $filePath, // Replace the file
            ]);
        }

        // Update the policy status and review details
        $policy->update([
            'status' => 'reviewed',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_notes' => $request->input('review_notes'),
        ]);

        return redirect()->route('main_admin.policies.index')->with('success', 'تمت مراجعة السياسة بنجاح');
    }

    public function audit(Request $request, Policy $policy)
    {
        $request->validate([
            'audit_notes' => 'required|string',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Optional file re-upload
        ]);

        // Check if a new file has been uploaded and replace the existing one
        if ($request->hasFile('file_path')) {
            $filePath = $this->upload($request->file_path); // Upload new file
            $policy->update([
                'file_path' => $filePath, // Replace the file
            ]);
        }

        // Update the policy with audit details
        $policy->update([
            'status' => 'audited',
            'audited_by' => Auth::id(), // Authenticated user
            'audited_at' => now(),
            'audit_notes' => $request->input('audit_notes'),
        ]);

        return redirect()->route('main_admin.policies.index')->with('success', 'تم تدقيق السياسة بنجاح');
    }


    public function updateNotes(Request $request, Policy $policy)
    {
        $employee = auth()->user();
        if ($employee->role->name !== 'normal') {
            return redirect()->route('main_admin.policies.index')->with('error', 'لا تملك صلاحية لإضافة ملاحظات.');
        }
        $request->validate([
            'notes_by_employee' => 'required|string',
        ]);
        $policy->update([
            'notes_by_employee' => $request->input('notes_by_employee'),
        ]);
        return redirect()->route('main_admin.policies.index')->with('success', 'تم تحديث ملاحظاتك بنجاح.');
    }


    public function updateCheckedStatus(Request $request, Policy $policy)
    {
        $request->validate([
            'checked' => 'required|boolean',
        ]);

        $policy->update([
            'checked' => $request->checked,
        ]);

        return response()->json(['message' => 'تم تحديث الحالة بنجاح.']);
    }


}
