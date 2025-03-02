<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

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
        'student_id',
        'grade',
        'midterm_grade',
        'completion_grade',
        're_exam_grade',
        'remarks',
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
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Students::class, 'student_id', 'id');
    }

    /**
     * Calculate the average grade for a specific semester and academic year.
     *
     * @param int $studentId
     * @param int $academicYear
     * @param int $semester
     * @return float|null
     */
    public static function calculateSemesterGPA(int $studentId, int $academicYear, int $semester): ?float
    {
        $average = self::query()
            ->where('student_id', $studentId)
            ->where('academic_year', $academicYear)
            ->where('semester', $semester)
            ->avg('grade'); // Use the 'grade' column

        return $average !== null ? (float) number_format($average, 2) : null;
    }

    /**
     * Calculate the average grade across two semesters (1st and 2nd) within a single academic year.
     * @param int $studentId
     * @param int $academicYear
     * @return float|null
     */
    public static function calculateYearlyGPA(int $studentId, int $academicYear): ?float
    {
        $firstSemesterGPA = self::calculateSemesterGPA($studentId, $academicYear, 1);
        $secondSemesterGPA = self::calculateSemesterGPA($studentId, $academicYear, 2);

        if ($firstSemesterGPA !== null && $secondSemesterGPA !== null) {
            return ($firstSemesterGPA + $secondSemesterGPA) / 2;
        } elseif ($firstSemesterGPA !== null) {
            return $firstSemesterGPA; // Only 1st semester grades available
        } elseif ($secondSemesterGPA !== null) {
            return $secondSemesterGPA; // Only 2nd semester grades available.
        } else {
            return null; // No grades for either semester
        }
    }

    /**
     * Calculate the average grade across all four academic years.
     *
     * @param int $studentId
     * @return float|null
     */
    public static function calculateOverallGPA(int $studentId): ?float
    {
        $yearlyGPAs = [];

        for ($year = 1; $year <= 4; $year++) {
            $yearlyGPA = self::calculateYearlyGPA($studentId, $year);
            if ($yearlyGPA !== null) {
                $yearlyGPAs[] = $yearlyGPA;
            }
        }

        if (count($yearlyGPAs) > 0) {
            $overallGPA = array_sum($yearlyGPAs) / count($yearlyGPAs);
             return (float) number_format($overallGPA, 2);
        } else {
            return null; // No grades available for any year.
        }
    }
    public function classEnrollment()
    {
        return $this->belongsTo(class_enrollments::class, 'class_id', 'class_id');
    }
}
