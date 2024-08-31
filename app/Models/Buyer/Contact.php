<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_person',
        'designation',
        'phone',
        'email',
        'address',
    ];

    public function tenders(): BelongsToMany 
    {
        return $this->belongsToMany(Tender::class, 'tender_tender_contact');
    }

    protected static function booted(): void
    {
        static::deleting(function (Contact $record) {
            $record->tenders()->detach();
        });
    }
}
