<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ABK extends Model
{
    protected $table = 'ABK';
    protected $primaryKey = 'NRP';
    public $incrementing = false;
    protected $keyType = 'integer';

    //protected $fillable = [
       // 'NRP',
        //'nama_ABK',
        //'status_ABK',
        //'id_kapal',
        //'id_jabatan'
   // ];

    // Relasi ke Kapal
    public function kapal()
    {
        return $this->belongsTo(Kapal::class, 'id_kapal');
    }

    // Relasi ke Jabatan
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

    // Relasi sebagai nrp_turun di Mutasi
    public function mutasiTurun()
    {
        return $this->hasMany(Mutasi::class, 'nrp_turun');
    }

    // Relasi sebagai nrp_naik di Mutasi
    public function mutasiNaik()
    {
        return $this->hasMany(Mutasi::class, 'nrp_naik');
    }
}