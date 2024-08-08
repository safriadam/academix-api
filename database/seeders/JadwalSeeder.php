<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definisikan data jadwal dengan waktu start dan finish
        $jadwalData = [
            [
                'id_kls'    => 4,
                'id_mk'     => 1,
                'ruang'     => 'TI-1',
                'hari'      => 'Senin',
                'start'     => '07:00:00',
                'finish'    => '08:40:00',
            ],
            [
                'id_kls'    => 4,
                'id_mk'     => 2,
                'ruang'     => 'TI-1',
                'hari'      => 'Senin',
                'start'     => '08:40:00',
                'finish'    => '15:50:00',
            ],
            [
                'id_kls'    => 4,
                'id_mk'     => 3,
                'ruang'     => 'R.Lab 11',
                'hari'      => 'Selasa',
                'start'     => '07:00:00',
                'finish'    => '08:40:00',
            ],
            [
                'id_kls'    => 4,
                'id_mk'     => 4,
                'ruang'     => 'R.Lab 11',
                'hari'      => 'Selasa',
                'start'     => '08:40:00',
                'finish'    => '15:00:00',
            ],
            [
                'id_kls'    => 4,
                'id_mk'     => 5,
                'ruang'     => 'R.Lab 14',
                'hari'      => 'Rabu',
                'start'     => '07:00:00',
                'finish'    => '08:40:00',
            ],
            [
                'id_kls'    => 4,
                'id_mk'     => 6,
                'ruang'     => 'R.Lab 14',
                'hari'      => 'Rabu',
                'start'     => '08:40:00',
                'finish'    => '15:50:00',
            ],
            [
                'id_kls'    => 4,
                'id_mk'     => 7,
                'ruang'     => 'TI-1',
                'hari'      => 'Kamis',
                'start'     => '07:50:00',
                'finish'    => '11:40:00',
            ],
            [
                'id_kls'    => 4,
                'id_mk'     => 8,
                'ruang'     => 'R.Lab 13',
                'hari'      => 'Jumat',
                'start'     => '07:00:00',
                'finish'    => '08:40:00',
            ],
            [
                'id_kls'    => 4,
                'id_mk'     => 9,
                'ruang'     => 'R.Lab 13',
                'hari'      => 'Jumat',
                'start'     => '08:40:00',
                'finish'    => '16:20:00',
            ],
        ];

        foreach ($jadwalData as $data) {
            // Hitung jumlah jam
            $start = Carbon::createFromFormat('H:i:s', $data['start']);
            $finish = Carbon::createFromFormat('H:i:s', $data['finish']);
            $jumlah_jam = $finish->diffInHours($start);

            // Jika finish lebih awal dari start, berarti selesai pada hari berikutnya
            if ($finish->lt($start)) {
                $jumlah_jam += 24; // Tambah 24 jam untuk melintasi tengah malam
            }

            // Simpan data ke database
            DB::table('jadwals')->insert([
                'id_kls'    => $data['id_kls'],
                'id_mk'     => $data['id_mk'],
                'ruang'     => $data['ruang'],
                'hari'      => $data['hari'],
                'start'     => $data['start'],
                'finish'    => $data['finish'],
                'jumlah_jam' => $jumlah_jam,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
