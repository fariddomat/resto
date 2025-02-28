<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyPurchase extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function purchaseItem()
    {
        return $this->belongsTo(PurchaseItem::class);
    }

    /**
     * Scope for filtering by exact date.
     */
    public function scopeByDate($query, $date)
    {
        if ($date) {
            return $query->whereDate('purchase_date', $date);
        }
        return $query;
    }

    /**
     * Scope for filtering by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('purchase_date', [$startDate, $endDate]);
        }
        return $query;
    }

    /**
     * Scope for filtering by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->whereHas('purchaseItem.purchaseCategory', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            });
        }
        return $query;
    }
}
