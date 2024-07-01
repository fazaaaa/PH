<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Hasil;
use App\Models\JenisBantuan;
use App\Models\Klasifikasi;
use App\Models\Pekerjaan;
use App\Models\Penduduk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('jenis_bantuans')->truncate();
        DB::table('penduduks')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'name' => 'Admin ',
            'email' => 'admin@gmail.com',
            'role' => 'kph',
            'password' => bcrypt('admin123')
        ]);

        User::create([
            'name' => 'rw1 ',
            'email' => 'rw1@gmail.com',
            'role' => 'rw',
            'password' => bcrypt('password123')
        ]);

        // Buat rw2 sampai rw10
        for ($i = 2; $i <= 10; $i++) {
            User::create([
                'name' => 'rw' . $i,
                'email' => 'rw' . $i . '@gmail.com',
                'role' => 'rw',
                'password' => bcrypt('password123')
            ]);
        }
        
        JenisBantuan::create([
            'nama_bantuan' => 'PKH', 
            'created_at' => now(), 
            'updated_at' => now()]);
        JenisBantuan::create([
            'nama_bantuan' => 'BPNT', 
            'created_at' => now(), 
            'updated_at' => now()]);

        $pendudukData = [
            [
                'No_KK' => 213123,
                'NIK' => 324323,
                'pas_foto' => '',
                'Nama_lengkap' => 'Rohidin Saepulloh',
                'Hbg_kel' => 'Suami',
                'JK' => 'laki-laki',
                'tmpt_lahir' => 'Bandung',
                'tgl_lahir' => '2024-05-08',
                'Agama' => 'Islam',
                'Pendidikan_terakhir' => 'S1',
                'jenis_bantuan_id' => '1',
                'Penerima_bantuan' => 'Ya'
            ],
            [
                'No_KK' => 213124,
                'NIK' => 324324,
                'pas_foto' => '',
                'Nama_lengkap' => 'Siti Nurjanah',
                'Hbg_kel' => 'Istri',
                'JK' => 'perempuan',
                'tmpt_lahir' => 'Jakarta',
                'tgl_lahir' => '2023-04-10',
                'Agama' => 'Kristen',
                'Pendidikan_terakhir' => 'SMA',
                'jenis_bantuan_id' => '1',
                'Penerima_bantuan' => 'Tidak'
            ],
            [
                'No_KK' => 213125,
                'NIK' => 324325,
                'pas_foto' => '',
                'Nama_lengkap' => 'Supriyanto',
                'Hbg_kel' => 'Anak',
                'JK' => 'laki-laki',
                'tmpt_lahir' => 'Surabaya',
                'tgl_lahir' => '2021-03-15',
                'Agama' => 'Hindu',
                'Pendidikan_terakhir' => 'SD',
                'jenis_bantuan_id' => '1',
                'Penerima_bantuan' => 'Ya'
            ],
            [
                'No_KK' => 213126,
                'NIK' => 324326,
                'pas_foto' => '',
                'Nama_lengkap' => 'Putra Jaya',
                'Hbg_kel' => 'Ayah',
                'JK' => 'laki-laki',
                'tmpt_lahir' => 'Medan',
                'tgl_lahir' => '1975-02-20',
                'Agama' => 'Buddha',
                'Pendidikan_terakhir' => 'D3',
                'jenis_bantuan_id' => '1',
                'Penerima_bantuan' => 'Ya'
            ],
            [
                'No_KK' => 213127,
                'NIK' => 324327,
                'pas_foto' => '',
                'Nama_lengkap' => 'putri jaya',
                'Hbg_kel' => 'Ibu',
                'JK' => 'perempuan',
                'tmpt_lahir' => 'Yogyakarta',
                'tgl_lahir' => '1980-07-25',
                'Agama' => 'Islam',
                'Pendidikan_terakhir' => 'S2',
                'jenis_bantuan_id' => '1',
                'Penerima_bantuan' => 'Tidak'
            ]
        ];

        foreach ($pendudukData as $penduduk) {
            Penduduk::create($penduduk);
        }  

        Pekerjaan::create([
            'id_penduduk' => 1,
            'pekerjaan' => 'Wiraswasta',
            'pendapatan' => 2000000,
            'jumlah' => 7,
            'status' => 'Tidak Bekerja'
        ]);

        Pekerjaan::create([
            'id_penduduk' => 2,
            'pekerjaan' => 'Karyawan',
            'pendapatan' => 4000000,
            'jumlah' => 1,
            'status' => 'Bekerja'
        ]);

        Pekerjaan::create([
            'id_penduduk' => 3,
            'pekerjaan' => 'Wirausaha',
            'pendapatan' => 10000000,
            'jumlah' => 3,
            'status' => 'Bekerja'
        ]);

        Pekerjaan::create([
            'id_penduduk' => 4,
            'pekerjaan' => 'Guide',
            'pendapatan' => 2000000,
            'jumlah' => 7,
            'status' => 'Bekerja'
        ]);

        Pekerjaan::create([
            'id_penduduk' => 5,
            'pekerjaan' => 'Satpam',
            'pendapatan' => 2000000,
            'jumlah' => 1,
            'status' => 'Tidak Bekerja'
        ]);

        Hasil::create([
            'id_penduduk' => 1,
            'pendapatan' => 'Rendah',
            'jumlah' => 'Banyak',
            'status' => 'Tidak Bekerja',
            'keterangan' => 'Layak'
        ]);

        Hasil::create([
            'id_penduduk' => 2,
            'pendapatan' => 'Menengah',
            'jumlah' => 'Sedikit',
            'status' => 'Bekerja',
            'keterangan' => 'Tidak Layak'
        ]);

        Hasil::create([
            'id_penduduk' => 3,
            'pendapatan' => 'Tinggi',
            'jumlah' => 'Sedang',
            'status' => 'Bekerja',
            'keterangan' => 'Tidak Layak'
        ]);

        Hasil::create([
            'id_penduduk' => 4,
            'pendapatan' => 'Rendah',
            'jumlah' => 'Banyak',
            'status' => 'Bekerja',
            'keterangan' => 'Layak'
        ]);

        Hasil::create([
            'id_penduduk' => 5,
            'pendapatan' => 'Rendah',
            'jumlah' => 'Sedikit',
            'status' => 'Tidak Bekerja',
            'keterangan' => 'Layak'
        ]);

        Klasifikasi::create([
            'id_penduduk' => 1,
            'pendapatan' => 'Rendah',
            'jumlah' => 'Banyak',
            'status' => 'Tidak Bekerja',
            'keterangan' => 'Layak'
        ]);

        Klasifikasi::create([
            'id_penduduk' => 2,
            'pendapatan' => 'Menengah',
            'jumlah' => 'Sedikit',
            'status' => 'Bekerja',
            'keterangan' => 'Tidak Layak'
        ]);

        Klasifikasi::create([
            'id_penduduk' => 3,
            'pendapatan' => 'Tinggi',
            'jumlah' => 'Sedang',
            'status' => 'Bekerja',
            'keterangan' => 'Tidak Layak'
        ]);

        Klasifikasi::create([
            'id_penduduk' => 4,
            'pendapatan' => 'Rendah',
            'jumlah' => 'Banyak',
            'status' => 'Bekerja',
            'keterangan' => 'Layak'
        ]);

        Klasifikasi::create([
            'id_penduduk' => 5,
            'pendapatan' => 'Rendah',
            'jumlah' => 'Sedikit',
            'status' => 'Tidak Bekerja',
            'keterangan' => 'Layak'
        ]);

    }
    
}
