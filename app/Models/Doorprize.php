<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doorprize extends Model
{
    protected $fillable = [
        'title',
        'category',
        'quantity',
        'sponsor',
        'icon',
    ];

    public function winners(): HasMany
    {
        return $this->hasMany(DoorprizeWinner::class, 'doorprize_id');
    }

    public function getRemainingSlotsAttribute(): int
    {
        return max(0, $this->quantity - $this->winners()->count());
    }
}
