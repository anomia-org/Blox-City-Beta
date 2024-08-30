<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Render\AvatarsController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;


/* APIs */
/* This section will be opened and improved upon later once we start doing public APIs, for now it just holds the Avatars and basic things to call upon elsewhere */
Route::domain('avatars.bloxcity.com')->group(function() {
    Route::controller(AvatarsController::class)->group(function ()
    {
        Route::get('/', 'index');
        /*
        * BLOX City Avatar APIs v1.0.0; originally created April 18th, 2021 at 4:30AM for BLOXCity.com
        */
        Route::group(['prefix' => 'v1'], function() {
            Route::get('/', 'v1');
            Route::get('/render/{user}', 'render');
            Route::get('/headshot/{user}', 'headshot');
            Route::get('/market/{item}', 'market');
        });
        
    });
    
});