<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    //
    function index()
    {
        // dd(Auth::user()->roles);
        // if (Auth::user()->hasPermission('post.delete'))
        //  return "User co quyen xoa bai viet";
        // return "User khong co quyen xoa bai viet";
        if (!Gate::allows('role.index')) {
            abort(403);
        } {
            $roles = Role::all();
            return view('admin.role.index', compact('roles'));
        }
    }
    function add()
    {
        if (!Gate::allows('role.add')) {
            abort(403);
        } {
            $permissions = Permission::all()->groupBy(function ($permission) {
                return explode('.', $permission->slug)[0];
            });
            return view('admin.role.add', compact('permissions'));
        }
    }
    function store(Request $request)
    {
        if (!Gate::allows('role.add')) {
            abort(403);
        } {
            $validate = $request->validate([
                'name' => 'required|unique:roles|max:255',
                'description' => 'required',
            ]);
            $role = Role::create(
                [
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                ]
            );
            $role->permissions()->attach($request->input('permission_id'));
            return redirect()->route('role.index')->with('status', 'Đã thêm vai trò thành công!');
            // return $role;
            // return $request->all();
            // return $request->input('permission_id');
        }
    }
    function edit(Role $role)
    {
        if (!Gate::allows('role.edit')) {
            abort(403);
        } {
            // return $role;
            // $role = Role::find($id);
            $permissions = Permission::all()->groupBy(function ($permission) {
                return explode('.', $permission->slug)[0];
            });
            if (!empty($role->permissions)) {
                $role_permissions = $role->permissions->groupBy(function ($permission) {
                    return explode('.', $permission->slug)[0];
                });
            } else $role_permissions = [];
            // dd($role_permissions);
            // return $role_permissions;
            return view('admin.role.edit', compact('role', 'permissions', 'role_permissions'));
        }
    }
    function update(Request $request, Role $role)
    {
        if (!Gate::allows('role.edit')) {
            abort(403);
        } {
            // return $request->all();
            // $role = Role::find($id);
            $validate = $request->validate([
                'name' => 'required|unique:roles,name,' . $role->id,
                'permission_id' => 'nullable|array',
                'permission_id.*' => 'exists:permissions,id',
                'description' => 'required',
            ]);
            $role->update(
                [
                    'name' => $request->input('name'),
                    'description' => $request->input('description'),
                ]
            );
            $role->permissions()->sync($request->input('permission_id', []));
            return redirect()->route('role.index')->with('status', 'Đã chỉnh sửa vai trò thành công!');
        }
    }
    function delete(Role $role)
    {
        if (!Gate::allows('role.delete')) {
            abort(403);
        } {
            $role->delete();
            return redirect()->route('role.index')->with('status', 'Đã xóa bản ghi thành công');
        }
    }
}
