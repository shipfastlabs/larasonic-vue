<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/* CREATE TABLE `guest_personal_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) DEFAULT NULL,
  `middleinitial` varchar(255) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `birthplace` varchar(255) DEFAULT NULL,
  `citizenship` varchar(50) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `sex` varchar(50) DEFAULT NULL,
  `civilstatus` varchar(50) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `currentaddress` text DEFAULT NULL,
  `permanentaddress` text DEFAULT NULL,
  `inputemail` varchar(255) DEFAULT NULL,
  `phone` bigint(20) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci */
class GuestPersonalInfo extends Model
{
    use HasFactory;

    protected $table = "guest_personal_info";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        "firstname",
        "middleinitial",
        "lastname",
        "birthdate",
        "birthplace",
        "citizenship",
        "religion",
        "sex",
        "civilstatus",
        "weight",
        "height",
        "currentaddress",
        "permanentaddress",
        "inputemail",
        "phone",
        "age",
    ];
    public function enrollee()
    {
        return $this->belongsTo(GuestEnrollment::class);
    }
}
