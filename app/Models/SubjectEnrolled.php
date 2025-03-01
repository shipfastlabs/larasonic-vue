<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/* CREATE  TABLE `laravel-v1`.subject_enrollments (
    id                   BIGINT UNSIGNED   NOT NULL AUTO_INCREMENT  PRIMARY KEY,
    subject_id           INT    NOT NULL   ,
    created_at           TIMESTAMP       ,
    updated_at           TIMESTAMP       ,
    grade                INT       ,
    instructor           VARCHAR(255)   COLLATE utf8mb4_unicode_ci    ,
    student_id           INT       ,
    academic_year        INT       ,
    school_year          VARCHAR(255)       ,
    semester             INT
 );

CREATE INDEX fk_subject_enrollments_subject ON `laravel-v1`.subject_enrollments ( subject_id );

ALTER TABLE `laravel-v1`.subject_enrollments ADD CONSTRAINT fk_subject_enrollments_subject FOREIGN KEY ( subject_id ) REFERENCES `laravel-v1`.subject( id ) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `laravel-v1`.subject_enrollments ADD CONSTRAINT fk_subject_enrollments_students FOREIGN KEY ( student_id ) REFERENCES `laravel-v1`.students( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;


 */

class SubjectEnrolled extends Model
{
    use HasFactory;

    protected $table = 'subject_enrollments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'subject_id',
        'created_at',
        'updated_at',
        'grade',
        'instructor',
        'student_id',
        'academic_year',
        'school_year',
        'semester',
        'enrollment_id',
    ];

    protected $casts = [
        'subject_id' => 'int',
        'grade' => 'int',
        'student_id' => 'int',
        'academic_year' => 'int',
        'semester' => 'int',

    ];
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    public function guestEnrollment(): BelongsTo
    {
        return $this->belongsTo(GuestEnrollment::class, 'enrollment_id', 'id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Students::class, 'student_id');
    }
}
