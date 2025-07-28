<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kapal extends Model
{
    protected $table = 'kapal';
    protected $primaryKey = 'id_kapal';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        'id_kapal',
        'nama_kapal',
        'jenis_kapal'
    ];

    // Relasi ke ABK
    public function abk()
    {
        return $this->hasMany(ABK::class, 'id_kapal');
    }
}