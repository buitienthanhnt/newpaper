<?php

namespace App\Api;

use App\Helper\HelperFunction;
use App\Models\ConfigCategory;
use App\Models\Writer;
use App\Services\FirebaseService;

final class WriterApi extends BaseApi
{
    protected $writer;
    protected $helperFunction;

    function __construct(
        HelperFunction $helperFunction,
        FirebaseService $firebaseService,
        Writer $writer
    ) {
        $this->writer = $writer;
        $this->helperFunction = $helperFunction;
        parent::__construct($firebaseService);
    }

    function allWriter() {
        $writers = Writer::all();
        foreach ($writers as &$value) {
            $value->image_path = $this->helperFunction->replaceImageUrl($value->image_path);
        }
        return $writers;
    }
}
