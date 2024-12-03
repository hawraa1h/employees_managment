<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'display_name' => 'required|string',
            'permissions' => 'required|array', // Ensure permissions are passed
        ]);

        $role = Role::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        // Attach the selected permissions to the role
        $role->permissions()->sync($request->permissions);

        return redirect()->route('main_admin.roles.index')->with('success', __('تم إضافة الدور بنجاح'));
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all(); // Fetch all permissions
        $rolePermissions = $role->permissions->pluck('id')->toArray(); // Get assigned permissions

        return view('admin.roles.update', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'display_name' => 'required|string|max:255',
            'permissions' => 'required|array', // Ensure permissions are passed
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        // Update role permissions
        $role->permissions()->sync($request->permissions);

        return redirect()->route('main_admin.roles.index')->with('success', __('تم تحديث الدور بنجاح'));
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('main_admin.roles.index')->with('success', __('تم حذف الدور بنجاح'));
    }
}
