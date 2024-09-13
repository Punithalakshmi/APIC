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
        Schema::create('apilogs', function (Blueprint $table) {
            $table->id();
            $table->string('action');
            $table->string('action_type');
            $table->integer('dealer_id');
            $table->longText('api_response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apilogs');
    }
};
