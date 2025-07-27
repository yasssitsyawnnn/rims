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
        Schema::create('inventory_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->integer('type')->comment("1 => New request 2 => Transfer request");
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('req_branch_id')->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('status')->default(1)->comment("1 => pending 2 => partially fulfilled 3 => fulfilled");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_requests');
    }
};
