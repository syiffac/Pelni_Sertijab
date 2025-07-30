<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kapal extends Model
{
    use HasFactory;

    protected $table = 'kapal';
    protected $primaryKey = 'id_kapal';
    
    protected $fillable = [
        'nama_kapal',
        'jenis_kapal',
        'kapasitas_penumpang',
        'tahun_pembuatan',
        'flag_state',
        'status_kapal'
    ];

    /**
     * Get the ABK for this kapal
     */
    public function abk(): HasMany
    {
        return $this->hasMany(ABK::class, 'id_kapal', 'id_kapal');
    }

    /**
     * Get mutasi where this kapal is the source
     */
    public function mutasiAsal(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'id_kapal_asal', 'id_kapal');
    }

    /**
     * Get mutasi where this kapal is the destination
     */
    public function mutasiTujuan(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'id_kapal_tujuan', 'id_kapal');
    }

    /**
     * Get all mutasi related to this kapal (asal or tujuan)
     */
    public function allMutasi()
    {
        return Mutasi::where('id_kapal_asal', $this->id_kapal)
                     ->orWhere('id_kapal_tujuan', $this->id_kapal);
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