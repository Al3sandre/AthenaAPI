<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        // Table for arrivals
        Schema::create('arrivals', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->string('status')->default('en cours');
            $table->timestamps();
        });

        // Table for arrival products
        Schema::create('arrival_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('arrival_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('arrival_id')->references('id')->on('arrivals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrivals');
        Schema::dropIfExists('arrival_products');
    }
};
