<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan foreign key constraint untuk layanan_hpp
        Schema::table('service_categories', function (Blueprint $table) {
            // Pastikan kolom layanan_hpp memiliki index
            $table->index('layanan_hpp');
            
            // Tambahkan foreign key constraint
            // Catatan: Ini akan menggunakan name sebagai referensi, bukan id
            $table->foreign('layanan_hpp')
                  ->references('name')
                  ->on('components')
                  ->onUpdate('cascade')
                  ->onDelete('restrict'); // Prevent deletion if still referenced
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_categories', function (Blueprint $table) {
            $table->dropForeign(['layanan_hpp']);
            $table->dropIndex(['layanan_hpp']);
        });
    }
};