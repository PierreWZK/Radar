<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mesures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('strategie', 10);
            $table->unsignedTinyInteger('performance');
            $table->unsignedTinyInteger('accessibilite');
            $table->unsignedTinyInteger('bonnes_pratiques');
            $table->unsignedTinyInteger('seo');
            $table->unsignedInteger('lcp_ms')->nullable();
            $table->decimal('cls', 6, 3)->nullable();
            $table->unsignedInteger('inp_ms')->nullable();
            $table->timestamp('mesuree_le');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mesures');
    }
};
