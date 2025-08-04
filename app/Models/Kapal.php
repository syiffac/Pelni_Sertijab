<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kapal extends Model
{
    use HasFactory;

    protected $table = 'kapal';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nama_kapal',
        'tipe_pax',
        'home_base',
        'status_kapal'
    ];

    // ===== RELATIONSHIPS =====

    /**
     * FIXED: Relasi ke mutasi baru dengan kolom yang benar
     */
    public function mutasiNew(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'id_kapal', 'id'); // FIXED: gunakan id_kapal
    }

    /**
     * Relasi ke ABK melalui mutasi
     */
    public function abk(): HasMany
    {
        return $this->hasMany(ABK::class, 'id_kapal', 'id');
    }

    /**
     * Get mutasi with sertijab for arsip purposes
     */
    public function mutasiWithSertijab(): HasMany
    {
        return $this->mutasiNew()->has('sertijab');
    }

    /**
     * Get completed arsip count
     */
    public function getCompletedArsipCountAttribute()
    {
        return $this->mutasiNew()
            ->whereHas('sertijab', function($query) {
                $query->where('status_dokumen', 'final');
            })
            ->count();
    }

    /**
     * Get total arsip count
     */
    public function getTotalArsipCountAttribute()
    {
        return $this->mutasiNew()->has('sertijab')->count();
    }

    /**
     * Get draft arsip count
     */
    public function getDraftArsipCountAttribute()
    {
        return $this->mutasiNew()
            ->whereHas('sertijab', function($query) {
                $query->whereIn('status_dokumen', ['draft', 'partial']);
            })
            ->count();
    }
}