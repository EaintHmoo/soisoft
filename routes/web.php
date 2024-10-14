<?php

use App\Models\Admin\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Mailjet\LaravelMailjet\Facades\Mailjet;
use Mailjet\Resources;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-email', function() {
    dd(User::role('supplier')->pluck('email')->toArray());
    $details = [
        'type' => 'Quotation',
        'body' => 'This is testing email form MPTC portal.'
    ];
   
    Mail::to(['konaingwin01@gmail.com', 'zeyar@thavara.agency'])->send(new \App\Mail\NewTender($details));
   
    dd("Email is Sent.");
});
