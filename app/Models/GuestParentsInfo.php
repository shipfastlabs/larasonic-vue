<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/* CREATE TABLE `guests_parents_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fathersname` varchar(100) DEFAULT NULL,
  `mothersname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci */
class GuestParentsInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        "fathersname",
        "mothersname",
    ];
    protected $table = "guests_parents_info";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function enrollee()
    {
        return $this->belongsTo(GuestEnrollment::class);
    }
}
