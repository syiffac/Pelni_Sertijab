<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kapal extends Model
{
    use HasFactory;

    protected $table = 'kapal';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nama_kapal',
        'tipe_pax',
        'home_base',
        'status_kapal'
    ];

    // ===== RELATIONSHIPS =====

    /**
     * FIXED: Main relationship to mutasi - use singular 'mutasi' instead of 'mutasiNew'
     */
    public function mutasi(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'id_kapal', 'id');
    }

    /**
     * Alternative name for backward compatibility
     */
    public function mutasiNew(): HasMany
    {
        return $this->mutasi();
    }

    /**
     * Relasi ke ABK
     */
    public function abk(): HasMany
    {
        // Kita perlu menghubungkan melalui relasi tidak langsung
        // Dapatkan semua ABK yang sedang aktif di kapal ini melalui mutasi
        return $this->hasManyThrough(
            ABKNew::class,      // Target model
            Mutasi::class,      // Model perantara
            'id_kapal',         // Foreign key di model perantara (Mutasi)
            'id',               // Foreign key di target model (ABKNew)
            'id',               // Local key di model ini (Kapal)
            'id_abk_naik'       // Local key di model perantara (Mutasi)
        )->whereIn('mutasi_new.status_mutasi', ['Aktif', 'Proses']);
    }

    /**
     * Get mutasi with sertijab for arsip purposes
     */
    public function mutasiWithSertijab(): HasMany
    {
        return $this->mutasi()->has('sertijab');
    }

    /**
     * Get mutasi with submitted sertijab
     */
    public function mutasiWithSubmittedSertijab(): HasMany
    {
        return $this->mutasi()->whereHas('sertijab', function($query) {
            $query->whereNotNull('submitted_at');
        });
    }

    // ===== ACCESSORS & ATTRIBUTES =====

    /**
     * Get completed arsip count
     */
    public function getCompletedArsipCountAttribute()
    {
        return $this->mutasi()
            ->whereHas('sertijab', function($query) {
                $query->where('status_dokumen', 'final');
            })
            ->count();
    }

    /**
     * Get total arsip count
     */
    public function getTotalArsipCountAttribute()
    {
        return $this->mutasi()->has('sertijab')->count();
    }

    /**
     * Get draft arsip count
     */
    public function getDraftArsipCountAttribute()
    {
        return $this->mutasi()
            ->whereHas('sertijab', function($query) {
                $query->whereIn('status_dokumen', ['draft', 'partial']);
            })
            ->count();
    }

    /**
     * Get final arsip count
     */
    public function getFinalArsipCountAttribute()
    {
        return $this->mutasi()
            ->whereHas('sertijab', function($query) {
                $query->where('status_dokumen', 'final');
            })
            ->count();
    }

    /**
     * Get pending verification count
     */
    public function getPendingVerificationCountAttribute()
    {
        return $this->mutasi()
            ->whereHas('sertijab', function($query) {
                $query->where('status_dokumen', 'partial');
            })
            ->count();
    }

    // ===== SCOPES =====

    /**
     * Scope for active ships
     */
    public function scopeActive($query)
    {
        return $query->where('status_kapal', 'Aktif');
    }

    /**
     * Scope for ships with arsip
     */
    public function scopeWithArsip($query)
    {
        return $query->has('mutasiWithSertijab');
    }

    /**
     * Scope to order by name
     */
    public function scopeOrderByName($query)
    {
        return $query->orderBy('nama_kapal');
    }

    // ===== METHODS =====

    /**
     * Get arsip statistics for this kapal
     */
    public function getArsipStats()
    {
        $total = $this->total_arsip_count;
        $final = $this->final_arsip_count;
        $draft = $this->draft_arsip_count;
        $pending = $this->pending_verification_count;
        
        $completionRate = $total > 0 ? round(($final / $total) * 100) : 0;
        
        return [
            'total_arsip' => $total,
            'final_arsip' => $final,
            'draft_arsip' => $draft,
            'pending_verification' => $pending,
            'completion_rate' => $completionRate
        ];
    }

    /**
     * Check if kapal has any arsip
     */
    public function hasArsip(): bool
    {
        return $this->total_arsip_count > 0;
    }

    /**
     * Check if all arsip are completed
     */
    public function isAllArsipCompleted(): bool
    {
        return $this->hasArsip() && $this->draft_arsip_count === 0;
    }

    /**
     * Get recent mutasi with sertijab
     */
    public function getRecentMutasiWithSertijab($limit = 5)
    {
        return $this->mutasiWithSertijab()
            ->with('sertijab')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}