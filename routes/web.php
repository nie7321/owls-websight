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

Route::domain(config('social.subdomain'))->group(function () {
    Route::get('/', [Controllers\SocialMediaController::class, 'index']);
    Route::get('.well-known/atproto-did', [Controllers\SocialMediaController::class, 'atprotoDID']);
});

Route::get('legal/credits', fn () => view('legal.credits'))->name('legal.credits');
Route::get('legal/terms-and-privacy', fn () => view('legal.terms-and-privacy'))->name('legal.terms');
Route::get('about', fn () => view('about'))->name('about');
Route::get('contact', fn () => view('contact'))->name('contact');

Route::get('social-card.svg', Controllers\SocialCardController::class)->name('social-card');
Route::get('links/{categorySlug}', [Controllers\LinkController::class, 'show'])->name('link.show');

Route::get('tags/{tagSlug}', [Controllers\TagController::class, 'show'])->name('tag.show');

Route::get('blog-post/preview/{id}', [Controllers\BlogPostController::class, 'preview'])->name('blog-post.preview');
Route::get('{year}/{month}/{day}/{slug}', [Controllers\BlogPostController::class, 'show'])
    ->where([
        'year' => '\d{4}',
        'month' => '\d{2}',
        'day' => '\d{2}',
    ])
    ->name('blog-post.show');

Route::get('/feed', Controllers\FeedController::class)->name('feed.atom');

Route::prefix('toybox')->group(function () {
    Route::view('balloons', 'toybox.balloons.index');
});

Route::get('/', [Controllers\BlogPostController::class, 'index'])->name('blog-post.index');
