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
        Schema::create('jalan', function (Blueprint $table) {
            $table->id('JalanID');
            $table->string('NamaJalan', 200);
            $table->text('KoordinatJSON'); // GeoJSON LineString polyline
            $table->unsignedBigInteger('StartPointID')->nullable();
            $table->unsignedBigInteger('EndPointID')->nullable();
            $table->decimal('DistanceKM', 8, 2)->nullable();
            $table->timestamps();
            
            $table->foreign('StartPointID')
                ->references('PointID')
                ->on('objekpoint')
                ->onDelete('no action');
                
            $table->foreign('EndPointID')
                ->references('PointID')
                ->on('objekpoint')
                ->onDelete('no action');
                
            $table->index('StartPointID');
            $table->index('EndPointID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jalan');
    }
};
