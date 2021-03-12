<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiscalRegistryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiscal_registry', function (Blueprint $table) {
            $table->id();
            $table->string('connection', 32)->index();
            $table->string('external_id', 128)->index();
            $table->string('internal_id', 128)->index();
            $table->timestamps();
            $table->unique(['connection', 'external_id']);
            $table->unique(['connection', 'internal_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fiscal_registry');
    }
}
