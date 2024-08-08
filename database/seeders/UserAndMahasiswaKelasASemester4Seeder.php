<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAndMahasiswaKelasASemester4Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswaData = [
            ['nim' => '3202216003', 'nama' => 'IBNU ILHAM','dosen_pa' => 1],
            ['nim' => '3202216005', 'nama' => 'ADIL FATHI ABDILLAH','dosen_pa' => 1],
            ['nim' => '3202216006', 'nama' => 'ATHA NALA ISRA RADITYA','dosen_pa' => 1],
            ['nim' => '3202216007', 'nama' => 'DIANA DWI APITA','dosen_pa' => 1],
            ['nim' => '3202216008', 'nama' => 'DESI VIVIANI','dosen_pa' => 1],
            ['nim' => '3202216009', 'nama' => 'TIFANIE RIZKI FADILLA','dosen_pa' => 1],
            ['nim' => '3202216011', 'nama' => 'FERRY PERDIAN','dosen_pa' => 1],
            ['nim' => '3202216013', 'nama' => 'ANDINI RAMADHANI','dosen_pa' => 1],
            ['nim' => '3202216014', 'nama' => 'ERICK ERDIANSYAH','dosen_pa' => 1],
            ['nim' => '3202216016', 'nama' => 'MUHAMMAD FITRIADI','dosen_pa' => 1],
            ['nim' => '3202216018', 'nama' => 'YOSHUA ANDREW SINAGA','dosen_pa' => 1],
            ['nim' => '3202216019', 'nama' => 'AULIA ADENIA PUTRI','dosen_pa' => 1],
            ['nim' => '3202216020', 'nama' => 'SITI FAZA ANASYA','dosen_pa' => 1],
            ['nim' => '3202216021', 'nama' => 'WYMPI SARISTO','dosen_pa' => 1],
            ['nim' => '3202216023', 'nama' => 'FATA HUMAM ASADILLAH','dosen_pa' => 1],
            ['nim' => '3202216030', 'nama' => 'GABRIEL SARWANOVAN TARIGAS','dosen_pa' => 1],
            ['nim' => '3202216034', 'nama' => 'FAIZ HIKMALIZAR','dosen_pa' => 1],
            ['nim' => '3202216035', 'nama' => 'KANISIUS YOSSA PRATAMA JUNIOR','dosen_pa' => 1],
            ['nim' => '3202216046', 'nama' => 'M.SYAHEKY PRIANDANA KUSUMA','dosen_pa' => 1],
            ['nim' => '3202216061', 'nama' => 'SEPTIAN ADRIANTO','dosen_pa' => 1],
            ['nim' => '3202216067', 'nama' => 'NOVAL ACHMAD RAISHA','dosen_pa' => 1],
            ['nim' => '3202216070', 'nama' => 'EJA SAPARIO','dosen_pa' => 1],
            ['nim' => '3202216090', 'nama' => 'RAYCAL PRANATA','dosen_pa' => 1],
            ['nim' => '3202216094', 'nama' => 'SULTAN ARYA ADHYAKSA','dosen_pa' => 1],
            ['nim' => '3202216096', 'nama' => 'FEBRI PRADANA','dosen_pa' => 1],
            ['nim' => '3202216110', 'nama' => 'NAIA FATIHA','dosen_pa' => 1],
            ['nim' => '3202216112', 'nama' => 'DONI FITRIANSYAH','dosen_pa' => 1],
            ['nim' => '3202216122', 'nama' => 'FACHRI HIBATUL HAQ','dosen_pa' => 1],
            ['nim' => '3202116086', 'nama' => 'FAADHILLAH DESTIA FITRI','dosen_pa' => 1],
        ];

        foreach ($mahasiswaData as $mahasiswa) {
            // Insert user data
            $userId = DB::table('users')->insertGetId([
                'role' => '3',
                'name' => $mahasiswa['nama'],
                'nomor_induk' => $mahasiswa['nim'],
                'email' => strtolower(str_replace(' ', '.', $mahasiswa['nama'])) . '@example.com',
                'password' => Hash::make('password123'),
            ]);

            // Insert mahasiswa data
            DB::table('mahasiswas')->insert([
                'user_id' => $userId,
                'id_kls' => 4,
                'nim' => $mahasiswa['nim'],
                'nama' => $mahasiswa['nama'],
                'nama_ortu' => 'Bapak',
                'no_hp_ortu' => '08123456789',
                'no_hp' => '08123456789',
                'foto' => 'default.png',
                'dosen_pa' => $mahasiswa['dosen_pa'],
            ]);
        }
    }
}
