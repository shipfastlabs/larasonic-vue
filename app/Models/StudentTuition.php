<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* CREATE  TABLE `laravel-v1`.student_tuition (
    id                   BIGINT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
    total_tuition        BIGINT       ,
    total_balance        INT       ,
    total_lectures       INT       ,
    total_laboratory     INT       ,
    total_miscelaneous_fees BIGINT       ,
    create_at            TIMESTAMP       ,
    updated_at           DATE       ,
    `status`             VARCHAR(40)       ,
    semester             INT       ,
    school_year          VARCHAR(255)       ,
    academic_year        INT       ,
    student_id           INT
 ) engine=InnoDB;

ALTER TABLE `laravel-v1`.student_tuition ADD CONSTRAINT fk_student_tuition_students FOREIGN KEY ( student_id ) REFERENCES `laravel-v1`.students( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

 */

class StudentTuition extends Model
{
    use HasFactory;

    protected $table = 'student_tuition';

    protected $primaryKey = 'id';

    protected $fillable = [
        'total_tuition',
        'total_balance',
        'total_lectures',
        'total_laboratory',
        'total_miscelaneous_fees',
        'status',
        'semester',
        'school_year',
        'academic_year',
        'student_id',
        'enrollment_id',
        'discount',
        'downpayment',
        'overall_tuition',
        'due_date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
