<?php

namespace App\Http\Controllers;

use App\Http\Resources\TenderQuestionResource;
use App\Http\Resources\TenderResource;
use App\Models\Buyer\Tender;
use App\Models\TenderQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TenderApiController extends Controller
{
    public function getTenders(Request $request)
    {
        $data = Tender::paginate(10);
        return response()
            ->json([
                'data' => TenderResource::collection($data)->response()->getData(true),
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function getTenderDetail($id)
    {
        $data = Tender::find($id);
        return response()
            ->json([
                'data' => new TenderResource($data),
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function getTenderQuestions(Request $request, $tender_id)
    {
        $data = TenderQuestion::where('tender_id', $tender_id)->paginate(10);
        return response()
            ->json([
                'data' => TenderQuestionResource::collection($data)->response()->getData(true),
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function createTenderQuestion(Request $request, $tender_id)
    {
        $request->validate([
            'question' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $data = TenderQuestion::create([
                'question' => $request->question,
                'tender_id' => $tender_id,
                'question_by_id' => auth()->user()->id,
            ]);
            DB::commit();
            return response()
                ->json([
                    'data' => new TenderQuestionResource($data),
                    'message' => 'question created successfully',
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
