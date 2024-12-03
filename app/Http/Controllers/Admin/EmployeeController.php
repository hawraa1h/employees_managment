<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Rules\PhoneNumber;
use App\Rules\SaudiNationalId;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        $roles = Role::all();
        return view('admin.employees.create', compact('departments', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:255',
//            'emp_number' => 'required|digits:10|unique:employees,emp_number',  // Exactly 10 digits
            'id_number' => ['required', 'digits:10', 'unique:employees,id_number', new SaudiNationalId],  // Exactly 10 digits
            'mobile' => ['required', 'unique:employees,mobile', new PhoneNumber],
            'email' => 'nullable|email|unique:employees,email',
            'password' => 'required|min:10|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{10,}$/',
            'department_id' => 'required|exists:departments,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $employeeData = $request->all();
        if ($request->hasFile('image')) {
            $employeeData['image'] = $request->file('image')->store('employee_images', 'public');
        }
        $employeeData['password'] = Hash::make($request->password); // Hashing the password

        Employee::create($employeeData);

        return redirect()->route('main_admin.employees.index')->with('success', __('تم إضافة الموظف بنجاح'));
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $departments = Department::all();
        $roles = Role::all();
        return view('admin.employees.update', compact('employee', 'departments', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'address' => 'nullable|string|max:255',
            'emp_number' => 'required|digits:10|unique:employees,emp_number,' . $employee->id,
//            'id_number' => ['required', 'digits:10', 'unique:employees,id_number,' . $employee->id, new SaudiNationalId],
            'mobile' => ['required', 'unique:employees,mobile,' . $employee->id, new PhoneNumber],
            'email' => 'nullable|email|unique:employees,email,' . $employee->id,
            'password' => 'nullable|min:10|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{10,}$/',
            'department_id' => 'required|exists:departments,id',
            'role_id' => 'required|exists:roles,id',
        ]);

        $employeeData = $request->except(['password']);
        if ($request->hasFile('image')) {
            $employeeData['image'] = $request->file('image')->store('employee_images', 'public');
        }
        if ($request->filled('password')) {
            $employeeData['password'] = Hash::make($request->password); // Hashing the password
        }

        $employee->update($employeeData);

        return redirect()->route('main_admin.employees.index')->with('success', __('تم تحديث الموظف بنجاح'));
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('main_admin.employees.index')->with('success', __('تم حذف الموظف بنجاح'));
    }
}
