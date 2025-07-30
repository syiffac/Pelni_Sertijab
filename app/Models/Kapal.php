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
        'jenis_kapal',
    ];

    /**
     * Get the ABK for this kapal
     */
    public function abk(): HasMany
    {
        return $this->hasMany(ABK::class, 'id_kapal', 'id');
    }

    public function abkAktif()
    {
        return $this->hasMany(ABK::class, 'id_kapal', 'id')->where('status_abk', 'Aktif');
    }

    /**
     * Get ABK tidak aktif untuk kapal ini
     */
    public function abkTidakAktif()
    {
        return $this->hasMany(ABK::class, 'id_kapal', 'id')->where('status_abk', '!=', 'Aktif');
    }

    /**
     * Get mutasi where this kapal is the source
     */
    public function mutasiAsal(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'id_kapal_asal', 'id');
    }

    /**
     * Get mutasi where this kapal is the destination
     */
    public function mutasiTujuan(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'id_kapal_tujuan', 'id');
    }

    /**
     * Get all mutasi related to this kapal (asal or tujuan)
     */
    public function allMutasi()
    {
        return Mutasi::where('id_kapal_asal', $this->id)
                     ->orWhere('id_kapal_tujuan', $this->id);
    }

    /**
     * Get count of arsip sertijab for this kapal
     */
    public function getArsipStats()
    {
        $totalArsip = $this->allMutasi()
            ->whereHas('sertijab')
            ->count();

        $finalArsip = $this->allMutasi()
            ->whereHas('sertijab', function($query) {
                $query->where('status_verifikasi', 'verified');
            })
            ->count();

        $draftArsip = $this->allMutasi()
            ->whereHas('sertijab', function($query) {
                $query->whereIn('status_verifikasi', ['pending', 'rejected']);
            })
            ->count();

        return [
            'total_arsip' => $totalArsip,
            'final_arsip' => $finalArsip,
            'draft_arsip' => $draftArsip,
        ];
    }
}