<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pemilih extends Model
{
    use HasFactory;

    protected $table = 'pemilih';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'rfid',
        'nama',
        'dept',
        'pilih',
        'voted_at',
    ];

    protected function casts(): array
    {
        return [
            'voted_at' => 'datetime',
        ];
    }

    public function sudahMemilih(): bool
    {
        return $this->pilih === 'T';
    }

    public function hasilKetua(): HasOne
    {
        return $this->hasOne(HasilKetua::class, 'pemilih_nik', 'nik');
    }

    public function hasilPengawas(): HasOne
    {
        return $this->hasOne(HasilPengawas::class, 'pemilih_nik', 'nik');
    }
}
