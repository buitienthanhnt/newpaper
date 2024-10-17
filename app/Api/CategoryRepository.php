<?php
namespace App\Api;

use App\Api\Data\Category\CategoryItem;
use App\Api\Data\Category\CategoryItemInterface;
use App\Helper\HelperFunction;
use App\Models\Category;
use App\Models\CategoryInterface;

class CategoryRepository{
    protected $category;

    protected $helperFunction;

    function __construct(
        Category $category,
        HelperFunction $helperFunction
    )
    {
        $this->category = $category;
        $this->helperFunction = $helperFunction;
    }

    /**
     * @param Category $category
     * @return CategoryItem
     */
    function convertCategoryItem(Category $category, $with_children = true){
        $_category = new CategoryItem();
        $_category->setName($category->{CategoryInterface::ATTR_NAME});
        $_category->setId($category->id);
        $_category->setActive($category->{CategoryInterface::ATTR_ACTIVE});
        $_category->setType($category->{CategoryInterface::ATTR_TYPE});
        $_category->setImagePath($this->helperFunction->replaceImageUrl($category->{CategoryInterface::ATTR_IMAGE_PATH} ?: ''));
        $_category->setParentId($category->{CategoryInterface::ATTR_PARENT_ID});
        $_category->setUrl(route('front_category', ['category' => $category->{CategoryInterface::ATTR_URL_ALIAS}]));
        if ($with_children){
            $_category->setChildrents($this->convertCategoryChildrent($category->getChildrent(), $with_children));
        }
        return $_category;
    }

    /**
     * @param Category[] $childrents
     * @return CategoryItemInterface[]
     */
    function convertCategoryChildrent($childrents, $with_children = true){
        $_childrentData = [];
        foreach ($childrents as $item) {
            $_childrentData[] = $this->convertCategoryItem($item, $with_children);
        }
        return $_childrentData;
    }

    function getById(int $category_id){
        /**
         * @var Category $category
         */
        $category = $this->category->find($category_id);
        return $this->convertCategoryItem($category);
    }
}
