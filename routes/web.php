<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/{any}', function () {
    $env = [
        'VUE_AVATAR_BASE_PATH' => config('filesystems.disks.remote.url') . '/' . config('filesystems.disks.remote.folders.avatars'),
        'VUE_APP_API_HTTP' => config('app.url') . '/api'
    ];
    return view('home', ['env' => $env]);
})->where('any', '.*');
