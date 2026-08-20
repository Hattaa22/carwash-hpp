<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Component;

class ComponentSeeder extends Seeder
{
    public function run()
    {
        $components = [
            // Car Wash Components
            [
                'name' => 'Touchless',
                'kategori' => 'Car Wash',
                'harga' => 1750000,
                'qty' => 20000,
                'satuan' => 'ml',
                'harga_per_ml' => 87.5,
                'harga_per_satuan' => 87.5
            ],
            [
                'name' => 'Jamur Kaca',
                'kategori' => 'Car Wash',
                'harga' => 400000,
                'qty' => 5000,
                'satuan' => 'ml',
                'harga_per_ml' => 80,
                'harga_per_satuan' => 80
            ],
            [
                'name' => 'WS',
                'kategori' => 'Car Wash',
                'harga' => 800000,
                'qty' => 10000,
                'satuan' => 'ml',
                'harga_per_ml' => 80,
                'harga_per_satuan' => 80
            ],
            [
                'name' => 'ALX',
                'kategori' => 'Car Wash',
                'harga' => 950000,
                'qty' => 10000,
                'satuan' => 'ml',
                'harga_per_ml' => 95,
                'harga_per_satuan' => 95
            ],
            [
                'name' => 'QS',
                'kategori' => 'Car Wash',
                'harga' => 450000,
                'qty' => 5000,
                'satuan' => 'ml',
                'harga_per_ml' => 90,
                'harga_per_satuan' => 90
            ],
            [
                'name' => 'APC',
                'kategori' => 'Car Wash',
                'harga' => 600000,
                'qty' => 10000,
                'satuan' => 'ml',
                'harga_per_ml' => 60,
                'harga_per_satuan' => 60
            ],
            [
                'name' => 'Super shine',
                'kategori' => 'Car Wash',
                'harga' => 750000,
                'qty' => 10000,
                'satuan' => 'ml',
                'harga_per_ml' => 75,
                'harga_per_satuan' => 75
            ],

            // Treatment Components
            [
                'name' => 'Degreaser',
                'kategori' => 'Treatment',
                'harga' => 1200000,
                'qty' => 15000,
                'satuan' => 'ml',
                'harga_per_ml' => 80,
                'harga_per_satuan' => 80
            ],
            [
                'name' => 'CWM',
                'kategori' => 'Treatment',
                'harga' => 650000,
                'qty' => 8000,
                'satuan' => 'ml',
                'harga_per_ml' => 81.25,
                'harga_per_satuan' => 81.25
            ],
            [
                'name' => 'Backing pleate 3\'',
                'kategori' => 'Treatment',
                'harga' => 350000,
                'qty' => 100,
                'satuan' => 'pcs',
                'harga_per_ml' => 3500,
                'harga_per_satuan' => 3500
            ],
            [
                'name' => 'Backing pleate 6\'',
                'kategori' => 'Treatment',
                'harga' => 450000,
                'qty' => 100,
                'satuan' => 'pcs',
                'harga_per_ml' => 4500,
                'harga_per_satuan' => 4500
            ],
            [
                'name' => 'Lake country',
                'kategori' => 'Treatment',
                'harga' => 200000,
                'qty' => 50,
                'satuan' => 'pcs',
                'harga_per_ml' => 4000,
                'harga_per_satuan' => 4000
            ],
            [
                'name' => 'Abralon 5\'',
                'kategori' => 'Treatment',
                'harga' => 150000,
                'qty' => 50,
                'satuan' => 'pcs',
                'harga_per_ml' => 3000,
                'harga_per_satuan' => 3000
            ],
            [
                'name' => 'E3 Pollar shine',
                'kategori' => 'Treatment',
                'harga' => 800000,
                'qty' => 5000,
                'satuan' => 'ml',
                'harga_per_ml' => 160,
                'harga_per_satuan' => 160
            ],
            [
                'name' => 'Obat Coating kaca',
                'kategori' => 'Treatment',
                'harga' => 1500000,
                'qty' => 1000,
                'satuan' => 'ml',
                'harga_per_ml' => 1500,
                'harga_per_satuan' => 1500
            ],
            [
                'name' => 'Microfiber 320 gsm',
                'kategori' => 'Treatment',
                'harga' => 250000,
                'qty' => 100,
                'satuan' => 'pcs',
                'harga_per_ml' => 2500,
                'harga_per_satuan' => 2500
            ],
            [
                'name' => 'Clay',
                'kategori' => 'Treatment',
                'harga' => 300000,
                'qty' => 2000,
                'satuan' => 'gram',
                'harga_per_ml' => 150,
                'harga_per_satuan' => 150
            ],
            [
                'name' => 'Obat Coating Velg',
                'kategori' => 'Treatment',
                'harga' => 1200000,
                'qty' => 1000,
                'satuan' => 'ml',
                'harga_per_ml' => 1200,
                'harga_per_satuan' => 1200
            ]
        ];

        foreach ($components as $component) {
            Component::updateOrCreate(['name' => $component['name']], $component);
        }
    }
}