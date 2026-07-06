<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectricityLog extends Model
{
    protected $fillable = ['item_id', 'log_date', 'before_kwh', 'amount', 'purchased_kwh', 'after_kwh', 'notes'];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function getAddedKwhAttribute(): float
    {
        return (float) ($this->purchased_kwh ?? ($this->after_kwh - $this->before_kwh));
    }
}
