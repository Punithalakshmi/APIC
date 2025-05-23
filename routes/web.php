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
    Route::get('/admin/dealerlists', [HomeController::class, 'dealerlists'])->name('admin/dealerlists');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('admin/dealers', [DealerController::class, 'index'])->name('admin/dealers');
    Route::get('/admin/dealers/create', [DealerController::class, 'create'])->name('admin/dealers/create');
    Route::post('/admin/dealers/save', [DealerController::class, 'save'])->name('admin/dealers/save');
    Route::get('/admin/dealers/edit/{id}', [DealerController::class, 'edit'])->name('admin/dealers/edit');
    Route::put('/admin/dealers/update/{id}', [DealerController::class, 'update'])->name('admin.dealers.update');
    Route::get('/admin/dealers/delete/{id}', [DealerController::class, 'delete'])->name('admin/dealers/delete');
    Route::get('/admin/dealers/lists', [DealerController::class, 'dealer_list'])->name('admin/dealers/lists');
    Route::get('/admin/dealers/upgrade/{id}', [DealerController::class, 'upgrade'])->name('admin/dealers/upgrade');
    Route::get('admin/dealers/rundelete', [DealerController::class, 'runDelete'])->name('admin/dealers/rundelete');
    Route::get('/admin/api/register/{id}', [ApiAuthenticationController::class, 'register'])->name('admin/api/register');
    Route::get('/admin/api/refreshToken/{id}', [ApiAuthenticationController::class, 'refreshToken'])->name('admin/api/refreshToken');
    Route::get('/admin/dealers/downgrade/{id}', [DealerController::class, 'downgrade'])->name('admin/dealers/downgrade');
    Route::get('/admin/dealers/downgradeprotobasic', [DealerController::class, 'DowngradeProToBasic'])->name('admin/dealers/downgradeprotobasic');
});

require __DIR__.'/auth.php';

//Route::get('admin/dashboard', [HomeController::class, 'index'])->middleware(['auth','admin']);
