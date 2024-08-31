<?php

namespace App\Http\Controllers;

use App\Http\Resources\TenderResource;
use App\Models\Buyer\Tender;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenderApiController extends Controller
{
    public function getTenders(Request $request){
        $data = Tender::paginate(10);
        return response()
            ->json([
                'data' => TenderResource::collection($data)->response()->getData(true),
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function getTenderDetail($id) {
        $data = Tender::find($id);
        return response()
            ->json([
                'data' => new TenderResource($data),
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }
}
