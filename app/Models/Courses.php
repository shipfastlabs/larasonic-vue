<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* CREATE TABLE test.courses (
    id                   int  NOT NULL  AUTO_INCREMENT,
    code                 varchar(20)  NOT NULL  ,
    title                varchar(60)  NOT NULL  ,
    description          text  NOT NULL  ,
    department           varchar(255)  NOT NULL  ,
    CONSTRAINT pk_courses PRIMARY KEY ( id )
 );

 */

class Courses extends Model
{
    use HasFactory;

    protected $table = 'courses';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'title',
        'description',
        'department',
        'remarks',
        'lec_per_unit',
        'lab_per_unit',
    ];

    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->code = strtoupper($model->code);
        });

        self::deleting(function ($model) {
            $model->Subjects()->delete();
        });
    }

    protected $primaryKey = 'id';

    // protected $casts = [
    //     'code' => 'array',
    // ];

    public function Subjects()
    {
        return $this->hasMany(Subject::class, 'course_id', 'id');
    }

    public static function getCourseDetails($courseId)
    {
        $course = self::find($courseId);

        return $course ? "{$course->title} (Code: {$course->code}, Department: {$course->department})" : 'Course details not available';
    }

    public function getCourseCodeAttribute()
    {
        return strtoupper($this->attributes['code']);
    }
}
