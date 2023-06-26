<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rule extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "rule";
    protected $guarded = [];
    protected $_selected = null;

    /**
     * hasOne tìm 1 khóa chính -> 1 khóa phụ.
     */
    // function toParent() : HasOne {
    //     return $this->hasOne($this, "parent_id");
    // }

    // belongsTo tìm 1 khóa phụ -> 1 khóa chính.
    function toParent() : belongsTo {
        return $this->belongsTo($this, "parent_id");
    }

    function getParent() {
        $parent = $this->toParent();
        return $parent->getResults() ?: null;
    }

    function toChildren() : HasMany {
        return $this->hasMany($this, "id", "parent_id");
    }


    function getChildren() {
        return $this->toChildren()->getResults() ?: null;
    }

    public function rule_tree_option($rule = null)
    {
        $parent_rule = '<option value="0">Root rule</option>';
        $list_rule = $this->all()->where("parent_id", "=", 0);
        if ($list_rule->count()) {
            if ($rule) {
                $parent_rule.= $this->rule_tree($list_rule, "", $rule->parent_id);
            }else {
                $parent_rule.= $this->rule_tree($list_rule);
            }

        }
        return $parent_rule;
    }

    protected function rule_tree($rule, $begin = "", $selected = null)
    {
        $html = "";
        foreach ($rule as $rul) {
            $html.='<option value="'.$rul->id.'" '.($this->_selected ? (in_array($rul->id, $this->_selected) ? "selected " : "") : ($selected === $rul->id ? "selected " : "")).'>'.$begin.$rul->label.'</option>';
            if ($list_rule = $this->all()->where("parent_id", "=", $rul->id)) {
                if ($list_rule->count()) {
                    $_be = $begin;
                    $begin.="___";
                    $html.=$this->rule_tree($list_rule, $begin, $selected);
                    $begin = $_be;
                }else {
                    continue;
                }
            }
        }
        return $html;
    }

    public function setSelected($_selected = [])
    {
        $this->_selected = $_selected;
        return $this;
    }
}
