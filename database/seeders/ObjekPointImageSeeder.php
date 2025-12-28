<?php

namespace Database\Seeders;

use App\Models\ObjekPoint;
use App\Models\ObjekPointImage;
use Illuminate\Database\Seeder;

class ObjekPointImageSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            ['NamaObjek' => 'SURABAYA RIVER VIEW (SRV HOTEL)', 'ImageURL' => 'https://lh3.googleusercontent.com/gps-cs-s/AG0ilSyd7ASstmk6EYF9_47tB1WPBmYJ6KFjPFtMjZnu0tnSR7_Hex7Kbor3_97TANQooTQtMkGf26W6uzSVKNHpFiFk7cKBqjbIsa56mianWkgn7-ZJQCNuqLdb3qco0oOepSI1LLQMOQ=s1360-w1360-h1020-rw'],
            ['NamaObjek' => 'HOTEL BUMI SURABAYA',            'ImageURL' => 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEh-4wZc_t8m_z4pLIof9FNSX0uVrqfVuUB2ylTFimnJvC3jB-VobbzFB0inCuLEQ6cXpZ2sE-9OeDF1Q3sEbpjqGmvpw0na1nCw5GnGde39jz2UKH8lyu4mQInHAXk1JwdsGueHe6UE7bKc/w1200-h630-p-k-no-nu/115J5829__hotel_exterior__Small.jpg'],
            ['NamaObjek' => 'FAVE HOTEL RUNGKUT',            'ImageURL' => 'https://lh3.googleusercontent.com/p/AF1QipOxm8Hm8-FB6B0eHfuP70mZ3lRbs-JpStgPSic3=s1360-w1360-h1020-rw'],
            ['NamaObjek' => 'NOVOTEL SAMATOR SURABAYA TIMUR','ImageURL' => 'https://lh3.googleusercontent.com/p/AF1QipNjJdCQMyBjDvk5K_VL6Q5YS56hEHCq4u2Vqo5Q=s1360-w1360-h1020-rw'],
            ['NamaObjek' => 'FOUR POINT BY SHERATON SURABAYA','ImageURL' => 'https://lh3.googleusercontent.com/p/AF1QipOgrA2lgIoE8SLnNojS-0vY3-EP5deSNDVPfBKL=s1360-w1360-h1020-rw'],
            ['NamaObjek' => 'HOTEL SHANGRILA',                'ImageURL' => 'https://lh3.googleusercontent.com/p/AF1QipPwoxAVlTNfXmJ6ZmugVi3sAbmnPGI4I1Wjmot5=s1360-w1360-h1020-rw'],
            ['NamaObjek' => 'THE CAPITAL HOTEL SURABAYA',     'ImageURL' => 'hotels/capital.jpg'],
            ['NamaObjek' => 'THE ALANA SURABAYA',             'ImageURL' => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/135024406.jpg?k=65a9708252f763295f487caed230ced905b17320e39f7c58cde31e84ba9cde69&o='],
            ['NamaObjek' => 'HOTEL BERLIAN INTERNATIONAL',    'ImageURL' => 'https://lh3.googleusercontent.com/gps-cs-s/AG0ilSx6lw0TD0x12Bqg15smjjMb_-2nCiqr4hK1Zuvm-16W0YeER20S03VxNZL33U6KqVAwd1mZ9N-my85TSoifIc-BqCyBS67xWGZfGTcsuIAMs-sIEj33GoyKnUAjR58_octr2y9O=s1360-w1360-h1020-rw'],
            ['NamaObjek' => 'HOTEL NEW GRAND PARK',           'ImageURL' => 'https://lh3.googleusercontent.com/p/AF1QipP-RN_XzfzIav3Aax_qMgeOvez4MciHQ5yO8_oH=s1360-w1360-h1020-rw'],
            ['NamaObjek' => 'Institut Teknologi Sepuluh Nopember',          'ImageURL' => 'hotels/its.jpg']
        ];

        foreach ($images as $img) {
            $point = ObjekPoint::where('NamaObjek', $img['NamaObjek'])->first();
            if (!$point) {
                throw new \RuntimeException("ObjekPoint not found for image: {$img['NamaObjek']}");
            }

            ObjekPointImage::updateOrCreate(
                ['PointID' => $point->getKey(), 'ImageURL' => $img['ImageURL']],
                ['PointID' => $point->getKey(), 'ImageURL' => $img['ImageURL']]
            );
        }
    }
}
