<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuotationQuestionResource;
use App\Http\Resources\QuotationResource;
use App\Http\Services\Api\QuotationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class QuotationApiController extends Controller
{
    protected $quoService;

    public function __construct(QuotationService $quoService)
    {
        $this->quoService = $quoService;
    }

    public function getQuotationDetail($id)
    {
        $data = $this->quoService->getQuotationDetail($id);
        return response()
            ->json([
                'data' => new QuotationResource($data),
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function getQuotationQuestions(Request $request, $quotation_id)
    {
        $data = $this->quoService->getQuotationQuestions($request, $quotation_id);
        return response()
            ->json([
                ...QuotationQuestionResource::collection($data)->response()->getData(true),
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function createQuotationQuestion(Request $request, $quotation_id)
    {
        $request->validate([
            'question' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $data = $this->quoService->createQuotationQuestion($request, $quotation_id);
            DB::commit();
            return response()
                ->json([
                    'data' => new QuotationQuestionResource($data),
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

    public function addQuotationDocument(Request $request, $quotation_id)
    {
        $request->validate([
            // 'document' => 'required|mimes:docx,xlsx,pdf,ppt,txt',
            'document' => 'required',
            'document_type' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $this->quoService->addQuotationDocument($request, $quotation_id);
            DB::commit();
            return response()
                ->json([
                    'message' => 'Quotation document added successfully.',
                    'status' => Response::HTTP_CREATED
                ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()
                ->json([
                    'message' => 'Fail to add quotation document.',
                    'status' => 500
                ], 500);
        }
    }

    public function acceptNDAToParticipate($id)
    {
        try {
            DB::beginTransaction();
            $this->quoService->acceptNDAToParticipate($id);
            DB::commit();
            return response()
                ->json([
                    'message' => 'Accepted NDA and participated successfully.',
                    'status' => Response::HTTP_OK
                ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()
                ->json([
                    'message' => 'Fail to accepted NDA.',
                    'status' => 500
                ], 500);
        }
    }
}
