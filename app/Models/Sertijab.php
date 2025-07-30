<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertijab extends Model
{
    protected $table = 'sertijab';
    protected $primaryKey = 'id_sertijab';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        'id_sertijab',
        'id_mutasi',
        'file_path',
        'status_verifikasi',
        'notes',
        'keterangan_pengunggah_puk',
        'uploaded_at',
        'verified_by_admin_nrp',
        'verified_at'
    ];

    protected $dates = ['uploaded_at', 'verified_at'];

    // Relasi ke Mutasi
    public function mutasi()
    {
        return $this->belongsTo(Mutasi::class, 'id_mutasi');
    }

    // Relasi ke Admin verifikator
    public function adminVerifikator()
    {
        return $this->belongsTo(User::class, 'verified_by_admin_nrp', 'NRP_admin');
    }
}