<?php

namespace App\Models\Admin;

use App\Models\Buyer\Tender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenderCategory extends Model
{
    use HasFactory;

    protected $table = "tender_categories";

    protected $fillable = ['key', 'name', 'slug', 'parent_id', 'order'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function tenders(): HasMany
    {
        return $this->hasMany(Tender::class, 'tender_category_id');
    }

    public function setKeyAttribute($value) {
        if ( empty($value) ) { 
            $this->attributes['key'] = NULL;
        } else {
            $this->attributes['key'] = $value;
        }
    }

    public function setParentIdAttribute($value) {
        if ( empty($value) ) { 
            $this->attributes['parent_id'] = -1;
        } else {
            $this->attributes['parent_id'] = $value;
        }
    }
}
