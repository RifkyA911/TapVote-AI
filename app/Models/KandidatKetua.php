<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KandidatKetua extends Model
{
    use HasFactory;

    protected $table = 'kandidat_ketua';
    protected $primaryKey = 'nik';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nik',
        'nama',
        'foto',
        'visi',
        'misi',
        'deskripsi',
        'nomor_urut',
    ];

    public function perolehanSuara(): HasMany
    {
        return $this->hasMany(HasilKetua::class, 'ketua_nik', 'nik');
    }

    public function totalSuara(): int
    {
        return $this->perolehanSuara()->count();
    }
}
