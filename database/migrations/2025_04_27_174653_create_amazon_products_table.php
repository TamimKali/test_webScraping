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
        Schema::create('amazon_products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->string('link');
            $table->float('product_price');
            $table->date('start_track_date');
            $table->date('last_track_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amazon_products');
    }
};
