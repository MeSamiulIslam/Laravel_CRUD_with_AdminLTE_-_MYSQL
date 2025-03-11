<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CVController;

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

Route::get('/', function () {
    return view('auth.login');
});

// Routes accessible only by guests (not authenticated)
Route::group(['middleware' => 'guest'], function () {
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::get('register', [AuthController::class, 'register_view'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register');
});

// Routes accessible only by authenticated users
Route::group(['middleware' => 'auth'], function () {
    Route::get('home', [AuthController::class, 'home'])->name('home');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('add-new', [CVController::class, 'create'])->name('cv');
    Route::post('/Upload', [CVController::class, 'store'])->name('Upload');
    Route::get('application-list', [CVController::class, 'list'])->name('show_data');
    Route::get('view-application/{id}', [CVController::class, 'view'])->name('show_all');
    Route::get('edit-application/{id}', [CVController::class, 'edit'])->name('edit_cv');

    Route::post('/upload-image', [CVController::class, 'uploadImage'])->name('upload_image');
    Route::get('/cv/{id}/pdf/view', [CVController::class, 'viewPDF'])->name('pdf.view');
    Route::get('/cv/{id}/pdf/download', [CVController::class, 'downloadPDF'])->name('pdf.download');

    //Route::post('update-application', [CVController::class, 'update'])->name('update_cv');
    Route::post('/update-application/{id}', [CVController::class, 'update'])->name('update_cv');

    Route::get('/view-pdf/{id}', [YourController::class, 'viewPdf'])->name('view_pdf');

});
