<?php

namespace App\Imports;

use App\Models\Mutasi;
use App\Models\ABKNew;
use App\Models\Kapal;
use App\Models\Jabatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class MutasiImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $skipDuplicates;
    protected $importedCount = 0;
    protected $skippedCount = 0;
    protected $errors = [];

    public function __construct($skipDuplicates = false)
    {
        $this->skipDuplicates = $skipDuplicates;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // Skip jika data wajib kosong
            if (empty($row['nama_kapal']) || empty($row['id_abk_naik'])) {
                $this->skippedCount++;
                return null;
            }

            // Parse TMT untuk checking duplikasi
            $tmtParsed = $this->parseDate($row['tmt']);
            if (!$tmtParsed) {
                $this->errors[] = "Kapal {$row['nama_kapal']} - ABK {$row['id_abk_naik']}: Format TMT tidak valid";
                return null;
            }

            // Cek duplikat berdasarkan kombinasi kapal, ABK naik, dan TMT
            $existingMutasi = Mutasi::where('nama_kapal', $row['nama_kapal'])
                ->where('id_abk_naik', $row['id_abk_naik'])
                ->whereDate('TMT', $tmtParsed)
                ->first();

            if ($this->skipDuplicates && $existingMutasi) {
                $this->skippedCount++;
                Log::info("Skipping duplicate mutasi: " . $row['nama_kapal'] . " - ABK " . $row['id_abk_naik']);
                return null;
            }

            // Cari data kapal
            $kapal = $this->getKapalByName($row['nama_kapal']);
            $idKapal = $kapal ? $kapal->id : null;

            // Validasi ABK naik exists dengan relasi jabatan
            $abkNaik = ABKNew::with('jabatanTetap')->where('id', $row['id_abk_naik'])->first();
            if (!$abkNaik) {
                $this->errors[] = "ABK dengan ID {$row['id_abk_naik']} tidak ditemukan di database";
                return null;
            }

            // Cari ABK turun jika ada dengan relasi jabatan
            $idAbkTurun = null;
            $abkTurun = null;
            $adaAbkTurun = false;
            if (!empty($row['id_abk_turun'])) {
                $abkTurun = ABKNew::with('jabatanTetap')->where('id', $row['id_abk_turun'])->first();
                if (!$abkTurun) {
                    $this->errors[] = "ABK turun dengan ID {$row['id_abk_turun']} tidak ditemukan di database";
                    return null;
                }
                $idAbkTurun = $abkTurun->id;
                $adaAbkTurun = true;
            }

            // Cari jabatan mutasi naik
            $jabatanMutasi = null;
            $idJabatanMutasi = null;
            if (!empty($row['id_jabatan_mutasi'])) {
                $jabatanMutasi = Jabatan::where('id', $row['id_jabatan_mutasi'])->first();
                if ($jabatanMutasi) {
                    $idJabatanMutasi = $jabatanMutasi->id;
                }
            }

            // TAMBAHAN: Cari jabatan mutasi turun (nullable)
            $jabatanMutasiTurun = null;
            $idJabatanMutasiTurun = null;
            if (!empty($row['id_jabatan_mutasi_turun'])) {
                $jabatanMutasiTurun = Jabatan::where('id', $row['id_jabatan_mutasi_turun'])->first();
                if ($jabatanMutasiTurun) {
                    $idJabatanMutasiTurun = $jabatanMutasiTurun->id;
                }
            }

            // Parse TAT naik
            $tatParsed = !empty($row['tat']) ? $this->parseDate($row['tat']) : null;

            // TAMBAHAN: Parse TMT dan TAT turun (nullable)
            $tmtTurunParsed = !empty($row['tmt_turun']) ? $this->parseDate($row['tmt_turun']) : null;
            $tatTurunParsed = !empty($row['tat_turun']) ? $this->parseDate($row['tat_turun']) : null;

            $mutasi = Mutasi::create([
                // Data Kapal
                'id_kapal' => $idKapal,
                'nama_kapal' => trim($row['nama_kapal']),
                
                // Data ABK naik
                'id_abk_naik' => $row['id_abk_naik'],
                'nama_lengkap_naik' => $abkNaik->nama_abk,
                'jabatan_tetap_naik' => $abkNaik->id_jabatan_tetap, // Integer ID
                'id_jabatan_mutasi' => $idJabatanMutasi,
                'nama_mutasi' => trim($row['nama_mutasi'] ?? ''),
                'jenis_mutasi' => $this->normalizeJenisMutasi($row['jenis_mutasi'] ?? 'Sementara'),
                'TMT' => $tmtParsed,
                'TAT' => $tatParsed,
                
                // Data ABK turun
                'id_abk_turun' => $idAbkTurun,
                'nama_lengkap_turun' => $abkTurun ? $abkTurun->nama_abk : null,
                'jabatan_tetap_turun' => $abkTurun ? $abkTurun->id_jabatan_tetap : null, // Integer ID
                
                // TAMBAHAN: Field baru untuk mutasi turun (nullable)
                'id_jabatan_mutasi_turun' => $idJabatanMutasiTurun, // Nullable
                'nama_mutasi_turun' => trim($row['nama_mutasi_turun'] ?? ''),
                'jenis_mutasi_turun' => $this->normalizeJenisMutasi($row['jenis_mutasi_turun'] ?? 'Sementara'),
                'TMT_turun' => $tmtTurunParsed, // NULLABLE - bisa berbeda dengan TMT naik
                'TAT_turun' => $tatTurunParsed, // NULLABLE - bisa berbeda dengan TAT naik
                
                // Status dan flags
                'status_mutasi' => 'Draft',
                'catatan' => trim($row['catatan'] ?? ''),
                'keterangan_turun' => null,
                'ada_abk_turun' => $adaAbkTurun,
                'perlu_sertijab' => true,
                
                // Timestamps
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->importedCount++;
            Log::info("Importing Mutasi: " . $row['nama_kapal'] . " - ABK " . $row['id_abk_naik'] . 
                     " - Jabatan Tetap ID: " . $abkNaik->id_jabatan_tetap . 
                     " (" . ($abkNaik->jabatanTetap ? $abkNaik->jabatanTetap->nama_jabatan : 'Tidak Ada') . ")" .
                     ($adaAbkTurun ? " - ABK Turun: " . $idAbkTurun : ""));
            
            return $mutasi;

        } catch (\Exception $e) {
            $this->errors[] = "Kapal {$row['nama_kapal']} - ABK {$row['id_abk_naik']}: " . $e->getMessage();
            Log::error("Error importing Mutasi {$row['nama_kapal']}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Validation rules for each row
     */
    public function rules(): array
    {
        return [
            'nama_kapal' => 'required|string|max:255',
            'id_abk_naik' => 'required|max:20',
            'nama_mutasi' => 'nullable|string|max:255',
            'jenis_mutasi' => 'nullable|string|in:Sementara,Definitif,sementara,definitif',
            'tmt' => 'required', // TMT naik wajib
            'tat' => 'nullable', // TAT naik tidak wajib
            'id_jabatan_mutasi' => 'nullable|integer',
            
            // Field untuk ABK turun (semua nullable)
            'id_abk_turun' => 'nullable|max:20',
            'nama_mutasi_turun' => 'nullable|string|max:255',
            'jenis_mutasi_turun' => 'nullable|string|in:Sementara,Definitif,sementara,definitif',
            'id_jabatan_mutasi_turun' => 'nullable|integer', // BARU: nullable
            'tmt_turun' => 'nullable', // BARU: nullable
            'tat_turun' => 'nullable', // BARU: nullable
            
            'catatan' => 'nullable|string|max:1000'
        ];
    }

    /**
     * Custom error messages
     */
    public function customValidationMessages()
    {
        return [
            'nama_kapal.required' => 'Nama Kapal wajib diisi',
            'id_abk_naik.required' => 'ID ABK yang naik wajib diisi',
            'tmt.required' => 'TMT (Terhitung Mulai Tanggal) wajib diisi',
            'jenis_mutasi.in' => 'Jenis mutasi harus Sementara atau Definitif',
            'jenis_mutasi_turun.in' => 'Jenis mutasi turun harus Sementara atau Definitif',
            'id_jabatan_mutasi.integer' => 'ID Jabatan Mutasi harus berupa angka',
            'id_jabatan_mutasi_turun.integer' => 'ID Jabatan Mutasi Turun harus berupa angka',
        ];
    }

    /**
     * Handle validation failures
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
        }
    }

    /**
     * Get kapal by name
     */
    private function getKapalByName($namaKapal)
    {
        if (empty($namaKapal)) return null;
        
        return Kapal::where('nama_kapal', 'LIKE', '%' . trim($namaKapal) . '%')->first();
    }

    /**
     * Parse date string with multiple formats
     */
    private function parseDate($dateString)
    {
        if (empty($dateString)) return null;
        
        try {
            // Handle Excel serial date (numeric)
            if (is_numeric($dateString)) {
                // Excel serial date starts from 1900-01-01
                $baseDate = Carbon::create(1899, 12, 30); // Excel's epoch
                return $baseDate->addDays($dateString)->format('Y-m-d');
            }
            
            // Handle string dates
            $dateString = trim($dateString);
            
            // Try common date formats
            $formats = [
                'd/m/Y',    // 09/11/2025
                'm/d/Y',    // 11/09/2025  
                'Y-m-d',    // 2025-11-09
                'd-m-Y',    // 09-11-2025
                'Y/m/d',    // 2025/11/09
                'd.m.Y',    // 09.11.2025
                'Y.m.d',    // 2025.11.09
            ];
            
            foreach ($formats as $format) {
                try {
                    $date = Carbon::createFromFormat($format, $dateString);
                    if ($date && $date->format($format) === $dateString) {
                        return $date->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            // Fallback to Carbon::parse for more flexible parsing
            try {
                $date = Carbon::parse($dateString);
                return $date->format('Y-m-d');
            } catch (\Exception $e) {
                Log::warning("Failed to parse date with Carbon::parse: {$dateString}");
            }
            
            // If all parsing attempts fail
            throw new \Exception("Format tanggal tidak dapat dikenali: {$dateString}");
            
        } catch (\Exception $e) {
            Log::warning("Failed to parse date: {$dateString} - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Normalize jenis mutasi
     */
    private function normalizeJenisMutasi($jenis)
    {
        if (empty($jenis)) return 'Sementara';
        
        $jenis = strtolower(trim($jenis));
        
        $jenisMap = [
            'sementara' => 'Sementara',
            'definitif' => 'Definitif',
            'permanent' => 'Definitif',
            'temp' => 'Sementara',
            'temporary' => 'Sementara',
            'tetap' => 'Definitif'
        ];

        return $jenisMap[$jenis] ?? 'Sementara';
    }

    /**
     * Get import statistics
     */
    public function getStats()
    {
        return [
            'imported' => $this->importedCount,
            'skipped' => $this->skippedCount,
            'failed' => count($this->failures()),
            'errors' => array_merge($this->errors, $this->getFailureMessages())
        ];
    }

    /**
     * Get failure messages
     */
    private function getFailureMessages()
    {
        $messages = [];
        foreach ($this->failures() as $failure) {
            $messages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
        }
        return $messages;
    }
}