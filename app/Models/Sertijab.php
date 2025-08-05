<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Sertijab extends Model
{
    use HasFactory;

    protected $table = 'sertijab_new';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'id_mutasi',
        'dokumen_sertijab_path',
        'dokumen_familisasi_path',
        'dokumen_lampiran_path',
        'status_sertijab',
        'status_familisasi',
        'status_lampiran',
        'status_dokumen',
        'catatan_admin', // UPDATED: Single catatan field
        'submitted_at',
        'verified_by_admin_nrp',
        'verified_at',
        'updated_by_admin'
    ];
    
    protected $casts = [
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];
    
    // ===== RELATIONSHIPS =====
    
    /**
     * Get the mutasi that owns the sertijab
     */
    public function mutasi(): BelongsTo
    {
        return $this->belongsTo(Mutasi::class, 'id_mutasi', 'id');
    }
    
    /**
     * Get the admin verifikator
     */
    public function adminVerifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_admin_nrp', 'NRP_admin');
    }
    
    /**
     * Get the admin who last updated
     */
    public function adminUpdater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_admin', 'NRP_admin');
    }
    
    // ===== ACCESSORS =====
    
    /**
     * Get sertijab document URL
     */
    public function getSertijabUrlAttribute()
    {
        return $this->dokumen_sertijab_path ? Storage::url($this->dokumen_sertijab_path) : null;
    }
    
    /**
     * Get familisasi document URL
     */
    public function getFamilisasiUrlAttribute()
    {
        return $this->dokumen_familisasi_path ? Storage::url($this->dokumen_familisasi_path) : null;
    }
    
    /**
     * Get lampiran document URL
     */
    public function getLampiranUrlAttribute()
    {
        return $this->dokumen_lampiran_path ? Storage::url($this->dokumen_lampiran_path) : null;
    }
    
    /**
     * Get all document URLs
     */
    public function getDocumentUrlsAttribute()
    {
        return [
            'sertijab' => $this->sertijab_url,
            'familisasi' => $this->familisasi_url,
            'lampiran' => $this->lampiran_url,
        ];
    }
    
    /**
     * Get status badge for overall document status
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => 'bg-secondary',
            'partial' => 'bg-warning',
            'final' => 'bg-success'
        ];
        
        return $badges[$this->status_dokumen] ?? 'bg-secondary';
    }
    
    /**
     * Get status text for overall document status
     */
    public function getStatusTextAttribute()
    {
        $statusText = [
            'draft' => 'Belum Diverifikasi',
            'partial' => 'Sebagian Diverifikasi',
            'final' => 'Terverifikasi Lengkap'
        ];
        
        return $statusText[$this->status_dokumen] ?? 'Unknown';
    }
    
    /**
     * Get document counts
     */
    public function getDocumentCountsAttribute()
    {
        $uploaded = 0;
        $verified = 0;
        
        if ($this->dokumen_sertijab_path) {
            $uploaded++;
            if ($this->status_sertijab === 'final') $verified++;
        }
        
        if ($this->dokumen_familisasi_path) {
            $uploaded++;
            if ($this->status_familisasi === 'final') $verified++;
        }
        
        // FIXED: Only count if lampiran exists
        if ($this->dokumen_lampiran_path) {
            $uploaded++;
            if ($this->status_lampiran === 'final') $verified++;
        }
        
        return [
            'uploaded' => $uploaded,
            'verified' => $verified,
            'total' => $uploaded // Total adalah jumlah yang diupload
        ];
    }
    
    /**
     * Get formatted ABK pairing info
     */
    public function getAbkPairingInfoAttribute()
    {
        if (!$this->mutasi) {
            return 'Data mutasi tidak ditemukan';
        }
        
        $abkNaik = $this->mutasi->nama_lengkap_naik ?? 'N/A';
        $abkTurun = $this->mutasi->nama_lengkap_turun ?? 'Tidak ada ABK turun';
        
        return "ABK Naik: {$abkNaik} | ABK Turun: {$abkTurun}";
    }
    
    // ===== SCOPES =====
    
    /**
     * Scope untuk filter berdasarkan status dokumen
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_dokumen', $status);
    }
    
    /**
     * Scope untuk dokumen yang sudah disubmit
     */
    public function scopeSubmitted($query)
    {
        return $query->whereNotNull('submitted_at');
    }
    
    /**
     * Scope untuk dokumen yang sudah diverifikasi
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }
    
    /**
     * Scope untuk dokumen yang belum diverifikasi
     */
    public function scopePendingVerification($query)
    {
        return $query->whereNotNull('submitted_at')
                    ->where('status_dokumen', 'draft');
    }
    
    /**
     * Scope untuk filter berdasarkan admin verifikator
     */
    public function scopeByVerifikator($query, $adminNrp)
    {
        return $query->where('verified_by_admin_nrp', $adminNrp);
    }
    
    /**
     * Scope untuk periode submission
     */
    public function scopeBySubmissionPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('submitted_at', [$startDate, $endDate]);
    }
    
    // ===== METHODS =====
    
    /**
     * Check if all required documents are uploaded
     */
    public function hasAllRequiredDocuments(): bool
    {
        return !empty($this->dokumen_sertijab_path) && !empty($this->dokumen_familisasi_path);
    }
    
    /**
     * Check if has any documents
     */
    public function hasAnyDocuments(): bool
    {
        return !empty($this->dokumen_sertijab_path) || 
               !empty($this->dokumen_familisasi_path) || 
               !empty($this->dokumen_lampiran_path);
    }
    
    /**
     * Check if all documents are verified as final
     */
    public function isFullyVerified()
    {
        // Dokumen sertijab wajib final
        if ($this->status_sertijab !== 'final') {
            return false;
        }
        
        // Dokumen opsional jika ada, juga harus final
        if ($this->dokumen_familisasi_path && $this->status_familisasi !== 'final') {
            return false;
        }
        
        if ($this->dokumen_lampiran_path && $this->status_lampiran !== 'final') {
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if partially verified
     */
    public function isPartiallyVerified(): bool
    {
        $verifiedCount = 0;
        $totalCount = 0;
        
        if (!empty($this->dokumen_sertijab_path)) {
            $totalCount++;
            if ($this->status_sertijab === 'final') $verifiedCount++;
        }
        
        if (!empty($this->dokumen_familisasi_path)) {
            $totalCount++;
            if ($this->status_familisasi === 'final') $verifiedCount++;
        }
        
        // FIXED: Only count lampiran if document exists
        if (!empty($this->dokumen_lampiran_path)) {
            $totalCount++;
            if ($this->status_lampiran === 'final') $verifiedCount++;
        }
        
        return $verifiedCount > 0 && $verifiedCount < $totalCount;
    }
    
    /**
     * Update overall document status based on individual document statuses
     */
    public function updateOverallStatus()
    {
        $status = 'draft';
        
        // Jika semua dokumen wajib sudah final, maka overall status adalah final
        if ($this->status_sertijab === 'final') {
            $status = 'final';
            
            // Jika ada dokumen opsional yang belum final (tapi ada), maka overall status adalah partial
            if (($this->dokumen_familisasi_path && $this->status_familisasi !== 'final') || 
                ($this->dokumen_lampiran_path && $this->status_lampiran !== 'final')) {
                $status = 'partial';
            }
        } 
        // Jika sertijab belum final tapi ada yang sudah final, maka overall status adalah partial
        else if ($this->status_familisasi === 'final' || $this->status_lampiran === 'final') {
            $status = 'partial';
        }
        
        $this->status_dokumen = $status;
        $this->save();
        
        return $status;
    }
    
    /**
     * Verify specific document type
     */
    public function verifyDocument(string $type, string $status = 'final'): bool
    {
        $allowedTypes = ['sertijab', 'familisasi', 'lampiran'];
        $allowedStatuses = ['draft', 'final'];
        
        if (!in_array($type, $allowedTypes) || !in_array($status, $allowedStatuses)) {
            return false;
        }
        
        $statusField = "status_{$type}";
        $pathField = "dokumen_{$type}_path";
        
        // Check if document exists
        if (empty($this->$pathField)) {
            return false;
        }
        
        $this->$statusField = $status;
        $this->save();
        $this->updateOverallStatus();
        
        return true;
    }
    
    /**
     * Verify all documents at once with admin comment
     */
    public function verifyAllDocuments(string $adminComment = null, int $adminNrp = null): bool
    {
        $updated = false;
        
        // Verify each existing document
        if (!empty($this->dokumen_sertijab_path)) {
            $this->status_sertijab = 'final';
            $updated = true;
        }
        
        if (!empty($this->dokumen_familisasi_path)) {
            $this->status_familisasi = 'final';
            $updated = true;
        }
        
        // FIXED: Only verify lampiran if document exists
        if (!empty($this->dokumen_lampiran_path)) {
            $this->status_lampiran = 'final';
            $updated = true;
        }
        
        if ($updated) {
            $this->catatan_admin = $adminComment;
            $this->updated_by_admin = $adminNrp;
            $this->save();
            $this->updateOverallStatus();
            
            // Mark as verified if fully verified
            if ($this->isFullyVerified() && $adminNrp) {
                $this->markAsVerified($adminNrp);
            }
        }
        
        return $updated;
    }
    
    /**
     * Update admin comment for the ABK pairing
     */
    public function updateAdminComment($comment, $adminNrp)
    {
        $this->catatan_admin = $comment;
        $this->updated_by_admin = $adminNrp;
        $this->save();
    }
    
    /**
     * Mark as verified by admin
     */
    public function markAsVerified($adminNrp)
    {
        $this->verified_at = now();
        $this->verified_by_admin_nrp = $adminNrp;
        $this->status_dokumen = 'final';
        $this->save();
    }
    
    /**
     * Get document info for specific type
     */
    public function getDocumentInfo(string $type): ?array
    {
        if (!in_array($type, ['sertijab', 'familisasi', 'lampiran'])) {
            return null;
        }
        
        $pathField = "dokumen_{$type}_path";
        $statusField = "status_{$type}";
        
        if (empty($this->$pathField)) {
            return null;
        }
        
        return [
            'path' => $this->$pathField,
            'url' => Storage::url($this->$pathField),
            'status' => $this->$statusField,
            'verified' => $this->$statusField === 'final'
        ];
    }
    
    /**
     * Get all documents info
     */
    public function getAllDocumentsInfo(): array
    {
        $documents = [];
        $types = ['sertijab', 'familisasi', 'lampiran'];
        
        foreach ($types as $type) {
            $info = $this->getDocumentInfo($type);
            if ($info) {
                $documents[$type] = $info;
            }
        }
        
        return $documents;
    }
    
    /**
     * Get verification progress percentage
     */
    public function getVerificationProgress(): int
    {
        $counts = $this->document_counts;
        
        if ($counts['uploaded'] === 0) {
            return 0;
        }
        
        return round(($counts['verified'] / $counts['uploaded']) * 100);
    }
    
    /**
     * Delete document file and update database
     */
    public function deleteDocument(string $type): bool
    {
        if (!in_array($type, ['sertijab', 'familisasi', 'lampiran'])) {
            return false;
        }
        
        $pathField = "dokumen_{$type}_path";
        $statusField = "status_{$type}";
        
        if (!empty($this->$pathField)) {
            // Delete file from storage
            Storage::disk('public')->delete($this->$pathField);
            
            // Update database
            $this->$pathField = null;
            $this->$statusField = 'draft';
            
            $this->save();
            $this->updateOverallStatus();
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Boot method untuk auto cleanup files ketika record dihapus
     */
    protected static function boot()
    {
        parent::boot();
        
        static::deleting(function ($sertijab) {
            // Delete all document files
            $types = ['sertijab', 'familisasi', 'lampiran'];
            foreach ($types as $type) {
                $sertijab->deleteDocument($type);
            }
        });
    }
    
    // ===== LEGACY COMPATIBILITY =====
    
    /**
     * Legacy accessor untuk catatan_umum (map ke catatan_admin)
     */
    public function getCatatanUmumAttribute()
    {
        return $this->catatan_admin;
    }
    
    /**
     * Legacy accessor untuk notes (map ke catatan_admin)
     */
    public function getNotesAttribute()
    {
        return $this->catatan_admin;
    }
    
    /**
     * Accessor untuk verification_progress
     */
    public function getVerificationProgressAttribute()
    {
        return $this->getVerificationProgress();
    }
}
