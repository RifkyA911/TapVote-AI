<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoorprizeWinner extends Model
{
    protected $fillable = [
        'doorprize_id',
        'nik',
        'won_at',
        'status',
        'status_note',
        'received_at',
    ];

    protected $casts = [
        'won_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function doorprize(): BelongsTo
    {
        return $this->belongsTo(Doorprize::class, 'doorprize_id');
    }

    public function pemilih(): BelongsTo
    {
        return $this->belongsTo(Pemilih::class, 'nik', 'nik');
    }
}
