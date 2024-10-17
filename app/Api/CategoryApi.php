<?php

namespace App\Api;

use App\Api\Data\Category\CategoryItemInterface;
use App\Helper\HelperFunction;
use App\Models\Category;
use App\Models\CategoryInterface;
use App\Models\ConfigCategory;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Cache;
use Thanhnt\Nan\Helper\LogTha;

final class CategoryApi extends BaseApi
{
    protected $category;
    protected $categoryRepository;
    protected $helperFunction;

    function __construct(
        HelperFunction $helperFunction,
        FirebaseService $firebaseService,
        Category $category,
        CategoryRepository $categoryRepository,
        LogTha $logTha
    ) {
        $this->category = $category;
        $this->categoryRepository = $categoryRepository;
        $this->helperFunction = $helperFunction;
        parent::__construct($firebaseService, $logTha);
    }

    function getCategoryById(int $category_id){
        $category_cache = 'category.info.'.$category_id;
        if (Cache::has($category_cache) && false){
            return  Cache::get($category_cache);
        }else{
            $value = $this->categoryRepository->getById($category_id);
            Cache::put($category_cache, $value);
            return $value;
        }
    }

    /**
     * @return CategoryItemInterface[]
     */
    function getCategoryTree(){
        $categories = Category::where(CategoryInterface::ATTR_PARENT_ID, 0)->get();
        return $this->categoryRepository->convertCategoryChildrent($categories);
    }

    /**
     * @return CategoryItemInterface[]
     */
    function getCategoryTop()
    {
        $top_category = ConfigCategory::where("path", "=", ConfigCategory::TOP_CATEGORY);
        $values = Category::whereIn("id", explode("&", $top_category->first()->value))->get();
        return $this->categoryRepository->convertCategoryChildrent($values, false);
    }

//    ===================================================================================================

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
