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
        Schema::create('puzzles', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('categorie');
            $table->unsignedBigInteger('category_id');  // Type approprié pour une clé étrangère
            $table->string('description');
            $table->string('image');
            $table->string('prix');
            $table->timestamps();

            // clef
            $table->foreign('category_id')
                  ->references('id')    
                  ->on('categories')    
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puzzles');
    }
};
