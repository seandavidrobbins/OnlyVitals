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
        Schema::create('core_web_vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained('websites')->cascadeOnDelete();
            $table->decimal('lcp', 6, 3);
            $table->decimal('inp', 8, 2);
            $table->decimal('cls', 5, 3);
            $table->timestamp('measured_at');
            $table->timestamps();

            $table->unique('website_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_web_vitals');
    }
};
