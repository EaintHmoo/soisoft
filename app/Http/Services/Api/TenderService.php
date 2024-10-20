<?php

namespace App\Http\Services\Api;

use App\Models\Buyer\Quotation;
use App\Models\Buyer\Tender;
use Carbon\Carbon;
use DB;
use stdClass;

class TenderService
{
    public function getTenderList($request)
    {
        $data = Tender::with([
            'tenderItems',
            'documents',
            'category',
            'subCategory',
            'department',
            'project',
            'tenderContacts'
        ])
            ->when($request->keyword ?? '', function ($query) use ($request) {
                $query->where('tender_no', 'like', '%' . $request->keyword . '%')
                    ->orWhere('tender_title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('mode_of_submission', 'like', '%' . $request->keyword . '%')
                    ->orWhereRelation('department', 'name', 'like', '%' . $request->keyword . '%')
                    ->orWhereRelation('category', 'name', 'like', '%' . $request->keyword . '%')
                    ->orWhereRelation('subCategory', 'name', 'like', '%' . $request->keyword . '%');
            })
            ->when($request->status ?? '',function ($query) use($request){
                $query->where('tender_status',$request->status);
            })
            ->when($request->date === 'today', function ($query){
                $query->whereDate('created_at',Carbon::today());
            })
            ->when($request->date === 'this_week', function ($query){
                $query->whereBetween('created_at', [
                    Carbon::parse('monday this week')->startOfDay(), 
                    Carbon::now()->endOfDay()
                ]);
            })
            ->when($request->date === 'this_month', function ($query){
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfDay()]);
            })
            ->when($request->date === 'last_seven_days', function ($query){
                $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()->endOfDay()]);
            })
            ->orderBy('start_datetime', 'desc')
            ->paginate(10);
        return $data;
    }

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
            ->when($request->status ?? '',function ($query) use($request){
                $query->where('quotation_status',$request->status);
            })
            ->when($request->date === 'today', function ($query){
                $query->whereDate('created_at',Carbon::today());
            })
            ->when($request->date === 'this_week', function ($query){
                $query->whereBetween('created_at', [
                    Carbon::parse('monday this week')->startOfDay(), 
                    Carbon::now()->endOfDay()
                ]);
            })
            ->when($request->date === 'this_month', function ($query){
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfDay()]);
            })
            ->when($request->date === 'last_seven_days', function ($query){
                $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()->endOfDay()]);
            })
            ->orderBy('start_datetime', 'desc')
            ->paginate(10);
        return $data;
    }

    public function getTenderListBySupplier($user_id)
    {
        $data = Tender::whereHas('tenderProposals',function($query) use ($user_id){
            $query->where('bidder_id',$user_id);
        })->get();

        return $data;
    }

    public function getQuotationListBySupplier($user_id)
    {
        $data = Quotation::whereHas('quotation_proposals',function($query) use ($user_id){
            $query->where('bidder_id',$user_id);
        })->get();

        return $data;
    }

    public function getTenderCountBySupplier($user_id)
    {
        $active_count = Tender::whereHas('tenderProposals',function($query) use ($user_id){
            $query->where('bidder_id',$user_id);
        })
        ->where('tender_status',config('soisoft.tender_status.open'))
        ->count();
        $pending_count = Tender::whereHas('tenderProposals',function($query) use ($user_id){
            $query->where('bidder_id',$user_id);
        })
        ->where('tender_status',config('soisoft.tender_status.pending'))
        ->count();
        $awarded_count = Tender::whereHas('tenderProposals',function($query) use ($user_id){
            $query->where('bidder_id',$user_id);
        })
        ->where('tender_status',config('soisoft.tender_status.awarded'))
        ->count();

        $obj = new stdClass();
        $obj->active_count = $active_count;
        $obj->pending_count = $pending_count;
        $obj->awarded_count = $awarded_count;

        return $obj;
    }

    public function getQuotationCountBySupplier($user_id)
    {
        $active_count = Quotation::whereHas('quotation_proposals',function($query) use ($user_id){
            $query->where('bidder_id',$user_id);
        })
        ->where('quotation_status',config('soisoft.tender_status.open'))
        ->count();
        $pending_count = Quotation::whereHas('quotation_proposals',function($query) use ($user_id){
            $query->where('bidder_id',$user_id);
        })
        ->where('quotation_status',config('soisoft.tender_status.pending'))
        ->count();
        $awarded_count = Quotation::whereHas('quotation_proposals',function($query) use ($user_id){
            $query->where('bidder_id',$user_id);
        })
        ->where('quotation_status',config('soisoft.tender_status.awarded'))
        ->count();

        $obj = new stdClass();
        $obj->active_count = $active_count;
        $obj->pending_count = $pending_count;
        $obj->awarded_count = $awarded_count;

        return $obj;
    }
}
