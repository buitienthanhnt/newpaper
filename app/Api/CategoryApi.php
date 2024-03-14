<?php

namespace App\Api;

use App\Helper\HelperFunction;
use App\Models\Category;
use App\Models\ConfigCategory;
use App\Services\FirebaseService;
use Thanhnt\Nan\Helper\LogTha;

final class CategoryApi extends BaseApi
{
    protected $category;
    protected $helperFunction;

    function __construct(
        HelperFunction $helperFunction,
        FirebaseService $firebaseService,
        Category $category,
        LogTha $logTha
    ) {
        $this->category = $category;
        $this->helperFunction = $helperFunction;
        parent::__construct($firebaseService, $logTha);
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
        try {
            $userRef = $this->firebaseDatabase->getReference('/newpaper/categoryTop');
            if ($userRef->getSnapshot()->getValue()) {
                $userRef->remove();
            }
            $userRef->push($this->categoryTopForFirebase() ?: null);
            $snapshot = $userRef->getSnapshot();
            return [
                'status' => true,
                'value' => $snapshot->getValue()
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'value' => null
            ];
        }
    }

    function asyncCategory(): void
    {
        $categoryTree = $this->category->getCategoryTree();
        $userRef = $this->firebaseDatabase->getReference('/newpaper/category');
        if ($userRef->getSnapshot()->getValue()) {
            $userRef->remove();
        }
        $userRef->push($categoryTree ?: null);
    }
}
