<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::get('credits', fn () => view('legal.credits'))->name('legal.credits');

Route::get('tags/{tagSlug}', [Controllers\TagController::class, 'show'])->name('tag.show');

Route::get('{year}/{month}/{day}/{slug}', [Controllers\BlogPostController::class, 'show'])
    ->where([
        'year' => '\d{4}',
        'month' => '\d{2}',
        'day' => '\d{2}',
    ])
    ->name('blog-post.show');

Route::get('/', [Controllers\BlogPostController::class, 'index'])->name('blog-post.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
