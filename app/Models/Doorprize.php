<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doorprize extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'quantity',
        'sponsor',
        'icon',
        'image',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    public function winners(): HasMany
    {
        return $this->hasMany(DoorprizeWinner::class, 'doorprize_id');
    }

    public function getRemainingSlotsAttribute(): int
    {
        return max(0, $this->quantity - $this->winners()->count());
    }
}
