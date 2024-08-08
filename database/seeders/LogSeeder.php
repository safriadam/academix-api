<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logData = [
            [
                'id_jdwl'       => 1,
                'id_dosen'      => 28,
                'tahun_awal'    => '2023-08-07',
                'tahun_akhir'   => '2024-08-07',
            ],
            [
                'id_jdwl'       => 2,
                'id_dosen'      => 28,
                'tahun_awal'    => '2023-08-07',
                'tahun_akhir'   => '2024-08-07',
            ],
            [
                'id_jdwl'       => 3,
                'id_dosen'      => 2,
                'tahun_awal'    => '2023-08-07',
                'tahun_akhir'   => '2024-08-07',
            ],
            [
                'id_jdwl'       => 4,
                'id_dosen'      => 2,
                'tahun_awal'    => '2023-08-07',
                'tahun_akhir'   => '2024-08-07',
            ],
            [
                'id_jdwl'       => 5,
                'id_dosen'      => 29,
                'tahun_awal'    => '2023-08-07',
                'tahun_akhir'   => '2024-08-07',
            ],
            [
                'id_jdwl'       => 6,
                'id_dosen'      => 29,
                'tahun_awal'    => '2023-08-07',
                'tahun_akhir'   => '2024-08-07',
            ],
            [
                'id_jdwl'       => 7,
                'id_dosen'      => 8,
                'tahun_awal'    => '2023-08-07',
                'tahun_akhir'   => '2024-08-07',
            ],
            [
                'id_jdwl'       => 8,
                'id_dosen'      => 21,
                'tahun_awal'    => '2023-08-07',
                'tahun_akhir'   => '2024-08-07',
            ],
            [
                'id_jdwl'       => 9,
                'id_dosen'      => 21,
                'tahun_awal'    => '2023-08-07',
                'tahun_akhir'   => '2024-08-07',
            ],

        ];

        foreach ($logData as $data) {
            DB::table('logs')->insert([
                'id_jdwl'       => $data['id_jdwl'],
                'id_dosen'      => $data['id_dosen'],
                'tahun_awal'    => $data['tahun_awal'],
                'tahun_akhir'   => $data['tahun_akhir'],
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);
        }
    }
}
