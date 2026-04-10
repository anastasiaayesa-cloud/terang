<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'nama' => 'American Express Bank Ltd.',
            ],
            [
                'nama' => 'Anglomas Internasional Bank ',
            ],
            [
                'nama' => 'Bank ABN AMRO ',
            ],
            [
                'nama' => 'Bank Agris (Bank Finconesia) ',
            ],
            [
                'nama' => 'Bank Akita ',
            ],
            [
                'nama' => 'Bank Antardaerah ',
            ],
            [
                'nama' => 'Bank ANZ Indonesia ',
            ],
            [
                'nama' => 'Bank Arta Niaga Kencana ',
            ],
            [
                'nama' => 'Bank Artha Graha Internasional ',
            ],
            [
                'nama' => 'Bank Artos Indonesia ',
            ],
            [
                'nama' => 'Bank BCA ',
            ],
            [
                'nama' => 'Bank BCA Syariah ',
            ],
            [
                'nama' => 'Bank Bengkulu ',
            ],
            [
                'nama' => 'Bank BII Maybank ',
            ],
            [
                'nama' => 'Bank Bintang Manunggal ',
            ],
            [
                'nama' => 'Bank Bisnis Internasional ',
            ],
            [
                'nama' => 'Bank BJB Syariah ',
            ],
            [
                'nama' => 'Bank BNI ',
            ],
            [
                'nama' => 'Bank BNI Syariah ',
            ],
            [
                'nama' => 'Bank BNP Paribas Indonesia ',
            ],
            [
                'nama' => 'Bank BPD Bali ',
            ],
            [
                'nama' => 'Bank BPD Banten ',
            ],
            [
                'nama' => 'Bank BRI ',
            ],
            [
                'nama' => 'Bank BRI Agro Niaga ',
            ],
            [
                'nama' => 'Bank BRI Syariah ',
            ],
            [
                'nama' => 'Bank BTN ',
            ],
            [
                'nama' => 'Bank BTPN ',
            ],
            [
                'nama' => 'Bank BTPN Syariah ',
            ],
            [
                'nama' => 'Bank Bukopin ',
            ],
            [
                'nama' => 'Bank Bukopin Syariah ',
            ],
            [
                'nama' => 'Bank Bumi Arta ',
            ],
            [
                'nama' => 'Bank Capital Indonesia ',
            ],
            [
                'nama' => 'Bank Century ',
            ],
            [
                'nama' => 'Bank CIMB Niaga ',
            ],
            [
                'nama' => 'Bank CIMB Niaga Syariah ',
            ],
            [
                'nama' => 'Bank Commonwealth ',
            ],
            [
                'nama' => 'Bank Credit Agricole Indosuez ',
            ],
            [
                'nama' => 'Bank CTBC (China Trust) Indonesia ',
            ],
            [
                'nama' => 'Bank Danamon ',
            ],
            [
                'nama' => 'Bank DBS Indonesia ',
            ],
            [
                'nama' => 'Bank DKI Jakarta ',
            ],
            [
                'nama' => 'Bank Ekonomi ',
            ],
            [
                'nama' => 'Bank Eksekutif ',
            ],
            [
                'nama' => 'Bank Ekspor Indonesia ',
            ],
            [
                'nama' => 'Bank Fama Internasional ',
            ],
            [
                'nama' => 'Bank Ganesha ',
            ],
            [
                'nama' => 'Bank Haga ',
            ],
            [
                'nama' => 'Bank Hagakita ',
            ],
            [
                'nama' => 'Bank Harda ',
            ],
            [
                'nama' => 'Bank Harfa ',
            ],
            [
                'nama' => 'Bank Harmoni International ',
            ],
            [
                'nama' => 'Bank Himpunan Saudara 1906 ',
            ],
            [
                'nama' => 'Bank HSBC ',
            ],
            [
                'nama' => 'Bank ICBC Indonesia (Halim Indonesia Bank) ',
            ],
            [
                'nama' => 'Bank IFI ',
            ],
            [
                'nama' => 'Bank INA Perdana ',
            ],
            [
                'nama' => 'Bank Index Selindo ',
            ],
            [
                'nama' => 'Bank Jabar ',
            ],
            [
                'nama' => 'Bank Jasa Jakarta ',
            ],
            [
                'nama' => 'Bank Jateng (Jawa Tengah) ',
            ],
            [
                'nama' => 'Bank Jatim (Jawa Timur) ',
            ],
            [
                'nama' => 'Bank Kalbar (Bank Kalimantan Barat) ',
            ],
            [
                'nama' => 'Bank Kalsel (Bank Kalimantan Selatan) ',
            ],
            [
                'nama' => 'Bank Kalteng (Bank Kalimantan Tengah) ',
            ],
            [
                'nama' => 'Bank Kaltimtara (Bank Kalimantan Timur dan Utara) ',
            ],
            [
                'nama' => 'Bank Keppel Tatlee Buana ',
            ],
            [
                'nama' => 'Bank Kesejahteraan Ekonomi ',
            ],
            [
                'nama' => 'Bank Lampung ',
            ],
            [
                'nama' => 'Bank Lippo ',
            ],
            [
                'nama' => 'Bank Maluku Malut ',
            ],
            [
                'nama' => 'Bank Mandiri ',
            ],
            [
                'nama' => 'Bank Mandiri Taspen Pos (Bank Sinar Harapan Bali) ',
            ],
            [
                'nama' => 'Bank Maspion Indonesia ',
            ],
            [
                'nama' => 'Bank Mayapada ',
            ],
            [
                'nama' => 'Bank Maybank Indocorp ',
            ],
            [
                'nama' => 'Bank Mayora Indonesia ',
            ],
            [
                'nama' => 'Bank Mega ',
            ],
            [
                'nama' => 'Bank Mega Syariah ',
            ],
            [
                'nama' => 'Bank Merincorp ',
            ],
            [
                'nama' => 'Bank Mestika Dharma ',
            ],
            [
                'nama' => 'Bank Mitraniaga ',
            ],
            [
                'nama' => 'Bank Mizuho Indonesia ',
            ],
            [
                'nama' => 'Bank MNC Internasional (Bank Bumiputera) ',
            ],
            [
                'nama' => 'Bank Muamalat ',
            ],
            [
                'nama' => 'Bank Multi Arta Sentosa ',
            ],
            [
                'nama' => 'Bank Multicor ',
            ],
            [
                'nama' => 'Bank Nagari (Bank Sumbar) ',
            ],
            [
                'nama' => 'Bank National Nobu (Bank Alfindo) ',
            ],
            [
                'nama' => 'Bank NTB ',
            ],
            [
                'nama' => 'Bank NTB Syariah ',
            ],
            [
                'nama' => 'Bank NTT ',
            ],
            [
                'nama' => 'Bank Nusantara Parahyangan ',
            ],
            [
                'nama' => 'Bank OCBC – Indonesia ',
            ],
            [
                'nama' => 'Bank OCBC NISP ',
            ],
            [
                'nama' => 'Bank of America ',
            ],
            [
                'nama' => 'Bank of China ',
            ],
            [
                'nama' => 'Bank Panin ',
            ],
            [
                'nama' => 'Bank Panin Dubai Syariah ',
            ],
            [
                'nama' => 'Bank Papua ',
            ],
            [
                'nama' => 'Bank Permata ',
            ],
            [
                'nama' => 'Bank Persyarikatan Indonesia ',
            ],
            [
                'nama' => 'Bank Purba Danarta ',
            ],
            [
                'nama' => 'Bank QNB Kesawan (Bank QNB Indonesia) ',
            ],
            [
                'nama' => 'Bank Resona Perdania ',
            ],
            [
                'nama' => 'Bank Riau Kepri ',
            ],
            [
                'nama' => 'Bank Royal Indonesia ',
            ],
            [
                'nama' => 'Bank Sahabat Sampeorna (Bank Dipo International) ',
            ],
            [
                'nama' => 'Bank SBI Indonesia (Bank Indomonex) ',
            ],
            [
                'nama' => 'Bank Shinhan Indonesia (Bank Metro Express) ',
            ],
            [
                'nama' => 'Bank Sinarmas ',
            ],
            [
                'nama' => 'Bank Sri Partha ',
            ],
            [
                'nama' => 'Bank Sulselbar (Bank Sulawesi Selatan dan Barat) ',
            ],
            [
                'nama' => 'Bank Sulteng (Bank Sulawesi Tengah) ',
            ],
            [
                'nama' => 'Bank Sultra ',
            ],
            [
                'nama' => 'Bank SulutGo (Bank Sulawesi Utara dan Gorontalo) ',
            ],
            [
                'nama' => 'Bank Sumitomo Mitsui Indonesia ',
            ],
            [
                'nama' => 'Bank Sumsel Babel ',
            ],
            [
                'nama' => 'Bank Sumut ',
            ],
            [
                'nama' => 'Bank Swadesi (Bank of India Indonesia) ',
            ],
            [
                'nama' => 'Bank Swaguna ',
            ],
            [
                'nama' => 'Bank Syariah Mandiri (BSM) ',
            ],
            [
                'nama' => 'Bank UOB Indonesia ',
            ],
            [
                'nama' => 'Bank UOB Indonesia (Bank Buana Indonesia) ',
            ],
            [
                'nama' => 'Bank Victoria International ',
            ],
            [
                'nama' => 'Bank Windu Kentjana ',
            ],
            [
                'nama' => 'Bank Woori Saudara ',
            ],
            [
                'nama' => 'Bank Yudha Bhakti ',
            ],
            [
                'nama' => 'BPD Aceh ',
            ],
            [
                'nama' => 'BPD Aceh Syariah ',
            ],
            [
                'nama' => 'BPD DIY (Yogyakarta) ',
            ],
            [
                'nama' => 'BPD Jambi ',
            ],
            [
                'nama' => 'BPR KS (Karyajatnika Sedaya) ',
            ],
            [
                'nama' => 'Centratama Nasional Bank ',
            ],
            [
                'nama' => 'Citibank ',
            ],
            [
                'nama' => 'Citibank N.A. ',
            ],
            [
                'nama' => 'Digibank ',
            ],
            [
                'nama' => 'Indosat Dompetku ',
            ],
            [
                'nama' => 'ING Indonesia Bank ',
            ],
            [
                'nama' => 'Jenius ',
            ],
            [
                'nama' => 'J.P. Morgan Chase Bank, N.A. ',
            ],
            [
                'nama' => 'Korea Exchange Bank Danamon ',
            ],
            [
                'nama' => 'Liman International Bank ',
            ],
            [
                'nama' => 'LinkAja ',
            ],
            [
                'nama' => 'Prima Master Bank ',
            ],
            [
                'nama' => 'Rabobank Internasional Indonesia ',
            ],
            [
                'nama' => 'Standard Chartered Bank ',
            ],
            [
                'nama' => 'Telkomsel TCash ',
            ],
            [
                'nama' => 'The Bangkok Bank Comp. Ltd. ',
            ],
            [
                'nama' => 'The Bank of Tokyo Mitsubishi UFJ Ltd. ',
            ],
        ];
        DB::table('banks')->insert($banks);
    }
}
