<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->success('Server is running...');
});
