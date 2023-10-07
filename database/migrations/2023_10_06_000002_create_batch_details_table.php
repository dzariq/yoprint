<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('batch_details', function (Blueprint $table) {
            $table->id();
            $table->string('unique_key',200);
            $table->string('style',10);
            $table->string('title',150);
            $table->string('color_name',100);
            $table->text('description');
            $table->string('size',3);
            $table->string('sanmar_mainframe_color', 100);
            $table->decimal('piece_price', 20, 2);
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('batch_details');
    }
};
