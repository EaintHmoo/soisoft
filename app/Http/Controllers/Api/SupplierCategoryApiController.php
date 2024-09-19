<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SupplierCategory;

class SupplierCategoryApiController extends Controller
{
    public function getSupplierCategoryList(Request $request)
    {
        $data = SupplierCategory::query()->whereNull('parent_id')->select('id', 'name')->orderBy('name', 'asc')->get();
        return response()
            ->json([
                'data' => $data,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function getSupplierSubCategoryList($parent_id)
    {
        $data = SupplierCategory::where('parent_id', $parent_id)->select('id', 'name')->orderBy('name', 'asc')->get();
        return response()
            ->json([
                'data' => $data,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }
}
