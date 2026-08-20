<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;

class ServiceCategorySeeder extends Seeder
{
    public function run()
    {
        $serviceCategories = [
            // Car Wash - Regular
            [
                'sumber_pendapatan' => 'Car Wash',
                'kategori_pendapatan' => 'Regular',
                'layanan_hpp' => 'Touchless',
                'proporsi_ml' => 50
            ],
            
            // Car Wash - Premium
            [
                'sumber_pendapatan' => 'Car Wash',
                'kategori_pendapatan' => 'Premium',
                'layanan_hpp' => 'Touchless',
                'proporsi_ml' => 40
            ],
            [
                'sumber_pendapatan' => 'Car Wash',
                'kategori_pendapatan' => 'Premium',
                'layanan_hpp' => 'Jamur Kaca',
                'proporsi_ml' => 20
            ],
            [
                'sumber_pendapatan' => 'Car Wash',
                'kategori_pendapatan' => 'Premium',
                'layanan_hpp' => 'WS',
                'proporsi_ml' => 15
            ],
            [
                'sumber_pendapatan' => 'Car Wash',
                'kategori_pendapatan' => 'Premium',
                'layanan_hpp' => 'ALX',
                'proporsi_ml' => 10
            ],
            [
                'sumber_pendapatan' => 'Car Wash',
                'kategori_pendapatan' => 'Premium',
                'layanan_hpp' => 'QS',
                'proporsi_ml' => 25
            ],
            [
                'sumber_pendapatan' => 'Car Wash',
                'kategori_pendapatan' => 'Premium',
                'layanan_hpp' => 'APC',
                'proporsi_ml' => 30
            ],
            [
                'sumber_pendapatan' => 'Car Wash',
                'kategori_pendapatan' => 'Premium',
                'layanan_hpp' => 'Super shine',
                'proporsi_ml' => 35
            ],
            
            // Treatment - Engine bay detailing
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Engine bay detailing',
                'layanan_hpp' => 'Degreaser',
                'proporsi_ml' => 100
            ],
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Engine bay detailing',
                'layanan_hpp' => 'ALX',
                'proporsi_ml' => 50
            ],
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Engine bay detailing',
                'layanan_hpp' => 'WS',
                'proporsi_ml' => 75
            ],
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Engine bay detailing',
                'layanan_hpp' => 'CWM',
                'proporsi_ml' => 60
            ],
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Engine bay detailing',
                'layanan_hpp' => 'Super shine',
                'proporsi_ml' => 40
            ],
            
            // Treatment - Glass Polish
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Glass Polish',
                'layanan_hpp' => 'E3 Pollar shine',
                'proporsi_ml' => 30
            ],
            
            // Treatment - Glass Coating
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Glass Coating',
                'layanan_hpp' => 'E3 Pollar shine',
                'proporsi_ml' => 25
            ],
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Glass Coating',
                'layanan_hpp' => 'Obat Coating kaca',
                'proporsi_ml' => 15
            ],
            
            // Treatment - Glass Scrub
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Glass Scrub',
                'layanan_hpp' => 'Clay',
                'proporsi_ml' => 50
            ],
            
            // Treatment - Detailing Wheel + Coating
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Detailing Wheel + Coating',
                'layanan_hpp' => 'Degreaser',
                'proporsi_ml' => 80
            ],
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Detailing Wheel + Coating',
                'layanan_hpp' => 'WS',
                'proporsi_ml' => 50
            ],
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Detailing Wheel + Coating',
                'layanan_hpp' => 'ALX',
                'proporsi_ml' => 40
            ],
            [
                'sumber_pendapatan' => 'Treatment',
                'kategori_pendapatan' => 'Detailing Wheel + Coating',
                'layanan_hpp' => 'Obat Coating Velg',
                'proporsi_ml' => 20
            ]
        ];

        foreach ($serviceCategories as $category) {
            ServiceCategory::create($category);
        }
    }
}