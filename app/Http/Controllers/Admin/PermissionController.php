<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Store a newly created permission in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'perm_name' => 'required|string|max:255|unique:permissions',
            'perm_label' => 'required|string|max:255',
        ]);

        Permission::create([
            'perm_name' => $request->perm_name,
            'perm_label' => $request->perm_label,
        ]);

        return redirect()->route('main_admin.permissions.index')
            ->with('success', __('تم إضافة الصلاحية بنجاح'));
    }

    /**
     * Show the form for editing the specified permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.permissions.update', compact('permission'));
    }

    /**
     * Update the specified permission in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'perm_name' => 'required|string|max:255|unique:permissions,perm_name,' . $id,
            'perm_label' => 'required|string|max:255',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'perm_name' => $request->perm_name,
            'perm_label' => $request->perm_label,
        ]);

        return redirect()->route('main_admin.permissions.index')
            ->with('success', __('تم تحديث الصلاحية بنجاح'));
    }

    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return redirect()->route('main_admin.permissions.index')
            ->with('success', __('تم حذف الصلاحية بنجاح'));
    }
}
