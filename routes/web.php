<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboards', "DashboardController@show");
    Route::get('admin', 'DashboardController@show');
    Route::get('admin/user/list', 'AdminUserController@show')->name('admin_user.list');
    Route::get('admin/user/add', 'AdminUserController@add')->name('admin_user.add');
    Route::post('admin/user/store', 'AdminUserController@store');
    Route::get('admin/user/delete/{id}','AdminUserController@delete')->name('admin_user.delete');
    Route::post('admin/user/action', 'AdminUserController@action')->name('admin_user.action');
    Route::get('admin/user/edit/{id}','AdminUserController@edit')->name('admin_user.edit');
    Route::post('admin/user/update/{id}','AdminUserController@update')->name('admin_user.update');

    Route::get('admin/user/permisssion/add',[PermissionController::class,'add'])->name('permission.add')->can('permission.add');
    Route::post('admin/user/permisssion/store',[PermissionController::class,'store'])->name('permission.store')->can('permission.add');
    Route::get('admin/user/permisssion/edit/{id}',[PermissionController::class,'edit'])->name('permission.edit')->can('permission.edit');
    Route::post('admin/user/permisssion/update/{id}',[PermissionController::class,'update'])->name('permission.update')->can('permission.edit');
    Route::get('admin/user/permisssion/delete/{id}',[PermissionController::class,'delete'])->name('permission.delete')->can('permission.delete');
    Route::get('admin/user/role',[RoleController::class,'index'])->name('role.index')->can('role.index');
    // Route::get('admin/user/role/add',[RoleController::class,'add' ])->name('role.add')->can('role.add');
    Route::get('admin/user/role/add',[RoleController::class,'add' ])->name('role.add')->can('role.add');
    Route::post('admin/user/role/store',[RoleController::class,'store' ])->name('role.store')->can('role.add');
    Route::get('admin/user/role/edit/{role}',[RoleController::class,'edit'])->name('role.edit')->can('role.edit');
    Route::post('admin/user/role/update/{role}',[RoleController::class,'update'])->name('role.update')->can('role.edit');
    Route::get('admin/user/role/delete/{role}',[RoleController::class,'delete'])->name('role.delete')->can('role.delete');
   

});
require __DIR__ . '/auth.php';
