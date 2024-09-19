<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FileUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TenderProposal;
use Symfony\Component\HttpFoundation\Response;

class TenderPropsalApiController extends Controller
{
    public function createTenderProposal(Request $request, $tender_id)
    {
        $request->validate([
            'tender_fee_receipt' => 'required|mimes:docx,xlsx,pdf,ppt,txt',
            'checklist_before_submit' => 'required',
        ]);
        try {
            DB::beginTransaction();
            if ($request->hasFile('tender_fee_receipt')) {
                $filepath = FileUpload::upload('tender-proposals', ($request->tender_fee_receipt));
            } else {
                $filepath = null;
            }
            TenderProposal::create([
                'tender_id' => $tender_id,
                'bidder_id' => auth()->user()->id,
                'tender_fee_receipt' => $filepath,
                'proposal_comment' => $request->comment,
                'checklist_before_submit' => $request->checklist_before_submit,
                'status' => config('soisoft.tender_proposal_status.proposed'),
            ]);
            DB::commit();
            return response()
                ->json([
                    'message' => 'Tender proposal submitted successfully.',
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

    public function cancelTenderProposal(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required',
            'cancel_comment' => 'nullable',
        ]);
        try {
            DB::beginTransaction();
            TenderProposal::find($id)->update([
                'status' => config('soisoft.tender_proposal_status.cancelled'),
                'cancel_reason' => $request->cancel_reason,
                'cancel_comment' => $request->cancel_comment,
            ]);
            DB::commit();
            return response()
                ->json([
                    'message' => 'Tender proposal cancelled successfully.',
                    'status' => Response::HTTP_OK
                ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()
                ->json([
                    'message' => 'Fail to cancelled tender proposal.',
                    'status' => 500
                ], 500);
        }
    }
}
