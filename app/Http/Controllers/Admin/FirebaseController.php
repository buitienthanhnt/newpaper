<?php

namespace App\Http\Controllers\Admin;

use App\Api\CategoryApi;
use App\Api\PaperApi;
use App\Http\Controllers\BaseController;
use App\Models\Paper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FirebaseController extends BaseController implements FirebaseControllerInterface
{
    /**
     * @var \App\Api\PaperApi $paperApi
     */
    protected $paperApi;

    /**
     * @var \App\Api\CategoryApi $categoryApi
     */
    protected $categoryApi;

    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    function __construct(
        \App\Services\FirebaseService $firebaseService,
        PaperApi $paperApi,
        CategoryApi $categoryApi,
        Request $request
    ) {
        $this->paperApi = $paperApi;
        $this->categoryApi = $categoryApi;
        $this->request = $request;
        parent::__construct($firebaseService);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function dashboard()
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
        $papers = Paper::whereNotIn('id', $uploadedIds)->where('active', '=', 1)->orderBy('id', 'DESC')->paginate(8);
        return view('adminhtml.templates.firebase.dashboard', ['listPaper' => array_slice($papersInFirebase, $this->request->get('page', 0) * 8, 8), 'papers' => $papers]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    function firebaseUploadPaper()
    {
        $params = $this->request->toArray();
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

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    function firebaseDeletePaper()
    {
        $paperId = $this->request->get('paper_id');
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

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    function firebaseSetupHomeInfo()
    {
        return view("adminhtml.templates.firebase.setupHome");
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    function firebaseUploadHomeInfo()
    {
        if ($this->paperApi->upFirebaseHomeInfo()) {
            return response(json_encode([
                'code' => 200
            ]));
        }
        return response(json_encode([
            'code' => 400
        ]));
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    function firebaseUploadCategoryTop()
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

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    function firebaseUploadCategoryTree()
    {
        try {
            // upload for category tree to firebase.
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

    /**
     * @return string
     */
    function remoteConfig(): string
    {
        $this->paperApi->getDefaultImagePath();
        return '';
    }
}
