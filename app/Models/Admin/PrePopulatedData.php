<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrePopulatedData extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'data',
    ];

    protected $casts = [
        'data' => 'array'
    ];
}
