<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Services\Api\QuotationService;
use Symfony\Component\HttpFoundation\Response;

class QuotationPropsalApiController extends Controller
{
    protected $quoService;

    public function __construct(QuotationService $quoService)
    {
        $this->quoService = $quoService;
    }

    public function createQuotationProposal(Request $request, $quotation_id)
    {
        $request->validate([
            // 'tender_fee_receipt' => 'required|mimes:docx,xlsx,pdf,ppt,txt',
            'quotation_fee_receipt' => 'required',
            'checklist_before_submit' => 'required',
        ]);
        try {
            DB::beginTransaction();
            $this->quoService->createQuotationProposal($request, $quotation_id);
            DB::commit();
            return response()
                ->json([
                    'message' => 'Quotation proposal submitted successfully.',
                    'status' => Response::HTTP_CREATED
                ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()
                ->json([
                    'message' => 'Fail to submit tender proposal.',
                    'status' => 500
                ], 500);
        }
    }

    public function cancelQuotationProposal(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required',
            'cancel_comment' => 'nullable',
        ]);
        try {
            DB::beginTransaction();
            $this->quoService->cancelQuotationProposal($request, $id);
            DB::commit();
            return response()
                ->json([
                    'message' => 'Quotation proposal cancelled successfully.',
                    'status' => Response::HTTP_OK
                ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()
                ->json([
                    'message' => 'Fail to cancelled quotation proposal.',
                    'status' => 500
                ], 500);
        }
    }
}
