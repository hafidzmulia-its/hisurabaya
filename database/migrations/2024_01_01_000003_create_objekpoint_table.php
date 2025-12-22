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
        Schema::create('objekpoint', function (Blueprint $table) {
            $table->id('PointID');
            $table->string('NamaObjek', 200);
            $table->text('Deskripsi')->nullable();
            $table->string('Alamat', 500)->nullable();
            $table->decimal('Latitude', 10, 8);
            $table->decimal('Longitude', 11, 8);
            $table->decimal('HargaMin', 12, 2)->nullable();
            $table->decimal('HargaMax', 12, 2)->nullable();
            $table->integer('StarClass')->default(0)->comment('0-5 stars');
            $table->unsignedBigInteger('KecamatanID')->nullable();
            $table->unsignedBigInteger('OwnerUserID')->nullable();
            $table->boolean('IsActive')->default(true);
            $table->timestamps();
            
            $table->foreign('KecamatanID')
                ->references('KecamatanID')
                ->on('kecamatan')
                ->onDelete('set null');
                
            $table->foreign('OwnerUserID')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
                
            $table->index(['Latitude', 'Longitude']);
            $table->index('KecamatanID');
            $table->index('IsActive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objekpoint');
    }
};
