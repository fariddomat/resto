<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleCategory extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
