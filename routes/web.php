<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\BookmarkController;

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
/*
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
*/

Route::group(['middleware' => ['auth']], function(){
    //URL追加
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth'])->name('dashboard');
    
    Route::get('/',[BookmarkController::class, 'topPage']);
    Route::get('/add/url', [BookmarkController::class, 'addUrl']);
    Route::post('/add/url', [BookmarkController::class, 'storeUrl']);
    
    Route::post('/mokuji', [BookmarkController::class, 'storeContent']);
    
    Route::get('/add/details', [BookmarkController::class, 'addDetails']);
    Route::post('/add/details', [BookmarkController::class, 'storeBookmark']);
});

require __DIR__.'/auth.php';