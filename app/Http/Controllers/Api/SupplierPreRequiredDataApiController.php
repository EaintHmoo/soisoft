<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\PrePopulatedData;
use App\Models\Country;
use App\Models\SupplierBusinessType;
use Illuminate\Http\Request;
use App\Models\SupplierIndustry;
use Symfony\Component\HttpFoundation\Response;

class SupplierPreRequiredDataApiController extends Controller
{
    public function getSupplierIndustryList()
    {
        $data = PrePopulatedData::where('type', 'supplier_industry')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item['id'] ?? '',
                    'name' => $item['data']['label'] ?? '',
                ];
            });
        return response()
            ->json([
                'data' => $data,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function getSupplierBusinessTypeList()
    {
        $data = PrePopulatedData::where('type', 'business_type')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item['id'] ?? '',
                    'name' => $item['data']['label'] ?? '',
                ];
            });
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

    public function getDocumentTypeList()
    {
        $data = PrePopulatedData::where('type', 'document_type')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item['id'] ?? '',
                    'name' => $item['data']['label'] ?? '',
                ];
            });
        return response()
            ->json([
                'data' => $data,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }
}
