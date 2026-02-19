<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model
{
    protected $fillable = [
        'conference_id', 'name', 'description', 'code', 'sort_order',
    ];

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }
}
