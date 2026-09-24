<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilPengawas extends Model
{
    use HasFactory;

    protected $table = 'hasil_pengawas';

    protected $fillable = [
        'pemilih_nik',
        'pengawas_nik',
    ];

    public function pemilih(): BelongsTo
    {
        return $this->belongsTo(Pemilih::class, 'pemilih_nik', 'nik');
    }

    public function kandidatPengawas(): BelongsTo
    {
        return $this->belongsTo(KandidatPengawas::class, 'pengawas_nik', 'nik');
    }
}
