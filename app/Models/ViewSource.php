<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewSource extends Model
{
    use HasFactory;
    const PAPER_TYPE = "paper";
    const CATEGORY_TYPE = "category";
    protected $guarded = [];
}
