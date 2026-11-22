<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IdolController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlaylistController; // <-- TAMBAHKAN INI

// ==========================================
// AUTHENTICATION
// ==========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// MAIN & SEARCH
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');

// ==========================================
// IDOLS
// ==========================================
Route::get('/idols', [IdolController::class, 'index'])->name('idols.index');
Route::get('/idols/{id}', [IdolController::class, 'show'])->name('idols.show');

// ==========================================
// GROUPS (Dengan Follow/Unfollow)
// ==========================================
Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
Route::post('/groups/follow', [GroupController::class, 'follow'])->name('groups.follow');
Route::post('/groups/unfollow', [GroupController::class, 'unfollow'])->name('groups.unfollow');

// ==========================================
// COMMUNITY (Feed Postingan / Pengganti Gallery)
// ==========================================
Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
Route::get('/community/{slug}', [CommunityController::class, 'show'])->name('community.show');
Route::post('/community/{slug}/post', [CommunityController::class, 'storePost'])->name('community.store');
Route::post('/community/{slug}/follow', [CommunityController::class, 'follow'])->name('community.follow');
Route::post('/community/{slug}/unfollow', [CommunityController::class, 'unfollow'])->name('community.unfollow');

// ==========================================
// NEWS
// ==========================================
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/calendar', [NewsController::class, 'calendar'])->name('news.calendar');
Route::get('/news/{id}', [NewsController::class, 'show'])->name('news.show');

// ==========================================
// PLAYLIST (HALAMAN UTAMA - BARU)
// ==========================================
Route::get('/playlist', [PlaylistController::class, 'index'])->name('playlist.index');
Route::post('/playlist/like', [PlaylistController::class, 'like'])->name('playlist.like');
Route::post('/playlist/unlike', [PlaylistController::class, 'unlike'])->name('playlist.unlike');

// ==========================================
// PROFILE
// ==========================================
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');

Route::get('/profile/bias', [ProfileController::class, 'bias'])->name('profile.bias');
Route::get('/profile/groups', [ProfileController::class, 'groups'])->name('profile.groups');
Route::get('/profile/playlist', [ProfileController::class, 'playlist'])->name('profile.playlist');
Route::get('/profile/notifications', [ProfileController::class, 'notifications'])->name('profile.notifications');