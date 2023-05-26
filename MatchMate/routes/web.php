<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\StandingsController;
use App\Http\Controllers\FavouritesController;
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

Route::get('/', [HomeController::class, 'welcome'])->name('home');
Route::get('/home', [HomeController::class, 'welcome'])->name('home');
Route::resource('games', GameController::class);
Route::resource('teams', TeamController::class);
Route::resource('events', EventController::class);
Route::resource('standings', StandingsController::class);
Route::resource('players', PlayerController::class);
Route::resource('favourites', FavouritesController::class);
Route::get('events/create/{game}', [EventController::class, 'create'])->name('events.create');
Route::get('players/create/{team}', [PlayerController::class, 'create'])->name('players.create');
Route::put('games/finish/{game}', [GameController::class, 'finish'])->name('games.finish');


Auth::routes();
