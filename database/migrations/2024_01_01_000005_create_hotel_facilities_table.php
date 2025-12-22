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
        Schema::create('hotel_facilities', function (Blueprint $table) {
            $table->unsignedBigInteger('PointID');
            $table->unsignedBigInteger('FacilityID');
            $table->boolean('IsAvailable')->default(true);
            $table->decimal('ExtraPrice', 12, 2)->nullable()->default(0);
            $table->timestamps();
            
            $table->primary(['PointID', 'FacilityID']);
            
            $table->foreign('PointID')
                ->references('PointID')
                ->on('objekpoint')
                ->onDelete('cascade');
                
            $table->foreign('FacilityID')
                ->references('FacilityID')
                ->on('facility_types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_facilities');
    }
};
