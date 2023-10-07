<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('batches', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('progress')->default(0);
            $table->string('filename', 150);
            $table->enum('status', ['pending','processing','failed','completed']);
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('batches');
    }
};
