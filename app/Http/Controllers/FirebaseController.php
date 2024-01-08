<?php

namespace App\Http\Controllers;

use App\Api\PaperApi;
use App\Models\Paper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FirebaseController extends BaseController
{
    /**
     * @var App\Api\PaperApi
     */
    protected $paperApi;

    function __construct(
        \App\Services\FirebaseService $firebaseService,
        PaperApi $paperApi
    ) {
        $this->paperApi = $paperApi;
        parent::__construct($firebaseService);
    }

    function dashboard(): \Illuminate\Contracts\View\View
    {
        if (Cache::has('paper_in_firebase')) {
            $papersInFirebase = Cache::get('paper_in_firebase');
        } else {
            $papersInFirebase = $this->paperApi->paperInFirebase();
            Cache::put('paper_in_firebase', $papersInFirebase);
        }
        $uploadedIds = array_column($papersInFirebase, 'id');
        $papers = Paper::where('show', '=', 0)->whereNotIn('id', $uploadedIds)->paginate(8);
        return view('adminhtml.templates.firebase.dashboard', ['listPaper' => $papersInFirebase, 'papers' => $papers]);
    }

    function addPaper(Request $request): \Illuminate\Http\Response
    {
        $params = $request->toArray();
        if (isset($params['paper_id'])) {
            try {
                $data = $this->paperApi->addFirebase($params['paper_id'], ['conten']);
                return response(json_encode([
                    'code' => 200,
                    'data' => $data['value']
                ]));
            } catch (\Throwable $exception) {
                Log::error($exception->getMessage());
            }
        }
        return response(json_encode([
            'code' => 400
        ]));
    }

    function deletePaper(Request $request): \Illuminate\Http\Response
    {
        $paperId = $request->get('paper_id');
        if (!empty($paperId)) {
            try {
                $response = $this->paperApi->removeInFirebase($paperId);
                return response(json_encode([
                    'code' => 200,
                    'data' => $response['value'],
                    'error' => null
                ]));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        return response(json_encode([
            'code' => 400,
            'data' => null,
            "error" => null,
        ]));
    }
}
