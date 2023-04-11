<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetupCategory extends Model
{
    use HasFactory;
    protected $table = "setup_category";
    protected $guarded = [];
    use SoftDeletes;
}
