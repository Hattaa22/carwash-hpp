<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('sumber_pendapatan'); // Car Wash, Treatment
            $table->string('kategori_pendapatan'); // Regular, Premium, Engine bay detailing, etc.
            $table->string('layanan_hpp'); // Touchless, Degreaser, etc.
            $table->decimal('proporsi_ml', 8, 2); // Proporsi dalam ml
            $table->timestamps();
            
            // Index untuk query yang lebih cepat
            $table->index(['sumber_pendapatan', 'kategori_pendapatan']);
            $table->index('layanan_hpp');
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_categories');
    }
};
