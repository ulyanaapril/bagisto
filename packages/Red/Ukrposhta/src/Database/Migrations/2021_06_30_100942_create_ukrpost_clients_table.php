<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUkrpostClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ukrpost_clients', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('uuid', 255);
            $table->string('name', 255);
            $table->string('external_id', 100);
            $table->string('counterparty_uuid', 255);
            $table->string('address_id', 100);
            $table->string('type', 100);
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
        Schema::dropIfExists('ukrpost_clients');
    }
}
