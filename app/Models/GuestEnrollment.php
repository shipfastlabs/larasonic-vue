<?php

namespace App\Models;

use App\Models\GeneralSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/* CREATE TABLE `guest_enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `selected_course` int(11) NOT NULL,
  `academic_year` int(11) DEFAULT NULL,
  `selected_semester` int(11) NOT NULL,
  `guest_personal_info_id` int(11) NOT NULL,
  `geust_education_id` int(11) DEFAULT NULL,
  `special_skills` text DEFAULT NULL,
  `guest_parents_id` int(11) DEFAULT NULL,
  `guest_guardian_id` int(11) DEFAULT NULL,
  `guest_documents_id` int(11) DEFAULT NULL,
  `guest_tuition_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `student_id` int(11) DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `selected_subjects` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_guest_enrolments_guest_guardian_contact` (`guest_guardian_id`),
  KEY `fk_guest_enrollments_guests_parents_info` (`guest_parents_id`),
  KEY `fk_guest_enrollments_guest_personal_info` (`guest_personal_info_id`),
  KEY `idx_guest_enrollments_guest_documents_id` (`guest_documents_id`),
  KEY `idx_guest_enrollments_guest_tuition_id` (`guest_tuition_id`),
  KEY `fk_guest_enrollments_guest_education_id` (`geust_education_id`),
  CONSTRAINT `fk_guest_enrollments_document_locations` FOREIGN KEY (`guest_documents_id`) REFERENCES `document_locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_guest_enrollments_guest_education_id` FOREIGN KEY (`geust_education_id`) REFERENCES `guest_education_id` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_guest_enrollments_guest_personal_info` FOREIGN KEY (`guest_personal_info_id`) REFERENCES `guest_personal_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_guest_enrollments_guest_tuition` FOREIGN KEY (`guest_tuition_id`) REFERENCES `guest_tuition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_guest_enrollments_guests_parents_info` FOREIGN KEY (`guest_parents_id`) REFERENCES `guests_parents_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_guest_enrolments_guest_guardian_contact` FOREIGN KEY (`guest_guardian_id`) REFERENCES `guest_guardian_contact` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ciCREATE TABLE `guest_enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `selected_course` int(11) NOT NULL,
  `academic_year` int(11) DEFAULT NULL,
  `selected_semester` int(11) NOT NULL,
  `guest_personal_info_id` int(11) NOT NULL,
  `geust_education_id` int(11) DEFAULT NULL,
  `special_skills` text DEFAULT NULL,
  `guest_parents_id` int(11) DEFAULT NULL,
  `guest_guardian_id` int(11) DEFAULT NULL,
  `guest_documents_id` int(11) DEFAULT NULL,
  `guest_tuition_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `student_id` int(11) DEFAULT NULL,
  `guest_email` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `selected_subjects` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_guest_enrolments_guest_guardian_contact` (`guest_guardian_id`),
  KEY `fk_guest_enrollments_guests_parents_info` (`guest_parents_id`),
  KEY `fk_guest_enrollments_guest_personal_info` (`guest_personal_info_id`),
  KEY `idx_guest_enrollments_guest_documents_id` (`guest_documents_id`),
  KEY `idx_guest_enrollments_guest_tuition_id` (`guest_tuition_id`),
  KEY `fk_guest_enrollments_guest_education_id` (`geust_education_id`),
  CONSTRAINT `fk_guest_enrollments_document_locations` FOREIGN KEY (`guest_documents_id`) REFERENCES `document_locations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_guest_enrollments_guest_education_id` FOREIGN KEY (`geust_education_id`) REFERENCES `guest_education_id` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_guest_enrollments_guest_personal_info` FOREIGN KEY (`guest_personal_info_id`) REFERENCES `guest_personal_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_guest_enrollments_guest_tuition` FOREIGN KEY (`guest_tuition_id`) REFERENCES `guest_tuition` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_guest_enrollments_guests_parents_info` FOREIGN KEY (`guest_parents_id`) REFERENCES `guests_parents_info` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_guest_enrolments_guest_guardian_contact` FOREIGN KEY (`guest_guardian_id`) REFERENCES `guest_guardian_contact` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci */
class GuestEnrollment extends Model
{
    use HasFactory;
    protected $table = "guest_enrollments";
    protected $primaryKey = "id";
    protected $fillable = [
        "selected_course",
        "academic_year",
        "selected_semester",
        "guest_personal_info_id",
        "geust_education_id",
        "special_skills",
        "guest_parents_id",
        "guest_guardian_id",
        "guest_documents_id",
        "guest_tuition_id",
        "student_id",
        "guest_email",
        "status",
        "selected_subjects",
        "type",
        'downpayment',
        'school_year',
        'semester',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($guestEnrollment) {
            $settings = GeneralSettings::first();
            $highestStudentId = max(
                Students::max('id') ?? 0,
                GuestEnrollment::max('student_id') ?? 0
            );
            $guestEnrollment->status = 'Pending';
            $guestEnrollment->school_year = $settings->getSchoolYear();
            $guestEnrollment->semester = $settings->semester;
            $guestEnrollment->student_id = $highestStudentId + 1;
        });
        static::deleting(function ($guestEnrollment) {
            // Delete related personal info
            if ($guestEnrollment->personalInfo) {
                $guestEnrollment->personalInfo->delete();
            }

            // Delete related education info
            if ($guestEnrollment->education) {
                $guestEnrollment->education->delete();
            }

            // Delete related parents info
            if ($guestEnrollment->parentsInfo) {
                $guestEnrollment->parentsInfo->delete();
            }

            // Delete related guardian contact
            if ($guestEnrollment->guardianContact) {
                $guestEnrollment->guardianContact->delete();
            }

            // Delete related tuition info
            if ($guestEnrollment->tuition) {
                $guestEnrollment->tuition->delete();
            }

            // Delete related documents
            if ($guestEnrollment->documents) {
                $guestEnrollment->documents->delete();
            }
        });
    }
    public function personalInfo()
    {
        return $this->belongsTo(GuestPersonalInfo::class, 'guest_personal_info_id', 'id');
    }
    public function education()
    {
        return $this->belongsTo(GuestEducation::class, 'geust_education_id', 'id');
    }
    public function parentsInfo()
    {
        return $this->belongsTo(GuestParentsInfo::class, 'guest_parents_id', 'id');
    }
    public function guardianContact()
    {
        return $this->belongsTo(GuardianContact::class, 'guest_guardian_id', 'id');
    }
    public function tuition()
    {
        return $this->belongsTo(GuestTuition::class, 'guest_tuition_id', 'id');
    }
    public function documents()
    {
        return $this->hasOne(DocumentLocation::class, 'id', 'guest_documents_id');
    }
    public function SubjectsEnrolled()
    {
        return $this->hasMany(
            SubjectEnrolled::class,
            'student_id',
            'student_id'
        );
    }
}
