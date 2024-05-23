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
        Schema::create('properties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('sub_category_id');
            $table->unsignedInteger('province_id');
            $table->unsignedInteger('city_id');
            $table->unsignedInteger('sub_district_id');
            $table->string('title');
            $table->text('description');
            $table->text('image')->nullable();
            $table->unsignedBigInteger('price', false);
            $table->integer('land_size', false, true);
            $table->integer('building_size', false, true);
            $table->string('offer_type');
            $table->integer('bedroom', false, true);
            $table->integer('bathroom', false, true);
            $table->boolean('is_sold')->default(false);
            $table->timestampsTz(6);
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('sub_district_id')->references('id')->on('sub_districts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
