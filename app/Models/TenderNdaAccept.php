<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderNdaAccept extends Model
{
    use HasFactory;

    protected $table = 'tender_nda_accepts';

    protected $fillable =
    [
        'tender_id',
        'bidder_id',
        'is_accept',
    ];

}
