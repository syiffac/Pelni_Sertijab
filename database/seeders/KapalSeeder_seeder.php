<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KapalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kapalData = [
            ['id' => 103, 'nama_kapal' => 'KM UMSINI', 'tipe_pax' => 2000, 'home_base' => 'JAKARTA'],
            ['id' => 104, 'nama_kapal' => 'KM KELIMUTU', 'tipe_pax' => 1000, 'home_base' => 'JAKARTA'],
            ['id' => 105, 'nama_kapal' => 'KM LAWIT', 'tipe_pax' => 1000, 'home_base' => 'SURABAYA'],
            ['id' => 106, 'nama_kapal' => 'KM TIDAR', 'tipe_pax' => 2000, 'home_base' => 'MAKASSAR'],
            ['id' => 107, 'nama_kapal' => 'KM TATAMAILAU', 'tipe_pax' => 1000, 'home_base' => 'BITUNG'],
            ['id' => 108, 'nama_kapal' => 'KM SIRIMAU', 'tipe_pax' => 1000, 'home_base' => 'KUPANG'],
            ['id' => 109, 'nama_kapal' => 'KM AWU', 'tipe_pax' => 1000, 'home_base' => 'SURABAYA'],
            ['id' => 110, 'nama_kapal' => 'KM CIREMAI', 'tipe_pax' => 2000, 'home_base' => 'JAKARTA'],
            ['id' => 111, 'nama_kapal' => 'KM DOBONSOLO', 'tipe_pax' => 2000, 'home_base' => 'JAKARTA'],
            ['id' => 112, 'nama_kapal' => 'KM LEUSER', 'tipe_pax' => 1000, 'home_base' => 'SURABAYA'],
            ['id' => 113, 'nama_kapal' => 'KM BINAIYA', 'tipe_pax' => 1000, 'home_base' => 'MAKASSAR'],
            ['id' => 114, 'nama_kapal' => 'KM BUKIT RAYA', 'tipe_pax' => 1000, 'home_base' => 'JAKARTA'],
            ['id' => 115, 'nama_kapal' => 'KM TILONG KABILA', 'tipe_pax' => 1000, 'home_base' => 'BENOA'],
            ['id' => 116, 'nama_kapal' => 'KM BUKIT SIGUNTANG', 'tipe_pax' => 2000, 'home_base' => 'MAKASSAR'],
            ['id' => 117, 'nama_kapal' => 'KM LAMBELU', 'tipe_pax' => 2000, 'home_base' => 'MAKASSAR'],
            ['id' => 118, 'nama_kapal' => 'KM SINABUNG', 'tipe_pax' => 2000, 'home_base' => 'SURABAYA'],
            ['id' => 119, 'nama_kapal' => 'KM KELUD', 'tipe_pax' => 2000, 'home_base' => 'JAKARTA'],
            ['id' => 120, 'nama_kapal' => 'KM PANGRANGO', 'tipe_pax' => 500, 'home_base' => 'AMBON'],
            ['id' => 122, 'nama_kapal' => 'KM SANGIANG', 'tipe_pax' => 500, 'home_base' => 'BITUNG'],
            ['id' => 123, 'nama_kapal' => 'KM WILIS', 'tipe_pax' => 500, 'home_base' => 'MAKASSAR'],
            ['id' => 126, 'nama_kapal' => 'KM EGON', 'tipe_pax' => 1000, 'home_base' => 'SURABAYA'],
            ['id' => 127, 'nama_kapal' => 'KM DOROLONDA', 'tipe_pax' => 2000, 'home_base' => 'SURABAYA'],
            ['id' => 128, 'nama_kapal' => 'KM NGGAPULU', 'tipe_pax' => 2000, 'home_base' => 'JAKARTA'],
            ['id' => 131, 'nama_kapal' => 'KM LABOBAR', 'tipe_pax' => 2000, 'home_base' => 'SURABAYA'],
            ['id' => 132, 'nama_kapal' => 'KM DEMPO', 'tipe_pax' => 2000, 'home_base' => 'JAKARTA'],
            ['id' => 152, 'nama_kapal' => 'KM JET LINER', 'tipe_pax' => 1000, 'home_base' => 'BAU-BAU'],
        ];

        $now = Carbon::now();

        foreach ($kapalData as $kapal) {
            // Tambahkan timestamps
            $kapal['created_at'] = $now;
            $kapal['updated_at'] = $now;

            // Gunakan updateOrInsert untuk handle duplicate key update
            DB::table('kapal')->updateOrInsert(
                ['id' => $kapal['id']], // Kondisi where
                $kapal // Data yang akan diinsert/update
            );
        }

        $this->command->info('Kapal seeder completed. ' . count($kapalData) . ' kapal records processed.');
        
        // Tampilkan statistik
        $this->displayStatistics();
    }

    /**
     * Display statistics after seeding
     */
    private function displayStatistics(): void
    {
        $totalKapal = DB::table('kapal')->count();
        $kapalByHomeBase = DB::table('kapal')
            ->select('home_base', DB::raw('count(*) as total'))
            ->groupBy('home_base')
            ->orderBy('total', 'desc')
            ->get();

        $kapalByTipePax = DB::table('kapal')
            ->select('tipe_pax', DB::raw('count(*) as total'))
            ->groupBy('tipe_pax')
            ->orderBy('tipe_pax')
            ->get();

        $this->command->info('=== STATISTIK KAPAL ===');
        $this->command->info("Total Kapal: {$totalKapal}");
        
        $this->command->info("\nKapal per Home Base:");
        foreach ($kapalByHomeBase as $base) {
            $this->command->info("- {$base->home_base}: {$base->total} kapal");
        }

        $this->command->info("\nKapal per Tipe PAX:");
        foreach ($kapalByTipePax as $tipe) {
            $this->command->info("- {$tipe->tipe_pax} PAX: {$tipe->total} kapal");
        }
    }
}
