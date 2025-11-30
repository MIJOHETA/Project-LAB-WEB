<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Pasien
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade'); // Dokter yg dinilai
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade'); // Transaksi yg mana
            $table->integer('rating'); // 1 - 5
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};