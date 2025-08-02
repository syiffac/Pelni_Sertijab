<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = true;

    protected $fillable = [
        'nama_jabatan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
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

    // Scope untuk pencarian berdasarkan nama
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('nama_jabatan', 'LIKE', "%{$search}%");
        }
        return $query;
    }

    // Scope untuk ordering
    public function scopeOrdered($query, $direction = 'asc')
    {
        return $query->orderBy('nama_jabatan', $direction);
    }

    // Accessor untuk nama lengkap jabatan (sama dengan nama jabatan karena tidak ada kode)
    public function getNamaLengkapAttribute()
    {
        return $this->nama_jabatan;
    }

    // Method untuk get display text (untuk dropdown)
    public function getDisplayTextAttribute()
    {
        return $this->nama_jabatan;
    }

    // Method untuk cek apakah jabatan memiliki ABK
    public function hasABK()
    {
        return $this->abkNew()->exists();
    }

    // Method untuk hitung jumlah ABK dengan jabatan ini
    public function countABK()
    {
        return $this->abkNew()->count();
    }

    // Method untuk cek apakah jabatan digunakan dalam mutasi
    public function isUsedInMutasi()
    {
        return $this->mutasiJabatanTetapNaik()->exists() || 
               $this->mutasiJabatanTetapTurun()->exists() || 
               $this->mutasiJabatanMutasi()->exists();
    }

    // Method untuk validasi sebelum delete
    public function canBeDeleted()
    {
        return !$this->hasABK() && !$this->isUsedInMutasi();
    }

    // Static method untuk dropdown options
    public static function getDropdownOptions()
    {
        return static::ordered()
            ->get()
            ->mapWithKeys(function ($jabatan) {
                return [$jabatan->id => $jabatan->nama_jabatan];
            });
    }

    // Static method untuk Select2 format
    public static function getSelect2Options($search = null)
    {
        return static::search($search)
            ->ordered()
            ->get()
            ->map(function ($jabatan) {
                return [
                    'id' => $jabatan->id,
                    'text' => $jabatan->nama_jabatan,
                    'nama_jabatan' => $jabatan->nama_jabatan
                ];
            });
    }
}