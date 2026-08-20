<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Component;

class ComponentSeeder extends Seeder
{
    public function run()
    {
        $components = [
            [
                'name' => 'Touchless',
                'harga' => 1750000,
                'qty' => 20000,
                'satuan' => 'ml',
                'harga_per_ml' => 87.5,
                'harga_per_satuan' => 87.5
            ],
            [
                'name' => 'Jamur Kaca',
                'harga' => 400000,
                'qty' => 5000,
                'satuan' => 'ml',
                'harga_per_ml' => 80,
                'harga_per_satuan' => 80
            ],
            [
                'name' => 'WS',
                'harga' => 800000,
                'qty' => 10000,
                'satuan' => 'ml',
                'harga_per_ml' => 80,
                'harga_per_satuan' => 80
            ],
            [
                'name' => 'ALX',
                'harga' => 950000,
                'qty' => 10000,
                'satuan' => 'ml',
                'harga_per_ml' => 95,
                'harga_per_satuan' => 95
            ],
            [
                'name' => 'QS',
                'harga' => 450000,
                'qty' => 5000,
                'satuan' => 'ml',
                'harga_per_ml' => 90,
                'harga_per_satuan' => 90
            ],
            [
                'name' => 'APC',
                'harga' => 600000,
                'qty' => 10000,
                'satuan' => 'ml',
                'harga_per_ml' => 60,
                'harga_per_satuan' => 60
            ],
            [
                'name' => 'Super shine',
                'harga' => 750000,
                'qty' => 10000,
                'satuan' => 'ml',
                'harga_per_ml' => 75,
                'harga_per_satuan' => 75
            ],
            [
                'name' => 'Degreaser',
                'harga' => 1200000,
                'qty' => 15000,
                'satuan' => 'ml',
                'harga_per_ml' => 80,
                'harga_per_satuan' => 80
            ],
            [
                'name' => 'CWM',
                'harga' => 650000,
                'qty' => 8000,
                'satuan' => 'ml',
                'harga_per_ml' => 81.25,
                'harga_per_satuan' => 81.25
            ],
            [
                'name' => 'Backing pleate 3\'',
                'harga' => 350000,
                'qty' => 100,
                'satuan' => 'pcs',
                'harga_per_ml' => 3500,
                'harga_per_satuan' => 3500
            ],
            [
                'name' => 'Backing pleate 6\'',
                'harga' => 450000,
                'qty' => 100,
                'satuan' => 'pcs',
                'harga_per_ml' => 4500,
                'harga_per_satuan' => 4500
            ],
            [
                'name' => 'Lake country',
                'harga' => 200000,
                'qty' => 50,
                'satuan' => 'pcs',
                'harga_per_ml' => 4000,
                'harga_per_satuan' => 4000
            ],
            [
                'name' => 'Abralon 5\'',
                'harga' => 150000,
                'qty' => 50,
                'satuan' => 'pcs',
                'harga_per_ml' => 3000,
                'harga_per_satuan' => 3000
            ],
            [
                'name' => 'E3 Pollar shine',
                'harga' => 800000,
                'qty' => 5000,
                'satuan' => 'ml',
                'harga_per_ml' => 160,
                'harga_per_satuan' => 160
            ],
            [
                'name' => 'Obat Coating kaca',
                'harga' => 1500000,
                'qty' => 1000,
                'satuan' => 'ml',
                'harga_per_ml' => 1500,
                'harga_per_satuan' => 1500
            ],
            [
                'name' => 'Microfiber 320 gsm',
                'harga' => 250000,
                'qty' => 100,
                'satuan' => 'pcs',
                'harga_per_ml' => 2500,
                'harga_per_satuan' => 2500
            ],
            [
                'name' => 'Clay',
                'harga' => 300000,
                'qty' => 2000,
                'satuan' => 'gram',
                'harga_per_ml' => 150,
                'harga_per_satuan' => 150
            ],
            [
                'name' => 'Obat Coating Velg',
                'harga' => 1200000,
                'qty' => 1000,
                'satuan' => 'ml',
                'harga_per_ml' => 1200,
                'harga_per_satuan' => 1200
            ]
        ];

        foreach ($components as $component) {
            Component::create($component);
        }
    }
}