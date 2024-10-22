<?php

namespace App\Http\Services\Api;

use App\Helpers\FileUpload;
use App\Models\Buyer\Quotation;
use App\Models\Buyer\QuotationDocument;
use App\Models\QuotationNdaAccept;
use App\Models\QuotationProposal;
use App\Models\QuotationQuestion;
use Carbon\Carbon;
use stdClass;

class QuotationService
{
    public function getQuotationList($request)
    {
        $data = Quotation::with([
            'quotationItems',
            'documents',
            'categories',
            'department',
            'project',
            'contacts'
        ])
            ->when($request->keyword ?? '', function ($query) use ($request) {
                $query->where('reference_no', 'like', '%' . $request->keyword . '%')
                    ->orWhere('quotation_title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('mode_of_submission', 'like', '%' . $request->keyword . '%')
                    ->orWhereRelation('department', 'name', 'like', '%' . $request->keyword . '%');
            })
            ->when($request->status ?? '', function ($query) use ($request) {
                $query->where('quotation_status', $request->status);
            })
            ->when($request->date === 'today', function ($query) {
                $query->whereDate('created_at', Carbon::today());
            })
            ->when($request->date === 'this_week', function ($query) {
                $query->whereBetween('created_at', [
                    Carbon::parse('monday this week')->startOfDay(),
                    Carbon::now()->endOfDay()
                ]);
            })
            ->when($request->date === 'this_month', function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfDay()]);
            })
            ->when($request->date === 'last_seven_days', function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()->endOfDay()]);
            })
            ->orderBy('start_datetime', 'desc')
            ->paginate(10);
        return $data;
    }

    public function getQuotationListBySupplier($user_id)
    {
        $data = Quotation::whereHas('quotation_proposals', function ($query) use ($user_id) {
            $query->where('bidder_id', $user_id);
        })->get();

        return $data;
    }

    public function getQuotationCountBySupplier($user_id)
    {
        $active_count = Quotation::whereHas('quotation_proposals', function ($query) use ($user_id) {
            $query->where('bidder_id', $user_id);
        })
            ->where('quotation_status', config('soisoft.tender_status.open'))
            ->count();
        $pending_count = Quotation::whereHas('quotation_proposals', function ($query) use ($user_id) {
            $query->where('bidder_id', $user_id);
        })
            ->where('quotation_status', config('soisoft.tender_status.pending'))
            ->count();
        $awarded_count = Quotation::whereHas('quotation_proposals', function ($query) use ($user_id) {
            $query->where('bidder_id', $user_id);
        })
            ->where('quotation_status', config('soisoft.tender_status.awarded'))
            ->count();

        $obj = new stdClass();
        $obj->active_count = $active_count;
        $obj->pending_count = $pending_count;
        $obj->awarded_count = $awarded_count;

        return $obj;
    }

    public function getQuotationDetail($id)
    {
        $data = Quotation::with([
            'quotationItems',
            'documents',
            'categories',
            'department',
            'project',
            'contacts'
        ])->find($id);
        $data['quotationProposal'] = $data->quotation_proposals()->where('bidder_id', auth()->user()->id)->first();
        $data['quotationNdaAccept'] = QuotationNdaAccept::where('bidder_id', auth()->user()->id)
            ->where('quotation_id', $id)->first();
        return $data;
    }

    public function getQuotationQuestions($request, $quotation_id)
    {
        return QuotationQuestion::with([
            'question_by',
            'answer_by'
        ])
            ->where('quotation_id', $quotation_id)
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('question', 'like', '%' . $request->keyword . '%')
                    ->orWhere('answer', 'like', '%' . $request->keyword . '%');
            })
            ->get();
    }

    public function createQuotationQuestion($request, $quotation_id)
    {
        return QuotationQuestion::create([
            'question' => $request->question,
            'quotation_id' => $quotation_id,
            'question_by_id' => auth()->user()->id,
        ]);
    }

    public function addQuotationDocument($request, $quotation_id)
    {
        if ($request->hasFile('document')) {
            $filepath = FileUpload::upload('tender-documents', ($request->document));
            $originalFileName = $request->file('document')->getClientOriginalName();
            $pathInfo = pathinfo($originalFileName);
            $name = $pathInfo['filename'];
        } else {
            $filepath = null;
            $name = '';
        }
        QuotationDocument::create([
            'quotation_id' => $quotation_id,
            'name' => $name,
            'document_type' => $request->document_type,
            'document_path' => $filepath,
            'comment' => $request->comment,
            'document_by_id' => auth()->user()->id,
        ]);
    }

    public function acceptNDAToParticipate($id)
    {
        QuotationNdaAccept::create([
            'quotation_id' => $id,
            'bidder_id' => auth()->user()->id,
            'is_accept' => true,
        ]);
    }

    public function createQuotationProposal($request, $quotation_id)
    {
        if ($request->hasFile('quotation_fee_receipt')) {
            $filepath = FileUpload::upload('tender-proposals', ($request->quotation_fee_receipt));
        } else {
            $filepath = null;
        }
        QuotationProposal::create([
            'quotation_id' => $quotation_id,
            'bidder_id' => auth()->user()->id,
            'quotation_fee_receipt' => $filepath,
            'proposal_comment' => $request->comment,
            'checklist_before_submit' => $request->checklist_before_submit,
            'status' => config('soisoft.tender_proposal_status.proposed'),
        ]);
    }

    public function cancelQuotationProposal($request, $id)
    {
        QuotationProposal::find($id)->update([
            'status' => config('soisoft.tender_proposal_status.cancelled'),
            'cancel_reason' => $request->cancel_reason,
            'cancel_comment' => $request->cancel_comment,
        ]);
    }
}
