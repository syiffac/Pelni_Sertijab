<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RiwayatImportExport extends Model
{
    protected $table = 'riwayat_import_export';

    protected $fillable = [
        'nama_file',
        'tipe', // 'import' or 'export'
        'jenis', // 'abk', 'mutasi', etc
        'status', // 'success', 'failed', 'processing', 'warning'
        'jumlah_data',
        'jumlah_berhasil',
        'jumlah_gagal',
        'jumlah_dilewati',
        'keterangan',
        'file_size',
        'durasi_proses', // dalam detik
        'user_id'
    ];

    protected $casts = [
        'jumlah_data' => 'integer',
        'jumlah_berhasil' => 'integer',
        'jumlah_gagal' => 'integer',
        'jumlah_dilewati' => 'integer',
        'file_size' => 'integer',
        'durasi_proses' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the import/export history.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) return '-';
        
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get formatted duration
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->durasi_proses) return '-';
        
        if ($this->durasi_proses < 60) {
            return $this->durasi_proses . ' detik';
        } elseif ($this->durasi_proses < 3600) {
            return round($this->durasi_proses / 60, 1) . ' menit';
        } else {
            return round($this->durasi_proses / 3600, 1) . ' jam';
        }
    }

    /**
     * Get success rate percentage
     */
    public function getSuccessRateAttribute()
    {
        if (!$this->jumlah_data || $this->jumlah_data == 0) return 0;
        
        return round(($this->jumlah_berhasil / $this->jumlah_data) * 100, 1);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'success' => 'bg-success',
            'failed' => 'bg-danger', 
            'warning' => 'bg-warning',
            'processing' => 'bg-info',
            default => 'bg-secondary'
        };
    }

    /**
     * Get status icon
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'success' => 'bi-check-circle-fill',
            'failed' => 'bi-x-circle-fill',
            'warning' => 'bi-exclamation-triangle-fill',
            'processing' => 'bi-hourglass-split',
            default => 'bi-question-circle-fill'
        };
    }

    /**
     * Get tipe icon
     */
    public function getTipeIconAttribute()
    {
        return match($this->tipe) {
            'import' => 'bi-upload',
            'export' => 'bi-download',
            default => 'bi-file-earmark'
        };
    }

    /**
     * Get jenis label
     */
    public function getJenisLabelAttribute()
    {
        return match($this->jenis) {
            'abk' => 'Data ABK',
            'mutasi' => 'Data Mutasi',
            'kapal' => 'Data Kapal',
            'jabatan' => 'Data Jabatan',
            default => ucfirst($this->jenis)
        };
    }
}
