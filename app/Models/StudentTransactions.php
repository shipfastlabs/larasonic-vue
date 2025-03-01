<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTransactions extends Model
{
    use HasFactory;

    protected $table = 'student_transactions';

    protected $fillable = [
        'student_id',
        'transaction_id',
        'amount',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Students::class, 'student_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
}
