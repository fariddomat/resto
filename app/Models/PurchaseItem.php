<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function purchaseCategory()
    {
        return $this->belongsTo(PurchaseCategory::class);
    }
    public function dailyPurchases()
    {
        return $this->hasMany(DailyPurchase::class);
    }
}
