<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfigCategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    const TOP_CATEGORY = "top_category";
    use SoftDeletes;
}
