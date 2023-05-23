<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;



class PermissionController extends Controller
{
   //
   function add()
   {
      $permissions = Permission::all()->groupBy(function ($permission) {
         return explode('.', $permission->slug)[0];
      });
      // dd($permissions);
      return view('admin.permission.add', compact('permissions'));
   }
   function store(Request $request)
   {
      $valiedated = $request->validate(
         [
            'name' => 'required|max:255',
            'slug' => 'required|',
         ]
      );
      // return $request->all();
      Permission::create([
         'name' => $request->input('name'),
         'slug' => $request->input('slug'),
         'description' => $request->input('description'),
      ]);
      return redirect()->route('permission.add')->with('status', 'Đã thêm quyền thành công');
   }
   function edit($id)
   {
      $permission = Permission::find($id);
      return view('admin.permission.edit', compact('permission'));
   }
   function update(Request $request, $id)
   {
      $request->validate(
         [
            'name' => 'required|max:255',
            'slug' => 'required|',
         ]
      );
      Permission::find($id)->update(
         [
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
         ]
      );
      return redirect()->route('permission.add')->with('status', 'Đã chỉnh sửa quyền thành công');
   }
   function delete($id){
      Permission::find($id)->delete();
      return redirect()->route('permission.add')->with('status', 'Đã xóa bản ghi thành công');
   }
}
