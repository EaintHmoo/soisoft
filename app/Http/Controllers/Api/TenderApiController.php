<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Http\Resources\TenderListResource;
use App\Http\Resources\TenderQuestionResource;
use App\Http\Resources\TenderResource;
use App\Models\Buyer\Tender;
use App\Models\Buyer\TenderDocument;
use App\Models\TenderQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TenderApiController extends Controller
{
    public function getTenders(Request $request)
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
            ->orderBy('start_datetime', 'desc')
            ->paginate(10);
        return response()
            ->json([
                ...TenderListResource::collection($data)->response()->getData(true),
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
            ->paginate(10);
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
            'document' => 'required|mimes:docx,xlsx,pdf,ppt,txt',
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
}
