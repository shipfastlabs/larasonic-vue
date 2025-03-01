<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

/* CREATE TABLE test.students (
    id                   int  NOT NULL  ,
    first_name           varchar(50)  NOT NULL  ,
    last_name            varchar(50)  NOT NULL  ,
    middle_name          varchar(20)  NOT NULL  ,
    gender               varchar(10)  NOT NULL  ,
    birth_date           date  NOT NULL  ,
    age                  int  NOT NULL  ,
    address              varchar(255)  NOT NULL  ,
    contacts             text  NOT NULL  ,
    course_id            int  NOT NULL  ,
    academic_year        int  NOT NULL  ,
    email                varchar(255)  NOT NULL  ,
    remarks              text    ,
    created_at           timestamp  NOT NULL DEFAULT current_timestamp() ,
    updated_at           timestamp  NOT NULL DEFAULT current_timestamp() ,
    profile_url          varchar(255)  NOT NULL  ,
    student_contact_id   int    ,
    student_parent_info  int    ,
    student_education_id int    ,
    student_personal_id  int    ,
    document_location_id int    ,
    CONSTRAINT pk_students PRIMARY KEY ( id )
 );

CREATE INDEX fk_students_courses ON test.students ( course_id );

CREATE INDEX idx_students_student_parent_info ON test.students ( student_parent_info );

CREATE INDEX idx_students_student_education_id ON test.students ( student_education_id );

CREATE INDEX idx_students_student_personal_id ON test.students ( student_personal_id );

CREATE INDEX idx_students_document_location_id ON test.students ( document_location_id );

CREATE INDEX fk_students_student_contacts ON test.students ( student_contact_id );

ALTER TABLE test.students ADD CONSTRAINT fk_students_courses FOREIGN KEY ( course_id ) REFERENCES test.courses( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE test.students ADD CONSTRAINT fk_students_student_contacts FOREIGN KEY ( student_contact_id ) REFERENCES test.student_contacts( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE test.students ADD CONSTRAINT fk_students_student_parents_info FOREIGN KEY ( student_parent_info ) REFERENCES test.student_parents_info( id ) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE test.students ADD CONSTRAINT fk_students_student_education_info FOREIGN KEY ( student_education_id ) REFERENCES test.student_education_info( id ) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE test.students ADD CONSTRAINT fk_students_students_personal_info FOREIGN KEY ( student_personal_id ) REFERENCES test.students_personal_info( id ) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE test.students ADD CONSTRAINT fk_students_document_locations FOREIGN KEY ( document_location_id ) REFERENCES test.document_locations( id ) ON DELETE NO ACTION ON UPDATE NO ACTION;

 */

