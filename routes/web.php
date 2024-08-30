<?php

use App\Livewire\Forum\ForumSearch;
use App\Livewire\Forum\ForumMyThreads;
use App\Livewire\Forum\ForumNewQuote;
use App\Livewire\Forum\ForumNewReply;
use App\Livewire\Forum\ForumNewThread;
use App\Livewire\Forum\ForumThread;
use App\Livewire\Forum\ForumTopic;
use App\Livewire\Market\CreatePants;
use App\Livewire\Market\CreateShirt;
use App\Livewire\Market\EditItem;
use App\Models\Item;
use Illuminate\Support\Facades\Route;

Route::domain('dev.bloxcity.com')->group(function() {

    // Guest ONLY routes
    Route::middleware(['guest'])->group(function() {
        Route::view('/', 'welcome')
            ->name('index');
    });

    Route::view('/dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');
    
    // Forum routes
    Route::prefix('forum')->group(function() {
        // Guests can view the forum
        Route::view('/', 'forum.index')
            ->name('forum.index');
        Route::get('/topic/{topic}', ForumTopic::class)
            ->name('forum.topic');
        Route::get('/thread/{thread}', ForumThread::class)
            ->name('forum.thread');
        Route::get('/search', ForumSearch::class)
            ->name('forum.search');

        // Authenticated users can view these parts
        Route::middleware(['auth', 'verified'])->group(function() {
            Route::get('/topic/{topic}/create', ForumNewThread::class)
                ->name('forum.thread.create');
            Route::get('/thread/{thread}/reply', ForumNewReply::class)
                ->name('forum.thread.reply');
            Route::get('/thread/{thread}/quote/{quote_id}/{quote_type}', ForumNewQuote::class)
                ->name('forum.thread.quote');
            Route::get('/my-threads', ForumMyThreads::class)
                ->name('forum.my.threads');
        });
    });

    Route::prefix('market')->group(function() {
        // Guests can view the market
        Route::view('/', 'market.index')
            ->name('market.index');
        Route::get('/item/{item}', function (Item $item) {
                return view('market.item', ['item' => $item]);
            })->name('market.item');

            // Authenticated users only
        Route::middleware(['auth', 'verified'])->group(function() {
            Route::view('/create', 'market.create')
                ->name('market.create');
            Route::get('/create/shirt', CreateShirt::class)
                ->name('market.create.shirt');
            Route::get('/create/pants', CreatePants::class)
                ->name('market.create.pants');
            Route::get('/edit/{item}', EditItem::class)
                ->name('market.edit');
        });
    });

    // User routes
    Route::prefix('user')->group(function() {
        Route::view('/{user}', 'user.profile')
            ->name('user.profile');
    
    });

    Route::view('/profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');

    require __DIR__.'/auth.php';
});

require __DIR__.'/api/render.php';