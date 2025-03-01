<?php

namespace App\Models;

use App\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'subject_code',
        'faculty_id',
        'academic_year',
        'semester',
        'school_year',
        'course_codes',
        'section',

    ];

    protected $casts = [
        'academic_year' => 'int',
        'course_codes' => 'array',
    ];

    protected static function boot()
    {

        parent::boot();
        self::deleting(function ($model) {

            $model->Schedule()->delete();
            $model->ClassStudents()->delete();
        });
    }

    public function Posts()
    {
        return $this->hasMany(ClassPost::class, 'class_id', 'id')
            ->latest()
            ->orderBy('created_at', 'desc');
    }

    public function Subject()
    {
        return $this->belongsTo(Subject::class, 'subject_code', 'code');
    }

    public function ShsSubject()
    {
        return $this->belongsTo(StrandSubjects::class, 'subject_code', 'code');
    }

    public function Faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }

    public function Room()
    {
        return $this->belongsTo(rooms::class, 'room_id', 'id');
    }

    public function Schedule()
    {
        return $this->hasMany(Schedule::class, 'class_id', 'id');
    }

    public function ClassStudents()
    {
        return $this->hasMany(class_enrollments::class, 'class_id', 'id');
    }

    public function getScheduleDaysAttribute()
    {
        return $this->Schedule->pluck('day_of_week')->toArray();
    }

    public function getScheduleRoomsAttribute()
    {
        return $this->Schedule->pluck('rooms')->toArray();
    }

    // get Class Subject Title
    public function getClassSubjectTitleAttribute()
    {
        return $this->Subject->title;
    }

    // get Fullname of Faculty
    public function getFacultyFullNameAttribute()
    {
        return $this->Faculty->full_name ?? 'N/A';
    }

    // Get all Rooms from the assiged schedule
    public function getAssignedRoomIDsAttribute()
    {
        return $this->Schedule->pluck('room_id')->toArray();
    }

    public function getAssignedRoomsAttribute()
    {
        return rooms::whereIn('id', $this->assigned_room_ids)->pluck('name')->toArray();
    }

    //formated Assiuged ROoms with Badge
    public function getFormatedAssignedRoomsAttribute()
    {
        return implode('', array_map(function ($room) {
            return $room ;
        }, $this->AssignedRooms));
    }
    // Count how many students are enrololed in the class
    public function getStudentCountAttribute()
    {
        return $this->ClassStudents->count();
    }

    // get the course_codes code from the Course table
    public function getCourseCodesCodeAttribute()
    {
        return Courses::whereIn('id', $this->course_codes)->pluck('code')->toArray();
    }

    // formated academic year
public function getFormatedAcademicYearAttribute()
{
    $yearMapping = [
        1 => '1st year',
        2 => '2nd year',
        3 => '3rd year',
        4 => '4th year'
    ];

    return $yearMapping[$this->academic_year] ?? 'Unknown year';
}

public function getSubjectTitleAttribute()
{
    if($this->ShsSubject){
        return $this->ShsSubject->title;
    }else{
        return $this->Subject->title;
    }
}

public function getFormatedSubjectCodeAttribute()
{
    if($this->ShsSubject){
        return $this->ShsSubject->code;
    }else{
        return $this->Subject->code;
    }
}

public function getFormatedTitleAttribute()
{
    if($this->ShsSubject){
        return $this->ShsSubject->title;
    }else{
        return $this->Subject->title;
    }
}

}