class Students extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'students';

    public $timestamps = true;

    // protected $primaryKey = 'id';

    protected $autoIncrement = false;

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'middle_name',
        'gender',
        'birth_date',
        'age',
        'address',
        'contacts',
        'course_id',
        'academic_year',
        'email',
        'remarks',
        'created_at',
        'updated_at',
        'profile_url',
        'student_contact_id',
        'student_parent_info',
        'student_education_id',
        'student_personal_id',
        'document_location_id',
        'student_id',
        'status',
        'clearance_status',

    ];

    protected static function boot()
    {
        parent::boot();
        // static::creating(function (Students $model) {
        //     $maxStudentId = Students::max('id');
        //     $newId = max($maxStudentId, 1) + 1;
        //     $model->student_id = $newId;
        // });

        static::forceDeleting(function ($student) {
            $student->StudentTransactions()->delete();
            $student->StudentTuition()->delete();
            $student->StudentParentInfo()->delete();
            $student->StudentEducationInfo()->delete();
            $student->StudentContactsInfo()->delete();
            $student->personalInfo()->delete();
            $student->subjectEnrolled()->delete();
            $student->DocumentLocation()->delete();
            $student->Accounts()->delete();
        });
    }

    public function DocumentLocation()
    {
        return $this->belongsTo(document_locations::class, 'document_location_id');
    }

    public function Accounts()
    {
        return $this->hasOne(User::class, 'person_id', 'id');
    }

    public function Course()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }

    public function subjects()
    {
        return $this->hasManyThrough(
            Subject::class,
            Courses::class,
            'id', // Foreign key on Course table
            'course_id', // Foreign key on Subject table
            'course_id', // Local key on Students table
            'id' // Local key on Course table
        );
    }

    public function personalInfo()
    {
        return $this->hasOne(StudentPersonal::class, 'id', 'student_personal_id');
    }

    public function studentEducationInfo()
    {
        return $this->hasOne(StudentEducationInfo::class, 'id', 'student_education_id');
    }

    public function studentContactsInfo()
    {
        return $this->hasOne(StudentContact::class, 'id', 'student_contact_id');
    }

    public function studentParentInfo()
    {
        return $this->hasOne(StudentParentInfo::class, 'id', 'student_parent_info');
    }

    public function classEnrollments()
    {
        return $this->hasMany(class_enrollments::class, 'student_id', 'id');
    }

    public function subjectEnrolled()
    {
        return $this->hasMany(SubjectEnrolled::class, 'student_id', 'id');
    }

    public function Classes()
    {
        return $this->hasMany(class_enrollments::class, 'student_id', 'id');
    }
    // app/Models/Students.php

    public function enrollInClasses()
    {
        $subjectEnrollments = $this->subjectEnrolled;

        foreach ($subjectEnrollments as $subjectEnrollment) {
            $subject = $subjectEnrollment->subject;

            Log::info("Enrolling student {$this->id} in classes for subject: {$subject->code}");

            $classes = Classes::where('subject_code', $subject->code)
                ->whereJsonContains('course_codes', "$this->course_id")

                ->where('academic_year', $subjectEnrollment->academic_year)
                ->where('semester', $subjectEnrollment->semester)
                ->get();

            Log::info('Found '.$classes->count()." classes for subject {$subject->code}");

            foreach ($classes as $class) {
                // Check if the student is already enrolled in the class
                $existingEnrollment = class_enrollments::where('class_id', $class->id)
                    ->where('student_id', $this->id)
                    ->first();

                if (! $existingEnrollment) {
                    Log::info("Enrolling student {$this->id} in class {$class->id}");

                    class_enrollments::create([
                        'class_id' => $class->id,
                        'student_id' => $this->id,
                    ]);
                } else {
                    Log::info("Student {$this->id} is already enrolled in class {$class->id}");
                }
            }
        }

        Log::info("Finished enrolling student {$this->id} in classes");
    }

    public function subjectsByYear($academicYear)
    {
        return $this->subjects()
            ->where('academic_year', $academicYear)
            ->get()
            ->map(function ($subject) {
                return "{$subject->title} (Code: {$subject->code}, Units: {$subject->units})";
            })->join(', ');
    }

    public function StudentTuition()
    {
        return $this->hasMany(StudentTuition::class, 'student_id', 'id');
    }

    public function StudentTransactions()
    {
        return $this->hasMany(StudentTransactions::class, 'student_id', 'id');
    }

    public function StudentTransact($type, $amount, $description)
    {
        StudentTransactions::create([
            'student_id' => $this->id,
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'balance' => $this->StudentTuition->balance + ($type == 'credit' ? $amount : -$amount),
            'date' => now(),
        ]);
    }

    // /get student picture_1x1
    public function getStudentPictureAttribute()
    {
        return $this->DocumentLocation->picture_1x1 ?? '';
    }

    // get Full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    //    transaction for students
    public function Transaction()
    {
        return $this->belongsToMany(Transaction::class, 'student_transactions', 'student_id', 'transaction_id');
    }

    public function getPicture1x1Attribute()
    {
        return $this->DocumentLocation->picture_1x1 ?? '';
    }

public function hasRequestedEnrollment()
{
    return $this->StudentTuition()
                ->where('semester', GeneralSettings::first()->semester)
                ->where('school_year', GeneralSettings::first()->getSchoolYear())
                ->exists();
}

    public function getStudentChecklistAttribute()
    {
        return $this->subjectEnrolled()->get();
    }

    public function getTotalEnrolledSubjectsAttribute()
    {
        return $this->subjectEnrolled()->count();
    }

    public function getTotalUnitsAttribute()
    {
        return $this->subjectEnrolled()->sum('units');
    }
}
