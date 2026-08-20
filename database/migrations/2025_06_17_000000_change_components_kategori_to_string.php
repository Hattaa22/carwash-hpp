<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah tipe data kolom kategori pada tabel components agar fleksibel (string)
        // mengikuti nama kategori layanan (misal: Regular, Premium, Engine Bay Detailing, Glass Polish, Nano Ceramic Coating)
        Schema::table('components', function (Blueprint $table) {
            if (Schema::hasColumn('components', 'kategori')) {
                $table->string('kategori', 100)->nullable()->change();
            } else {
                $table->string('kategori', 100)->nullable()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('components', function (Blueprint $table) {
            if (Schema::hasColumn('components', 'kategori')) {
                $table->string('kategori', 100)->nullable()->change();
            }
        });
    }
};
