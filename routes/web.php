<?php

use App\Models\Admin\Category;
use App\Models\Buyer\Quotation;
use App\Models\Buyer\Tender;
use App\Models\TenderProposal;
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
    // $pro = TenderProposal::find(1);
    // $pro->bidder_id = 5;
    // $pro->save();
    // dd("done");

    // $quotation = Tender::where('tender_state', 'published')->first();
    
    // $details = [
    //     'title' => 'Test Email',
    //     'category' => 'Test category',
    //     'deadline' => "blahblah",
    //     'url' => 'https://mptc.soisoft.com/quotations/' 
    // ];
   
    // Mail::to(['konaingwin01@gmail.com'])->send(new \App\Mail\NewTender($details));

    //Send congratulation mail to bidder
    $details = [
        'title' => 'Test',
        'category' => "Blah blah",
        'supplier' => "Supplier name"
    ];
    Mail::to(['konaingwin01@gmail.com'])->send(new \App\Mail\Awarded($details));

    //Send to all participate suppliers
    Mail::to(['konaingwin01@gmail.com'])->send(new \App\Mail\AwardedNotification($details));
   
    dd("Email is Sent.");
});
