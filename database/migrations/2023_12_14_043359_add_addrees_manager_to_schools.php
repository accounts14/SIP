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
        Schema::table('schools', function (Blueprint $table) {
            $table->unsignedBigInteger('province_id')->nullable()->after('location');
            $table->unsignedBigInteger('district_id')->nullable()->after('province_id');
            $table->unsignedBigInteger('city_id')->nullable()->after('district_id');
            $table->unsignedBigInteger('subdistrict_id')->nullable()->after('district_id');

            // probably different engines implemented so cannot create any foreign key
            // $table->foreign('province_id')->references('prov_id')->on('provinces');
            // $table->foreign('city_id')->references('city_id')->on('cities');
            // $table->foreign('district_id')->references('dis_id')->on('districts');
            // $table->foreign('subdistrict_id')->references('subdis_id')->on('subdistricts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // $table->dropForeign(['province_id']);
            // $table->dropForeign(['city_id']);
            // $table->dropForeign(['district_id']);
            // $table->dropForeign(['subdistrict_id']);

            $table->dropColumn('province_id');
            $table->dropColumn('city_id');
            $table->dropColumn('district_id');
            $table->dropColumn('subdistrict_id');
        });
    }
};
