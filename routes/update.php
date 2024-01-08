<?php
/*
|--------------------------------------------------------------------------
| Install Routes
|--------------------------------------------------------------------------
|
| This route is responsible for handling the intallation process
|
|
|
*/

use Illuminate\Support\Facades\Route;

Route::any('/', 'UpdateController@update_software_index')->name('index');
Route::any('update-system', 'UpdateController@update_software')->name('update-system');

Route::fallback(function () {
    return redirect('/');
});
