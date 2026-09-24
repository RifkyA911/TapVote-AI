<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilKetua extends Model
{
    use HasFactory;

    protected $table = 'hasil_ketua';

    protected $fillable = [
        'pemilih_nik',
        'ketua_nik',
    ];

    public function pemilih(): BelongsTo
    {
        return $this->belongsTo(Pemilih::class, 'pemilih_nik', 'nik');
    }

    public function kandidatKetua(): BelongsTo
    {
        return $this->belongsTo(KandidatKetua::class, 'ketua_nik', 'nik');
    }
}
