<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* CREATE  TABLE test.student_education_info (
    id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
    elementary_school    VARCHAR(100)       ,
    elementary_graduate_year INT       ,
    senior_high_name     VARCHAR(100)       ,
    senior_high_graduate_year INT
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

 */

class StudentEducationInfo extends Model
{
    use HasFactory;

    protected $table = 'student_education_info';

    protected $fillable = [

        'elementary_school',
        'elementary_graduate_year',
        'elementary_school_address',
        'senior_high_name',
        'senior_high_graduate_year',
        'senior_high_address',
        'junior_high_school_name',
        'junior_high_school_address',
        'junior_high_graduation_year',

    ];

    public $timestamps = false;

    protected $primaryKey = 'id';

    public function student()
    {

        return $this->belongsTo(Students::class);
    }
}
