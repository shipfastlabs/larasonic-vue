<?php

namespace App\Models;

use App\Models\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentEnrollment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'student_enrollment';

    protected $fillable = [
        'student_id',
        'course_id',
        'downpayment',
        'academic_year',
    ];

    // protected $dates = ['enrollment_date', 'completion_date'];

    public static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $settings = GeneralSettings::first();
            $model->status = 'Pending';
            $model->school_year = $settings->getSchoolYearString();
            $model->semester = $settings->semester;
        });

        // delete also the subjects enrolled
        // static::deleting(function (self $model) {
        //     $model->subjectsEnrolled()->delete();
        // });
    }

    public function signature()
    {
        return $this->morphOne(EnrollmentSignatures::class, 'enrollment');
    }

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id', 'id');
    }

    public function getStudentNameAttribute(): string
    {
        return $this->student->full_name;
    }

    public function course()
    {
        return $this->belongsTo(Courses::class);
    }

    public function subjectsEnrolled()
    {
        return $this->hasMany(SubjectEnrolled::class, 'enrollment_id', 'id');
    }

    public function studentTuition()
    {
        return $this->hasOne(StudentTuition::class, 'enrollment_id', 'id');
    }

    public function resources()
    {
        return $this->morphMany(Resource::class, 'resourceable');
    }

    public function getAssessmentPathAttribute(): string
    {
        return $this->resources()->where('type', 'assessment')->first()->file_path;
    }

    public function getCertificatePathAttribute(): string
    {
        return $this->resources()->where('type', 'certificate')->first()->file_path;
    }

    public function getAssessmentUrlAttribute(): string
    {
        $resource = $this->resources()->where('type', 'assessment')->first();
        return $resource ? Storage::disk('public')->url($resource->file_path) : '';
    }

    public function getCertificateUrlAttribute(): string
    {
        $resource = $this->resources()->where('type', 'certificate')->first();
        return $resource ? Storage::disk('public')->url($resource->file_path) : '';
    }
}
