<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update Tabel Users (Cek dulu sebelum tambah kolom)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'dokter', 'pasien'])->default('pasien')->after('email');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('phone');
            }
        });

        // 2. Tabel Poli (Poli Management)
        if (!Schema::hasTable('polis')) {
            Schema::create('polis', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }

        // 3. Tabel Doctors (Detail Dokter)
        if (!Schema::hasTable('doctors')) {
            Schema::create('doctors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('poli_id')->constrained('polis')->onDelete('cascade');
                $table->string('sip')->nullable();
                $table->timestamps();
            });
        }

        // 4. Tabel Medicines (Obat)
        if (!Schema::hasTable('medicines')) {
            Schema::create('medicines', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->enum('type', ['keras', 'biasa', 'luar']);
                $table->integer('stock')->default(0);
                $table->integer('price')->default(0);
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }

        // 5. Tabel Schedules (Jadwal Dokter)
        if (!Schema::hasTable('schedules')) {
            Schema::create('schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
                $table->enum('day', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
                $table->time('start_time');
                $table->time('end_time');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 6. Tabel Appointments (Janji Temu)
        if (!Schema::hasTable('appointments')) {
            Schema::create('appointments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
                $table->date('appointment_date');
                $table->text('complaint');
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
                $table->text('admin_note')->nullable();
                $table->timestamps();
            });
        }

        // 7. Tabel Medical Records (Rekam Medis)
        if (!Schema::hasTable('medical_records')) {
            Schema::create('medical_records', function (Blueprint $table) {
                $table->id();
                $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
                $table->text('diagnosis');
                $table->text('treatment');
                $table->timestamps();
            });
        }
        
        // 8. Tabel Pivot Medical Record - Medicines (Resep Obat)
        if (!Schema::hasTable('medical_record_medicine')) {
            Schema::create('medical_record_medicine', function (Blueprint $table) {
                $table->id();
                $table->foreignId('medical_record_id')->constrained('medical_records')->onDelete('cascade');
                $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->text('instruction')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_record_medicine');
        Schema::dropIfExists('medical_records');
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('medicines');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('polis');
        
        // Hapus kolom tambahan di users jika rollback
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) $table->dropColumn('role');
            if (Schema::hasColumn('users', 'phone')) $table->dropColumn('phone');
            if (Schema::hasColumn('users', 'address')) $table->dropColumn('address');
        });
    }
};