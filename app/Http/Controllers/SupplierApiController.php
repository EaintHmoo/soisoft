<?php

namespace App\Http\Controllers;

use App\Http\Resources\SupplierResource;
use App\Models\SupplierBusinessType;
use App\Models\SupplierInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SupplierApiController extends Controller
{
    public function createIndividualRegister(Request $request)
    {
        $request->validate([
            'business_type_id' => 'required',
            'individual_contact_full_name' => 'required',
            'individual_contact_designation' => 'required',
            'individual_contact_phone' => 'required',
            'individual_contact_email' => 'required|email',
            'individual_contact_address' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $data = SupplierInfo::create([
                'supplier_id' => auth()->user()->id,
                'business_type_id' => $request->business_type_id,
                'business_type' => SupplierBusinessType::find($request->business_type_id)?->name ?? '',
                'supplier_type' => "individual",
                'individual_contact_full_name' => $request->individual_contact_full_name,
                'individual_contact_designation' => $request->individual_contact_designation,
                'individual_contact_phone' => $request->individual_contact_phone,
                'individual_contact_email' => $request->individual_contact_email,
                'individual_contact_address' => $request->individual_contact_address,
            ]);
            $data->supplier_industries()->sync($request->supplier_industries);
            DB::commit();
            return response()
                ->json([
                    'data' => new SupplierResource($data),
                    'message' => 'Supplier created successfully',
                    'status' => Response::HTTP_CREATED
                ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()
                ->json([
                    'message' => 'Fail to create',
                    'status' => 500
                ], 500);
        }
    }

    public function createCorporateRegister(Request $request)
    {
        $request->validate([
            'business_type_id' => 'required',
            'registration_number' => 'required',
            'vat_number' => 'required',
            'company_name' => 'required',

            'primary_contact_full_name' => "required",
            'primary_contact_designation' => "required",
            'primary_contact_phone' => "required",
            'primary_contact_email' => "required|email",
            'primary_contact_address1' => "required",
            'primary_contact_province'  => 'required',
            'primary_contact_city'  => 'required',
            'primary_contact_postal_code'   => 'required',
            'primary_contact_country_id'   => 'required',

            'supplier_sub_category_id'   => 'required',

            'company_contact_full_name' => 'required',
            'company_contact_designation' => 'required',
            'company_contact_phone' => 'required',
            'company_contact_email' => 'required|email',
            'company_contact_address1' => 'required',
            'company_contact_province' => 'required',
            'company_contact_city' => 'required',
            'company_contact_postal_code' => 'required',
            'company_contact_country_id' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $data = SupplierInfo::create([
                'supplier_id' => auth()->user()->id,
                'business_type_id' => $request->business_type_id,
                'business_type' => SupplierBusinessType::find($request->business_type_id)?->name ?? '',
                'supplier_type' => "corporate",
                'registration_number'   => $request->registration_number,
                'vat_number'    => $request->vat_number,
                'company_name'  => $request->company_name,

                'primary_contact_full_name' => $request->primary_contact_full_name,
                'primary_contact_designation'   => $request->primary_contact_designation,
                'primary_contact_phone' => $request->primary_contact_phone,
                'primary_contact_email' => $request->primary_contact_email,
                'primary_contact_address1'  => $request->primary_contact_address1,
                'primary_contact_address2'  => $request->primary_contact_address2,
                'primary_contact_province'  => $request->primary_contact_province,
                'primary_contact_city'  => $request->primary_contact_city,
                'primary_contact_postal_code'   => $request->primary_contact_postal_code,
                'primary_contact_country_id'   => $request->primary_contact_country_id,

                'company_contact_full_name' => $request->company_contact_full_name,
                'company_contact_designation'   => $request->company_contact_designation,
                'company_contact_phone' => $request->company_contact_phone,
                'company_contact_email' => $request->company_contact_email,
                'company_contact_address1'  => $request->company_contact_address1,
                'company_contact_address2'  => $request->company_contact_address2,
                'company_contact_province'  => $request->company_contact_province,
                'company_contact_city'  => $request->company_contact_city,
                'company_contact_postal_code'   => $request->company_contact_postal_code,
                'company_contact_country_id'   => $request->company_contact_country_id,
                'supplier_category_id'  => $request->supplier_category_id,
                'supplier_sub_category_id'  => $request->supplier_sub_category_id,

            ]);

            DB::commit();
            return response()
                ->json([
                    'data' => new SupplierResource($data),
                    'message' => 'Supplier created successfully',
                    'status' => Response::HTTP_CREATED
                ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()
                ->json([
                    'message' => 'Fail to create',
                    'status' => 500
                ], 500);
        }
    }
}
