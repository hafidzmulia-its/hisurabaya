<?php

namespace Database\Seeders;

use App\Models\ObjekPoint;
use Illuminate\Database\Seeder;

class ObjekPointSeeder extends Seeder
{
    public function run(): void
    {
        $hotels = [
            [
                'NamaObjek' => 'Hotel Majapahit Surabaya',
                'Deskripsi' => 'Hotel mewah bersejarah dengan arsitektur kolonial Belanda yang elegan',
                'Alamat' => 'Jl. Tunjungan No.65, Genteng, Surabaya',
                'Latitude' => -7.2632,
                'Longitude' => 112.7379,
                'HargaMin' => 1200000,
                'HargaMax' => 3500000,
                'StarClass' => 5,
                'KecamatanID' => 28, // Genteng
                'OwnerUserID' => 2,
                'IsActive' => true,
            ],
            [
                'NamaObjek' => 'Shangri-La Hotel Surabaya',
                'Deskripsi' => 'Hotel berbintang 5 dengan pemandangan kota yang menakjubkan',
                'Alamat' => 'Jl. Mayjend Sungkono No.120, Gubeng, Surabaya',
                'Latitude' => -7.2886,
                'Longitude' => 112.7341,
                'HargaMin' => 1500000,
                'HargaMax' => 4000000,
                'StarClass' => 5,
                'KecamatanID' => 8, // Gubeng
                'OwnerUserID' => 2,
                'IsActive' => true,
            ],
            [
                'NamaObjek' => 'JW Marriott Hotel Surabaya',
                'Deskripsi' => 'Hotel modern dengan fasilitas lengkap di pusat kota',
                'Alamat' => 'Jl. Embong Malang No.85-89, Tegalsari, Surabaya',
                'Latitude' => -7.2649,
                'Longitude' => 112.7419,
                'HargaMin' => 1300000,
                'HargaMax' => 3800000,
                'StarClass' => 5,
                'KecamatanID' => 29, // Tegalsari
                'OwnerUserID' => 2,
                'IsActive' => true,
            ],
            [
                'NamaObjek' => 'Vasa Hotel Surabaya',
                'Deskripsi' => 'Hotel butik dengan desain modern dan nyaman',
                'Alamat' => 'Jl. Walikota Mustajab No.2, Sukolilo, Surabaya',
                'Latitude' => -7.2839,
                'Longitude' => 112.7649,
                'HargaMin' => 600000,
                'HargaMax' => 1200000,
                'StarClass' => 4,
                'KecamatanID' => 9, // Sukolilo
                'OwnerUserID' => 3,
                'IsActive' => true,
            ],
            [
                'NamaObjek' => 'Grand Mercure Surabaya City',
                'Deskripsi' => 'Hotel dengan lokasi strategis dan fasilitas modern',
                'Alamat' => 'Jl. Kombes Pol. M. Duryat, Bubutan, Surabaya',
                'Latitude' => -7.2522,
                'Longitude' => 112.7378,
                'HargaMin' => 800000,
                'HargaMax' => 1800000,
                'StarClass' => 4,
                'KecamatanID' => 30, // Bubutan
                'OwnerUserID' => 3,
                'IsActive' => true,
            ],
            [
                'NamaObjek' => 'Swiss-Belinn Tunjungan',
                'Deskripsi' => 'Hotel di pusat perbelanjaan dengan akses mudah',
                'Alamat' => 'Jl. Praban No.1, Genteng, Surabaya',
                'Latitude' => -7.2658,
                'Longitude' => 112.7398,
                'HargaMin' => 500000,
                'HargaMax' => 900000,
                'StarClass' => 3,
                'KecamatanID' => 28, // Genteng
                'OwnerUserID' => 3,
                'IsActive' => true,
            ],
            [
                'NamaObjek' => 'Hotel Ciputra World Surabaya',
                'Deskripsi' => 'Hotel mewah terintegrasi dengan mall dan convention center',
                'Alamat' => 'Jl. Mayjend Sungkono No.89, Tegalsari, Surabaya',
                'Latitude' => -7.2843,
                'Longitude' => 112.7285,
                'HargaMin' => 1400000,
                'HargaMax' => 4200000,
                'StarClass' => 5,
                'KecamatanID' => 29, // Tegalsari
                'OwnerUserID' => 2,
                'IsActive' => true,
            ],
            [
                'NamaObjek' => 'Favehotel PGS Surabaya',
                'Deskripsi' => 'Hotel budget dengan fasilitas lengkap dan nyaman',
                'Alamat' => 'Jl. Pemuda No.108, Genteng, Surabaya',
                'Latitude' => -7.2617,
                'Longitude' => 112.7451,
                'HargaMin' => 300000,
                'HargaMax' => 600000,
                'StarClass' => 3,
                'KecamatanID' => 28, // Genteng
                'OwnerUserID' => 3,
                'IsActive' => true,
            ],
            [
                'NamaObjek' => 'Pop! Hotel Gubeng Surabaya',
                'Deskripsi' => 'Hotel murah dan modern dengan lokasi strategis',
                'Alamat' => 'Jl. Gubeng Pojok No.1, Gubeng, Surabaya',
                'Latitude' => -7.2713,
                'Longitude' => 112.7509,
                'HargaMin' => 250000,
                'HargaMax' => 450000,
                'StarClass' => 2,
                'KecamatanID' => 8, // Gubeng
                'OwnerUserID' => 3,
                'IsActive' => true,
            ],
            [
                'NamaObjek' => 'The Westin Surabaya',
                'Deskripsi' => 'Hotel mewah dengan spa dan fasilitas premium',
                'Alamat' => 'Jl. Mayjen Sungkono, Tegalsari, Surabaya',
                'Latitude' => -7.2821,
                'Longitude' => 112.7291,
                'HargaMin' => 1600000,
                'HargaMax' => 4500000,
                'StarClass' => 5,
                'KecamatanID' => 29, // Tegalsari
                'OwnerUserID' => 2,
                'IsActive' => true,
            ],
        ];
        
        foreach ($hotels as $hotel) {
            ObjekPoint::create($hotel);
        }
    }
}
