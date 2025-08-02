<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ABKNew extends Model
{
    use HasFactory;

    protected $table = 'abk_new';
    protected $primaryKey = 'id';
    public $incrementing = true; // Auto increment ID
    protected $keyType = 'int'; // Primary key bertipe integer
    
    protected $fillable = [
        'nama_abk',
        'id_jabatan_tetap',
        'status_abk'
    ];

    protected $casts = [
        'status_abk' => 'string',
        'id_jabatan_tetap' => 'integer'
    ];

    /**
     * Relasi ke Jabatan (jabatan tetap)
     */
    public function jabatanTetap(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan_tetap', 'id');
    }

    /**
     * Relasi ke Mutasi sebagai ABK naik
     */
    public function mutasiSebagaiNaik(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'id_abk_naik', 'id');
    }

    /**
     * Relasi ke Mutasi sebagai ABK turun
     */
    public function mutasiSebagaiTurun(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'id_abk_turun', 'id');
    }

    /**
     * Relasi ke semua mutasi (naik atau turun)
     */
    public function semuaMutasi()
    {
        return $this->mutasiSebagaiNaik()->union($this->mutasiSebagaiTurun());
    }

    /**
     * Scope untuk ABK organik
     */
    public function scopeOrganik($query)
    {
        return $query->where('status_abk', 'Organik');
    }

    /**
     * Scope untuk ABK non organik
     */
    public function scopeNonOrganik($query)
    {
        return $query->where('status_abk', 'Non Organik');
    }

    /**
     * Scope untuk ABK aktif (organik + non organik)
     */
    public function scopeAktif($query)
    {
        return $query->whereIn('status_abk', ['Organik', 'Non Organik']);
    }

    /**
     * Scope untuk ABK pensiun
     */
    public function scopePensiun($query)
    {
        return $query->where('status_abk', 'Pensiun');
    }

    /**
     * Scope untuk pencarian berdasarkan nama
     */
    public function scopeCariNama($query, $nama)
    {
        return $query->where('nama_abk', 'LIKE', '%' . $nama . '%');
    }

    /**
     * Scope untuk filter berdasarkan jabatan
     */
    public function scopeByJabatan($query, $jabatanId)
    {
        return $query->where('id_jabatan_tetap', $jabatanId);
    }

    /**
     * Accessor untuk mendapatkan nama lengkap dengan ID
     */
    public function getNamaLengkapAttribute()
    {
        return $this->id . ' - ' . $this->nama_abk;
    }

    /**
     * Accessor untuk status ABK dengan styling
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Organik' => '<span class="badge bg-success">Organik</span>',
            'Non Organik' => '<span class="badge bg-info">Non Organik</span>',
            'Pensiun' => '<span class="badge bg-secondary">Pensiun</span>'
        ];
        
        return $badges[$this->status_abk] ?? '<span class="badge bg-warning">Unknown</span>';
    }

    /**
     * Method untuk cek apakah ABK sedang dalam mutasi
     */
    public function isInMutasi()
    {
        return $this->mutasiSebagaiNaik()
                   ->whereIn('status_mutasi', ['Draft', 'Disetujui'])
                   ->exists() ||
               $this->mutasiSebagaiTurun()
                   ->whereIn('status_mutasi', ['Draft', 'Disetujui'])
                   ->exists();
    }

    /**
     * Method untuk mendapatkan mutasi aktif
     */
    public function getMutasiAktif()
    {
        return $this->mutasiSebagaiNaik()
                   ->whereIn('status_mutasi', ['Draft', 'Disetujui'])
                   ->orWhere(function($query) {
                       $query->where('id_abk_turun', $this->id)
                             ->whereIn('status_mutasi', ['Draft', 'Disetujui']);
                   })
                   ->first();
    }

    /**
     * Boot method untuk events
     */
    protected static function boot()
    {
        parent::boot();

        // Event ketika ABK dibuat
        static::creating(function ($abk) {
            // Bisa tambahkan logic khusus disini
        });

        // Event ketika ABK diupdate
        static::updating(function ($abk) {
            // Bisa tambahkan logic khusus disini
        });
    }
}
