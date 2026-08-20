<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hpp_results', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('jenis_kendaraan');
            $table->string('sumber_pendapatan');
            $table->string('kategori_pendapatan');
            $table->string('layanan_hpp');
            $table->decimal('proporsi_ml', 8, 2);
            $table->decimal('proporsi_decimal', 8, 3);
            $table->decimal('pemakaian', 10, 2);
            $table->decimal('harga_per_ml', 8, 2);
            $table->decimal('hpp', 10, 2);
            $table->decimal('margin_member', 10, 2);
            $table->decimal('margin_non_member', 10, 2);
            $table->decimal('persen_hpp_member', 5, 2);
            $table->decimal('persen_hpp_non_member', 5, 2);
            $table->timestamps();
            
            // Foreign key relationships
            $table->foreign('jenis_kendaraan')->references('jenis_kendaraan')->on('vehicles');
            $table->foreign('layanan_hpp')->references('name')->on('components');
            
            // Index untuk reporting
            $table->index(['sumber_pendapatan', 'kategori_pendapatan']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hpp_results');
    }
};