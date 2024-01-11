<?php

namespace App\Api;

use App\Helper\HelperFunction;
use App\Models\Category;
use App\Models\ConfigCategory;
use App\Services\FirebaseService;

final class CategoryApi extends BaseApi
{
    protected $helperFunction;

    function __construct(
        HelperFunction $helperFunction,
        FirebaseService $firebaseService
    ) {
        $this->helperFunction = $helperFunction;
        parent::__construct($firebaseService);
    }

    function Topcategories()
    {
        $top_category = ConfigCategory::where("path", "=", ConfigCategory::TOP_CATEGORY);
        $values = Category::whereIn("id", explode("&", $top_category->first()->value))->get()->toArray();
        return $values;
    }

    function categoryTop()
    {
        $topCategories = $this->Topcategories();
        foreach ($topCategories as &$value) {
            $value["image_path"] = $this->helperFunction->replaceImageUrl($value["image_path"] ?: '');
        }
        return $topCategories;
    }

    function categoryTopForFirebase()
    {
        $topCategories = $this->Topcategories();
        foreach ($topCategories as &$category) {
            if (empty($category['image_path'])) {
                continue;
            }
            $category["image_path"] = $this->upLoadImageFirebase($category['image_path']);
        }
        return $topCategories;
    }

    function addCategoryTopFirebase()
    {
        $userRef = $this->firebaseDatabase->getReference('/newpaper/categoryTop');
        $userRef->push($this->categoryTopForFirebase() ?: null);
        $snapshot = $userRef->getSnapshot();
        return [
            'status' => true,
            'value' => $snapshot->getValue()
        ];
    }
}
