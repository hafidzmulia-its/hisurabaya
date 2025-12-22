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
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id('KecamatanID');
            $table->string('NamaKecamatan', 100);
            $table->enum('Wilayah', [
                'Surabaya Barat',
                'Surabaya Timur',
                'Surabaya Utara',
                'Surabaya Selatan',
                'Surabaya Tengah'
            ]);
            $table->text('PolygonJSON')->nullable(); // GeoJSON Polygon
            $table->timestamps();
            
            $table->index('Wilayah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatan');
    }
};
