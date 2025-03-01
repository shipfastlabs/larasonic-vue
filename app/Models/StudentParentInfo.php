<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* CREATE  TABLE test.student_parents_info (
    id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
    fathers_name         VARCHAR(100)       ,
    mothers_name         VARCHAR(100)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

 */

class StudentParentInfo extends Model
{
    use HasFactory;

    protected $table = 'student_parents_info';

    protected $fillable = [
        'fathers_name', 'mothers_name',
    ];

    public $timestamps = false;

    protected $primaryKey = 'id';

    public function Student()
    {

        return $this->belongsTo(Students::class);
    }
}
