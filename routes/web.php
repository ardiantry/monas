<?php
 
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\UserController; 
use Illuminate\Support\Facades\Route;

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

 
Route::get('/', function()
{
	return redirect('/dashboard');
});
route::group(['middleware' => ['guest']], function () {

		Route::get('/login', [UserController::class, 'login'])->name('login');
		Route::post('/login', [UserController::class, 'loginUser'])->name('auth.login');
		Route::get('/register', [UserController::class, 'register']);
		Route::post('/register', [UserController::class, 'saveUser'])->name('auth.register'); 
    // Route::get('/forgot', [UserController::class, 'forgot']);
    // Route::get('/reset', [UserController::class, 'reset']);

});
 
route::group(['middleware' => ['auth']], function () {
   Route::get('/dashboard', [UserController::class, 'dashboard']);
   Route::get('/data-mobil', [UserController::class, 'datamobil']);

   Route::post('/simpan-mobil', [UserController::class, 'simpanmobil']);
   Route::get('/show-table-data-mobil', [UserController::class, 'showtabledatamobil']);
   Route::post('/hapus-mobil', [UserController::class, 'hapusmobil']);
   Route::get('/data-pengguna', [UserController::class, 'datapengguna']);
   Route::get('/data-mobil-list', [UserController::class, 'datamobillist']);

   
   Route::post('/simpan-pengguna', [UserController::class, 'simpanpengguna']);
   Route::get('/show-table-data-pengguna', [UserController::class, 'showtabledatapengguna']);
   Route::post('/hapus-pengguna', [UserController::class, 'hapuspengguna']);

   Route::get('/data', [UserController::class, 'dataservice']);

   Route::post('/simpan-data-service', [UserController::class, 'simpandataservice']);
   Route::get('/show-table-data-service', [UserController::class, 'showtabledataservice']);
   Route::get('/get-data-pengguna', [UserController::class, 'getdatapengguna']);





 

   

   

   
    // Route::post('/profile-image', [UserController::class, 'profileImageUpdate'])->name('auth.profile-image-update');
    // Route::post('/profile-update', [UserController::class, 'profileUpdate'])->name('auth.profile-update');
    Route::get('/logout', [UserController::class, 'logout'])->name('auth.logout');

 
});
 
