<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationNdaAccept extends Model
{
    use HasFactory;

    protected $table = 'quotation_nda_accepts';

    protected $fillable =
    [
        'quotation_id',
        'bidder_id',
        'is_accept',
    ];
}
