<?php

// app/Models/Component.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $table = 'components';

    protected $fillable = [
        'name',
        'harga',
        'qty',
        'satuan',
        'harga_per_ml',
        'harga_per_satuan',
        'kategori',
        'biaya_satuan'
    ];

    protected $casts = [
        'harga' => 'float',
        'harga_per_ml' => 'float',
        'harga_per_satuan' => 'float',
        'biaya_satuan' => 'float',
        'qty' => 'float'
    ];

    // Relationship ke ServiceCategory via pivot layanan_komponen
    public function serviceCategories()
    {
        return $this->hasMany(ServiceCategory::class, 'component_id', 'id');
    }

    public function layanans()
    {
        return $this->belongsToMany(ServiceCategory::class, 'layanan_komponen', 'component_id', 'service_category_id')
                    ->withPivot('jumlah_pemakaian', 'subtotal_biaya');
    }
    
    // Method untuk cek stock (jika ada kolom stock)
    public function hasStock()
    {
        // Implementasi logic stock di sini
        // Misalnya: return $this->stock > 0;
        return true; // Default true jika tidak ada sistem stock
    }
    
    // Accessor untuk format harga
    public function getFormattedHargaPerMlAttribute()
    {
        return 'Rp ' . number_format($this->harga_per_ml, 2, ',', '.');
    }
}