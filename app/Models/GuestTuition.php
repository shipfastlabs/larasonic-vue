<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/* CREATE TABLE `guest_tuition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `totaltuition` int(11) DEFAULT NULL,
  `downpayment` int(11) DEFAULT NULL,
  `totalbalance` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `invoice_uuid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci */
class GuestTuition extends Model
{
    use HasFactory;
    protected $table = 'guest_tuition';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'totaltuition',
        'downpayment',
        'totalbalance',
        'payment_method',
        'due_date',
        'invoice_uuid',
        'discount',
        'total_lecture',
        'total_laboratory',
        'miscellaneous',
        'overall_total',
    ];

    protected $casts = [
        'totaltuition' => 'int',
        'downpayment' => 'int',
        'totalbalance' => 'int',
        'payment_method' => 'string',
        'due_date' => 'date',
        'invoice_uuid' => 'string',
        'discount' => 'string',
        'total_lecture' => 'int',
        'total_laboratory' => 'int',
        'miscellaneous' => 'int',
        'overall_total' => 'int',
    ];
    public function enrollee()
    {
        return $this->belongsTo(GuestEnrollment::class);
    }   
}
