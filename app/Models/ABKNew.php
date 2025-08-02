<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ABKNew extends Model
{
    use HasFactory;

    protected $table = 'abk_new';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
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
     * Relasi ke Kapal aktif
     */
    public function kapalAktif()
    {
        // Sesuaikan dengan struktur database Anda
        return $this->belongsTo(Kapal::class, 'id_kapal_aktif', 'id');
    }

    // COMMENT OUT relasi mutasi sampai model Mutasi dibuat
    // /**
    //  * Relasi ke Mutasi sebagai ABK naik
    //  */
    // public function mutasiSebagaiNaik(): HasMany
    // {
    //     return $this->hasMany(Mutasi::class, 'id_abk_naik', 'id');
    // }

    // /**
    //  * Relasi ke Mutasi sebagai ABK turun
    //  */
    // public function mutasiSebagaiTurun(): HasMany
    // {
    //     return $this->hasMany(Mutasi::class, 'id_abk_turun', 'id');
    // }

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
     * Scope untuk pencarian berdasarkan nama atau NRP
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('id', 'LIKE', '%' . $search . '%')
              ->orWhere('nama_abk', 'LIKE', '%' . $search . '%');
        });
    }

    /**
     * Scope untuk filter berdasarkan jabatan
     */
    public function scopeByJabatan($query, $jabatanId)
    {
        return $query->where('id_jabatan_tetap', $jabatanId);
    }

    /**
     * Accessor untuk mendapatkan nama lengkap dengan NRP
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
     * Method untuk cek apakah ABK sedang dalam mutasi - DIPERBAIKI
     */
    public function isInMutasi()
    {
        try {
            // Cek apakah tabel mutasi ada
            if (!Schema::hasTable('mutasi')) {
                return false;
            }
            
            // Gunakan query langsung karena relasi belum dibuat
            $mutasiCount = DB::table('mutasi')
                ->where(function($query) {
                    // Cek berbagai kemungkinan nama kolom
                    if (Schema::hasColumn('mutasi', 'id_abk_naik')) {
                        $query->where('id_abk_naik', $this->id);
                    } elseif (Schema::hasColumn('mutasi', 'nrp_naik')) {
                        $query->where('nrp_naik', $this->id);
                    }
                })
                ->orWhere(function($query) {
                    if (Schema::hasColumn('mutasi', 'id_abk_turun')) {
                        $query->where('id_abk_turun', $this->id);
                    } elseif (Schema::hasColumn('mutasi', 'nrp_turun')) {
                        $query->where('nrp_turun', $this->id);
                    }
                })
                ->whereIn('status_mutasi', ['Draft', 'Disetujui', 'Pending', 'Proses'])
                ->count();
                
            return $mutasiCount > 0;
            
        } catch (\Exception $e) {
            Log::error('Error checking mutasi for ABK ' . $this->id . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Method untuk validasi format NRP
     */
    public static function validateNRP($nrp)
    {
        // Validasi format NRP - hanya angka, 4-20 karakter
        return preg_match('/^[0-9]{4,20}$/', $nrp);
    }

    /**
     * Method untuk cek apakah NRP sudah ada
     */
    public static function isNRPExists($nrp, $excludeId = null)
    {
        $query = static::where('id', $nrp);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Boot method untuk events
     */
    protected static function boot()
    {
        parent::boot();

        // Event ketika ABK dibuat
        static::creating(function ($abk) {
            // Validasi NRP format
            if (!static::validateNRP($abk->id)) {
                throw new \InvalidArgumentException('Format NRP tidak valid. Gunakan angka saja, 4-20 digit.');
            }
            
            // Pastikan NRP unique
            if (static::isNRPExists($abk->id)) {
                throw new \InvalidArgumentException('NRP sudah terdaftar dalam sistem.');
            }
        });

        // Event ketika ABK diupdate
        static::updating(function ($abk) {
            // Validasi NRP format jika diubah
            if ($abk->isDirty('id')) {
                if (!static::validateNRP($abk->id)) {
                    throw new \InvalidArgumentException('Format NRP tidak valid. Gunakan angka saja, 4-20 digit.');
                }
                
                // Pastikan NRP unique (kecuali untuk record ini sendiri)
                if (static::isNRPExists($abk->id, $abk->getOriginal('id'))) {
                    throw new \InvalidArgumentException('NRP sudah terdaftar dalam sistem.');
                }
            }
        });
    }

    /**
     * Method untuk mendapatkan statistik ABK
     */
    public static function getStatistik()
    {
        return [
            'total_abk' => static::count(),
            'abk_aktif' => static::aktif()->count(),
            'abk_organik' => static::organik()->count(),
            'abk_non_organik' => static::nonOrganik()->count(),
            'abk_pensiun' => static::pensiun()->count()
        ];
    }

    /**
     * Method untuk cek apakah ABK bisa dihapus
     */
    public function canBeDeleted()
    {
        // Tidak bisa dihapus jika sedang dalam mutasi
        if ($this->isInMutasi()) {
            return false;
        }
        
        return true;
    }

    /**
     * Method untuk mendapatkan status detail ABK
     */
    public function getDetailedStatus()
    {
        return [
            'basic_status' => $this->status_abk,
            'is_active' => in_array($this->status_abk, ['Organik', 'Non Organik']),
            'is_in_mutasi' => $this->isInMutasi(),
            'can_be_deleted' => $this->canBeDeleted()
        ];
    }
}
