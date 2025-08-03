<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Mutasi extends Model
{
    use HasFactory;

    protected $table = 'mutasi_new'; // UBAH INI - gunakan tabel baru
    protected $primaryKey = 'id';
    
    protected $fillable = [
        // Data Kapal
        'id_kapal',
        'nama_kapal',
        
        // Data ABK naik
        'id_abk_naik',
        'nama_lengkap_naik',
        'jabatan_tetap_naik',
        'id_jabatan_mutasi',
        'nama_mutasi',
        'jenis_mutasi',
        'TMT',
        'TAT',
        
        // Data ABK turun
        'id_abk_turun',
        'nama_lengkap_turun',
        'jabatan_tetap_turun',
        'id_jabatan_mutasi_turun',
        'nama_mutasi_turun',
        'jenis_mutasi_turun',
        'TMT_turun',
        'TAT_turun',
        
        // Dokumen
        'dokumen_sertijab',
        'dokumen_familisasi',
        'dokumen_lampiran',
        
        // Status dan flags
        'status_mutasi',
        'catatan',
        'keterangan_turun',
        'ada_abk_turun',
        'perlu_sertijab'
    ];

    protected $casts = [
        // Cast integer fields
        'id_kapal' => 'integer',
        'id_abk_naik' => 'integer',
        'id_abk_turun' => 'integer',
        'jabatan_tetap_naik' => 'integer',
        'jabatan_tetap_turun' => 'integer',
        'id_jabatan_mutasi' => 'integer',
        'id_jabatan_mutasi_turun' => 'integer',
        
        // Cast date fields
        'TMT' => 'date',
        'TAT' => 'date',
        'TMT_turun' => 'date',
        'TAT_turun' => 'date',
        
        // Cast boolean fields
        'ada_abk_turun' => 'boolean',
        'perlu_sertijab' => 'boolean',
        
        // Cast timestamp fields
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Relasi ke Kapal
     */
    public function kapal(): BelongsTo
    {
        return $this->belongsTo(Kapal::class, 'id_kapal', 'id');
    }

    /**
     * Relasi ke ABK yang naik
     */
    public function abkNaik(): BelongsTo
    {
        return $this->belongsTo(ABKNew::class, 'id_abk_naik', 'id');
    }

    /**
     * Relasi ke ABK yang turun
     */
    public function abkTurun(): BelongsTo
    {
        return $this->belongsTo(ABKNew::class, 'id_abk_turun', 'id');
    }

    /**
     * Relasi ke jabatan tetap ABK naik
     */
    public function jabatanTetapNaik(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_tetap_naik', 'id');
    }

    /**
     * Relasi ke jabatan tetap ABK turun
     */
    public function jabatanTetapTurun(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_tetap_turun', 'id');
    }

    /**
     * Relasi ke jabatan mutasi ABK naik
     */
    public function jabatanMutasi(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan_mutasi', 'id');
    }

    /**
     * Relasi ke jabatan mutasi ABK turun
     */
    public function jabatanMutasiTurun(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan_mutasi_turun', 'id');
    }

    // ===== ACCESSORS =====

    /**
     * Accessor untuk mendapatkan info periode mutasi ABK naik
     */
    public function getPeriodeMutasiNaikAttribute()
    {
        if ($this->TMT && $this->TAT) {
            return $this->TMT->format('d/m/Y') . ' - ' . $this->TAT->format('d/m/Y');
        }
        return '-';
    }

    /**
     * Accessor untuk mendapatkan info periode mutasi ABK turun
     */
    public function getPeriodeMutasiTurunAttribute()
    {
        if ($this->TMT_turun && $this->TAT_turun) {
            return $this->TMT_turun->format('d/m/Y') . ' - ' . $this->TAT_turun->format('d/m/Y');
        }
        return '-';
    }

    /**
     * Accessor untuk URL dokumen Sertijab
     */
    public function getDokumenSertijabUrlAttribute()
    {
        return $this->dokumen_sertijab ? Storage::url($this->dokumen_sertijab) : null;
    }

    /**
     * Accessor untuk URL dokumen Familisasi
     */
    public function getDokumenFamilisasiUrlAttribute()
    {
        return $this->dokumen_familisasi ? Storage::url($this->dokumen_familisasi) : null;
    }

    /**
     * Accessor untuk URL dokumen Lampiran
     */
    public function getDokumenLampiranUrlAttribute()
    {
        return $this->dokumen_lampiran ? Storage::url($this->dokumen_lampiran) : null;
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Draft' => 'bg-secondary',
            'Disetujui' => 'bg-success',
            'Ditolak' => 'bg-danger',
            'Selesai' => 'bg-primary'
        ];
        
        return $badges[$this->status_mutasi] ?? 'bg-secondary';
    }

    /**
     * Accessor untuk jenis badge
     */
    public function getJenisBadgeAttribute()
    {
        $badges = [
            'Sementara' => 'bg-info',
            'Definitif' => 'bg-success'
        ];
        
        return $badges[$this->jenis_mutasi] ?? 'bg-warning';
    }

    // ===== SCOPES =====

    /**
     * Scope untuk filter berdasarkan kapal
     */
    public function scopeByKapal($query, $kapalId)
    {
        return $query->where('id_kapal', $kapalId);
    }

    /**
     * Scope untuk filter yang ada ABK turun
     */
    public function scopeWithAbkTurun($query)
    {
        return $query->where('ada_abk_turun', true);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_mutasi', $status);
    }

    /**
     * Scope untuk filter berdasarkan jenis mutasi
     */
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_mutasi', $jenis);
    }

    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopeByPeriode($query, $startDate, $endDate)
    {
        return $query->whereBetween('TMT', [$startDate, $endDate]);
    }

    /**
     * Scope untuk mutasi terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope untuk mutasi yang memerlukan dokumen
     */
    public function scopePerluDokumen($query)
    {
        return $query->where('perlu_sertijab', true);
    }

    // ===== METHODS =====

    /**
     * Method untuk cek apakah ada dokumen
     */
    public function hasDokumen($jenis = null)
    {
        if ($jenis) {
            $column = "dokumen_{$jenis}";
            return !empty($this->$column);
        }
        
        return !empty($this->dokumen_sertijab) || 
               !empty($this->dokumen_familisasi) || 
               !empty($this->dokumen_lampiran);
    }

    /**
     * Method untuk mendapatkan semua dokumen yang ada
     */
    public function getAllDokumen()
    {
        $dokumen = [];
        
        if ($this->dokumen_sertijab) {
            $dokumen['sertijab'] = [
                'name' => 'Dokumen Serah Terima Jabatan',
                'path' => $this->dokumen_sertijab,
                'url' => $this->dokumen_sertijab_url
            ];
        }
        
        if ($this->dokumen_familisasi) {
            $dokumen['familisasi'] = [
                'name' => 'Dokumen Familisasi',
                'path' => $this->dokumen_familisasi,
                'url' => $this->dokumen_familisasi_url
            ];
        }
        
        if ($this->dokumen_lampiran) {
            $dokumen['lampiran'] = [
                'name' => 'Dokumen Lampiran',
                'path' => $this->dokumen_lampiran,
                'url' => $this->dokumen_lampiran_url
            ];
        }
        
        return $dokumen;
    }

    /**
     * Upload dokumen mutasi
     */
    public function uploadDokumen($file, $type)
    {
        if (!in_array($type, ['sertijab', 'familisasi', 'lampiran'])) {
            throw new \InvalidArgumentException("Tipe dokumen tidak valid: {$type}");
        }

        // Hapus file lama jika ada
        $this->deleteDokumen($type);

        // Upload file baru
        $filename = time() . '_' . $type . '_' . $this->id . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("dokumen/mutasi/{$type}", $filename, 'public');
        
        $column = "dokumen_{$type}";
        $this->$column = $path;
        $this->save();
        
        return $path;
    }

    /**
     * Hapus dokumen mutasi
     */
    public function deleteDokumen($type)
    {
        if (!in_array($type, ['sertijab', 'familisasi', 'lampiran'])) {
            throw new \InvalidArgumentException("Tipe dokumen tidak valid: {$type}");
        }

        $column = "dokumen_{$type}";
        if ($this->$column) {
            Storage::disk('public')->delete($this->$column);
            $this->update([$column => null]);
            return true;
        }
        
        return false;
    }

    /**
     * Hapus semua dokumen
     */
    public function deleteAllDokumen()
    {
        $dokumenFields = ['sertijab', 'familisasi', 'lampiran'];
        
        foreach ($dokumenFields as $type) {
            $this->deleteDokumen($type);
        }
    }

    /**
     * Get URL dokumen
     */
    public function getDokumenUrl($type)
    {
        if (!in_array($type, ['sertijab', 'familisasi', 'lampiran'])) {
            return null;
        }

        $column = "dokumen_{$type}";
        return $this->$column ? Storage::url($this->$column) : null;
    }

    /**
     * Cek apakah mutasi dapat disetujui
     */
    public function canBeApproved()
    {
        return $this->status_mutasi === 'Draft';
    }

    /**
     * Cek apakah mutasi dapat ditolak
     */
    public function canBeRejected()
    {
        return in_array($this->status_mutasi, ['Draft', 'Disetujui']);
    }

    /**
     * Update status mutasi
     */
    public function updateStatus($status, $catatan = null)
    {
        $allowedStatuses = ['Draft', 'Disetujui', 'Ditolak', 'Selesai'];
        
        if (!in_array($status, $allowedStatuses)) {
            throw new \InvalidArgumentException("Status tidak valid: {$status}");
        }

        $updateData = ['status_mutasi' => $status];
        
        if ($catatan) {
            $updateData['catatan'] = $catatan;
        }

        return $this->update($updateData);
    }

    /**
     * Method untuk cek kelengkapan dokumen
     */
    public function isDocumentComplete()
    {
        // Jika perlu sertijab, maka dokumen sertijab harus ada
        if ($this->perlu_sertijab && !$this->dokumen_sertijab) {
            return false;
        }
        
        // Minimal ada satu dokumen
        return $this->hasDokumen();
    }

    /**
     * Boot method untuk auto delete files ketika record dihapus
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($mutasi) {
            $mutasi->deleteAllDokumen();
        });
    }
}