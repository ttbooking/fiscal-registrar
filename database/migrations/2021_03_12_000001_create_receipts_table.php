<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('state')->default(0)->index();
            $table->string('connection', 32)->nullable()->index();
            $table->string('operation', 32)->nullable();
            $table->string('external_id', 128)->nullable()->index();
            $table->string('internal_id', 128)->nullable()->index();
            $table->json('data');
            $table->json('result')->nullable();
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
        Schema::dropIfExists('receipts');
    }
}
