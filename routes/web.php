<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.contoh');
});

Route::get('/index', function () {
    return view('pages.index');
});

Route::get('/welcome', function () {
    return view('pages.front-page');
});

Route::get('/details', function () {
    return view('pages.details');
});

Route::get('/metode-bayar', function () {
    return view('pages.metode-bayar');
});