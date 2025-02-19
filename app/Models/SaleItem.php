<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function saleCategory()
    {
        return $this->belongsTo(SaleCategory::class);
    }
    public function dailySales()
    {
        return $this->hasMany(DailySale::class);
    }

}
