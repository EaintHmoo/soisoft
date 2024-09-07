<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInfo extends Model
{
    use HasFactory;

    protected $table = 'supplier_infos';

    protected $fillable = [
        'supplier_id',
        'supplier_type',
        'business_type',
        'registration_number',
        'vat_number',
        'company_name',

        'primary_contact_full_name',
        'primary_contact_designation',
        'primary_contact_phone',
        'primary_contact_email',
        'primary_contact_address1',
        'primary_contact_address2',
        'primary_contact_province',
        'primary_contact_city',
        'primary_contact_postal_code',
        'primary_contact_country',
        'supplier_industry',

        'company_contact_full_name',
        'company_contact_designation',
        'company_contact_phone',
        'company_contact_email',
        'company_contact_address1',
        'company_contact_address2',
        'company_contact_province',
        'company_contact_city',
        'company_contact_postal_code',
        'company_contact_country',

        'individual_contact_full_name',
        'individual_contact_designation',
        'individual_contact_phone',
        'individual_contact_email',
        'individual_contact_address',

        'category',

        'business_type_id',
        'primary_contact_country_id',
        'company_contact_country_id',
        'supplier_category_id',
        'supplier_sub_category_id',
    ];


    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id', 'id');
    }

    public function business_type_name()
    {
        return $this->belongsTo(SupplierBusinessType::class, 'business_type_id', 'id');
    }

    public function primary_contact_country_name()
    {
        return $this->belongsTo(Country::class, 'supplier_category_id', 'id');
    }

    public function company_contact_country_name()
    {
        return $this->belongsTo(Country::class, 'company_contact_country_id', 'id');
    }

    public function supplier_category_name()
    {
        return $this->belongsTo(SupplierCategory::class, 'supplier_category_id', 'id');
    }

    public function supplier_sub_category_name()
    {
        return $this->belongsTo(SupplierCategory::class, 'supplier_sub_category_id', 'id');
    }

    public function supplier_industries()
    {
        return $this->belongsToMany(SupplierIndustry::class, 'supplier_info_industry', 'supplier_info_id', 'supplier_industry_id');
    }
}