<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return [
        'version' => '1.0',
    ];
});
