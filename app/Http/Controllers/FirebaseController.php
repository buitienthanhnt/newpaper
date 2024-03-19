<?php

namespace App\Http\Controllers;

use App\Api\CategoryApi;
use App\Api\PaperApi;
use App\Models\Paper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FirebaseController extends BaseController
{
    /**
     * @var \App\Api\PaperApi $paperApi
     */
    protected $paperApi;

    /**
     * @var \App\Api\CategoryApi $categoryApi
     */
    protected $categoryApi;

    function __construct(
        \App\Services\FirebaseService $firebaseService,
        PaperApi $paperApi,
        CategoryApi $categoryApi
    ) {
        $this->paperApi = $paperApi;
        $this->categoryApi = $categoryApi;
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
        // $papers = Paper::where('show', '=', 1)->whereNotIn('id', $uploadedIds)->orderBy('id', 'DESC')->paginate(8);
        
        // tạm thời lấy hết
        $papers = Paper::whereNotIn('id', $uploadedIds)->orderBy('id', 'DESC')->paginate(8);
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
            $response = $this->paperApi->removeInFirebase($paperId);
            return response(json_encode([
                'code' => 200,
                'data' => $response['value'],
                'error' => null
            ]));
        }
        return response(json_encode([
            'code' => 400,
            'data' => null,
            "error" => null,
        ]));
    }

    function fireStore()
    {
        $this->paperApi->removeImageFirebase('https://firebasestorage.googleapis.com/v0/b/newpaper-25148.appspot.com/o/demo%2F1TBJN2EdRj.png?alt=media&token=5af678f4-4110-4a4c-aad6-b718f5c7ec21');
    }

    function upCategoryTop()
    {
        try {
            $this->categoryApi->addCategoryTopFirebase();
            return response(json_encode([
                'code' => 200
            ]));
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response(json_encode([
            'code' => 400
        ]));
    }

    function asyncCategory(): \Illuminate\Http\Response
    {
        try {
            $this->categoryApi->asyncCategory();
            return response(json_encode([
                'code' => 200
            ]));
        } catch (\Throwable $th) {
            //throw $th;
        }
        return response(json_encode([
            'code' => 400
        ]));
    }
}
