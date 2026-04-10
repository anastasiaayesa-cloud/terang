<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\PaguHotel;
use Illuminate\Database\Seeder;

class PaguHotelSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['provinsi' => 'Aceh', 'eselon_i' => 4774000, 'eselon_ii' => 3526000, 'eselon_iii_gol_iv' => 1578000, 'eselon_iv_gol_iii_ii_i' => 770000],
            ['provinsi' => 'Sumatera Utara', 'eselon_i' => 4960000, 'eselon_ii' => 2195000, 'eselon_iii_gol_iv' => 1188000, 'eselon_iv_gol_iii_ii_i' => 699000],
            ['provinsi' => 'Riau', 'eselon_i' => 3820000, 'eselon_ii' => 3119000, 'eselon_iii_gol_iv' => 1650000, 'eselon_iv_gol_iii_ii_i' => 852000],
            ['provinsi' => 'Kepulauan Riau', 'eselon_i' => 5772000, 'eselon_ii' => 2318000, 'eselon_iii_gol_iv' => 1297000, 'eselon_iv_gol_iii_ii_i' => 792000],
            ['provinsi' => 'Jambi', 'eselon_i' => 5004000, 'eselon_ii' => 4102000, 'eselon_iii_gol_iv' => 1225000, 'eselon_iv_gol_iii_ii_i' => 580000],
            ['provinsi' => 'Sumatera Barat', 'eselon_i' => 5236000, 'eselon_ii' => 3032000, 'eselon_iii_gol_iv' => 1353000, 'eselon_iv_gol_iii_ii_i' => 701000],
            ['provinsi' => 'Sumatera Selatan', 'eselon_i' => 6298000, 'eselon_ii' => 3083000, 'eselon_iii_gol_iv' => 1966000, 'eselon_iv_gol_iii_ii_i' => 861000],
            ['provinsi' => 'Lampung', 'eselon_i' => 4491000, 'eselon_ii' => 2488000, 'eselon_iii_gol_iv' => 1539000, 'eselon_iv_gol_iii_ii_i' => 580000],
            ['provinsi' => 'Bengkulu', 'eselon_i' => 2140000, 'eselon_ii' => 1628000, 'eselon_iii_gol_iv' => 1546000, 'eselon_iv_gol_iii_ii_i' => 692000],
            ['provinsi' => 'Bangka Belitung', 'eselon_i' => 4134000, 'eselon_ii' => 2838000, 'eselon_iii_gol_iv' => 1957000, 'eselon_iv_gol_iii_ii_i' => 676000],
            ['provinsi' => 'Banten', 'eselon_i' => 5725000, 'eselon_ii' => 2373000, 'eselon_iii_gol_iv' => 1301000, 'eselon_iv_gol_iii_ii_i' => 724000],
            ['provinsi' => 'Jawa Barat', 'eselon_i' => 8512000, 'eselon_ii' => 2755000, 'eselon_iii_gol_iv' => 1298000, 'eselon_iv_gol_iii_ii_i' => 686000],
            ['provinsi' => 'DKI Jakarta', 'eselon_i' => 8720000, 'eselon_ii' => 2063000, 'eselon_iii_gol_iv' => 992000, 'eselon_iv_gol_iii_ii_i' => 730000],
            ['provinsi' => 'Jawa Tengah', 'eselon_i' => 5728000, 'eselon_ii' => 1998000, 'eselon_iii_gol_iv' => 1201000, 'eselon_iv_gol_iii_ii_i' => 810000],
            ['provinsi' => 'DI Yogyakarta', 'eselon_i' => 5017000, 'eselon_ii' => 2695000, 'eselon_iii_gol_iv' => 1495000, 'eselon_iv_gol_iii_ii_i' => 845000],
            ['provinsi' => 'Jawa Timur', 'eselon_i' => 4449000, 'eselon_ii' => 2820000, 'eselon_iii_gol_iv' => 1153000, 'eselon_iv_gol_iii_ii_i' => 814000],
            ['provinsi' => 'Bali', 'eselon_i' => 6845000, 'eselon_ii' => 2433000, 'eselon_iii_gol_iv' => 1685000, 'eselon_iv_gol_iii_ii_i' => 1138000],
            ['provinsi' => 'Nusa Tenggara Barat', 'eselon_i' => 4375000, 'eselon_ii' => 2648000, 'eselon_iii_gol_iv' => 1418000, 'eselon_iv_gol_iii_ii_i' => 907000],
            ['provinsi' => 'Nusa Tenggara Timur', 'eselon_i' => 3750000, 'eselon_ii' => 2133000, 'eselon_iii_gol_iv' => 1355000, 'eselon_iv_gol_iii_ii_i' => 688000],
            ['provinsi' => 'Kalimantan Barat', 'eselon_i' => 2654000, 'eselon_ii' => 1923000, 'eselon_iii_gol_iv' => 1125000, 'eselon_iv_gol_iii_ii_i' => 538000],
            ['provinsi' => 'Kalimantan Tengah', 'eselon_i' => 4901000, 'eselon_ii' => 2931000, 'eselon_iii_gol_iv' => 1160000, 'eselon_iv_gol_iii_ii_i' => 659000],
            ['provinsi' => 'Kalimantan Selatan', 'eselon_i' => 7797000, 'eselon_ii' => 3316000, 'eselon_iii_gol_iv' => 1500000, 'eselon_iv_gol_iii_ii_i' => 697000],
            ['provinsi' => 'Kalimantan Timur', 'eselon_i' => 4000000, 'eselon_ii' => 2188000, 'eselon_iii_gol_iv' => 1507000, 'eselon_iv_gol_iii_ii_i' => 804000],
            ['provinsi' => 'Kalimantan Utara', 'eselon_i' => 4400000, 'eselon_ii' => 2735000, 'eselon_iii_gol_iv' => 1507000, 'eselon_iv_gol_iii_ii_i' => 804000],
            ['provinsi' => 'Sulawesi Utara', 'eselon_i' => 4918000, 'eselon_ii' => 2290000, 'eselon_iii_gol_iv' => 1270000, 'eselon_iv_gol_iii_ii_i' => 975000],
            ['provinsi' => 'Gorontalo', 'eselon_i' => 4168000, 'eselon_ii' => 3107000, 'eselon_iii_gol_iv' => 1606000, 'eselon_iv_gol_iii_ii_i' => 953000],
            ['provinsi' => 'Sulawesi Barat', 'eselon_i' => 4076000, 'eselon_ii' => 3098000, 'eselon_iii_gol_iv' => 1344000, 'eselon_iv_gol_iii_ii_i' => 704000],
            ['provinsi' => 'Sulawesi Selatan', 'eselon_i' => 4820000, 'eselon_ii' => 1938000, 'eselon_iii_gol_iv' => 1423000, 'eselon_iv_gol_iii_ii_i' => 745000],
            ['provinsi' => 'Sulawesi Tengah', 'eselon_i' => 2309000, 'eselon_ii' => 2027000, 'eselon_iii_gol_iv' => 1679000, 'eselon_iv_gol_iii_ii_i' => 951000],
            ['provinsi' => 'Sulawesi Tenggara', 'eselon_i' => 3089000, 'eselon_ii' => 2574000, 'eselon_iii_gol_iv' => 1297000, 'eselon_iv_gol_iii_ii_i' => 786000],
            ['provinsi' => 'Maluku', 'eselon_i' => 3467000, 'eselon_ii' => 2240000, 'eselon_iii_gol_iv' => 1059000, 'eselon_iv_gol_iii_ii_i' => 667000],
            ['provinsi' => 'Maluku Utara', 'eselon_i' => 4612000, 'eselon_ii' => 3843000, 'eselon_iii_gol_iv' => 1160000, 'eselon_iv_gol_iii_ii_i' => 654000],
            ['provinsi' => 'Papua', 'eselon_i' => 3859000, 'eselon_ii' => 3318000, 'eselon_iii_gol_iv' => 2521000, 'eselon_iv_gol_iii_ii_i' => 1038000],
            ['provinsi' => 'Papua Barat', 'eselon_i' => 3872000, 'eselon_ii' => 3341000, 'eselon_iii_gol_iv' => 2056000, 'eselon_iv_gol_iii_ii_i' => 967000],
            ['provinsi' => 'Papua Barat Daya', 'eselon_i' => 3872000, 'eselon_ii' => 3341000, 'eselon_iii_gol_iv' => 2056000, 'eselon_iv_gol_iii_ii_i' => 967000],
            ['provinsi' => 'Papua Tengah', 'eselon_i' => 3859000, 'eselon_ii' => 3318000, 'eselon_iii_gol_iv' => 2521000, 'eselon_iv_gol_iii_ii_i' => 1038000],
            ['provinsi' => 'Papua Selatan', 'eselon_i' => 5673000, 'eselon_ii' => 4877000, 'eselon_iii_gol_iv' => 3706000, 'eselon_iv_gol_iii_ii_i' => 1526000],
            ['provinsi' => 'Papua Pegunungan', 'eselon_i' => 5711000, 'eselon_ii' => 4911000, 'eselon_iii_gol_iv' => 3731000, 'eselon_iv_gol_iii_ii_i' => 1536000],
        ];

        foreach ($data as $item) {
            PaguHotel::updateOrCreate(
                ['provinsi' => $item['provinsi']],
                [
                    'eselon_i' => $item['eselon_i'],
                    'eselon_ii' => $item['eselon_ii'],
                    'eselon_iii_gol_iv' => $item['eselon_iii_gol_iv'],
                    'eselon_iv_gol_iii_ii_i' => $item['eselon_iv_gol_iii_ii_i'],
                    'tahun' => 2025,
                ]
            );
        }
    }
}
