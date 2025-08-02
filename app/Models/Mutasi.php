<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Mutasi extends Model
{
    use HasFactory;

    protected $table = 'mutasi';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id_abk_naik',
        'nama_lengkap_naik',
        'jabatan_tetap_naik',
        'id_jabatan_mutasi',
        'id_abk_turun',
        'nama_lengkap_turun',
        'jabatan_tetap_turun',
        'nama_mutasi',
        'jenis_mutasi',
        'TMT',
        'TAT',
        'dokumen_sertijab',
        'dokumen_familisasi',
        'dokumen_lampiran',
        'status_mutasi',
        'catatan',
        'perlu_sertijab'
    ];

    protected $casts = [
        'TMT' => 'date',
        'TAT' => 'date',
        'perlu_sertijab' => 'boolean',
        'dokumen_familisasi' => 'array' // Cast JSON ke array jika multiple files
    ];

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
     * Relasi ke jabatan mutasi (jabatan baru)
     */
    public function jabatanMutasi(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan_mutasi', 'id');
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
     * Method untuk hapus file dokumen tertentu
     */
    public function deleteDokumen($jenis)
    {
        $column = "dokumen_{$jenis}";
        
        if (!in_array($jenis, ['sertijab', 'familisasi', 'lampiran'])) {
            return false;
        }
        
        if ($this->$column && Storage::exists($this->$column)) {
            Storage::delete($this->$column);
            $this->$column = null;
            $this->save();
            return true;
        }
        
        return false;
    }

    /**
     * Method untuk hapus semua dokumen
     */
    public function deleteAllDokumen()
    {
        $dokumenFields = [
            'dokumen_sertijab',
            'dokumen_familisasi',
            'dokumen_lampiran'
        ];
        
        foreach ($dokumenFields as $field) {
            if ($this->$field && Storage::exists($this->$field)) {
                Storage::delete($this->$field);
                $this->$field = null;
            }
        }
        
        $this->save();
    }

    /**
     * Method untuk upload dokumen
     */
    public function uploadDokumen($file, $jenis)
    {
        if (!in_array($jenis, ['sertijab', 'familisasi', 'lampiran'])) {
            return false;
        }
        
        // Hapus file lama jika ada
        $this->deleteDokumen($jenis);
        
        // Upload file baru
        $filename = time() . '_' . $jenis . '_' . $this->id . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("dokumen/mutasi/{$jenis}", $filename, 'public');
        
        $column = "dokumen_{$jenis}";
        $this->$column = $path;
        $this->save();
        
        return $path;
    }

    /**
     * Accessor untuk status badge
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Draft' => '<span class="badge bg-secondary">Draft</span>',
            'Disetujui' => '<span class="badge bg-success">Disetujui</span>',
            'Ditolak' => '<span class="badge bg-danger">Ditolak</span>',
            'Selesai' => '<span class="badge bg-primary">Selesai</span>'
        ];
        
        return $badges[$this->status_mutasi] ?? '<span class="badge bg-warning">Unknown</span>';
    }

    /**
     * Accessor untuk jenis badge
     */
    public function getJenisBadgeAttribute()
    {
        $badges = [
            'Sementara' => '<span class="badge bg-info">Sementara</span>',
            'Definitif' => '<span class="badge bg-success">Definitif</span>'
        ];
        
        return $badges[$this->jenis_mutasi] ?? '<span class="badge bg-warning">Unknown</span>';
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
     * Scope untuk filter berdasarkan jenis mutasi
     */
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_mutasi', $jenis);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_mutasi', $status);
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('TMT', [$startDate, $endDate]);
    }

    /**
     * Scope untuk mutasi yang memerlukan dokumen
     */
    public function scopePerluDokumen($query)
    {
        return $query->where('perlu_sertijab', true);
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