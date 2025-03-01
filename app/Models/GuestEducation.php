<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/* CREATE TABLE `guest_education_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `elementaryschoolname` varchar(100) DEFAULT NULL,
  `elementarygraduationyear` int(11) DEFAULT NULL,
  `seniorhighschoolname` varchar(100) DEFAULT NULL,
  `seniorhighgraduationyear` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci */
class GuestEducation extends Model
{
    use HasFactory;

    protected $fillable = [
        "elementaryschoolname",
        "elementarygraduationyear",
        "elementaryschooladdress",
        "juniorhighschoolname",
        "juniorhighgraduationyear",
        "juniorhighschooladdress",
        "seniorhighschoolname",
        "seniorhighgraduationyear",
        "seniorhighschooladdress",
    ];

    protected $table = "guest_education_id";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function enrollee()
    {
        return $this->belongsTo(GuestEnrollment::class);
    }   
}
