<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Touchless, Jamur Kaca, WS, etc.
            $table->decimal('harga', 12, 2); // Harga beli
            $table->integer('qty'); // Quantity dalam ml
            $table->string('satuan')->default('ml');
            $table->decimal('harga_per_ml', 8, 2); // Harga per ml
            $table->decimal('harga_per_satuan', 8, 2); // Same as harga_per_ml
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('components');
    }
};
