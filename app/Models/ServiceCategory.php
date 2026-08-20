<?php
// app/Models/ServiceCategory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    // CORRECTED: Nama tabel yang sesuai dengan database
    protected $table = 'service_categories';

    protected $fillable = [
        'sumber_pendapatan',
        'kategori_pendapatan', 
        'layanan_hpp',
        'proporsi_ml',
        'component_id'
    ];

    protected $casts = [
        'proporsi_ml' => 'float',
        'component_id' => 'integer'
    ];

    // Relationship ke Component via pivot layanan_komponen
    public function component()
    {
        return $this->belongsTo(Component::class, 'component_id', 'id');
    }

    public function komponens()
    {
        return $this->belongsToMany(Component::class, 'layanan_komponen', 'service_category_id', 'component_id')
                    ->withPivot('jumlah_pemakaian', 'subtotal_biaya');
    }
    
    // Scope untuk filter berdasarkan sumber pendapatan
    public function scopeBySumberPendapatan($query, $sumber)
    {
        return $query->where('sumber_pendapatan', $sumber);
    }
    
    // Scope untuk filter berdasarkan kategori pendapatan
    public function scopeByKategoriPendapatan($query, $kategori)
    {
        return $query->where('kategori_pendapatan', $kategori);
    }
    
    // Scope untuk filter berdasarkan layanan hpp
    public function scopeByLayananHpp($query, $layanan)
    {
        return $query->where('layanan_hpp', $layanan);
    }
}