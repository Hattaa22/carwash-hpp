<?php
// database/seeders/VehicleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run()
    {
        $vehicles = [
            [
                'jenis_kendaraan' => 'S',
                'volume_campuran' => 750,
                'harga_member' => 55000,
                'harga_non_member' => 75000
            ],
            [
                'jenis_kendaraan' => 'M',
                'volume_campuran' => 1000,
                'harga_member' => 60000,
                'harga_non_member' => 80000
            ],
            [
                'jenis_kendaraan' => 'L',
                'volume_campuran' => 1250,
                'harga_member' => 75000,
                'harga_non_member' => 90000
            ],
            [
                'jenis_kendaraan' => 'XL',
                'volume_campuran' => 1500,
                'harga_member' => 95000,
                'harga_non_member' => 120000
            ],
            [
                'jenis_kendaraan' => 'Sport & Luxury',
                'volume_campuran' => 1500,
                'harga_member' => 120000,
                'harga_non_member' => 150000
            ]
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}