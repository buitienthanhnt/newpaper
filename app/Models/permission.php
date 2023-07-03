<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model
{
    use HasFactory;
    protected $guarded = [];

    function rulePermission() : HasMany {
        return $this->hasMany(RulePermission::class, "permission_id");
    }
}
