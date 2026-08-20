<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kendaraan')->unique(); // S, M, L, XL, Sport & Luxury
            $table->integer('volume_campuran'); // 750, 1000, 1250, 1500
            $table->decimal('harga_member', 10, 2);
            $table->decimal('harga_non_member', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};