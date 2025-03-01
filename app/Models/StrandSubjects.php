<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrandSubjects extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'grade_year',
        'semester',
        'strand_id',
    ];

    public function strand()
    {
        return $this->belongsTo(TracksStrands::class, 'strand_id');
    }
}
