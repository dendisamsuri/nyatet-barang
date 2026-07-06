<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = ['item_id', 'service_date', 'notes'];

    protected function casts(): array
    {
        return [
            'service_date' => 'date',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(ServiceDetail::class);
    }

    public function getTotalCostAttribute(): float
    {
        return (float) $this->details()->sum('price');
    }
}
