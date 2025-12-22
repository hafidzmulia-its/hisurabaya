<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatanData = [
            // Surabaya Barat - with sample polygon
            ['NamaKecamatan' => 'Asemrowo', 'Wilayah' => 'Surabaya Barat', 'PolygonJSON' => json_encode(['type' => 'Polygon', 'coordinates' => [[[112.69, -7.23], [112.70, -7.23], [112.70, -7.24], [112.69, -7.24], [112.69, -7.23]]]])],
            ['NamaKecamatan' => 'Benowo', 'Wilayah' => 'Surabaya Barat'],
            ['NamaKecamatan' => 'Pakal', 'Wilayah' => 'Surabaya Barat'],
            ['NamaKecamatan' => 'Lakarsantri', 'Wilayah' => 'Surabaya Barat'],
            ['NamaKecamatan' => 'Sambikerep', 'Wilayah' => 'Surabaya Barat'],
            ['NamaKecamatan' => 'Tandes', 'Wilayah' => 'Surabaya Barat'],
            ['NamaKecamatan' => 'Sukomanunggal', 'Wilayah' => 'Surabaya Barat'],
            
            // Surabaya Timur - with sample polygon
            ['NamaKecamatan' => 'Gubeng', 'Wilayah' => 'Surabaya Timur', 'PolygonJSON' => json_encode(['type' => 'Polygon', 'coordinates' => [[[112.75, -7.27], [112.76, -7.27], [112.76, -7.28], [112.75, -7.28], [112.75, -7.27]]]])],
            ['NamaKecamatan' => 'Sukolilo', 'Wilayah' => 'Surabaya Timur'],
            ['NamaKecamatan' => 'Tambaksari', 'Wilayah' => 'Surabaya Timur'],
            ['NamaKecamatan' => 'Mulyorejo', 'Wilayah' => 'Surabaya Timur'],
            ['NamaKecamatan' => 'Rungkut', 'Wilayah' => 'Surabaya Timur', 'PolygonJSON' => json_encode(['type' => 'Polygon', 'coordinates' => [[[112.76, -7.30], [112.78, -7.30], [112.78, -7.32], [112.76, -7.32], [112.76, -7.30]]]])],
            ['NamaKecamatan' => 'Tenggilis Mejoyo', 'Wilayah' => 'Surabaya Timur'],
            ['NamaKecamatan' => 'Gunung Anyar', 'Wilayah' => 'Surabaya Timur'],
            
            // Surabaya Utara - with sample polygon
            ['NamaKecamatan' => 'Bulak', 'Wilayah' => 'Surabaya Utara', 'PolygonJSON' => json_encode(['type' => 'Polygon', 'coordinates' => [[[112.73, -7.20], [112.75, -7.20], [112.75, -7.22], [112.73, -7.22], [112.73, -7.20]]]])],
            ['NamaKecamatan' => 'Kenjeran', 'Wilayah' => 'Surabaya Utara'],
            ['NamaKecamatan' => 'Semampir', 'Wilayah' => 'Surabaya Utara'],
            ['NamaKecamatan' => 'Pabean Cantian', 'Wilayah' => 'Surabaya Utara'],
            ['NamaKecamatan' => 'Krembangan', 'Wilayah' => 'Surabaya Utara'],
            
            // Surabaya Selatan - with sample polygon
            ['NamaKecamatan' => 'Wonokromo', 'Wilayah' => 'Surabaya Selatan', 'PolygonJSON' => json_encode(['type' => 'Polygon', 'coordinates' => [[[112.73, -7.29], [112.74, -7.29], [112.74, -7.30], [112.73, -7.30], [112.73, -7.29]]]])],
            ['NamaKecamatan' => 'Wonocolo', 'Wilayah' => 'Surabaya Selatan'],
            ['NamaKecamatan' => 'Wiyung', 'Wilayah' => 'Surabaya Selatan', 'PolygonJSON' => json_encode(['type' => 'Polygon', 'coordinates' => [[[112.68, -7.30], [112.70, -7.30], [112.70, -7.32], [112.68, -7.32], [112.68, -7.30]]]])],
            ['NamaKecamatan' => 'Karang Pilang', 'Wilayah' => 'Surabaya Selatan'],
            ['NamaKecamatan' => 'Dukuh Pakis', 'Wilayah' => 'Surabaya Selatan'],
            ['NamaKecamatan' => 'Gayungan', 'Wilayah' => 'Surabaya Selatan'],
            ['NamaKecamatan' => 'Jambangan', 'Wilayah' => 'Surabaya Selatan'],
            ['NamaKecamatan' => 'Sawahan', 'Wilayah' => 'Surabaya Selatan'],
            
            // Surabaya Tengah - with sample polygon
            ['NamaKecamatan' => 'Genteng', 'Wilayah' => 'Surabaya Tengah', 'PolygonJSON' => json_encode(['type' => 'Polygon', 'coordinates' => [[[112.73, -7.26], [112.74, -7.26], [112.74, -7.27], [112.73, -7.27], [112.73, -7.26]]]])],
            ['NamaKecamatan' => 'Tegalsari', 'Wilayah' => 'Surabaya Tengah'],
            ['NamaKecamatan' => 'Bubutan', 'Wilayah' => 'Surabaya Tengah'],
            ['NamaKecamatan' => 'Simokerto', 'Wilayah' => 'Surabaya Tengah'],
        ];
        
        foreach ($kecamatanData as $kecamatan) {
            Kecamatan::create($kecamatan);
        }
    }
}
