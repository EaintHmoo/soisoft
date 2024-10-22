<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Http\Resources\QuotationListResource;
use App\Http\Resources\SupplierQuotationListResource;
use App\Http\Resources\SupplierTenderListResource;
use App\Http\Resources\TenderListResource;
use App\Http\Resources\TenderQuestionResource;
use App\Http\Resources\TenderResource;
use App\Http\Services\Api\QuotationService;
use App\Http\Services\Api\TenderService;
use App\Models\Buyer\Tender;
use App\Models\Buyer\TenderDocument;
use App\Models\TenderNdaAccept;
use App\Models\TenderQuestion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TenderApiController extends Controller
{
    protected $service;
    protected $quoService;

    public function __construct(TenderService $service, QuotationService $quoService)
    {
        $this->service = $service;
        $this->quoService = $quoService;
    }

    public function getTenders(Request $request)
    {
        if ($request->type == 1) {
            $data = $this->service->getTenderList($request);
            $data = TenderListResource::collection($data)->response()->getData(true);
        } else {
            $data = $this->quoService->getQuotationList($request);
            $data = QuotationListResource::collection($data)->response()->getData(true);
        }
        return response()
            ->json([
                ...$data,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function getTenderDetail($id)
    {
        $data = Tender::with([
            'tenderItems',
            'documents',
            'category',
            'subCategory',
            'department',
            'project',
            'tenderContacts'
        ])->find($id);
        $data['tenderProposal'] = $data->tenderProposals()->where('bidder_id', auth()->user()->id)->first();
        $data['tenderNdaAccept'] = TenderNdaAccept::where('bidder_id', auth()->user()->id)
            ->where('tender_id', $id)->first();
        return response()
            ->json([
                'data' => new TenderResource($data),
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function getTenderQuestions(Request $request, $tender_id)
    {
        $data = TenderQuestion::with([
            'question_by',
            'answer_by'
        ])
            ->where('tender_id', $tender_id)
            ->when($request->keyword, function ($query) use ($request) {
                $query->where('question', 'like', '%' . $request->keyword . '%')
                    ->orWhere('answer', 'like', '%' . $request->keyword . '%');
            })
            ->get();
        return response()
            ->json([
                ...TenderQuestionResource::collection($data)->response()->getData(true),
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

    public function addTenderDocument(Request $request, $tender_id)
    {
        $request->validate([
            // 'document' => 'required|mimes:docx,xlsx,pdf,ppt,txt',
            'document' => 'required',
            'document_type' => 'required',
        ]);
        try {
            DB::beginTransaction();
            if ($request->hasFile('document')) {
                $filepath = FileUpload::upload('tender-documents', ($request->document));
                $originalFileName = $request->file('document')->getClientOriginalName();
                $pathInfo = pathinfo($originalFileName);
                $name = $pathInfo['filename'];
            } else {
                $filepath = null;
                $name = '';
            }
            TenderDocument::create([
                'tender_id' => $tender_id,
                'name' => $name,
                'document_type' => $request->document_type,
                'document_path' => $filepath,
                'comment' => $request->comment,
                'document_by_id' => auth()->user()->id,
            ]);
            DB::commit();
            return response()
                ->json([
                    'message' => 'Tender document added successfully.',
                    'status' => Response::HTTP_CREATED
                ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()
                ->json([
                    'message' => 'Fail to add tender document.',
                    'status' => 500
                ], 500);
        }
    }

    public function acceptNDAToParticipate($id)
    {
        try {
            DB::beginTransaction();
            TenderNdaAccept::create([
                'tender_id' => $id,
                'bidder_id' => auth()->user()->id,
                'is_accept' => true,
            ]);
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

    public function getTenderListBySupplier(Request $request)
    {
        $user_id = auth()->user()->id;
        if ($request->type == 1) {
            $data = $this->service->getTenderListBySupplier($user_id);
            $data = SupplierTenderListResource::collection($data)->response()->getData(true);
        } else {
            $data = $this->quoService->getQuotationListBySupplier($user_id);
            $data = SupplierQuotationListResource::collection($data)->response()->getData(true);
        }

        return response()
            ->json([
                ...$data,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function getTenderCountBySupplier(Request $request)
    {
        $user_id = auth()->user()->id;
        if ($request->type == 1) {
            $data = $this->service->getTenderCountBySupplier($user_id);
        } else {
            $data = $this->quoService->getQuotationCountBySupplier($user_id);
        }

        return response()
            ->json([
                'data' => $data,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);
    }
}
