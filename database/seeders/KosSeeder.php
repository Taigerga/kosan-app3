<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class KosSeeder extends Seeder
{
    public function run()
    {
        // Pastikan ada pemilik
        $pemilik = DB::table('pemilik')->first();
        
        if (!$pemilik) {
            $pemilikId = DB::table('pemilik')->insertGetId([
                'nama' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'email' => 'budi@pemilikkos.com',
                'foto_profil' => null,
                'username' => 'budi_pemilik',
                'password' => Hash::make('password123'),
                'alamat' => 'Jl. Pemilik Kos No. 123, Bandung',
                'status_pemilik' => 'aktif',
                'role' => 'pemilik',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $pemilikId = $pemilik->id_pemilik;
        }

        // Pastikan ada penghuni
        $penghuni = DB::table('penghuni')->first();
        
        if (!$penghuni) {
            $penghuniId = DB::table('penghuni')->insertGetId([
                'nama' => 'Sari Indah',
                'nik' => '1234567890123456',
                'no_hp' => '081298765432',
                'email' => 'sari@penghuni.com',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1995-05-15',
                'alamat' => 'Jl. Penghuni No. 456, Bandung',
                'foto_profil' => null,
                'username' => 'sari_penghuni',
                'password' => Hash::make('password123'),
                'status_penghuni' => 'calon',
                'role' => 'penghuni',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $penghuniId = $penghuni->id_penghuni;
        }

        // Pastikan ada fasilitas
        $fasilitasCount = DB::table('fasilitas')->count();
        if ($fasilitasCount == 0) {
            DB::table('fasilitas')->insert([
                // Umum
                ['nama_fasilitas' => 'WiFi', 'kategori' => 'umum', 'icon' => 'wifi', 'created_at' => now()],
                ['nama_fasilitas' => 'Laundry', 'kategori' => 'umum', 'icon' => 'laundry', 'created_at' => now()],
                ['nama_fasilitas' => 'Dapur Bersama', 'kategori' => 'umum', 'icon' => 'kitchen', 'created_at' => now()],
                ['nama_fasilitas' => 'Ruang Tamu', 'kategori' => 'umum', 'icon' => 'living-room', 'created_at' => now()],
                ['nama_fasilitas' => 'Taman', 'kategori' => 'umum', 'icon' => 'garden', 'created_at' => now()],
                
                // Kamar Mandi
                ['nama_fasilitas' => 'Kamar Mandi Dalam', 'kategori' => 'kamar_mandi', 'icon' => 'bath', 'created_at' => now()],
                ['nama_fasilitas' => 'Air Panas', 'kategori' => 'kamar_mandi', 'icon' => 'hot-water', 'created_at' => now()],
                ['nama_fasilitas' => 'Shower', 'kategori' => 'kamar_mandi', 'icon' => 'shower', 'created_at' => now()],
                
                // Dapur
                ['nama_fasilitas' => 'Kompor', 'kategori' => 'dapur', 'icon' => 'stove', 'created_at' => now()],
                ['nama_fasilitas' => 'Kulkas', 'kategori' => 'dapur', 'icon' => 'refrigerator', 'created_at' => now()],
                ['nama_fasilitas' => 'Microwave', 'kategori' => 'dapur', 'icon' => 'microwave', 'created_at' => now()],
                
                // Parkir
                ['nama_fasilitas' => 'Parkir Motor', 'kategori' => 'parkir', 'icon' => 'motorcycle', 'created_at' => now()],
                ['nama_fasilitas' => 'Parkir Mobil', 'kategori' => 'parkir', 'icon' => 'car', 'created_at' => now()],
                ['nama_fasilitas' => 'Parkir Sepeda', 'kategori' => 'parkir', 'icon' => 'bicycle', 'created_at' => now()],
                
                // Keamanan
                ['nama_fasilitas' => 'CCTV', 'kategori' => 'keamanan', 'icon' => 'cctv', 'created_at' => now()],
                ['nama_fasilitas' => 'Security 24 Jam', 'kategori' => 'keamanan', 'icon' => 'security', 'created_at' => now()],
                ['nama_fasilitas' => 'Gerbang Otomatis', 'kategori' => 'keamanan', 'icon' => 'gate', 'created_at' => now()],
                
                // Lainnya
                ['nama_fasilitas' => 'AC', 'kategori' => 'lainnya', 'icon' => 'ac', 'created_at' => now()],
                ['nama_fasilitas' => 'Kipas Angin', 'kategori' => 'lainnya', 'icon' => 'fan', 'created_at' => now()],
                ['nama_fasilitas' => 'Lemari', 'kategori' => 'lainnya', 'icon' => 'wardrobe', 'created_at' => now()],
                ['nama_fasilitas' => 'Kasur', 'kategori' => 'lainnya', 'icon' => 'bed', 'created_at' => now()],
                ['nama_fasilitas' => 'Meja Belajar', 'kategori' => 'lainnya', 'icon' => 'desk', 'created_at' => now()],
            ]);
        }

        // Data kos contoh
        $kosData = [
            [
                'id_pemilik' => $pemilikId,
                'nama_kos' => 'Kosan Bahagia Sentosa',
                'alamat' => 'Jl. Merdeka No. 123, Sukajadi',
                'kecamatan' => 'Sukajadi',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40123',
                'latitude' => -6.917464,
                'longitude' => 107.619125,
                'deskripsi' => 'Kosan nyaman di pusat kota Bandung dengan fasilitas lengkap. Dekat dengan kampus, mall, dan pusat perbelanjaan. Lingkungan yang aman dan nyaman untuk mahasiswa dan pekerja.',
                'peraturan' => '1. Tidak boleh membawa hewan peliharaan
2. Tidak boleh merokok di dalam kamar
3. Wajib menjaga kebersihan lingkungan
4. Tamu hanya boleh menginap maksimal 2 malam
5. Tidak boleh berisik setelah jam 22:00',
                'jenis_kos' => 'putri',
                'tipe_sewa' => 'bulanan',
                'foto_utama' => null,
                'status_kos' => 'aktif',
                'created_at' => now()->subMonths(6),
                'updated_at' => now(),
            ],
            [
                'id_pemilik' => $pemilikId,
                'nama_kos' => 'Kosan Sejahtera Mandiri',
                'alamat' => 'Jl. Sudirman No. 456, Cibeunying',
                'kecamatan' => 'Cibeunying',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40124',
                'latitude' => -6.903273,
                'longitude' => 107.630428,
                'deskripsi' => 'Kosan strategis dekat kampus UNPAD dan pusat perbelanjaan. Fasilitas lengkap dengan keamanan 24 jam. Cocok untuk mahasiswa dan young professional.',
                'peraturan' => '1. Wajib lapor jika ada tamu menginap
2. Tidak boleh berisik setelah jam 10 malam
3. Dilarang membawa narkoba
4. Wajib membayar tepat waktu
5. Dilarang merusak fasilitas kos',
                'jenis_kos' => 'putra',
                'tipe_sewa' => 'bulanan',
                'foto_utama' => null,
                'status_kos' => 'aktif',
                'created_at' => now()->subMonths(4),
                'updated_at' => now(),
            ],
            [
                'id_pemilik' => $pemilikId,
                'nama_kos' => 'Kosan Damai Indah',
                'alamat' => 'Jl. Gatot Subroto No. 789, Coblong',
                'kecamatan' => 'Coblong',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40125',
                'latitude' => -6.891512,
                'longitude' => 107.608578,
                'deskripsi' => 'Kosan dengan lingkungan yang asri dan tenang. View pegunungan yang indah, cocok untuk yang menyukai ketenangan. Dekat dengan ITB dan pusat kota.',
                'peraturan' => '1. Bebas aturan selama tidak mengganggu penghuni lain
2. Wajib menjaga kebersihan kamar mandi bersama
3. Dilarang mencuri listrik
4. Wajip mematikan AC ketika keluar kamar
5. Tamu wanita dilarang masuk kamar putra (khusus campuran)',
                'jenis_kos' => 'campuran',
                'tipe_sewa' => 'bulanan',
                'foto_utama' => null,
                'status_kos' => 'aktif',
                'created_at' => now()->subMonths(2),
                'updated_at' => now(),
            ],
            [
                'id_pemilik' => $pemilikId,
                'nama_kos' => 'Kosan Mawar Putih',
                'alamat' => 'Jl. Cihampelas No. 321, Bandung Wetan',
                'kecamatan' => 'Bandung Wetan',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40126',
                'latitude' => -6.909762,
                'longitude' => 107.612654,
                'deskripsi' => 'Kosan eksklusif untuk wanita dengan keamanan ketat. Fasilitas premium dan lingkungan yang sangat nyaman. Dekat dengan kampus dan pusat fashion.',
                'peraturan' => '1. Khusus wanita
2. Tamu pria dilarang masuk area kamar
3. Wajib absen pulang jika malam
4. Dilarang membawa tamu menginap
5. Wajib menjaga kerapian kamar',
                'jenis_kos' => 'putri',
                'tipe_sewa' => 'bulanan',
                'foto_utama' => null,
                'status_kos' => 'aktif',
                'created_at' => now()->subMonths(3),
                'updated_at' => now(),
            ],
            [
                'id_pemilik' => $pemilikId,
                'nama_kos' => 'Kosan Jaya Makmur',
                'alamat' => 'Jl. Asia Afrika No. 654, Braga',
                'kecamatan' => 'Braga',
                'kota' => 'Bandung',
                'provinsi' => 'Jawa Barat',
                'kode_pos' => '40127',
                'latitude' => -6.921428,
                'longitude' => 107.608849,
                'deskripsi' => 'Kosan ekonomis di pusat kota dengan harga terjangkau. Cocok untuk mahasiswa dan pekerja dengan budget terbatas tapi tetap nyaman.',
                'peraturan' => '1. Bayar di awal bulan
2. Dilarang memasak di kamar
3. Wajib hemat listrik dan air
4. Tamu hanya sampai jam 21:00
5. Dilarang memodifikasi instalasi listrik',
                'jenis_kos' => 'putra',
                'tipe_sewa' => 'bulanan',
                'foto_utama' => null,
                'status_kos' => 'aktif',
                'created_at' => now()->subMonths(5),
                'updated_at' => now(),
            ]
        ];

        $allKosIds = [];

        foreach ($kosData as $kos) {
            $kosId = DB::table('kos')->insertGetId($kos);
            $allKosIds[] = $kosId;

            // Buat kamar untuk setiap kos
            $kamarData = [
                // Kamar Standar
                [
                    'id_kos' => $kosId,
                    'nomor_kamar' => 'A1',
                    'tipe_kamar' => 'Standar',
                    'harga' => 1500000,
                    'luas_kamar' => '3x4',
                    'kapasitas' => 1,
                    'fasilitas_kamar' => json_encode(['Kamar mandi dalam', 'AC', 'WiFi', 'Kasur', 'Lemari']),
                    'foto_kamar' => null,
                    'status_kamar' => 'tersedia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id_kos' => $kosId,
                    'nomor_kamar' => 'A2',
                    'tipe_kamar' => 'Standar',
                    'harga' => 1500000,
                    'luas_kamar' => '3x4',
                    'kapasitas' => 1,
                    'fasilitas_kamar' => json_encode(['Kamar mandi dalam', 'AC', 'WiFi', 'Kasur', 'Lemari']),
                    'foto_kamar' => null,
                    'status_kamar' => 'tersedia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id_kos' => $kosId,
                    'nomor_kamar' => 'A3',
                    'tipe_kamar' => 'Standar',
                    'harga' => 1500000,
                    'luas_kamar' => '3x4',
                    'kapasitas' => 1,
                    'fasilitas_kamar' => json_encode(['Kamar mandi dalam', 'AC', 'WiFi', 'Kasur', 'Lemari']),
                    'foto_kamar' => null,
                    'status_kamar' => 'tersedia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

                // Kamar Deluxe
                [
                    'id_kos' => $kosId,
                    'nomor_kamar' => 'B1',
                    'tipe_kamar' => 'Deluxe',
                    'harga' => 2500000,
                    'luas_kamar' => '4x4',
                    'kapasitas' => 2,
                    'fasilitas_kamar' => json_encode(['Kamar mandi dalam', 'AC', 'WiFi', 'Kasur besar', 'Lemari besar', 'Meja belajar', 'Kursi']),
                    'foto_kamar' => null,
                    'status_kamar' => 'tersedia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id_kos' => $kosId,
                    'nomor_kamar' => 'B2',
                    'tipe_kamar' => 'Deluxe',
                    'harga' => 2500000,
                    'luas_kamar' => '4x4',
                    'kapasitas' => 2,
                    'fasilitas_kamar' => json_encode(['Kamar mandi dalam', 'AC', 'WiFi', 'Kasur besar', 'Lemari besar', 'Meja belajar', 'Kursi']),
                    'foto_kamar' => null,
                    'status_kamar' => 'tersedia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

                // Kamar VIP
                [
                    'id_kos' => $kosId,
                    'nomor_kamar' => 'C1',
                    'tipe_kamar' => 'VIP',
                    'harga' => 3500000,
                    'luas_kamar' => '4x5',
                    'kapasitas' => 2,
                    'fasilitas_kamar' => json_encode(['Kamar mandi dalam', 'AC', 'WiFi', 'Kasur premium', 'Lemari besar', 'Meja belajar', 'Kursi ergonomis', 'TV', 'Kulkas mini']),
                    'foto_kamar' => null,
                    'status_kamar' => 'tersedia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],

                // Kamar Ekonomi
                [
                    'id_kos' => $kosId,
                    'nomor_kamar' => 'D1',
                    'tipe_kamar' => 'Ekonomi',
                    'harga' => 800000,
                    'luas_kamar' => '3x3',
                    'kapasitas' => 1,
                    'fasilitas_kamar' => json_encode(['Kamar mandi luar', 'Kipas angin', 'WiFi', 'Kasur', 'Lemari']),
                    'foto_kamar' => null,
                    'status_kamar' => 'tersedia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id_kos' => $kosId,
                    'nomor_kamar' => 'D2',
                    'tipe_kamar' => 'Ekonomi',
                    'harga' => 800000,
                    'luas_kamar' => '3x3',
                    'kapasitas' => 1,
                    'fasilitas_kamar' => json_encode(['Kamar mandi luar', 'Kipas angin', 'WiFi', 'Kasur', 'Lemari']),
                    'foto_kamar' => null,
                    'status_kamar' => 'tersedia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id_kos' => $kosId,
                    'nomor_kamar' => 'D3',
                    'tipe_kamar' => 'Ekonomi',
                    'harga' => 800000,
                    'luas_kamar' => '3x3',
                    'kapasitas' => 1,
                    'fasilitas_kamar' => json_encode(['Kamar mandi luar', 'Kipas angin', 'WiFi', 'Kasur', 'Lemari']),
                    'foto_kamar' => null,
                    'status_kamar' => 'tersedia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ];

            DB::table('kamar')->insert($kamarData);

            // Attach fasilitas ke kos (ambil 5-8 fasilitas random)
            $fasilitasIds = DB::table('fasilitas')->inRandomOrder()->limit(rand(5, 8))->pluck('id_fasilitas');
            foreach ($fasilitasIds as $fasilitasId) {
                DB::table('kos_fasilitas')->insert([
                    'id_kos' => $kosId,
                    'id_fasilitas' => $fasilitasId,
                    'created_at' => now(),
                ]);
            }
        }

        // Buat kontrak sewa aktif untuk penghuni pertama
        $firstKosId = $allKosIds[0]; // Kos Bahagia Sentosa
        $kamarAktif = DB::table('kamar')->where('id_kos', $firstKosId)->where('nomor_kamar', 'A1')->first();

        if ($kamarAktif) {
            $kontrakId = DB::table('kontrak_sewa')->insertGetId([
                'id_penghuni' => $penghuniId,
                'id_kos' => $firstKosId,
                'id_kamar' => $kamarAktif->id_kamar,
                'foto_ktp' => 'ktp/ktp_sari.jpg',
                // ...hapus bukti_pembayaran
                'tanggal_daftar' => now()->subMonth(),
                'tanggal_mulai' => now()->subMonth(),
                'tanggal_selesai' => now()->addMonths(11),
                'durasi_sewa' => 12,
                'harga_sewa' => $kamarAktif->harga,
                'status_kontrak' => 'aktif',
                'alasan_ditolak' => null,
                'created_at' => now()->subMonth(),
                'updated_at' => now(),
            ]);

            // Update status penghuni menjadi aktif
            DB::table('penghuni')->where('id_penghuni', $penghuniId)
                ->update(['status_penghuni' => 'aktif']);

            // Update status kamar menjadi terisi
            DB::table('kamar')->where('id_kamar', $kamarAktif->id_kamar)
                ->update(['status_kamar' => 'terisi']);

            // Buat pembayaran contoh
            DB::table('pembayaran')->insert([
                // Pembayaran bulan lalu (lunas)
                [
                    'id_kontrak' => $kontrakId,
                    'id_penghuni' => $penghuniId,
                    'bulan_tahun' => now()->subMonth()->format('Y-m'),
                    'tanggal_jatuh_tempo' => now()->subMonth()->startOfMonth(),
                    'tanggal_bayar' => now()->subMonth()->addDays(2),
                    'jumlah' => $kamarAktif->harga,
                    // ...hapus bukti_pembayaran
                    'metode_pembayaran' => 'transfer',
                    'status_pembayaran' => 'lunas',
                    'keterangan' => 'Pembayaran pertama',
                    'created_at' => now()->subMonth()->addDays(2),
                    'updated_at' => now()->subMonth()->addDays(2),
                ],
                // Pembayaran bulan ini (lunas)
                [
                    'id_kontrak' => $kontrakId,
                    'id_penghuni' => $penghuniId,
                    'bulan_tahun' => now()->format('Y-m'),
                    'tanggal_jatuh_tempo' => now()->startOfMonth(),
                    'tanggal_bayar' => now()->subDays(5),
                    'jumlah' => $kamarAktif->harga,
                    // ...hapus bukti_pembayaran
                    'metode_pembayaran' => 'transfer',
                    'status_pembayaran' => 'lunas',
                    'keterangan' => 'Pembayaran rutin',
                    'created_at' => now()->subDays(5),
                    'updated_at' => now()->subDays(5),
                ],
                // Pembayaran bulan depan (belum)
                [
                    'id_kontrak' => $kontrakId,
                    'id_penghuni' => $penghuniId,
                    'bulan_tahun' => now()->addMonth()->format('Y-m'),
                    'tanggal_jatuh_tempo' => now()->addMonth()->startOfMonth(),
                    'tanggal_bayar' => null,
                    'jumlah' => $kamarAktif->harga,
                    // ...hapus bukti_pembayaran
                    'metode_pembayaran' => 'transfer',
                    'status_pembayaran' => 'belum',
                    'keterangan' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }

        // Buat beberapa kontrak pending untuk testing
        $pendingKamar = DB::table('kamar')->where('id_kos', $allKosIds[1])->where('nomor_kamar', 'B1')->first();
        if ($pendingKamar) {
            DB::table('kontrak_sewa')->insert([
                [
                    'id_penghuni' => $penghuniId,
                    'id_kos' => $allKosIds[1],
                    'id_kamar' => $pendingKamar->id_kamar,
                    'foto_ktp' => 'ktp/ktp_pending.jpg',
                    // ...hapus bukti_pembayaran
                    'tanggal_daftar' => now()->subDays(3),
                    'tanggal_mulai' => null,
                    'tanggal_selesai' => null,
                    'durasi_sewa' => 6,
                    'harga_sewa' => $pendingKamar->harga,
                    'status_kontrak' => 'pending',
                    'alasan_ditolak' => null,
                    'created_at' => now()->subDays(3),
                    'updated_at' => now()->subDays(3),
                ]
            ]);
        }

        // Buat beberapa reviews
        DB::table('reviews')->insert([
            [
                'id_kos' => $firstKosId,
                'id_penghuni' => $penghuniId,
                'id_kontrak' => $kontrakId,
                'rating' => 4.5,
                'komentar' => 'Kosan sangat nyaman dan bersih. Lokasi strategis dekat kampus. Pemiliknya ramah dan responsif.',
                'foto_review' => null,
                'status_review' => 'disetujui',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(15),
            ],
            [
                'id_kos' => $allKosIds[1],
                'id_penghuni' => $penghuniId,
                'id_kontrak' => $kontrakId,
                'rating' => 4.0,
                'komentar' => 'Fasilitas lengkap dan harga terjangkau. Recommended untuk mahasiswa.',
                'foto_review' => null,
                'status_review' => 'disetujui',
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ]
        ]);

        // Buat pengaturan kos
        foreach ($allKosIds as $kosId) {
            DB::table('pengaturan_kos')->insert([
                'id_kos' => $kosId,
                'notifikasi_pembayaran_h_min' => 5,
                'denda_keterlambatan' => 50000.00,
                'toleransi_keterlambatan' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Buat beberapa notifikasi
        DB::table('notifications')->insert([
            [
                'id_user' => $penghuniId,
                'user_type' => 'penghuni',
                'judul' => 'Pembayaran Berhasil',
                'pesan' => 'Pembayaran sewa bulan ' . now()->format('F Y') . ' telah berhasil diproses.',
                'tipe' => 'success',
                'dibaca' => 'tidak',
                'link' => '/penghuni/pembayaran',
                'created_at' => now()->subDays(2),
            ],
            [
                'id_user' => $pemilikId,
                'user_type' => 'pemilik',
                'judul' => 'Permohonan Baru',
                'pesan' => 'Ada permohonan sewa baru menunggu persetujuan Anda.',
                'tipe' => 'info',
                'dibaca' => 'tidak',
                'link' => '/pemilik/kontrak',
                'created_at' => now()->subDays(1),
            ]
        ]);

        $this->command->info('KosSeeder berhasil dijalankan!');
        $this->command->info('Data yang dibuat:');
        $this->command->info('- 5 kos dengan berbagai tipe');
        $this->command->info('- 40+ kamar dengan berbagai harga');
        $this->command->info('- 1 kontrak aktif');
        $this->command->info('- 1 kontrak pending'); 
        $this->command->info('- 3 riwayat pembayaran');
        $this->command->info('- 2 review');
        $this->command->info('- 5 pengaturan kos');
        $this->command->info('- 2 notifikasi');
    }
}