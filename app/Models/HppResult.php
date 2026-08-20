<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HppResult extends Model
{
    use HasFactory;

    protected $table = 'hpp_results';

    protected $fillable = [
        'title',
        'jenis_kendaraan',
        'sumber_pendapatan',
        'kategori_pendapatan', 
        'layanan_hpp',
        'proporsi_ml',
        'proporsi_decimal',
        'pemakaian',
        'harga_per_ml',
        'hpp',
        'margin_member',
        'margin_non_member',
        'persen_hpp_member',
        'persen_hpp_non_member'
    ];

    protected $casts = [
        'proporsi_ml' => 'float',
        'proporsi_decimal' => 'float',
        'pemakaian' => 'float',
        'harga_per_ml' => 'float',
        'hpp' => 'float',
        'margin_member' => 'float',
        'margin_non_member' => 'float',
        'persen_hpp_member' => 'float',
        'persen_hpp_non_member' => 'float'
    ];

    // Relationship (jika diperlukan)
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'jenis_kendaraan', 'jenis_kendaraan');
    }
    
    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class, 'layanan_hpp', 'layanan_hpp');
    }
    
    // Accessor untuk format currency
    public function getFormattedHppAttribute()
    {
        return 'Rp ' . number_format($this->hpp, 2, ',', '.');
    }
    
    public function getFormattedMarginMemberAttribute()
    {
        return 'Rp ' . number_format($this->margin_member, 2, ',', '.');
    }
    
    public function getFormattedMarginNonMemberAttribute()
    {
        return 'Rp ' . number_format($this->margin_non_member, 2, ',', '.');
    }
}