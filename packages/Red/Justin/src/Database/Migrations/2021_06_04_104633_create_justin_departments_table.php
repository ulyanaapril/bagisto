<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJustinDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('justin_departments', function (Blueprint $table) {
            $table->id();
            $table->string('branch', 255)->nullable();
            $table->string('number');
            $table->string('uuid', 255)->nullable();
            $table->text('depart_descr')->nullable();
            $table->text('description')->nullable();
            $table->string('region_uuid', 255)->nullable();
            $table->text('region_name')->nullable();
            $table->string('city_uuid', 255)->nullable();
            $table->string('city_name', 255)->nullable();
            $table->string('street_uuid', 255)->nullable();
            $table->text('street_name')->nullable();
            $table->string('street_number', 255)->nullable();
            $table->integer('weight_limit')->nullable();
            $table->text('address')->nullable();
            $table->string('lat', 255)->nullable();
            $table->string('lng', 255)->nullable();
            $table->integer('type_value')->nullable();
            $table->string('type_descr', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('justin_departments');
    }
}
