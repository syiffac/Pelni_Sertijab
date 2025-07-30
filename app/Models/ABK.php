<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ABK extends Model
{
    protected $table = 'abk';
    protected $primaryKey = 'id_abk';
    public $timestamps = true;

    protected $fillable = [
        'id_kapal',
        'nrp_naik',
        'nama_naik',
        'jabatan_naik',
        'nama_mutasi',
        'jenis_mutasi',
        'tmt',
        'tat',
        'nrp_turun',
        'nama_turun',
        'jabatan_turun',
        'alasan_turun',
        'keterangan_turun',
        'status_abk'
    ];

    protected $casts = [
        'tmt' => 'date',
        'tat' => 'date'
    ];

    /**
     * Relationship dengan Kapal
     */
    public function kapal()
    {
        return $this->belongsTo(Kapal::class, 'id_kapal', 'id_kapal');
    }

    /**
     * Relationship dengan Jabatan (yang naik)
     */
    public function jabatanNaik()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_naik', 'id_jabatan');
    }

    /**
     * Relationship dengan Jabatan (yang turun)
     */
    public function jabatanTurun()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_turun', 'id_jabatan');
    }

    // Accessor untuk mendapatkan nama jabatan
    public function getJabatanNaikNamaAttribute()
    {
        return $this->jabatanNaik ? $this->jabatanNaik->nama_jabatan : '-';
    }

    public function getJabatanTurunNamaAttribute()
    {
        return $this->jabatanTurun ? $this->jabatanTurun->nama_jabatan : '-';
    }
}