<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
class AdminUserController extends Controller
{
    function __construct(){
        $this -> middleware(function ($request, $next){
            session(['module_active' => 'user']);
            return $next($request);
        });
    }
    function show(Request $request)
    {
        // return $request->input('keyword');
        $status = $request->input('status');
        $list_act = [
            'delete' => 'Xóa tạm thời'
        ];
        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            $users = User::onlyTrashed()->simplePaginate(3);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            // return $keyword;
            $users = User::where('name', 'LIKE', "%{$keyword}%")->simplePaginate(3);
        }
        // $role= User::find(12)->roles->toArray();
        // return $role[0];
        $count_user_active = User::count();
        $count_user_delete = User::onlyTrashed()->count();
        $count = [$count_user_active, $count_user_delete];
        // return $users->items();
        // dd($users);
        return view('admin.user.list', compact('users', 'count','list_act'));
    }
    function add()
    {
        $roles= Role::all();
        return view('admin.user.add',compact('roles'));
    }
    function store(Request $request)
    {
        // return $request;
        if ($request->input('btn_add')) {
            $request->validate(
                [
                    'name' => 'required|regex:/^[A-Za-z ]{6,100}$/',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:6|max:32|confirmed',
                    'password_confirmation' => 'required'
                ],
                [
                    'required' => ':attribute không được để trống',
                ],
                [
                    'name' => 'Họ và tên',
                    'password' => 'Mật khẩu',
                    'password_confirmation' => 'Mật khẩu nhập lại'
                ]
            );

            $input = array();
            $input['name'] = $request->input('name');
            $input['email'] = $request->input('email');
            $input['password'] = Hash::make($request->input('password'));
            $user = User::create($input);
            $user->roles()->attach($request->input('role_id')); 
            return redirect()->route('admin_user.list')->with('status', 'Thêm người dùng thành công');
        };
    }
    function delete($id)
    {
        if (Auth::id() != $id) {
            $user = User::find($id);
            $user->delete();
            return redirect()->route('admin_user.list')->with('status', 'Đã xóa user thành công');
        } else return redirect()->route('admin_user.list')->with('status', 'Bạn không thể xóa bản thân');
    }
    function action(Request $request)
    {
        //  return $request -> input('list_check');
        $list_check = $request->input('list_check');
        if ($list_check) {
            foreach ($list_check as $key => $id) {
                if (Auth::id() == $id)
                    unset($list_check[$key]);
            }
            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == 'delete') {
                    User::destroy($list_check);
                    return redirect()->route('admin_user.list')->with('status', 'Bạn đã xóa thành công');
                }
                if ($act == 'restore') {
                    User::withTrashed()
                        ->whereIn('id', $list_check)
                        ->restore();
                    return redirect('admin/user/list')->with('status', 'Bạn đã khôi phục thành công');
                }
                if ($act == 'forceDelete') {
                    User::withTrashed()
                        ->whereIn('id', $list_check)
                        ->forceDelete();
                    return redirect('admin/user/list')->with('status', 'Bạn đã xóa vĩnh viễn user thành công');
                }
            }
            return redirect()->route('admin_user.list')->with('status', 'Bạn không thể thao tác trên chính tài khoản của mình');
        }
        return redirect()->route('admin_user.list')->with('status', 'Bạn chưa chọn user để thực hiện tác vụ');
    }
    function edit($id){
        $user = User::find($id);
        $roles= Role::all();
        return view('admin.user.edit',compact('user','roles'));
    }
    function update(Request $request,$id){
        if ($request->input('btn_update')) {
            $request->validate(
                [
                    'name' => 'required|regex:/^[A-Za-z ]{6,100}$/',
                ],
                [
                    'required' => ':attribute không được để trống',
                ],
                [
                    'name' => 'Họ và tên',
                   
                ]
            );
            $input = array();
            $input['name'] = $request->input('name');
            $user=User::where('id',$id)->update($input);
            $user = User::find($id);
            $user->roles()->sync($request->input('role_id',[]));
            return redirect()->route('admin_user.list')->with('status', 'Chỉnh sửa thông tin người dùng thành công');
        };
    }
}
