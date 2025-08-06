<?php

namespace App\Imports;

use App\Models\ABKNew;
use App\Models\Jabatan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AbkImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
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
            // Skip jika NRP kosong
            if (empty($row['id']) || empty($row['nama_abk'])) {
                $this->skippedCount++;
                return null;
            }

            // Cek duplikat jika skip_duplicates aktif
            if ($this->skipDuplicates && ABKNew::where('id', $row['id'])->exists()) {
                $this->skippedCount++;
                Log::info("Skipping duplicate NRP: " . $row['id']);
                return null;
            }

            // Cari jabatan berdasarkan nama
            $jabatanId = $this->getJabatanId($row['jabatan_tetap'] ?? '');
            if (!$jabatanId) {
                $jabatanId = 1; // Default jabatan jika tidak ditemukan
            }

            $abk = ABKNew::create([
                'id' => $row['id'],
                'nama_abk' => $row['nama_abk'],
                'id_jabatan_tetap' => $jabatanId,
                'status_abk' => $this->normalizeStatus($row['status_abk'] ?? 'Organik'),
            ]);

            $this->importedCount++;
            Log::info("Importing ABK: " . $row['id'] . " - " . $row['nama_abk']);
            
            return $abk;

        } catch (\Exception $e) {
            $this->errors[] = "NRP {$row['id']}: " . $e->getMessage();
            Log::error("Error importing ABK {$row['id']}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Validation rules for each row
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'min:4',
                'max:20',
                'regex:/^[0-9]+$/',
                function ($attribute, $value, $fail) {
                    if (!$this->skipDuplicates && ABKNew::where('id', $value)->exists()) {
                        $fail('NRP sudah terdaftar dalam sistem');
                    }
                }
            ],
            'nama_abk' => 'required|string|max:255|min:2',
            'jabatan_tetap' => 'nullable|string|max:255',
            'status_abk' => 'nullable|string|in:Organik,Non Organik,Pensiun'
        ];
    }

    /**
     * Custom error messages
     */
    public function customValidationMessages()
    {
        return [
            'id.required' => 'ID wajib diisi',
            'id.min' => 'ID minimal 4 karakter',
            'id.max' => 'ID maksimal 20 karakter',
            'id.regex' => 'ID hanya boleh berisi angka',
            'nama_abk.required' => 'Nama ABK wajib diisi',
            'nama_abk.min' => 'Nama ABK minimal 2 karakter',
            'status_abk.in' => 'Status ABK tidak valid'
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
     * Get jabatan ID by name
     */
    private function getJabatanId($namaJabatan)
    {
        if (empty($namaJabatan)) return null;
        
        $jabatan = Jabatan::where('nama_jabatan', 'LIKE', '%' . trim($namaJabatan) . '%')->first();
        return $jabatan ? $jabatan->id : null;
    }

    /**
     * Normalize status ABK
     */
    private function normalizeStatus($status)
    {
        $status = trim($status);
        
        $statusMap = [
            'organik' => 'Organik',
            'non organik' => 'Non Organik',
            'non-organik' => 'Non Organik',
            'pensiun' => 'Pensiun',
            'aktif' => 'Organik'
        ];

        return $statusMap[strtolower($status)] ?? 'Organik';
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
