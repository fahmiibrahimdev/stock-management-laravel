<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockManage extends Model
{
    use HasFactory;
    protected $table = "stock_manages";
    protected $guarded = [];
}
