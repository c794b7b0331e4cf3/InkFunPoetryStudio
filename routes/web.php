<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PoemController;
use App\Http\Controllers\PoemHistoryRecordController;
use App\Http\Controllers\PoemImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'home.',
], function () {
    Route::get('/', [HomeController::class, 'render'])->name('render');
});

Route::group([
    'prefix' => 'file',
    'as' => 'file.',
], function () {
    Route::get('/{file}/download', [FileController::class, 'download'])->middleware(['signed'])->name('download');
});

Route::group([
    'prefix' => 'auth',
    'as' => 'auth.',
], function () {
    Route::group([
        'middleware' => ['guest'],
    ], function () {
        Route::get('/', [AuthController::class, 'render'])->name('render');

        Route::post('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group([
    'middleware' => ['auth'],
], function () {
    Route::group([
        'prefix' => 'profile',
        'as' => 'profile.',
    ], function () {
        Route::get('/', [ProfileController::class, 'render'])->name('render');
        Route::get('/summarize', [ProfileController::class, 'renderSummarize'])->name('summarize.render');
    });

    Route::group([
        'prefix' => 'ai',
        'as' => 'ai.',
    ], function () {
        Route::get('/image-generate', [AiController::class, 'renderImageGenerate'])->name('image-generate.render');
        Route::post('/image-generate', [AiController::class, 'imageGenerate'])->name('image-generate');

        Route::get('/couplet', [AiController::class, 'renderCouplet'])->name('couplet.render');
        Route::post('/couplet', [AiController::class, 'couplet'])->name('couplet');

        Route::get('/suggest', [AiController::class, 'renderSuggest'])->name('suggest.render');
        Route::post('/suggest', [AiController::class, 'suggest'])->name('suggest');

        Route::get('/image-to-poem', [AiController::class, 'renderImageToPoem'])->name('image-to-poem.render');
        Route::post('/image-to-poem', [AiController::class, 'imageToPoem'])->name('image-to-poem');

        Route::get('/character-talk', [AiController::class, 'renderCharacterTalk'])->name('character-talk.render');
        Route::post('/character-talk', [AiController::class, 'characterTalk'])->name('character-talk');

        Route::get('/poetic-chain', [AiController::class, 'renderPoeticChain'])->name('poetic-chain.render');
        Route::post('/poetic-chain', [AiController::class, 'poeticChain'])->name('poetic-chain');
    });

    Route::group([
        'prefix' => 'poems',
        'as' => 'poems.',
    ], function () {
        Route::get('/{item}/edit', [PoemController::class, 'renderEdit'])->name('edit.render');
        Route::post('/{item}/edit', [PoemController::class, 'edit'])->name('edit');
        Route::post('/{item}/delete', [PoemController::class, 'delete'])->name('delete');
    });

    Route::group([
        'prefix' => 'poem_history_records',
        'as' => 'poem_history_records.',
    ], function () {
        Route::post('/{item}/delete', [PoemHistoryRecordController::class, 'delete'])->name('delete');
        Route::post('/clear_all', [PoemHistoryRecordController::class, 'clearAll'])->name('clear_all');
    });

    Route::group([
        'prefix' => 'poem_images',
        'as' => 'poem_images.',
    ], function () {
        Route::post('/{item}/like', [PoemImageController::class, 'like'])->name('like');
        Route::post('/{item}/delete', [PoemImageController::class, 'delete'])->name('delete');

        Route::get('/{item}/comments', [PoemImageController::class, 'comments'])->name('comments');
        Route::post('/{item}/comments', [PoemImageController::class, 'sendComment'])->name('comments.send');
        Route::post('/comments/{item}/delete', [PoemImageController::class, 'deleteComment'])->name('comments.delete');
    });
});

Route::group([
    'prefix' => 'explore',
    'as' => 'explore.',
], function () {
    Route::get('/', [ExploreController::class, 'render'])->name('render');
});

Route::group([
    'prefix' => 'users',
    'as' => 'users.',
], function () {
    Route::get('/{item}', [UserController::class, 'render'])->name('render');
});

Route::group([
    'prefix' => 'poems',
    'as' => 'poems.',
], function () {
    Route::get('/{item}', [PoemController::class, 'render'])->name('render');
});

Route::group([
    'prefix' => 'suggestion',
    'as' => 'suggestion.',
], function () {
    Route::post('/submit', [SuggestionController::class, 'submit'])->name('submit');
});
