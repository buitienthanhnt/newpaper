<?php

namespace App\Api;

use App\Helper\HelperFunction;
use App\Models\ConfigCategory;
use App\Models\Writer;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

final class WriterApi extends BaseApi
{
    protected $writer;
    protected $helperFunction;
    protected $request;

    function __construct(
        HelperFunction $helperFunction,
        FirebaseService $firebaseService,
        Writer $writer,
        Request $request
    ) {
        $this->writer = $writer;
        $this->helperFunction = $helperFunction;
        $this->request = $request;
        parent::__construct($firebaseService);
    }

    function allWriter()
    {
        $writers = Writer::all();
        foreach ($writers as &$value) {
            $value->image_path = $this->helperFunction->replaceImageUrl($value->image_path);
        }
        return $writers;
    }

    function getPapers($writer_id)
    {
        $writer = $this->writer->find($writer_id);
        $p = $this->request->get('p', 1) -1 ;
        $limit = $this->request->get('limit', 12);
        $sort_by = $this->request->get('sort_by', 'created_at');
        $sort_direction = $this->request->get('direction', 'DESC') === "DESC";
        $papers = $writer->getPapers()->getResults()->sortBy($sort_by, SORT_REGULAR, $sort_direction)->skip($limit * $p)->take($limit)->makeHidden(['conten']);
        return [
            'writer' => $writer,
            'papers' => $papers,
        ];
    }
}
