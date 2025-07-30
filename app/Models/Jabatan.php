<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        'id_jabatan',
        'nama_jabatan'
    ];

    // Relasi ke ABK
    public function abk()
    {
        return $this->hasMany(ABK::class, 'id_jabatan');
    }
}