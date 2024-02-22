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
            $table->string('linkedin', 150)->nullable()->after('web');
            $table->string('twitter', 150)->nullable()->after('web');
            $table->string('tiktok', 150)->nullable()->after('web');
            $table->string('facebook', 150)->nullable()->after('web');
            $table->string('instagram', 150)->nullable()->after('web');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('linkedin', 150)->nullable()->after('remember_token');
            $table->string('twitter', 150)->nullable()->after('remember_token');
            $table->string('tiktok', 150)->nullable()->after('remember_token');
            $table->string('facebook', 150)->nullable()->after('remember_token');
            $table->string('instagram', 150)->nullable()->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('linkedin');
            $table->dropColumn('twitter');
            $table->dropColumn('tiktok');
            $table->dropColumn('facebook');
            $table->dropColumn('instagram');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('linkedin');
            $table->dropColumn('twitter');
            $table->dropColumn('tiktok');
            $table->dropColumn('facebook');
            $table->dropColumn('instagram');
        });
    }
};
