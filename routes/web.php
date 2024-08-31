<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/delete-jobs', function() {
    DB::table('jobs')->delete();
    // $jobs->delete();
    dd("done");
});
