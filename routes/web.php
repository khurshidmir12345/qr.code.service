<?php

use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostController;
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


// bu routing sistemasi controllersiz ishni amalga oshirildi faqatgina user systemasi pastda esa
// controller orqali bu ishlarni amalga oshiramiz


Route::get('/admin', function () {
    return view('admin.home');
})->name('admin.home')->middleware('auth');
//
//
//Route::get('admin/users',function (){
//    $users = User::all();
//    return view('admin.users.index',compact('users'));
//})->name('admin.users.index');
//
//Route::get('admin/users/{user}/edit',function (User $user){
//    return view('admin.users.edit',['user'=>$user]);
//})->name('admin.users.edit');
//
//Route::put('admin/users/{user}/update',function (UserUpdateRequest $request, User $user){
//    $request->validated();
//    $user->update([
//        'name'=>$request->input('name',false),
//        'email'=>$request->email,
//        'password'=>bcrypt($request->password)
//    ]);
//    return redirect()->route('admin.users.index');
//})->name('admin.users.update');
//
//
//Route::get('admin/users/create',function (){
//    return view('admin.users.create');
//})->name('admin.users.create');
//
//Route::post('admin/users/store',function (UserStoreeRequest $request){
//    $request->validated();
//    User::query()->create([
//        'name'=>$request->input('name',false),
//        'email'=>$request->email,
//        'password'=>bcrypt($request->password)
//    ]);
//
//    return redirect()->route('admin.users.index');
//
//})->name('admin.users.store');
//
//
//Route::delete('admin/users/{user}/destroy',function (User $user){
//    $user->delete();
//    return back();
//})->name('admin.users.destroy');


//----------------------------------------------------------------------------------------------------------------------


//bu esa controller orqali user CRUD sistemasini amalga oshirish qisqartirilmagan korinishda


//Route::get('admin/users',[UserController::class,'index'])->name('admin.users.index');
//
//Route::get('admin/users/{user}/edit',[UserController::class,'edit'])->name('admin.users.edit');
//
//Route::put('admin/users/{user}/update',[UserController::class,'update'])->name('admin.users.update');
//
//Route::get('admin/users/create',[UserController::class,'create'])->name('admin.users.create');
//
//Route::post('admin/users/store',[UserController::class,'store'])->name('admin.users.store');
//
//Route::delete('admin/users/{user}/destroy',[UserController::class,'destroy'])->name('admin.users.destroy');
//


//----------------------------------------------------------------------------------------------------------------------


// bu controllerniham qisqartirilgan CRUD sistemasi bu resource tizimi faqat GROUP ni ichida turadi tashqarida ishlamidi
//    chunki rout tizimi   ->name('admin.index') shunga qarab ishlaydi   Route:: resourse  name qabul qilmaydi
//    name groupga  'as'=>'admin.' deb qoshib qoyoladi

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
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/', function () {
    return view('layouts.header');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth','verified'])->name('dashboard');




require __DIR__ . '/auth.php';
