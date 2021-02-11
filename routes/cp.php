<?php

/*
|--------------------------------------------------------------------------
| CP routes
|--------------------------------------------------------------------------
*/

Route::prefix('html-minify/')->name('html-minify.')->group(function() {
    Route::get('/', function () {
        \Illuminate\Support\Facades\Config::set('html-minify.optimizeViaHtmlDomParser', false);
        return view('html-minify::settings');
    })->name('settings');
});
