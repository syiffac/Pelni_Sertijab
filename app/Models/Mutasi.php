<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    protected $table = 'mutasi';
    protected $primaryKey = 'id_mutasi';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        'id_mutasi',
        'nrp_turun',
        'nrp_naik',
        'id_kapal_asal',
        'id_kapal_tujuan',
        'id_jabatan_lama',
        'id_jabatan_baru',
        'case_mutasi',
        'jenis_mutasi',
        'nama_mutasi',
        'notes_mutasi',
        'perlu_sertijab',
        'TMT',
        'TAT'
    ];

    protected $dates = ['TMT', 'TAT'];

    // Relasi ke ABK yang turun
    public function abkTurun()
    {
        return $this->belongsTo(ABK::class, 'nrp_turun', 'NRP');
    }

    // Relasi ke ABK yang naik
    public function abkNaik()
    {
        return $this->belongsTo(ABK::class, 'nrp_naik', 'NRP');
    }

    // Relasi ke Kapal asal
    public function kapalAsal()
    {
        return $this->belongsTo(Kapal::class, 'id_kapal_asal');
    }

    // Relasi ke Kapal tujuan
    public function kapalTujuan()
    {
        return $this->belongsTo(Kapal::class, 'id_kapal_tujuan');
    }

    // Relasi ke Jabatan lama
    public function jabatanLama()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan_lama');
    }

    // Relasi ke Jabatan baru
    public function jabatanBaru()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan_baru');
    }

    // Relasi ke Sertijab
    public function sertijab()
    {
        return $this->hasOne(Sertijab::class, 'id_mutasi');
    }
}