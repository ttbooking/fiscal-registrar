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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('state')->default(0)->index();
            $table->string('connection', 32)->nullable()->index();
            $table->string('operation', 32)->nullable();
            $table->string('external_id', 128)->nullable()->index();
            $table->string('internal_id', 128)->nullable()->index();
            $table->json('payload');
            $table->json('result')->nullable();
            $table->string('fn_number')->virtualAs('result->>"$.payload.fn_number"')->index();
            $table->unsignedInteger('fiscal_document_number')->virtualAs('result->>"$.payload.fiscal_document_number"');
            $table->unsignedInteger('fiscal_document_attribute')->virtualAs('result->>"$.payload.fiscal_document_attribute"');
            $table->unsignedFloat('total', 10)->virtualAs('result->>"$.payload.total"');
            $table->timestamps();
            $table->unique(['connection', 'external_id']);
            $table->unique(['connection', 'internal_id']);
            $table->unique(['fn_number', 'fiscal_document_number', 'fiscal_document_attribute'], 'receipt_attributes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
