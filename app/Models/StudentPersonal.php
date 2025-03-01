<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/* CREATE  TABLE `laravel-v1`.students_personal_info ( 
	id                   INT    NOT NULL AUTO_INCREMENT  PRIMARY KEY,
	birthplace           VARCHAR(100)       ,
	civil_status         VARCHAR(40)       ,
	citizenship          VARCHAR(20)       ,
	religion             VARCHAR(30)       ,
	weight               INT       ,
	height               INT       ,
	current_adress       VARCHAR(200)       ,
	permanent_address    VARCHAR(255)       
 );

 */
class StudentPersonal extends Model
{
    use HasFactory;
    protected $table = 'students_personal_info';
    protected $fillable = [
        'birthplace',
        'civil_status',
        'citizenship',
        'religion',
        'weight',
        'height',
        'current_adress',
        'permanent_address',
    ];
    public function student(){
        return $this->belongsTo(Student::class);
    }
}
