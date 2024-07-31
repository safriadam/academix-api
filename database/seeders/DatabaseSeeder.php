<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kaldik;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Kaldik::create([
            'tahun' => '2023',
            'semester' => 'ganjil',
            'kegiatan' => 'MASA PERKULIAHAN SEMESTER GANJIL TAHUN AKADEMIK 2023/2024',
            'waktu_mulai' => '2023-09-25',
            'waktu_selesai' => '2024-02-10',
            'lampiran' => '-',
            'status' => 'kuliah',
            'keterangan' => '-',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ],
        Kaldik::create(
            [
                'tahun' => '2024',
                'semester' => 'genap',
                'kegiatan' => 'MASA PERKULIAHAN SEMESTER GENAP TAHUN AKADEMIK 2023/2024',
                'waktu_mulai' => '2024-05-04',
                'waktu_selesai' => '2024-08-19',
                'lampiran' => '-',
                'status' => 'kuliah',
                'keterangan' => '-',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        )
    );
    }
}
