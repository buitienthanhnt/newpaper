<?php

namespace App\Helper;

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

    public function format_page_category($page_id, $values)
    {
        $result = [];
        foreach ($values as $value) {
            $result[] = array("page_id" => $page_id, "category_id" => $value);
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
