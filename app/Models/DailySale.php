<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class DailySale extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function saleItem()
    {
        return $this->belongsTo(SaleItem::class);
    }
    public function scopeByDate(Builder $query, $date): Builder
    {
        return $query->where('sale_date', $date);
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeByDateRange(Builder $query, $startDate, $endDate): Builder
    {
        return $query->whereBetween('sale_date', [$startDate, $endDate]);
    }

    /**
     * Scope to filter by category.
     */
    public function scopeByCategory(Builder $query, $categoryId): Builder
    {
        return $query->whereHas('saleItem.saleCategory', function ($q) use ($categoryId) {
            $q->where('id', $categoryId);
        });
    }
}
