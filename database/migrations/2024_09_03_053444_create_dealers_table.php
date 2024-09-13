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
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->string('dealer_id');
            $table->string('name');
            $table->longText('appuid')->unique();
            $table->string('email');
            $table->string('status');
            $table->timestamp('time_of_url_generation')->useCurrent();
            $table->longText('current_url');
            $table->date('onboarding_date');
            $table->string('apic_user_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
