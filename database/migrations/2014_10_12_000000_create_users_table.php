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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // start field
            $table->string('ktp_photo'); // path ke foto KTP
            $table->enum('role', ['bendahara', 'rt', 'warga'])->default('warga');
            $table->enum('status', ['tetap', 'kontrak'])->default('tetap');
            $table->string('phone_number');
            $table->boolean('is_married')->default(false);
            // end field
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
