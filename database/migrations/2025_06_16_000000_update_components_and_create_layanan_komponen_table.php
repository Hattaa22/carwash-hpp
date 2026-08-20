<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah enum kategori & biaya_satuan pada tabel components jika belum ada
        Schema::table('components', function (Blueprint $table) {
            if (!Schema::hasColumn('components', 'kategori')) {
                $table->enum('kategori', ['bahan_baku', 'tenaga_kerja', 'overhead'])
                      ->default('bahan_baku')
                      ->after('name');
            }
            if (!Schema::hasColumn('components', 'biaya_satuan')) {
                $table->decimal('biaya_satuan', 15, 2)->default(0)->after('kategori');
            }
        });

        // 2. Buat tabel pivot/relasi layanan_komponen (ServiceCategory <-> Component)
        if (!Schema::hasTable('layanan_komponen')) {
            Schema::create('layanan_komponen', function (Blueprint $table) {
                $table->id();
                $table->foreignId('service_category_id')->constrained('service_categories')->onDelete('cascade');
                $table->foreignId('component_id')->constrained('components')->onDelete('cascade');
                $table->decimal('jumlah_pemakaian', 10, 2)->default(1);
                $table->decimal('subtotal_biaya', 15, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan_komponen');

        Schema::table('components', function (Blueprint $table) {
            if (Schema::hasColumn('components', 'kategori')) {
                $table->dropColumn('kategori');
            }
            if (Schema::hasColumn('components', 'biaya_satuan')) {
                $table->dropColumn('biaya_satuan');
            }
        });
    }
};
