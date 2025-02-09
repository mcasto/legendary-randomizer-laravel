<?php

use App\Http\Controllers\GameRandomizerController;
use Illuminate\Support\Facades\Route;

Route::get('/api/randomizer/{numPlayers}', [GameRandomizerController::class, 'generateGame']);
