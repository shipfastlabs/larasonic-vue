<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* CREATE TABLE "schedule" (
  "id" int unsigned NOT NULL AUTO_INCREMENT,
  "created_at" timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  "deleted_at" datetime DEFAULT NULL,
  "day_of_week" varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  "start_time" time NOT NULL,
  "end_time" time NOT NULL,
  "name" varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY ("id")
) */

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedule';

    protected $fillable = [
        'day_of_week',
        'start_time',
        'end_time',
        'room_id',
        'class_id',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'room_id' => 'int',
    ];

    public function room()
    {
        return $this->belongsTo(rooms::class, 'room_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    // public function getRoomsAttribute()
    // {
    //     return implode(', ', $this->room()->get()->pluck('name')->toArray());
    // }

    // get Schedule Subject title
    public function getSubjectAttribute()
    {
        return $this->class->Subject->title;
    }
}
