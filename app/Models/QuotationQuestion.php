<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationQuestion extends Model
{
    use HasFactory;

    protected $table = 'quotation_questions';

    protected $fillable =
    [
        'quotation_id',
        'question_by_id',
        'answer_by_id',
        'question',
        'answer'
    ];

    public function question_by()
    {
        return $this->belongsTo(User::class, 'question_by_id', 'id');
    }

    public function answer_by()
    {
        return $this->belongsTo(User::class, 'answer_by_id', 'id');
    }
}
