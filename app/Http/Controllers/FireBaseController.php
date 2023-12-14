<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Paper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FireBaseController extends BaseController
{
    const ROOT_DATABASE = "/newpaper";
    /**
     * @var Category $category
     */
    protected $category;

    function __construct(
        \App\Services\FirebaseService $firebaseService,
        Category $category
    ) {
        $this->category = $category;
        parent::__construct(
            $firebaseService
        );
    }


    function upPaper(Request $request)
    {
        /**
         * khong lay field conten
         */
        $paper = Paper::find($request->get('paper_id', 1))->makeHidden(['conten'])->toArray();
        if ($paper) {
            try {
                $userRef = $this->database->getReference('/newpaper/papers');
                $userRef->push($paper);
            } catch (Exception $exception) {
                Log::error($exception->getMessage());
            }
            $snapshot = $userRef->getSnapshot();
            dd($snapshot->getValue());
        } else {
            dd('not has data!');
        }
    }

    function asyncCategory(Request $request)
    {
        $categoryTree = $this->category->getCategoryTree();
        $database = $this->database;
        try {
            $ref = $database->getReference($this->useRef('category'));
            $ref->push($categoryTree);
            $snapshot = $ref->getSnapshot();
            dd($snapshot);
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
        return 123;
    }

    function useRef(string $ref): string
    {
        return self::ROOT_DATABASE . '/' . $ref;
    }
}
