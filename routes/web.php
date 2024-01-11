<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\DB;

Route::get('/test.connections', function () {
    try {
        DB::connection('mysql')->getPdo();
        echo "Default database connected successfully.<br>";


        DB::connection('mysql_second')->getPdo();
        echo "Second database connected successfully.";
    } catch (\Exception $e) {
        die("Error: " . $e->getMessage());
    }
});

use App\Http\Controllers\LoginController;

Route::get('/login-ip-details', [LoginController::class, 'indstaffdetailsex']);
Route::get('/login-ip-details1', [LoginController::class, 'Employee']);
