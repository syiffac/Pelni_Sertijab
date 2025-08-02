<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';

    protected $fillable = [
        'nama_jabatan',
        'kode_jabatan',
        'deskripsi',
        'level_jabatan',
        'status_aktif'
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'level_jabatan' => 'integer'
    ];

    // Relasi ke ABK New
    public function abkNew(): HasMany
    {
        return $this->hasMany(ABKNew::class, 'id_jabatan_tetap', 'id');
    }

    // Relasi ke Mutasi sebagai jabatan tetap naik
    public function mutasiJabatanTetapNaik(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'jabatan_tetap_naik', 'id');
    }

    // Relasi ke Mutasi sebagai jabatan tetap turun
    public function mutasiJabatanTetapTurun(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'jabatan_tetap_turun', 'id');
    }

    // Relasi ke Mutasi sebagai jabatan mutasi
    public function mutasiJabatanMutasi(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'id_jabatan_mutasi', 'id');
    }

    // Scope untuk jabatan aktif
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    // Scope untuk level jabatan tertentu
    public function scopeLevel($query, $level)
    {
        return $query->where('level_jabatan', $level);
    }

    // Accessor untuk nama lengkap jabatan
    public function getNamaLengkapAttribute()
    {
        $namaLengkap = $this->nama_jabatan;
        if ($this->kode_jabatan) {
            $namaLengkap .= ' (' . $this->kode_jabatan . ')';
        }
        return $namaLengkap;
    }

    // Method untuk cek apakah jabatan ini level tinggi
    public function isLevelTinggi()
    {
        return $this->level_jabatan >= 3; // Asumsi level 3+ adalah jabatan tinggi
    }
}