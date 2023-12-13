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
            $table->string('accreditation')->nullable();
            $table->string('level')->nullable();
            $table->string('established')->nullable();
            $table->string('npsn')->nullable();
            $table->string('headmaster')->nullable();
            $table->string('class')->nullable();
            $table->string('curriculum')->nullable();
            $table->string('student')->nullable();
            $table->string('location')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('link_location')->nullable();
            $table->string('telephone')->nullable();
            $table->string('web')->nullable();
            $table->string('motto')->nullable();
            $table->string('content_array')->nullable();
            $table->string('school_status')->nullable();
            $table->string('avatar')->nullable();
            $table->string('banner')->nullable();
            $table->string('slug');
            $table->timestamps();
        });
    }
};
