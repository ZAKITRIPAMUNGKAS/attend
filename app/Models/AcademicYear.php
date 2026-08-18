<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'semester',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'academic_year_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'academic_year_id');
    }

    public function attendanceSetting(): HasOne
    {
        return $this->hasOne(AttendanceSetting::class, 'academic_year_id');
    }

    public static function active(): ?self
    {
        return static::where('is_active', true)->first();
    }
}
