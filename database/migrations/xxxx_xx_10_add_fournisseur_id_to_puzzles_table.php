<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('puzzles', function (Blueprint $table) {
            $table->unsignedBigInteger('fournisseur_id')->nullable()->after('category_id');
            $table->foreign('fournisseur_id')->references('id')->on('fournisseurs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('puzzles', function (Blueprint $table) {
            $table->dropForeign(['fournisseur_id']);
            $table->dropColumn('fournisseur_id');
        });
    }
};
