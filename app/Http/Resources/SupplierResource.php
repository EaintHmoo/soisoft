<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'supplier_id'   => $this->supplier_id,
            'supplier'   => $this->supplier?->name,
            'supplier_type' => $this->supplier_type,
            'business_type' => $this->business_type_name?->name,
            'registration_number'   => $this->registration_number,
            'vat_number'    => $this->vat_number,
            'company_name'  => $this->company_name,

            'primary_contact_full_name' => $this->primary_contact_full_name,
            'primary_contact_designation'   => $this->primary_contact_designation,
            'primary_contact_phone' => $this->primary_contact_phone,
            'primary_contact_email' => $this->primary_contact_email,
            'primary_contact_address1'  => $this->primary_contact_address1,
            'primary_contact_address2'  => $this->primary_contact_address2,
            'primary_contact_province'  => $this->primary_contact_province,
            'primary_contact_city'  => $this->primary_contact_city,
            'primary_contact_postal_code'   => $this->primary_contact_postal_code,
            'primary_contact_country'   => $this->primary_contact_country_name?->name,
            'supplier_industry' => $this->supplier_industry,

            'company_contact_full_name' => $this->company_contact_full_name,
            'company_contact_designation'   => $this->company_contact_designation,
            'company_contact_phone' => $this->company_contact_phone,
            'company_contact_email' => $this->company_contact_email,
            'company_contact_address1'  => $this->company_contact_address1,
            'company_contact_address2'  => $this->company_contact_address2,
            'company_contact_province'  => $this->company_contact_province,
            'company_contact_city'  => $this->company_contact_city,
            'company_contact_postal_code'   => $this->company_contact_postal_code,
            'company_contact_country'   => $this->company_contact_country_name?->name,

            'individual_contact_full_name'  => $this->individual_contact_full_name,
            'individual_contact_designation'    => $this->individual_contact_designation,
            'individual_contact_phone'  => $this->individual_contact_phone,
            'individual_contact_email'  => $this->individual_contact_email,
            'individual_contact_address'    => $this->individual_contact_address,

            'supplier_industries'  => $this->supplier_industries,
            'supplier_category'  => $this->supplier_category_name?->name,
            'supplier_sub_category'  => $this->supplier_sub_category_name?->name,
        ];
    }
}
