<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/*CREATE TABLE laravel.subject (
    id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
    code                 varchar(255)  NOT NULL    ,
    title                varchar(255)  NOT NULL    ,
    units                int  NOT NULL    ,
    lecture              int  NOT NULL    ,
    laboratory           int      ,
    pre_riquisite        text  NOT NULL DEFAULT '[]'   ,
    academic_year        int  NOT NULL    ,
    semester             int  NOT NULL    ,
    course_id            int  NOT NULL
 );

ALTER TABLE laravel.subject ADD CONSTRAINT fk_subject_courses FOREIGN KEY ( course_id ) REFERENCES laravel.courses( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

*/

class Subject extends Model
{
    protected $table = 'subject';

    protected $primaryKey = 'id';

    protected $fillable = [
        'code', 'title', 'units', 'lecture', 'laboratory', 'course_id', 'pre_riquisite', 'academic_year', 'semester',
    ];

    protected $casts = [
        'pre_riquisite' => 'array',
    ];

    public $timestamps = false;

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id', 'id');
    }

    public static function getSubjectsDetailsByYear($subjects, $year)
    {
        return $subjects->where('academic_year', $year)->map(function ($subject) {
            return "{$subject->title} (Code: {$subject->code}, Units: {$subject->units})";
        })->join(', ');
    }

    public function subjectEnrolleds()
    {
        return $this->hasMany(SubjectEnrolled::class, 'subject_id');
    }

    // GEt pre requisites
    public function getAllPreRequisitesAttribute()
    {
        return $this->pre_riquisite;
    }
}
