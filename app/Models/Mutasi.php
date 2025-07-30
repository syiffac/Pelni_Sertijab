<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mutasi extends Model
{
    use HasFactory;

    protected $table = 'mutasi';
    protected $primaryKey = 'id_mutasi';
    
    protected $fillable = [
        'nrp_turun',
        'nrp_naik',
        'id_kapal_asal',
        'id_kapal_tujuan',
        'id_jabatan_lama',
        'id_jabatan_baru',
        'case_mutasi',
        'jenis_mutasi',
        'nama_mutasi',
        'TMT',
        'TAT',
        'notes_mutasi',
        'perlu_sertijab'
    ];

    protected $casts = [
        'TMT' => 'datetime',
        'TAT' => 'datetime',
        'perlu_sertijab' => 'boolean'
    ];

    /**
     * Get the ABK turun
     */
    public function abkTurun(): BelongsTo
    {
        return $this->belongsTo(ABK::class, 'nrp_turun', 'NRP');
    }

    /**
     * Get the ABK naik
     */
    public function abkNaik(): BelongsTo
    {
        return $this->belongsTo(ABK::class, 'nrp_naik', 'NRP');
    }

    /**
     * Get the kapal asal
     */
    public function kapalAsal(): BelongsTo
    {
        return $this->belongsTo(Kapal::class, 'id_kapal_asal', 'id_kapal');
    }

    /**
     * Get the kapal tujuan
     */
    public function kapalTujuan(): BelongsTo
    {
        return $this->belongsTo(Kapal::class, 'id_kapal_tujuan', 'id_kapal');
    }

    /**
     * Get the jabatan lama
     */
    public function jabatanLama(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan_lama', 'id_jabatan');
    }

    /**
     * Get the jabatan baru
     */
    public function jabatanBaru(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan_baru', 'id_jabatan');
    }

    /**
     * Get the sertijab
     */
    public function sertijab(): HasOne
    {
        return $this->hasOne(Sertijab::class, 'id_mutasi', 'id_mutasi');
    }
}