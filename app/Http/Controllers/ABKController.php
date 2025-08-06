<?php
// filepath: c:\laragon\www\SertijabPelni\app\Http\Controllers\ABKController.php

namespace App\Http\Controllers;

use App\Models\Kapal;
use App\Models\ABKNew;
use App\Models\Mutasi;
use App\Models\Jabatan;
use App\Imports\AbkImport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Models\RiwayatImportExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // TAMBAH INI
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Validation\ValidationException;

class ABKController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Data statistik ABK
            $totalStatistik = [
                'total_abk' => ABKNew::count(),
                'abk_aktif' => ABKNew::where('status_abk', '!=', 'Pensiun')->count(),
                'abk_organik' => ABKNew::where('status_abk', 'Organik')->count(),
                'abk_non_organik' => ABKNew::where('status_abk', 'Non Organik')->count(),
                'abk_pensiun' => ABKNew::where('status_abk', 'Pensiun')->count()
            ];

            // Data ABK per kapal - DARI TABEL MUTASI
            $abkPerKapal = Mutasi::with('kapal')
                ->select('id_kapal', 'nama_kapal')
                ->selectRaw('COUNT(DISTINCT id_abk_naik) as total_abk_naik')
                ->selectRaw('COUNT(DISTINCT id_abk_turun) as total_abk_turun')
                ->selectRaw('COUNT(CASE WHEN status_mutasi = "Selesai" THEN 1 END) as mutasi_selesai')
                ->selectRaw('COUNT(CASE WHEN status_mutasi IN ("Draft", "Disetujui") THEN 1 END) as mutasi_proses')
                ->groupBy('id_kapal', 'nama_kapal')
                ->orderByDesc('total_abk_naik')
                ->limit(6)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id_kapal,
                        'nama_kapal' => $item->nama_kapal,
                        'total_abk' => $item->total_abk_naik + $item->total_abk_turun,
                        'abk_aktif' => $item->mutasi_selesai,
                        'abk_tidak_aktif' => $item->mutasi_proses
                    ];
                });

            // Data mutasi terbaru - DARI TABEL MUTASI
            $mutasiTerbaru = Mutasi::with(['kapal', 'abkNaik', 'abkTurun'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($mutasi) {
                    return [
                        'id' => $mutasi->id,
                        'abkTurun' => [
                            'nama_abk' => $mutasi->nama_lengkap_naik // ABK yang naik ke kapal
                        ],
                        'kapalTurun' => [
                            'nama_kapal' => 'From Previous Ship' // Kapal asal (bisa ditambahkan field nanti)
                        ],
                        'kapalNaik' => [
                            'nama_kapal' => $mutasi->nama_kapal // Kapal tujuan
                        ],
                        'status_mutasi' => $mutasi->status_mutasi,
                        'created_at' => $mutasi->created_at,
                        'jenis_mutasi' => $mutasi->jenis_mutasi,
                        'periode' => $mutasi->TMT ? $mutasi->TMT->format('d/m/Y') : 'N/A'
                    ];
                });

            // Data ABK list terbaru
            $abkList = ABKNew::with(['jabatanTetap', 'kapalAktif'])
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get();

            Log::info('ABK data loaded successfully', [
                'total_abk' => $totalStatistik['total_abk'],
                'kapal_count' => $abkPerKapal->count(),
                'mutasi_count' => $mutasiTerbaru->count(),
                'abk_list_count' => $abkList->count()
            ]);
                
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
            $abkList = collect();
            
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
     * Show export import page - UPDATE method ini
     */
    public function exportPage()
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
            
            // Import/Export history dengan limit 10 dan data yang lebih lengkap
            $importHistory = RiwayatImportExport::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10) // Batasi hanya 10 record terbaru
                ->get()
                ->map(function($history) {
                    return (object)[
                        'id' => $history->id,
                        'file_name' => $history->nama_file,
                        'import_type' => $history->jenis,
                        'tipe' => $history->tipe,
                        'jenis' => $history->jenis,
                        'status' => $history->status,
                        'processed_records' => $history->jumlah_berhasil,
                        'total_records' => $history->jumlah_data,
                        'failed_records' => $history->jumlah_gagal,
                        'jumlah_dilewati' => $history->jumlah_dilewati ?? 0,
                        'admin_name' => $history->user->nama_admin ?? 'System',
                        'created_at' => $history->created_at,
                        'error_log' => $history->keterangan,
                        'file_size' => $history->file_size,
                        'durasi_proses' => $history->durasi_proses,
                        
                        // Computed attributes dari model
                        'formatted_file_size' => $history->formatted_file_size,
                        'formatted_duration' => $history->formatted_duration,
                        'success_rate' => $history->success_rate,
                        'status_badge_class' => $history->status_badge_class,
                        'status_icon' => $history->status_icon,
                        'tipe_icon' => $history->tipe_icon,
                        'jenis_label' => $history->jenis_label
                    ];
                });
            
            return view('kelolaABK.export', compact(
                'daftarKapal',
                'exportStats', 
                'importHistory'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading export page: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('abk.index')->with('error', 'Gagal memuat halaman export: ' . $e->getMessage());
        }
    }

    /**
     * Export ABK data - UPDATE dengan riwayat
     */
    public function export(Request $request, $format = 'excel')
    {
        try {
            Log::info('Export request:', [
                'format' => $format,
                'params' => $request->all(),
                'user_id' => Auth::id()
            ]);

            // Build query with filters
            $query = ABKNew::with(['jabatanTetap']);
            
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
            $totalRecords = $abkData->count();
            
            // Generate filename
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "data_abk_{$format}_{$timestamp}.{$format}";
            
            // Simpan riwayat export ke database
            $riwayat = RiwayatImportExport::create([
                'nama_file' => $filename,
                'tipe' => 'export',
                'jenis' => 'abk',
                'status' => 'processing',
                'jumlah_data' => $totalRecords,
                'jumlah_berhasil' => 0,
                'jumlah_gagal' => 0,
                'keterangan' => 'Export dimulai',
                'user_id' => Auth::id()
            ]);
            
            try {
                if ($format === 'excel') {
                    $response = $this->exportExcel($abkData, $request, $filename);
                } elseif ($format === 'pdf') {
                    $response = $this->exportPdf($abkData, $request, $filename);
                } else {
                    throw new \Exception('Format tidak didukung');
                }
                
                // Update riwayat sukses
                $riwayat->update([
                    'status' => 'success',
                    'jumlah_berhasil' => $totalRecords,
                    'keterangan' => "Export berhasil. {$totalRecords} data diekspor."
                ]);
                
                return $response;
                
            } catch (\Exception $e) {
                // Update riwayat gagal
                $riwayat->update([
                    'status' => 'failed',
                    'keterangan' => 'Export gagal: ' . $e->getMessage()
                ]);
                
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal export data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export to Excel - UPDATE dengan filename parameter
     */
    private function exportExcel($abkData, $request, $filename = null)
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set title dan info
            $sheet->setCellValue('A1', 'LAPORAN DATA ABK');
            $sheet->setCellValue('A2', 'Tanggal Export: ' . date('d/m/Y H:i:s'));
            $sheet->setCellValue('A3', 'Total Data: ' . $abkData->count() . ' ABK');
            
            // Merge title cells
            $sheet->mergeCells('A1:F1');
            $sheet->mergeCells('A2:F2');
            $sheet->mergeCells('A3:F3');
            
            // Style title
            $sheet->getStyle('A1:A3')->getFont()->setBold(true);
            $sheet->getStyle('A1')->getFont()->setSize(16);
            $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            
            // Set headers
            $headers = [
                'A5' => 'NRP',
                'B5' => 'Nama ABK',
                'C5' => 'Jabatan Tetap',
                'D5' => 'Status ABK',
                'E5' => 'Kapal Aktif',
                'F5' => 'Tanggal Dibuat'
            ];
            
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }
            
            // Style headers
            $sheet->getStyle('A5:F5')->getFont()->setBold(true);
            $sheet->getStyle('A5:F5')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E3F2FD');
            $sheet->getStyle('A5:F5')->getBorders()->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            // Add data
            $row = 6;
            foreach ($abkData as $abk) {
                $sheet->setCellValue('A' . $row, $abk->id);
                $sheet->setCellValue('B' . $row, $abk->nama_abk);
                $sheet->setCellValue('C' . $row, $abk->jabatanTetap->nama_jabatan ?? '-');
                $sheet->setCellValue('D' . $row, $abk->status_abk);
                $sheet->setCellValue('E' . $row, 'N/A'); // Kapal aktif field tidak ada di model
                $sheet->setCellValue('F' . $row, $abk->created_at ? $abk->created_at->format('d/m/Y') : '-');
                
                // Add borders to data rows
                $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                
                $row++;
            }
            
            // Auto-size columns
            foreach (range('A', 'F') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Set default filename if not provided
            if (!$filename) {
                $filename = 'data_abk_' . date('Y-m-d_H-i-s') . '.xlsx';
            }
            
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
     * Export to PDF - UPDATE dengan filename parameter
     */
    private function exportPdf($abkData, $request, $filename = null)
    {
        try {
            $data = [
                'abkData' => $abkData,
                'filters' => $request->all(),
                'generated_at' => now(),
                'total_records' => $abkData->count(),
                'exported_by' => Auth::user()->name ?? 'System'
            ];
            
            $pdf = PDF::loadView('kelolaABK.export-pdf', $data);
            
            if (!$filename) {
                $filename = 'data_abk_' . date('Y-m-d_H-i-s') . '.pdf';
            }
            
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
            
            // Template headers untuk data ABK
            $headers = [
                'A1' => 'ID',
                'B1' => 'Nama ABK', 
                'C1' => 'Jabatan Tetap',
                'D1' => 'Status ABK'
            ];
            
            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }
            
            // Style headers
            $sheet->getStyle('A1:D1')->getFont()->setBold(true);
            $sheet->getStyle('A1:D1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E8F5E8');
            
            // Add example data untuk ABK
            $exampleData = [
                ['12345', 'John Doe', 'Nahkoda', 'Organik'],
                ['23456', 'Ahmad Ali', 'Mualim I', 'Non Organik'],
                ['34567', 'Budi Santoso', 'Masinis I', 'Organik'],
                ['45678', 'Siti Aminah', 'Radio Officer', 'Organik']
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
            foreach (range('A', 'D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // Add instructions in separate sheet
            $instructionSheet = $spreadsheet->createSheet();
            $instructionSheet->setTitle('Instruksi');
            
            $instructions = [
                ['INSTRUKSI IMPORT DATA ABK'],
                [''],
                ['1. Kolom yang wajib diisi:'],
                ['   - NRP: Nomor Registrasi Pokok (hanya angka, 4-20 digit)'],
                ['   - Nama ABK: Nama lengkap ABK'],
                [''],
                ['2. Kolom opsional:'],
                ['   - Jabatan Tetap: Nama jabatan (akan dicari otomatis di database)'],
                ['   - Status ABK: Organik, Non Organik, atau Pensiun (default: Organik)'],
                [''],
                ['3. Format data:'],
                ['   - NRP harus unik (tidak boleh sama)'],
                ['   - Gunakan format Excel (.xlsx atau .xls)'],
                ['   - Maksimal 1000 baris data'],
                [''],
                ['4. Tips:'],
                ['   - Hapus baris contoh sebelum input data sesungguhnya'],
                ['   - Pastikan tidak ada baris kosong di tengah data'],
                ['   - Gunakan opsi "Skip Duplicates" jika tidak ingin import data yang sudah ada']
            ];
            
            $row = 1;
            foreach ($instructions as $instruction) {
                $instructionSheet->setCellValue('A' . $row, $instruction[0]);
                if ($row === 1) {
                    $instructionSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                }
                $row++;
            }
            
            $instructionSheet->getColumnDimension('A')->setWidth(80);
            
            $filename = 'template_data_abk.xlsx';
            $writer = new Xlsx($spreadsheet);
            $tempFile = tempnam(sys_get_temp_dir(), 'template_');
            $writer->save($tempFile);
            
            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
            
        } else {
            // PDF template - simple instruction
            $data = ['type' => 'abk_template'];
            $pdf = PDF::loadView('kelolaABK.template-pdf', $data);
            return $pdf->download('template_format_abk.pdf');
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
        // Validasi request dengan handling khusus untuk boolean
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'import_type' => 'required|in:mutasi,abk',
            'skip_duplicates' => 'nullable' // Hapus boolean validation, handle manual
        ]);
        
        // PERBAIKAN: Handle skip_duplicates dengan lebih fleksibel
        $skipDuplicates = $this->parseBooleanValue($request->get('skip_duplicates', true));
        
        Log::info('Import request received:', [
            'type' => $request->import_type,
            'file' => $request->file('import_file')->getClientOriginalName(),
            'skip_duplicates_raw' => $request->get('skip_duplicates'),
            'skip_duplicates_parsed' => $skipDuplicates
        ]);
        
        $file = $request->file('import_file');
        
        if ($request->import_type === 'abk') {
            return $this->importABKWithClass($file, $skipDuplicates);
        } else {
            return $this->importMutasi($file, $skipDuplicates);
        }
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation error in import:', $e->errors());
        
        // Gunakan Laravel Collection untuk flatten
        $allErrors = collect($e->errors())->flatten()->toArray();
        
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal: ' . implode(', ', $allErrors),
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        Log::error('Import error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'success' => false,
            'message' => 'Gagal import data: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Helper method untuk parse boolean value dari request
 */
private function parseBooleanValue($value)
{
    // Handle berbagai format boolean
    if (is_bool($value)) {
        return $value;
    }
    
    if (is_string($value)) {
        $value = strtolower(trim($value));
        return in_array($value, ['1', 'true', 'on', 'yes']);
    }
    
    if (is_numeric($value)) {
        return (bool) $value;
    }
    
    // Default ke true jika tidak ada atau null
    return $value !== null ? (bool) $value : true;
}

/**
 * Import ABK data using AbkImport class
 */
private function importABKWithClass($file, $skipDuplicates)
{
    try {
        $originalFilename = $file->getClientOriginalName();
        
        // Simpan riwayat import ke database
        $riwayat = RiwayatImportExport::create([
            'nama_file' => $originalFilename,
            'tipe' => 'import',
            'jenis' => 'abk',
            'status' => 'processing',
            'jumlah_data' => 0,
            'jumlah_berhasil' => 0,
            'jumlah_gagal' => 0,
            'keterangan' => 'Import dimulai',
            'user_id' => Auth::id()
        ]);
        
        DB::beginTransaction();
        
        // Create import instance
        $import = new \App\Imports\AbkImport($skipDuplicates);
        
        // Import the file
        Excel::import($import, $file);
        
        // Get statistics
        $stats = $import->getStats();
        
        DB::commit();
        
        $totalProcessed = $stats['imported'] + $stats['skipped'] + $stats['failed'];
        
        // Prepare response message
        $message = "Import selesai. ";
        $message .= "{$stats['imported']} data berhasil diimport";
        
        if ($stats['skipped'] > 0) {
            $message .= ", {$stats['skipped']} data dilewati";
        }
        
        if ($stats['failed'] > 0) {
            $message .= ", {$stats['failed']} data gagal";
        }
        
        // Update riwayat import
        $status = $stats['failed'] > 0 ? 'warning' : 'success';
        $keterangan = $message;
        
        if (!empty($stats['errors'])) {
            $keterangan .= "\n\nDetail Error:\n" . implode("\n", array_slice($stats['errors'], 0, 5));
            if (count($stats['errors']) > 5) {
                $keterangan .= "\n... dan " . (count($stats['errors']) - 5) . " error lainnya";
            }
        }
        
        $riwayat->update([
            'status' => $status,
            'jumlah_data' => $totalProcessed,
            'jumlah_berhasil' => $stats['imported'],
            'jumlah_gagal' => $stats['failed'],
            'keterangan' => $keterangan
        ]);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'total_records' => $totalProcessed,
            'success_records' => $stats['imported'],
            'skipped_records' => $stats['skipped'],
            'failed_records' => $stats['failed'],
            'errors' => $stats['errors'],
            'riwayat_id' => $riwayat->id
        ]);
        
    } catch (\Exception $e) {
        DB::rollback();
        
        // Update riwayat jika ada error
        if (isset($riwayat)) {
            $riwayat->update([
                'status' => 'failed',
                'keterangan' => 'Import gagal: ' . $e->getMessage()
            ]);
        }
        
        Log::error('Import ABK with class error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal import data ABK: ' . $e->getMessage()
        ], 500);
    }
}


public function getRiwayatHistory(Request $request)
{
    try {
        $limit = $request->get('limit', 10);
        
        $riwayat = RiwayatImportExport::with('user')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($history) {
                return [
                    'id' => $history->id,
                    'nama_file' => $history->nama_file,
                    'tipe' => $history->tipe,
                    'jenis' => $history->jenis,
                    'status' => $history->status,
                    'jumlah_data' => $history->jumlah_data,
                    'jumlah_berhasil' => $history->jumlah_berhasil,
                    'jumlah_gagal' => $history->jumlah_gagal,
                    'jumlah_dilewati' => $history->jumlah_dilewati ?? 0,
                    'admin_name' => $history->user->name ?? 'System',
                    'created_at' => $history->created_at->toISOString(),
                    'keterangan' => $history->keterangan,
                    'file_size' => $history->file_size,
                    'durasi_proses' => $history->durasi_proses,
                    
                    // Computed fields
                    'formatted_file_size' => $history->formatted_file_size,
                    'formatted_duration' => $history->formatted_duration,
                    'success_rate' => $history->success_rate
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $riwayat
        ]);
        
    } catch (\Exception $e) {
        Log::error('Error getting riwayat history: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat riwayat'
        ], 500);
    }
}

/**
 * Import mutasi data - Updated
 */
private function importMutasi($file, $skipDuplicates)
{
    try {
        // Implementasi import mutasi
        return response()->json([
            'success' => true,
            'message' => 'Import mutasi berhasil (demo)',
            'total_records' => 0,
            'success_records' => 0,
            'skipped_records' => 0,
            'failed_records' => 0,
            'errors' => []
        ]);
        
    } catch (\Exception $e) {
        Log::error('Import mutasi error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Import mutasi belum diimplementasikan: ' . $e->getMessage()
        ], 501);
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
