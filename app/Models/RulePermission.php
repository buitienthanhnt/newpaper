<?php

namespace App\Models;

use App\Helper\Nan;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RulePermission extends Model
{
    use HasFactory;
    use Nan;
    protected $guarded = [];

    function insert_permission_rules($permission_id, $rule_ids) {
        if ($rule_ids && $permission_id) {
            DB::beginTransaction();
            try {
                foreach ($rule_ids as $value) {
                    DB::table($this->permissionRulesTable())->updateOrInsert(["permission_id" => $permission_id, "rule_id" => $value]);
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
}
