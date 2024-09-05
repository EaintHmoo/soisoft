<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\SupplierBusinessType;
use Illuminate\Http\Request;
use App\Models\SupplierIndustry;
use Symfony\Component\HttpFoundation\Response;

class SupplierPreRequiredDataApiController extends Controller
{
    public function getSupplierIndustryList()
    {
        $data = SupplierIndustry::select('name', 'id')->orderBy('name', 'asc')->get();
        return response()
            ->json([
                'data' => $data,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function getSupplierBusinessTypeList()
    {
        $data = SupplierBusinessType::select('name', 'id')->orderBy('name', 'asc')->get();
        return response()
            ->json([
                'data' => $data,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function getCountryList()
    {
        $data = Country::select('name', 'id')->orderBy('name', 'asc')->get();
        return response()
            ->json([
                'data' => $data,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }
}
