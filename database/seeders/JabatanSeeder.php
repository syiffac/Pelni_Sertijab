<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatanData = [
            ['id' => 1, 'nama_jabatan' => 'NAKHODA'],
            ['id' => 2, 'nama_jabatan' => 'MUALIM I'],
            ['id' => 3, 'nama_jabatan' => 'MUALIM II SR'],
            ['id' => 4, 'nama_jabatan' => 'MUALIM II YR'],
            ['id' => 5, 'nama_jabatan' => 'MUALIM II'],
            ['id' => 6, 'nama_jabatan' => 'MUALIM III SR'],
            ['id' => 7, 'nama_jabatan' => 'MUALIM III YR'],
            ['id' => 8, 'nama_jabatan' => 'MUALIM III'],
            ['id' => 9, 'nama_jabatan' => 'MUALIM IV'],
            ['id' => 10, 'nama_jabatan' => 'PERWIRA RADIO I'],
            ['id' => 11, 'nama_jabatan' => 'PERWIRA RADIO II'],
            ['id' => 12, 'nama_jabatan' => 'ITTO'],
            ['id' => 13, 'nama_jabatan' => 'JENANG I'],
            ['id' => 14, 'nama_jabatan' => 'JENANG II'],
            ['id' => 15, 'nama_jabatan' => 'JENANG III'],
            ['id' => 16, 'nama_jabatan' => 'DOKTER'],
            ['id' => 17, 'nama_jabatan' => 'KKM'],
            ['id' => 18, 'nama_jabatan' => 'MASINIS I SR'],
            ['id' => 19, 'nama_jabatan' => 'MASINIS I YR'],
            ['id' => 20, 'nama_jabatan' => 'MASINIS I'],
            ['id' => 21, 'nama_jabatan' => 'MASINIS II'],
            ['id' => 22, 'nama_jabatan' => 'MASINIS III SR'],
            ['id' => 23, 'nama_jabatan' => 'MASINIS III YR'],
            ['id' => 24, 'nama_jabatan' => 'MASINIS III'],
            ['id' => 25, 'nama_jabatan' => 'MASINIS IV SR'],
            ['id' => 26, 'nama_jabatan' => 'MASINIS IV YR'],
            ['id' => 27, 'nama_jabatan' => 'MASINIS IV'],
            ['id' => 28, 'nama_jabatan' => 'JURU MOTOR'],
            ['id' => 29, 'nama_jabatan' => 'AHLI LISTRIK I'],
            ['id' => 30, 'nama_jabatan' => 'AHLI LISTRIK II'],
            ['id' => 31, 'nama_jabatan' => 'AHLI LISTRIK III'],
            ['id' => 32, 'nama_jabatan' => 'ETO'],
            ['id' => 33, 'nama_jabatan' => 'PUK I'],
            ['id' => 34, 'nama_jabatan' => 'PUK II'],
            ['id' => 35, 'nama_jabatan' => 'PUK III'],
            ['id' => 36, 'nama_jabatan' => 'PUK'],
            ['id' => 37, 'nama_jabatan' => 'PERAWAT'],
            ['id' => 38, 'nama_jabatan' => 'PERAKIT MASAK'],
            ['id' => 39, 'nama_jabatan' => 'JURU MASAK'],
            ['id' => 40, 'nama_jabatan' => 'PELAYAN KEPALA'],
            ['id' => 41, 'nama_jabatan' => 'PELAYAN'],
            ['id' => 42, 'nama_jabatan' => 'PENATU'],
            ['id' => 43, 'nama_jabatan' => 'SERANG'],
            ['id' => 44, 'nama_jabatan' => 'TANDIL'],
            ['id' => 45, 'nama_jabatan' => 'MISTRI I'],
            ['id' => 46, 'nama_jabatan' => 'KASAP DEK'],
            ['id' => 47, 'nama_jabatan' => 'JURU MUDI'],
            ['id' => 48, 'nama_jabatan' => 'PANJARWALA'],
            ['id' => 49, 'nama_jabatan' => 'KELASI'],
            ['id' => 50, 'nama_jabatan' => 'Mandor Mesin'],
            ['id' => 51, 'nama_jabatan' => 'Pandai Besi'],
            ['id' => 52, 'nama_jabatan' => 'Kasap Mesin'],
            ['id' => 53, 'nama_jabatan' => 'Juru Minyak'],
            ['id' => 54, 'nama_jabatan' => 'Tukang Angsur'],
        ];

        foreach ($jabatanData as $data) {
            Jabatan::updateOrCreate(
                ['id' => $data['id']], // Kondisi untuk mencari record
                $data // Data yang akan diinsert/update
            );
        }

        $this->command->info('Jabatan seeder completed. ' . count($jabatanData) . ' jabatan records processed.');
    }
}
