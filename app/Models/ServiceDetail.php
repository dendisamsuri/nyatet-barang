<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceDetail extends Model
{
    protected $fillable = ['service_id', 'name', 'price', 'notes'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
