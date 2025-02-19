<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseCategory extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
