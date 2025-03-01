<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracksStrands extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'track_id',
    ];

    public function Track(){
        return $this->belongsTo(ShsTracks::class, 'track_id');
    }

    public function Subjects(){
        return $this->hasMany(StrandSubjects::class, 'strand_id');
    }
}
