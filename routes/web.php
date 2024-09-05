<?php

use App\Models\Admin\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/delete-categories', function() {
    $categories = Category::where('parent_id', '!=', -1)
                        ->get()
                        ->groupBy('parent.name');

    $to_return = [];

    foreach($categories as $parent => $child) {
        // dd($child->pluck('name', 'id')->toArray());
        $to_return[$parent] = $child->pluck('name', 'id')->toArray();
    }
    dd($to_return);
    dd($categories);
});
