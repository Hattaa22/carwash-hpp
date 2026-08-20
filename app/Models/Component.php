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
        'kategori'
    ];

    protected $casts = [
        'harga' => 'float',
        'harga_per_ml' => 'float',
        'harga_per_satuan' => 'float',
        'qty' => 'float'
    ];

    // CORRECTED: Relationship ke ServiceCategory
    public function serviceCategories()
    {
        return $this->hasMany(ServiceCategory::class, 'component_id', 'id');
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