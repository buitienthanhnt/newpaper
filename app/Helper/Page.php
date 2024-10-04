<?php

namespace App\Helper;

use App\Models\CategoryInterface;
use App\Models\PaperInterface;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 *
 */
trait Page
{
    use Nan;

    /**
     * @return bool
     */
    public function insert_page_category($page_id, $values = [])
    {
        if ($page_id && $values && $values = $this->format_page_category($page_id, $values)) {
            DB::beginTransaction();
            try {
                foreach ($values as $value) {
                    DB::table($this->pageCategoryTable())->updateOrInsert($value);
                }
                DB::commit();
                return true;
            } catch (Exception $e) {
                DB::rollBack();
                throw new Exception($e->getMessage());
            }
        }
        return false;
    }

    public function delete_page_category($paper)
    {
        $paper_categories = $paper->to_category();
        try {
            if ($categories = $paper_categories->getResults()) {
                foreach ($categories as $category) {
                    $category->forceDelele();
                }
            }
            return true;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return false;
    }

    public function delete_page_tag($paper)
    {
        $paper_tags = $paper->to_tag();
        try {
            if ($tags = $paper_tags->getResults()) {
                foreach ($tags as $tag) {
                    $tag->forceDelete();
                }
            }
            return true;
        } catch (\Throwable $th) {
            //throw $th;
        }
        return false;
    }

    /**
     * @param Paper $paper
     */
    protected function delete_paper_content($paper){
        $contents = $paper->to_contents();
        if (count($contents)) {
            foreach ($contents as $content) {
                $content->delete();
            }
        }
    }

    public function format_page_category($page_id, $values)
    {
        $result = [];
        foreach ($values as $value) {
            $result[] = array(PaperInterface::PRIMARY_ALIAS => $page_id, CategoryInterface::PRIMARY_ALIAS => $value);
        }
        return $result;
    }

    /**
     * ['value'=>'str', 'entity_id'=>'int', 'type'=>'str']
     * @return bool
     */
    public function insert_page_tag($values, $entity_id, $type)
    {
        if ($values && $entity_id && $type && $values = $this->format_page_tag($values, $entity_id, $type)) {
            DB::beginTransaction();
            try {
                foreach ($values as $value) {
                    DB::table($this->pageTagTable())->updateOrInsert($value);
                }
                DB::commit();
                return true;
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
            }
        }
        return false;
    }

    function insert_paper_price($value, $paper_id)
    {
        if ($value) {
            try {
                DB::table($this->pagePriceTable())->updateOrInsert(array("value" => $value, "paper_id" => $paper_id));
                DB::commit();
                return true;
            } catch (\Throwable $th) {
                //throw $th;
                DB::rollBack();
            }
        }
    }

    /**
     * @return array
     */
    public function format_page_tag($values, $entity_id, $type)
    {
        $result = [];
        if ($values && $entity_id && $type) {
            foreach ($values as $value) {
                $result[] = array("value" => $value, "entity_id" => $entity_id, "type" => $type);
            }
        }
        return $result;
    }
}
