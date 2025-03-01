<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShsTracks extends Model
{
    use HasFactory;

    protected $fillable = [
        'track_name',
        'description',
    ];

    public function strands()
    {
        return $this->hasMany(TracksStrands::class, 'track_id');
    }
}
