<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DealerController;
use App\Http\Controllers\ApiAuthenticationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/

Route::middleware(['auth','admin'])->group(function () {
    Route::get('/admin/dashboard', [HomeController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('admin/dealers', [DealerController::class, 'index'])->name('admin/dealers');
    Route::get('/admin/dealers/create', [DealerController::class, 'create'])->name('admin/dealers/create');
    Route::post('/admin/dealers/save', [DealerController::class, 'save'])->name('admin/dealers/save');
    Route::get('/admin/dealers/edit/{id}', [DealerController::class, 'edit'])->name('admin/dealers/edit');
    Route::put('/admin/dealers/update/{id}', [DealerController::class, 'update'])->name('admin.dealers.update');
    Route::get('/admin/dealers/delete/{id}', [DealerController::class, 'delete'])->name('admin/dealers/delete');

    Route::get('/admin/api/register/{id}', [ApiAuthenticationController::class, 'register'])->name('admin/api/register');
});

require __DIR__.'/auth.php';

//Route::get('admin/dashboard', [HomeController::class, 'index'])->middleware(['auth','admin']);
