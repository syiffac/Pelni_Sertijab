<?php
// filepath: c:\laragon\www\SertijabPelni\app\Http\Controllers\ABKController.php

namespace App\Http\Controllers;

use App\Models\ABKNew;
use App\Models\Jabatan;
use App\Models\Kapal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class ABKController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Data statistik ABK saja
            $totalStatistik = [
                'total_abk' => ABKNew::count(),
                'abk_aktif' => ABKNew::where('status_abk', '!=', 'Pensiun')->count(),
                'abk_organik' => ABKNew::where('status_abk', 'Organik')->count(),
                'abk_non_organik' => ABKNew::where('status_abk', 'Non Organik')->count(),
                'abk_pensiun' => ABKNew::where('status_abk', 'Pensiun')->count()
            ];

            // Data ABK per kapal (dummy data untuk sementara)
            $abkPerKapal = collect([
                [
                    'id' => 1,
                    'nama_kapal' => 'KM Sirimau',
                    'total_abk' => 25,
                    'abk_aktif' => 20,
                    'abk_tidak_aktif' => 5
                ],
                [
                    'id' => 2,
                    'nama_kapal' => 'KM Tatamailau',
                    'total_abk' => 30,
                    'abk_aktif' => 28,
                    'abk_tidak_aktif' => 2
                ],
                [
                    'id' => 3,
                    'nama_kapal' => 'KM Dorolonda',
                    'total_abk' => 22,
                    'abk_aktif' => 22,
                    'abk_tidak_aktif' => 0
                ],
                [
                    'id' => 4,
                    'nama_kapal' => 'KM Pangrango',
                    'total_abk' => 35,
                    'abk_aktif' => 32,
                    'abk_tidak_aktif' => 3
                ]
            ]);

            // Data mutasi terbaru (dummy data untuk sementara)
            $mutasiTerbaru = collect([
                [
                    'id' => 1,
                    'abkTurun' => ['nama_abk' => 'Ahmad Syafiq'],
                    'kapalTurun' => ['nama_kapal' => 'KM Sirimau'],
                    'kapalNaik' => ['nama_kapal' => 'KM Tatamailau'],
                    'status_mutasi' => 'Proses',
                    'created_at' => now()->subDays(2)
                ],
                [
                    'id' => 2,
                    'abkTurun' => ['nama_abk' => 'Budi Santoso'],
                    'kapalTurun' => ['nama_kapal' => 'KM Dorolonda'],
                    'kapalNaik' => ['nama_kapal' => 'KM Pangrango'],
                    'status_mutasi' => 'Selesai',
                    'created_at' => now()->subDays(5)
                ],
                [
                    'id' => 3,
                    'abkTurun' => ['nama_abk' => 'Citra Dewi'],
                    'kapalTurun' => ['nama_kapal' => 'KM Tatamailau'],
                    'kapalNaik' => ['nama_kapal' => 'KM Sirimau'],
                    'status_mutasi' => 'Pending',
                    'created_at' => now()->subWeek()
                ]
            ]);

            // Data ABK list terbaru dengan relasi yang benar
            $abkList = ABKNew::with(['jabatanTetap', 'kapalAktif'])
                ->orderBy('created_at', 'desc')
                ->take(20) // Ubah dari 10 menjadi 20 untuk lebih banyak data search
                ->get();

            // Debug data untuk memastikan query bekerja
            Log::info('ABK List Count: ' . $abkList->count());
            if ($abkList->count() > 0) {
                Log::info('Sample ABK: ', $abkList->first()->toArray());
            }
                
            return view('kelolaABK.index', compact(
                'abkList',
                'totalStatistik', 
                'abkPerKapal', 
                'mutasiTerbaru'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading ABK index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Fallback data jika error
            $totalStatistik = [
                'total_abk' => 0,
                'abk_aktif' => 0,
                'abk_organik' => 0,
                'abk_non_organik' => 0,
                'abk_pensiun' => 0
            ];
            
            $abkPerKapal = collect();
            $mutasiTerbaru = collect();
            $abkList = collect(); // Empty collection
            
            return view('kelolaABK.index', compact(
                'abkList',
                'totalStatistik', 
                'abkPerKapal', 
                'mutasiTerbaru'
            ))->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $daftarJabatan = Jabatan::orderBy('nama_jabatan', 'asc')->get();
            
            return view('kelolaABK.create', compact('daftarJabatan'));
        } catch (\Exception $e) {
            Log::error('Error loading create ABK form: ' . $e->getMessage());
            return redirect()->route('abk.index')->with('error', 'Gagal memuat form tambah ABK');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Log request untuk debugging
        Log::info('ABK Store Request:', $request->all());
        
        try {
            // Validasi input
            $validatedData = $request->validate([
                'id' => [
                    'required',
                    'string',
                    'min:4',
                    'max:20',
                    'regex:/^[0-9]+$/',
                    'unique:abk_new,id'
                ],
                'nama_abk' => 'required|string|max:255|min:2',
                'id_jabatan_tetap' => 'required|exists:jabatan,id',
                'status_abk' => 'required|in:Organik,Non Organik'
            ], [
                'id.required' => 'NRP/ID ABK wajib diisi',
                'id.min' => 'NRP minimal 4 karakter',
                'id.max' => 'NRP maksimal 20 karakter',
                'id.regex' => 'NRP hanya boleh berisi angka',
                'id.unique' => 'NRP sudah terdaftar dalam sistem',
                'nama_abk.required' => 'Nama ABK wajib diisi',
                'nama_abk.min' => 'Nama ABK minimal 2 karakter',
                'id_jabatan_tetap.required' => 'Jabatan tetap wajib dipilih',
                'id_jabatan_tetap.exists' => 'Jabatan yang dipilih tidak valid',
                'status_abk.required' => 'Status ABK wajib dipilih',
                'status_abk.in' => 'Status ABK tidak valid'
            ]);

            DB::beginTransaction();

            // Cek apakah jabatan ada
            $jabatan = Jabatan::findOrFail($validatedData['id_jabatan_tetap']);

            // Create ABK baru
            $abk = ABKNew::create([
                'id' => $validatedData['id'],
                'nama_abk' => $validatedData['nama_abk'],
                'id_jabatan_tetap' => $validatedData['id_jabatan_tetap'],
                'status_abk' => $validatedData['status_abk']
            ]);

            DB::commit();

            Log::info('ABK created successfully:', ['id' => $abk->id]);

            // Response untuk AJAX request
            if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Data ABK berhasil ditambahkan',
                    'data' => [
                        'id' => $abk->id,
                        'nama_abk' => $abk->nama_abk,
                        'jabatan' => $jabatan->nama_jabatan,
                        'status' => $abk->status_abk
                    ]
                ], 200);
            }

            // Fallback untuk non-AJAX
            return redirect()->route('abk.index')->with('success', 'Data ABK berhasil ditambahkan');

        } catch (ValidationException $e) {
            DB::rollback();
            
            if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withInput()->withErrors($e->errors());
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error storing ABK: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan data ABK: ' . $e->getMessage()]);
        }
    }

    /**
     * AJAX endpoint untuk cek NRP availability
     */
    public function checkNRP(Request $request)
    {
        try {
            $nrp = $request->get('nrp');
            $excludeId = $request->get('exclude_id'); // ID yang dikecualikan untuk edit
            
            Log::info('Check NRP request:', ['nrp' => $nrp, 'exclude_id' => $excludeId]);
            
            if (!$nrp) {
                return response()->json([
                    'available' => false,
                    'message' => 'NRP tidak boleh kosong'
                ]);
            }
            
            // Validasi format
            if (!preg_match('/^[0-9]{4,20}$/', $nrp)) {
                return response()->json([
                    'available' => false,
                    'message' => 'Format NRP tidak valid. Gunakan angka saja, 4-20 karakter'
                ]);
            }
            
            // Cek ketersediaan dengan exclude ID untuk edit
            $query = ABKNew::where('id', $nrp);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            $exists = $query->exists();
            
            return response()->json([
                'available' => !$exists,
                'message' => $exists ? 'NRP sudah terdaftar dalam sistem' : 'NRP tersedia'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error checking NRP: ' . $e->getMessage());
            return response()->json([
                'available' => false,
                'message' => 'Terjadi kesalahan saat validasi NRP'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $abk = ABKNew::with('jabatanTetap')->findOrFail($id);
            return view('kelolaABK.show', compact('abk'));
        } catch (\Exception $e) {
            Log::error('Error showing ABK: ' . $e->getMessage());
            return redirect()->route('abk.index')->with('error', 'ABK tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $abk = ABKNew::with('jabatanTetap')->findOrFail($id);
            $daftarJabatan = Jabatan::orderBy('nama_jabatan', 'asc')->get();
            
            return view('kelolaABK.edit', compact('abk', 'daftarJabatan'));
        } catch (\Exception $e) {
            Log::error('Error loading edit ABK form: ' . $e->getMessage());
            return redirect()->route('abk.index')->with('error', 'Gagal memuat form edit ABK');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $abk = ABKNew::findOrFail($id);
            
            $validatedData = $request->validate([
                'id' => [
                    'required',
                    'string',
                    'min:4',
                    'max:20',
                    'regex:/^[0-9]+$/',
                    'unique:abk_new,id,' . $abk->id . ',id'
                ],
                'nama_abk' => 'required|string|max:255|min:2',
                'id_jabatan_tetap' => 'required|exists:jabatan,id',
                'status_abk' => 'required|in:Organik,Non Organik,Pensiun'
            ]);

            DB::beginTransaction();

            $abk->update($validatedData);

            DB::commit();

            return redirect()->route('abk.index')->with('success', 'Data ABK berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating ABK: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Gagal memperbarui data ABK']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $abk = ABKNew::findOrFail($id);
            $namaABK = $abk->nama_abk;
            
            // Cek apakah ABK masih memiliki mutasi aktif
            // Uncomment jika ada relasi mutasi
            /*
            if ($abk->mutasiAktif()->exists()) {
                return redirect()->back()->with('error', 'ABK tidak dapat dihapus karena masih memiliki mutasi aktif.');
            }
            */
            
            $abk->delete();
            
            return redirect()->route('abk.index')->with('success', "ABK {$namaABK} berhasil dihapus dari sistem.");
            
        } catch (\Exception $e) {
            Log::error('Error deleting ABK: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus ABK: ' . $e->getMessage());
        }
    }

    /**
     * Show export import page
     */
    public function exportImport()
    {
        try {
            // Get data for filters
            $daftarKapal = Kapal::select('id as id_kapal', 'nama_kapal')
                ->orderBy('nama_kapal')
                ->get();
            
            // Export statistics
            $exportStats = [
                'total_abk' => ABKNew::count(),
                'aktif' => ABKNew::where('status_abk', '!=', 'Pensiun')->count(),
                'kapal' => $daftarKapal->count()
            ];
            
            // Import history (dummy for now)
            $importHistory = collect([
                (object)[
                    'id' => 1,
                    'file_name' => 'mutasi_abk_2025.xlsx',
                    'import_type' => 'mutasi',
                    'status' => 'success',
                    'processed_records' => 25,
                    'total_records' => 25,
                    'failed_records' => 0,
                    'admin_name' => 'Admin',
                    'created_at' => now()->subHours(2),
                    'error_log' => null
                ],
                (object)[
                    'id' => 2,
                    'file_name' => 'data_abk_baru.xlsx',
                    'import_type' => 'abk',
                    'status' => 'failed',
                    'processed_records' => 15,
                    'total_records' => 20,
                    'failed_records' => 5,
                    'admin_name' => 'Admin',
                    'created_at' => now()->subDays(1),
                    'error_log' => 'Format tanggal tidak valid'
                ]
            ]);
            
            return view('kelolaABK.export', compact(
                'daftarKapal',
                'exportStats',
                'importHistory'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading export page: ' . $e->getMessage());
            return redirect()->route('abk.index')->with('error', 'Gagal memuat halaman export');
        }
    }

    /**
     * Export ABK data
     */
    public function export(Request $request, $format = 'excel')
    {
        try {
            Log::info('Export request:', [
                'format' => $format,
                'params' => $request->all()
            ]);

            // Build query with filters
            $query = ABKNew::with(['jabatanTetap', 'kapalAktif']);
            
            // Apply filters
            if ($request->export_kapal) {
                $query->where('id_kapal_aktif', $request->export_kapal);
            }
            
            if ($request->export_status) {
                $query->where('status_abk', $request->export_status);
            }
            
            if ($request->export_start_date) {
                $query->whereDate('created_at', '>=', $request->export_start_date);
            }
            
            if ($request->export_end_date) {
                $query->whereDate('created_at', '<=', $request->export_end_date);
            }
            
            $abkData = $query->orderBy('nama_abk')->get();
            
            if ($format === 'excel') {
                return $this->exportExcel($abkData, $request);
            } elseif ($format === 'pdf') {
                return $this->exportPdf($abkData, $request);
            }
            
            return response()->json(['error' => 'Format tidak didukung'], 400);
            
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal export data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export to Excel
     */
    private function exportExcel($abkData, $request)
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set headers
            $headers = [
                'A1' => 'NRP',
                'B1' => 'Nama ABK',
                'C1' => 'Jabatan Tetap',
                'D1' => 'Status ABK',
                'E1' => 'Kapal Aktif',
                'F1' => 'Tanggal Dibuat'
            ];
            
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }
            
            // Style headers
            $sheet->getStyle('A1:F1')->getFont()->setBold(true);
            $sheet->getStyle('A1:F1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E3F2FD');
            
            // Add data
            $row = 2;
            foreach ($abkData as $abk) {
                $sheet->setCellValue('A' . $row, $abk->id);
                $sheet->setCellValue('B' . $row, $abk->nama_abk);
                $sheet->setCellValue('C' . $row, $abk->jabatanTetap->nama_jabatan ?? '-');
                $sheet->setCellValue('D' . $row, $abk->status_abk);
                $sheet->setCellValue('E' . $row, $abk->kapalAktif->nama_kapal ?? '-');
                $sheet->setCellValue('F' . $row, $abk->created_at ? $abk->created_at->format('d/m/Y') : '-');
                $row++;
            }
            
            // Auto-size columns
            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Generate filename
            $filename = 'data_abk_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            // Create writer and save to temp file
            $writer = new Xlsx($spreadsheet);
            $tempFile = tempnam(sys_get_temp_dir(), 'abk_export_');
            $writer->save($tempFile);
            
            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Excel export error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Export to PDF
     */
    private function exportPdf($abkData, $request)
    {
        try {
            $data = [
                'abkData' => $abkData,
                'filters' => $request->all(),
                'generated_at' => now(),
                'total_records' => $abkData->count()
            ];
            
            $pdf = PDF::loadView('kelolaABK.export-pdf', $data);
            $filename = 'data_abk_' . date('Y-m-d_H-i-s') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('PDF export error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Download Excel template
     */
    public function downloadTemplate($type = 'excel')
    {
        try {
            if ($type === 'excel') {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                
                // Template headers for mutasi data
                $headers = [
                    'A1' => 'NRP ABK Naik',
                    'B1' => 'Nama ABK Naik', 
                    'C1' => 'Jabatan Baru',
                    'D1' => 'NRP ABK Turun',
                    'E1' => 'Nama ABK Turun',
                    'F1' => 'Jabatan Lama',
                    'G1' => 'Nama Kapal',
                    'H1' => 'Jenis Mutasi',
                    'I1' => 'TMT (YYYY-MM-DD)',
                    'J1' => 'TAT (YYYY-MM-DD)',
                    'K1' => 'Keterangan'
                ];
                
                foreach ($headers as $cell => $value) {
                    $sheet->setCellValue($cell, $value);
                }
                
                // Style headers
                $sheet->getStyle('A1:K1')->getFont()->setBold(true);
                $sheet->getStyle('A1:K1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E8F5E8');
                
                // Add example data
                $exampleData = [
                    ['12345', 'John Doe', 'Nahkoda', '67890', 'Jane Smith', 'Mualim I', 'KM Sirimau', 'Mutasi Rutin', '2025-01-15', '2025-01-20', 'Mutasi rutin bulanan'],
                    ['23456', 'Ahmad Ali', 'Masinis I', '', '', '', 'KM Tatamailau', 'Mutasi Darurat', '2025-01-16', '2025-01-18', 'ABK naik tanpa pengganti']
                ];
                
                $row = 2;
                foreach ($exampleData as $data) {
                    $col = 'A';
                    foreach ($data as $value) {
                        $sheet->setCellValue($col . $row, $value);
                        $col++;
                    }
                    $row++;
                }
                
                // Auto-size columns
                foreach (range('A', 'K') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                
                $filename = 'template_mutasi_abk.xlsx';
                $writer = new Xlsx($spreadsheet);
                $tempFile = tempnam(sys_get_temp_dir(), 'template_');
                $writer->save($tempFile);
                
                return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
                
            } else {
                // PDF template - simple instruction
                $data = ['type' => 'template'];
                $pdf = PDF::loadView('kelolaABK.template-pdf', $data);
                return $pdf->download('template_format_mutasi.pdf');
            }
            
        } catch (\Exception $e) {
            Log::error('Template download error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal download template'], 500);
        }
    }

    /**
     * Import data from file
     */
    public function import(Request $request)
    {
        try {
            $request->validate([
                'import_file' => 'required|file|mimes:xlsx,xls,pdf|max:10240',
                'import_type' => 'required|in:mutasi,abk',
                'skip_duplicates' => 'nullable|boolean'
            ]);
            
            Log::info('Import request:', [
                'type' => $request->import_type,
                'file' => $request->file('import_file')->getClientOriginalName()
            ]);
            
            $file = $request->file('import_file');
            $skipDuplicates = $request->boolean('skip_duplicates');
            
            if ($request->import_type === 'mutasi') {
                return $this->importMutasi($file, $skipDuplicates);
            } else {
                return $this->importABK($file, $skipDuplicates);
            }
            
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal import data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import mutasi data
     */
    private function importMutasi($file, $skipDuplicates)
    {
        try {
            DB::beginTransaction();
            
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Skip header row
            array_shift($rows);
            
            $imported = 0;
            $failed = 0;
            $errors = [];
            
            foreach ($rows as $index => $row) {
                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) continue;
                    
                    $rowNum = $index + 2; // +2 because we removed header and array is 0-indexed
                    
                    // Validate required fields
                    if (empty($row[0]) || empty($row[1]) || empty($row[6])) {
                        $errors[] = "Baris {$rowNum}: NRP ABK Naik, Nama ABK Naik, dan Nama Kapal wajib diisi";
                        $failed++;
                        continue;
                    }
                    
                    // Create mutasi record (simplified for demo)
                    $mutasiData = [
                        'id_abk_naik' => $row[0],
                        'nama_lengkap_naik' => $row[1],
                        'jabatan_tetap_naik' => $this->getJabatanId($row[2]),
                        'id_abk_turun' => $row[3] ?: null,
                        'nama_lengkap_turun' => $row[4] ?: null,
                        'jabatan_tetap_turun' => $row[5] ? $this->getJabatanId($row[5]) : null,
                        'id_kapal' => $this->getKapalId($row[6]),
                        'jenis_mutasi' => $row[7] ?: 'Mutasi Rutin',
                        'TMT' => $this->parseDate($row[8]),
                        'TAT' => $this->parseDate($row[9]),
                        'keterangan' => $row[10] ?: null,
                        'status_mutasi' => 'Draft',
                        'ada_abk_turun' => !empty($row[3]),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    
                    // Insert to mutasi_new table
                    DB::table('mutasi_new')->insert($mutasiData);
                    $imported++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNum}: " . $e->getMessage();
                    $failed++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Import selesai. {$imported} data berhasil, {$failed} gagal",
                'total_records' => count($rows),
                'success_records' => $imported,
                'failed_records' => $failed,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Import mutasi error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Import ABK data
     */
    private function importABK($file, $skipDuplicates)
    {
        try {
            DB::beginTransaction();
            
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Skip header row
            array_shift($rows);
            
            $imported = 0;
            $failed = 0;
            $errors = [];
            
            foreach ($rows as $index => $row) {
                try {
                    if (empty(array_filter($row))) continue;
                    
                    $rowNum = $index + 2;
                    
                    if (empty($row[0]) || empty($row[1])) {
                        $errors[] = "Baris {$rowNum}: NRP dan Nama ABK wajib diisi";
                        $failed++;
                        continue;
                    }
                    
                    // Check for duplicates
                    if ($skipDuplicates && ABKNew::where('id', $row[0])->exists()) {
                        continue; // Skip duplicate
                    }
                    
                    ABKNew::create([
                        'id' => $row[0],
                        'nama_abk' => $row[1],
                        'id_jabatan_tetap' => $this->getJabatanId($row[2]) ?: 1,
                        'status_abk' => $row[3] ?: 'Organik'
                    ]);
                    
                    $imported++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNum}: " . $e->getMessage();
                    $failed++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Import selesai. {$imported} data berhasil, {$failed} gagal",
                'total_records' => count($rows),
                'success_records' => $imported,
                'failed_records' => $failed,
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Import ABK error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Helper: Get jabatan ID by name
     */
    private function getJabatanId($namaJabatan)
    {
        if (empty($namaJabatan)) return null;
        
        $jabatan = Jabatan::where('nama_jabatan', 'LIKE', '%' . trim($namaJabatan) . '%')->first();
        return $jabatan ? $jabatan->id : null;
    }

    /**
     * Helper: Get kapal ID by name
     */
    private function getKapalId($namaKapal)
    {
        if (empty($namaKapal)) return null;
        
        $kapal = Kapal::where('nama_kapal', 'LIKE', '%' . trim($namaKapal) . '%')->first();
        return $kapal ? $kapal->id : null;
    }

    /**
     * Helper: Parse date string
     */
    private function parseDate($dateString)
    {
        if (empty($dateString)) return null;
        
        try {
            return \Carbon\Carbon::parse($dateString)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
