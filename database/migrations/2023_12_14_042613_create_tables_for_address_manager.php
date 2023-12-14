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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('regencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('province_id');
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('provinces');
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('regency_id');
            $table->timestamps();

            $table->foreign('regency_id')->references('id')->on('regencies');
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('city_id');
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities');
        });

        Schema::create('subdistricts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('district_id');
            $table->timestamps();

            $table->foreign('district_id')->references('id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('regencies', function(Blueprint $table) {
            $table->dropForeign(['province_id']);
        });

        Schema::table('cities', function(Blueprint $table) {
            $table->dropForeign(['regency_id']);
        });

        Schema::table('districts', function(Blueprint $table) {
            $table->dropForeign(['city_id']);
        });

        Schema::table('subdistricts', function(Blueprint $table) {
            $table->dropForeign(['district_id']);
        });

        Schema::dropIfExists('provinces');
        Schema::dropIfExists('regencies');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('subdistricts');
    }
};
