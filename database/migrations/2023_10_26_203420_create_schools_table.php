<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('accreditation');
            $table->string('level');
            $table->string('established');
            $table->string('npsn');
            $table->string('headmaster');
            $table->string('class');
            $table->string('curriculum');
            $table->string('student');
            $table->string('location');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('link_location');
            $table->string('telephone');
            $table->string('web');
            $table->string('motto');
            $table->string('content_array');
            $table->string('school_status');
            $table->string('avatar');
            $table->string('banner');
            $table->string('slug');
            $table->timestamps();
        });
    }
};
