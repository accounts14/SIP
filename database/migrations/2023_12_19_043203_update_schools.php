<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("UPDATE schools SET level = NULL, school_status = 1");
        Schema::table('schools', function (Blueprint $table) {
            $table->enum('type', ['Swasta', 'Negeri'])->change();
            $table->unsignedBigInteger('level')->nullable()->change();
            $table->enum('accreditation', ['A', 'B', 'C', 'TT'])->nullable()->change();
            $table->text('motto')->nullable()->change();
            $table->double('longitude')->default(0)->nullable()->change();
            $table->double('latitude')->default(0)->nullable()->change();
            $table->set('school_status', [0,1])->default(1)->change();

            $table->foreign('level')->references('id')->on('school_levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropForeign(['level']);

            $table->string('type')->change();
            // $table->string('level')->nullable()->change();
            $table->string('accreditation')->nullable()->change();
            $table->string('motto')->nullable()->change();
            $table->string('school_status')->nullable()->change();
            $table->string('longitude')->nullable()->change();
            $table->string('latitude')->nullable()->change();
        });
    }
};
