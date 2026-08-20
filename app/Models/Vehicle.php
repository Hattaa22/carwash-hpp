<?php
// app/Models/Vehicle.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';

    protected $fillable = [
        'jenis_kendaraan',
        'volume_campuran',
        'harga_member',
        'harga_non_member'
    ];

    protected $casts = [
        'volume_campuran' => 'float',
        'harga_member' => 'float',
        'harga_non_member' => 'float'
    ];
    
    // Scope untuk filter berdasarkan jenis kendaraan
    public function scopeByJenisKendaraan($query, $jenis)
    {
        return $query->where('jenis_kendaraan', $jenis);
    }
    
    // Accessor untuk format harga
    public function getFormattedHargaMemberAttribute()
    {
        return 'Rp ' . number_format($this->harga_member, 0, ',', '.');
    }
    
    public function getFormattedHargaNonMemberAttribute()
    {
        return 'Rp ' . number_format($this->harga_non_member, 0, ',', '.');
    }
}