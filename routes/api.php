<?php
use App\Http\Controllers\RouteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthJWT;
use App\Http\Controllers\EditFormController;
use Illuminate\Http\Request;
// API 
Route::get('/get-routes', [RouteController::class, 'getRoutes']);
Route::get('/routes', [RouteController::class, 'getFilteredRoutes']);
Route::get('/marker/{name_id}', [RouteController::class, 'getMarker']);
Route::post('/save-electric-pole', [RouteController::class, 'saveElectricPole']);
Route::get('/export-notify-to-excel', [ExportController::class, 'exportNotifyToExcel']);
Route::get('/export-repair-to-excel', [ExportController::class, 'exportRepairToExcel']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

    

Route::middleware(['auth:api'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    
});


Route::middleware([AuthJWT::class])->group(function () {
    Route::get('/edit', [EditFormController::class, 'update']);
});


use App\Http\Controllers\KMLUploadController;

Route::post('/upload-kml', [KMLUploadController::class, 'upload']);


use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index']);
Route::put('/users/{id}/status', [UserController::class, 'updateStatus']);
Route::middleware('auth:api')->group(function () {
    Route::put('users/{id}/role', [AuthController::class, 'updateUserRole']); // เปลี่ยน role ของผู้ใช้งาน
});





