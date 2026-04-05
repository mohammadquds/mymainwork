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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('national_id', 10)->unique();
            $table->string('id_version_number')->unique();
            $table->date('date_of_birth');
            $table->string('store_name');
            $table->string('employee_name');
            $table->decimal('weight', 8, 2);
            $table->integer('karat');
            $table->decimal('sale_price', 10, 2);
            $table->string('product_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
