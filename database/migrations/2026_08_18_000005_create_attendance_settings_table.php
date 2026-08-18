<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->time('check_in_start')->default('06:00:00');
            $table->time('on_time_until')->default('07:00:00');
            $table->time('late_until')->default('10:00:00');
            $table->time('auto_absent_at')->default('10:00:00');
            $table->boolean('allow_checkout')->default(false);
            $table->time('check_out_start')->nullable();
            $table->time('check_out_end')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_settings');
    }
};
