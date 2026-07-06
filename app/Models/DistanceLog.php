<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistanceLog extends Model
{
    protected $fillable = ['item_id', 'log_date', 'distance', 'notes'];

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
}
