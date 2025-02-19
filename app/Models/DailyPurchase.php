<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyPurchase extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function purchaseItem()
    {
        return $this->belongsTo(PurchaseItem::class);
    }
}
