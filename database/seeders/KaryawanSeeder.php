<?php
namespace Database\Seeders;
use App\Models\Karyawan;
use App\Models\Toko;
use Illuminate\Database\Seeder;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        $tokos = Toko::all();
        foreach ($tokos as $toko) {
            $count = rand(3, 5);
            for ($i = 1; $i <= $count; $i++) {
                $karyawanNumber = Karyawan::count() + 1;
                $status = ['Aktif', 'Aktif', 'Aktif', 'Aktif', 'Cuti', 'Resign'][rand(0, 5)];
                Karyawan::create([
                    'id_toko' => $toko->id_toko,
                    'kode_karyawan' => 'KRY' . str_pad($karyawanNumber, 3, '0', STR_PAD_LEFT),
                    'nik' => '3507' . str_pad(rand(1, 999999999999), 12, '0', STR_PAD_LEFT),
                    'nama_lengkap' => $this->getName($karyawanNumber),
                    'tempat_lahir' => $this->getCity($karyawanNumber),
                    'tanggal_lahir' => now()->subYears(rand(20, 50))->subDays(rand(1, 365)),
                    'jenis_kelamin' => ['L', 'P'][rand(0, 1)],
                    'alamat' => 'Jl. Karyawan No. ' . rand(1, 100),
                    'no_hp' => '0812' . rand(10000000, 99999999),
                    'email' => 'karyawan' . $karyawanNumber . '@tokotani.com',
                    'jabatan' => ['Kasir', 'Kasir', 'Supervisor', 'Manager', 'Admin', 'Staff Gudang', 'Sales'][rand(0, 6)],
                    'tanggal_masuk' => now()->subMonths(rand(1, 60)),
                    'tanggal_keluar' => $status == 'Resign' ? now()->subDays(rand(1, 30)) : null,
                    'status_karyawan' => $status,
                    'gaji_pokok' => rand(3000, 8000) * 1000,
                    'keterangan' => 'Karyawan ' . $this->getName($karyawanNumber),
                ]);
            }
        }
    }
    private function getName($number) {
        $names = ['Budi Santoso', 'Siti Aminah', 'Ahmad Fauzi', 'Dewi Lestari', 'Eko Prasetyo', 'Fitri Handayani', 'Gunawan', 'Hesti Wulandari', 'Irfan Hakim', 'Joko Widodo', 'Kartika Sari', 'Lukman Hakim'];
        return $names[($number - 1) % count($names)];
    }
    private function getCity($number) {
        $cities = ['Malang', 'Surabaya', 'Sidoarjo', 'Pasuruan', 'Blitar', 'Kediri'];
        return $cities[($number - 1) % count($cities)];
    }
}
