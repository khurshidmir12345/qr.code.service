<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrCode\QrCodeController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('scan/{id}', [QrCodeController::class, 'scan'])->name('qrcodes.scan');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('qrcodes', QrCodeController::class);

});
Route::group(['middleware' => ['web']], function () {
    Route::get('/api/auth/google/redirect', [SocialAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/api/auth/google/callback', [SocialAuthController::class, 'callback']);
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/{id}', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth','verified'])->name('dashboard');

Route::get('/', function () {
    return redirect()->route('admin.qrcodes.index');
})->middleware('auth');


require __DIR__ . '/auth.php';
