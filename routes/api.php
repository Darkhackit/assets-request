<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Permissions\PermissionsController;
use App\Http\Controllers\Roles\RolesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'guest:api','prefix' => 'auth'],function ($router) {
    Route::post('/login', LoginController::class);
});

Route::group(['middleware' => 'auth:api','prefix' => 'user'],function ($router) {
    Route::post('/logout', LogoutController::class);

    Route::post('/permission',[PermissionsController::class,'create']);
    Route::get('/permission',[PermissionsController::class,'index']);
    Route::get('/permission/{permission}',[PermissionsController::class,'show']);
    Route::patch('/permission/{permission}',[PermissionsController::class,'update']);
    Route::post('/permission/delete',[PermissionsController::class,'delete']);
    Route::get('/permission/name/{val}',[PermissionsController::class,'names']);


    Route::post('/role',[RolesController::class,'create']);
    Route::get('/role',[RolesController::class,'index']);
    Route::get('/role/{role}',[RolesController::class,'show']);
    Route::patch('/role/{role}',[RolesController::class,'update']);
    Route::post('/role/delete',[RolesController::class,'delete']);
    Route::get('/role/name/{val}',[RolesController::class,'names']);

    Route::post('/employee',[EmployeeController::class,'create']);
    Route::get('/employee',[EmployeeController::class,'index']);
    Route::get('/employee/{employee}',[EmployeeController::class,'show']);
    Route::patch('/employee/{employee}',[EmployeeController::class,'update']);
    Route::post('/employee/delete',[EmployeeController::class,'delete']);
    Route::get('/employee/name/{val}',[EmployeeController::class,'names']);
});
