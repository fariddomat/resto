<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class);
    }
}
