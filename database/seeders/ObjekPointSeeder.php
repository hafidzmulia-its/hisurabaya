<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\ObjekPoint;
use App\Models\User;
use Illuminate\Database\Seeder;

class ObjekPointSeeder extends Seeder
{
    public function run(): void
    {
        $points = [
            [
                'NamaObjek' => 'SURABAYA RIVER VIEW (SRV HOTEL)',
                'Deskripsi' => 'Hotel bintang tiga di wilayah Surabaya Pusat beralamat di Genteng Kali 73-75. Akses dekat area pusat kota. Kisaran harga per malam: 328k-428k (dapat berubah tergantung tanggal dan promo)',
                'Alamat' => 'GENTENG KALI 73-75',
                'Latitude' => -7.256111,
                'Longitude' => 112.74,
                'HargaMin' => 328000,
                'HargaMax' => 428000,
                'StarClass' => 3,
                'NamaKecamatan' => 'Genteng',
                'OwnerUsername' => 'owner_hotela',
                'IsActive' => 1,
            ],
            [
                'NamaObjek' => 'HOTEL BUMI SURABAYA',
                'Deskripsi' => 'Hotel bintang lima di Surabaya Pusat dengan alamat Jl. Basuki Rahmat 106-128. Berada di koridor utama pusat bisnis/kota. Kisaran harga per malam: 726k-4000k (tergantung tipe kamar, tanggal, dan promo).',
                'Alamat' => 'JL. BASUKI RAHMAT 106 - 128',
                'Latitude' => -7.270919042,
                'Longitude' => 112.7412225,
                'HargaMin' => 726000,
                'HargaMax' => 4000000,
                'StarClass' => 5,
                'NamaKecamatan' => 'Genteng',
                'OwnerUsername' => 'owner_hotela',
                'IsActive' => 1,
            ],
            [
                'NamaObjek' => 'FAVE HOTEL RUNGKUT',
                'Deskripsi' => 'Hotel bintang dua di Surabaya Timur, beralamat di Raya Kali Rungkut 23-25. Cocok sebagai opsi menginap area Rungkut dan sekitarnya. Kisaran harga per malam: 427k-493k (bisa berubah mengikuti tanggal/promo).',
                'Alamat' => 'RAYA KALI RUNGKUT 23-25',
                'Latitude' => -7.32052,
                'Longitude' => 112.77233,
                'HargaMin' => 427000,
                'HargaMax' => 493000,
                'StarClass' => 2,
                'NamaKecamatan' => 'Rungkut',
                'OwnerUsername' => 'owner_hotelb',
                'IsActive' => 1,
            ],
            [
                'NamaObjek' => 'NOVOTEL SAMATOR SURABAYA TIMUR',
                'Deskripsi' => 'Hotel bintang empat di Surabaya Timur dengan alamat Kedung Baruk No.26-28. Lokasi berada di area timur kota. Kisaran harga per malam: 581k-934k (tergantung tanggal, kebijakan sarapan, dan promo).',
                'Alamat' => 'KEDUNG BARUK NO.26-28',
                'Latitude' => -7.310460069,
                'Longitude' => 112.7731501,
                'HargaMin' => 581000,
                'HargaMax' => 934000,
                'StarClass' => 4,
                'NamaKecamatan' => 'Rungkut',
                'OwnerUsername' => 'owner_hotelb',
                'IsActive' => 1,
            ],
            [
                'NamaObjek' => 'FOUR POINT BY SHERATON SURABAYA',
                'Deskripsi' => 'Hotel bintang empat di Surabaya Barat beralamat di Puncak Indah Lontar 2. Berada di kawasan barat Surabaya. Kisaran harga per malam: 1260k-1549k (bisa berubah sesuai tanggal, tipe kamar, dan promo).',
                'Alamat' => 'PUNCAK INDAH LONTAR 2',
                'Latitude' => -7.283889,
                'Longitude' => 112.670556,
                'HargaMin' => 1260000,
                'HargaMax' => 1549000,
                'StarClass' => 4,
                'NamaKecamatan' => 'Wiyung',
                'OwnerUsername' => 'owner_hotela',
                'IsActive' => 1,
            ],
            [
                'NamaObjek' => 'HOTEL SHANGRILA',
                'Deskripsi' => 'Hotel bintang lima di Surabaya Barat dengan alamat Jl. Mayjen Sungkono 120. Berlokasi di jalur utama Mayjen Sungkono. Kisaran harga per malam: 1153k-1573k (dapat berubah tergantung tanggal dan ketersediaan).',
                'Alamat' => 'JL. MAYJEN SUNGKONO 120',
                'Latitude' => -7.28982,
                'Longitude' => 112.71632,
                'HargaMin' => 1299000,
                'HargaMax' => 8503000,
                'StarClass' => 5,
                'NamaKecamatan' => 'Sawahan',
                'OwnerUsername' => 'owner_hotelb',
                'IsActive' => 1,
            ],
            [
                'NamaObjek' => 'THE CAPITAL HOTEL SURABAYA',
                'Deskripsi' => 'Hotel bintang empat di Surabaya Selatan, beralamat di Raya Jemursari No.258. Akses mudah ke area Jemursari dan sekitarnya. Kisaran harga per malam: 334k-406k (tergantung tanggal, tipe kamar, dan promo).',
                'Alamat' => 'RAYA JEMURSARI NO.258',
                'Latitude' => -7.313073503,
                'Longitude' => 112.7564959,
                'HargaMin' => 334000,
                'HargaMax' => 406000,
                'StarClass' => 4,
                'NamaKecamatan' => 'Tenggilis Mejoyo',
                'OwnerUsername' => 'owner_hotela',
                'IsActive' => 1,
            ],
            [
                'NamaObjek' => 'THE ALANA SURABAYA',
                'Deskripsi' => 'Hotel bintang empat di Surabaya Selatan dengan alamat Jl. Ketintang Baru I / 10-12. Lokasi berada di area Ketintang. Kisaran harga per malam: 613k-750k (bisa berubah mengikuti tanggal, paket, dan promo).',
                'Alamat' => 'JL. KETINTANG BARU I / 10 - 12',
                'Latitude' => -7.318786,
                'Longitude' => 112.731555,
                'HargaMin' => 613000,
                'HargaMax' => 750000,
                'StarClass' => 4,
                'NamaKecamatan' => 'Gayungan',
                'OwnerUsername' => 'owner_hotela',
                'IsActive' => 1,
            ],
            [
                'NamaObjek' => 'HOTEL BERLIAN INTERNATIONAL',
                'Deskripsi' => 'Hotel bintang dua di Surabaya Utara beralamat di Perak Barat 63. Berada di area utara kota. Kisaran harga per malam: 260k-594k (tergantung tanggal, dan promo).',
                'Alamat' => 'PERAK BARAT 63',
                'Latitude' => -7.228760778,
                'Longitude' => 112.7296494,
                'HargaMin' => 260000,
                'HargaMax' => 594000,
                'StarClass' => 2,
                'NamaKecamatan' => 'Krembangan',
                'OwnerUsername' => 'owner_hotelb',
                'IsActive' => 1,
            ],
            [
                'NamaObjek' => 'HOTEL NEW GRAND PARK',
                'Deskripsi' => 'Hotel bintang tiga di Surabaya Utara, beralamat di Jl. Samodra 3-5. Lokasi berada di kawasan utara Surabaya. Kisaran harga per malam: 208k-525k (dapat berubah tergantung tanggal dan promo).',
                'Alamat' => 'JL. SAMODRA 3 - 5',
                'Latitude' => -7.2175624,
                'Longitude' => 112.7216063,
                'HargaMin' => 208000,
                'HargaMax' => 525000,
                'StarClass' => 3,
                'NamaKecamatan' => 'Pabean Cantikan',
                'OwnerUsername' => 'owner_hotela',
                'IsActive' => 1,
            ],

            // Route start point (non-hotel)
            [
                'NamaObjek' => 'Institut Teknologi Sepuluh Nopember',
                'Deskripsi' => 'Kampus perjuangan ITS, sebagai titik awal rute perjalanan.',
                'Alamat' => 'Kampus ITS, Sukolilo, Jl. Raya ITS, Keputih, Surabaya, Jawa Timur 60117',
                'Latitude' => -7.28127035,
                'Longitude' => 112.7943131,
                'HargaMin' => 0,
                'HargaMax' => 0,
                'StarClass' => 3,
                'NamaKecamatan' => 'Keputih',
                'OwnerUsername' => null,
                'IsActive' => 1,
            ],
        ];

        foreach ($points as $p) {
            $kecamatan = Kecamatan::where('NamaKecamatan', $p['NamaKecamatan'])->first();
            if (!$kecamatan) {
                throw new \RuntimeException("Kecamatan not found: {$p['NamaKecamatan']}");
            }

            $ownerId = null;
            if (!empty($p['OwnerUsername'])) {
                $owner = User::where('username', $p['OwnerUsername'])->first();
                if (!$owner) {
                    throw new \RuntimeException("Owner user not found: {$p['OwnerUsername']}");
                }
                $ownerId = $owner->getKey();
            }

            ObjekPoint::updateOrCreate(
                ['NamaObjek' => $p['NamaObjek']],
                [
                    'Deskripsi' => $p['Deskripsi'],
                    'Alamat' => $p['Alamat'],
                    'Latitude' => $p['Latitude'],
                    'Longitude' => $p['Longitude'],
                    'HargaMin' => $p['HargaMin'],
                    'HargaMax' => $p['HargaMax'],
                    'StarClass' => $p['StarClass'],
                    'KecamatanID' => $kecamatan->getKey(),
                    'OwnerUserID' => $ownerId,
                    'IsActive' => (bool) $p['IsActive'],
                ]
            );
        }
    }
}
