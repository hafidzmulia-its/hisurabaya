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
        Schema::create('objekpoint_images', function (Blueprint $table) {
            $table->id('ImageID');
            $table->unsignedBigInteger('PointID');
            $table->string('ImageURL', 500);
            $table->timestamps();
            
            $table->foreign('PointID')
                ->references('PointID')
                ->on('objekpoint')
                ->onDelete('cascade');
                
            $table->index('PointID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objekpoint_images');
    }
};
