<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'check_in_start',
        'on_time_until',
        'late_until',
        'auto_absent_at',
        'allow_checkout',
        'check_out_start',
        'check_out_end',
    ];

    protected function casts(): array
    {
        return [
            'allow_checkout' => 'boolean',
        ];
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public static function current(): self
    {
        $setting = static::latest()->first();
        if (!$setting) {
            $setting = static::create([
                'check_in_start' => '06:00:00',
                'on_time_until' => '07:00:00',
                'late_until' => '10:00:00',
                'auto_absent_at' => '10:00:00',
                'allow_checkout' => false,
            ]);
        }
        return $setting;
    }
}
