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
        Schema::create('api_authentications', function (Blueprint $table) {
            $table->id();
            $table->longText('app_key');
            $table->longText('app_secret');
         // $table->unique('app_key(191)', 'api_authentications_app_key');
         // $table->unique('app_secret(191)', 'api_authentications_app_secret');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_authentications');
    }
};
