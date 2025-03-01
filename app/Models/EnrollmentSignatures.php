<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentSignatures extends Model
{
    use HasFactory;

    protected $table = 'enrollment_signatures';

    protected $fillable = [
        'depthead_signature',
        'registrar_signature',
        'cashier_signature',
        'enrollment_id',
        'enrollment_type',
    ];

    public function enrollment()
    {
        return $this->morphTo();
    }
}
