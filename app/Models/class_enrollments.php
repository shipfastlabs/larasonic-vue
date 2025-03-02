<?php

namespace App\Models;

use App\Models\ShsStudents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/* CREATE TABLE `class_enrollments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `class_id` int NOT NULL,
  `student_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_class_enrollments_classes` (`class_id`),
  KEY `fk_class_enrollments_students` (`student_id`),
  CONSTRAINT `fk_class_enrollments_classes` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`),
  CONSTRAINT `fk_class_enrollments_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci */

class class_enrollments extends Model
{
    use HasFactory;

    protected $table = 'class_enrollments';

    protected $fillable = [
        'class_id',
        'student_id',
        'completion_date',
        'status',
        'prelim_grade',
        'midterm_grade',
        'finals_grade',
        'total_average',
        'remarks',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function student()
    {
        return $this->belongsTo(Students::class);
    }

    public function ShsStudent()
    {
        return $this->belongsTo(ShsStudents::class, 'student_id', 'student_lrn');
    }

    public function getStudentNameAttribute()
    {
        if($this->ShsStudent){  
            return $this->ShsStudent->fullname;
        }else{
            return $this->student->full_name;
        }
    }

    public function getStudentYearStandingAttribute(){
        if($this->ShsStudent){
            return $this->ShsStudent->grade_level;
        }else{
            return $this->student->academic_year;
        }
    }

    public function getStudentIdNumAttribute(){
        if($this->ShsStudent){
            return $this->ShsStudent->student_lrn;
        }else{
            return $this->student->id;
        }
    }

    public function getCourseStrandAttribute(){
        if($this->ShsStudent){
            return $this->ShsStudent->track;
        }else{
            return $this->student->course->code;
        }
    }

    public function EnrolledStudent(){
        if ($this->ShsStudent){
            return $this->ShsStudent;
        }else{
            return $this->student;
        }
    }

    public function Attendances(){
        return $this->hasMany(Attendances::class, 'class_enrollment_id', 'id');
    }
}
