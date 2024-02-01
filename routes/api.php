<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Permissions\PermissionsController;
use App\Http\Controllers\Roles\RolesController;
use App\Http\Controllers\VendorBranchController;
use App\Http\Controllers\VendorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'guest:api','prefix' => 'auth'],function ($router) {
    Route::post('/login', LoginController::class);
    Route::post('/employee/resetPassword',[EmployeeController::class,'resetPassword']);

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
    Route::get('/employee/send_mail/{val}',[EmployeeController::class,'send_mail']);
    Route::get('/employee/sendAll',[EmployeeController::class,'sendAll']);
    Route::post('/employee/changePassword',[EmployeeController::class,'changePassword']);

    Route::post('/send-invoice',[InvoiceController::class,'create']);
    Route::get('/send-invoice',[InvoiceController::class,'index']);
    Route::get('/all-invoice',[InvoiceController::class,'getAllAssets']);
    Route::get('/download-invoice',[InvoiceController::class,'downloadInvoice']);
    Route::get('/get-invoice/{invoice}',[InvoiceController::class,'show']);
    Route::patch('/update-invoice/{invoice}',[InvoiceController::class,'update']);
    Route::post('/update-document/{invoice}',[DocumentController::class,'create']);
    Route::post('/update-data/{invoice}',[InvoiceController::class,'updateData']);

    Route::post('/vendors',[VendorController::class,'create']);
    Route::get('/vendors',[VendorController::class,'index']);
    Route::get('/vendors/{vendor}',[VendorController::class,'show']);
    Route::patch('/vendors/{vendor}',[VendorController::class,'update']);
    Route::post('/vendors/delete',[VendorController::class,'delete']);
    Route::post('/vendors/name',[VendorController::class,'names']);

    Route::post('/vendor_branches',[VendorBranchController::class,'create']);
    Route::get('/vendor_branches',[VendorBranchController::class,'index']);
    Route::get('/vendor_branches/{vendorBranch}',[VendorBranchController::class,'show']);
    Route::patch('/vendor_branches/{vendorBranch}',[VendorBranchController::class,'update']);
    Route::post('/vendor_branches/delete',[VendorBranchController::class,'delete']);
    Route::post('/vendor_branches/name/{val}',[VendorBranchController::class,'names']);
});
