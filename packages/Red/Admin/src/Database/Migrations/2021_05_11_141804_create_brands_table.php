<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->integer('red_id')->nullable();
            $table->integer('greyd')->nullable();
            $table->string('name')->nullable(false);
            $table->string('country_brand')->nullable();
            $table->string('image')->nullable();
            $table->string('logo')->nullable();
            $table->text('text')->nullable();
            $table->text('text_uk')->nullable();
            $table->string('track')->nullable();
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
        Schema::dropIfExists('brands');
    }
}
