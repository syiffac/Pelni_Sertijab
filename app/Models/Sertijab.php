<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sertijab extends Model
{
    use HasFactory;

    protected $table = 'sertijab';
    protected $primaryKey = 'id_sertijab';
    public $timestamps = true;
    
    protected $fillable = [
        'id_mutasi',
        'file_path',
        'status_verifikasi',
        'notes',
        'verified_by_admin_nrp',
        'verified_at',
        'uploaded_at',
        'keterangan_pengunggah_puk'
    ];
    
    protected $casts = [
        'verified_at' => 'datetime',
        'uploaded_at' => 'datetime',
    ];
    
    /**
     * Get the mutasi that owns the sertijab
     */
    public function mutasi(): BelongsTo
    {
        return $this->belongsTo(Mutasi::class, 'id_mutasi', 'id_mutasi');
    }
    
    /**
     * Get the admin verifikator (if Admin model exists)
     */
    public function adminVerifikator(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'verified_by_admin_nrp', 'NRP_admin');
    }
}