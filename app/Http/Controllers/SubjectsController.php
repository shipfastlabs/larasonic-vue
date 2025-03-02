<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Students;
use App\Models\Subject;
use App\Models\SubjectEnrolled;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class SubjectsController extends Controller
{
    public function index(): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ensure the user is a student.
        if (! $user->student) {
            abort(403, 'Only students can access this page.');
        }

        /** @var Students $student */
        $student = $user->student;
        $course = $student->course; // Get the student's course

        if (! $course) {
            abort(404, 'Student course not found.');
        }

        // Get all subjects associated with the student's course
        $allCourseSubjects = Subject::query()
            ->where('course_id', $course->id)
            ->orderBy('academic_year')
            ->orderBy('semester')
            ->get();

        // Get the student's enrolled subjects, eager loading the subject and handling duplicates
        $enrolledSubjects = SubjectEnrolled::query()
            ->where('student_id', $student->id)
            ->with('subject')
            ->get()
            ->sortByDesc('id') // Sort by ID descending to get the newest enrollment for duplicates
            ->unique('subject_id'); // Keep only the *newest* enrollment for each subject

        // Create maps for easy lookup
        $enrolledSubjectsMap = $enrolledSubjects->keyBy('subject_id');

        $completedSubjects = [];
        $ongoingSubjects = [];
        $incompleteSubjects = [];

        // Categorize subjects
        foreach ($allCourseSubjects as $subject) {
            if (isset($enrolledSubjectsMap[$subject->id])) {
                $enrolledSubject = $enrolledSubjectsMap[$subject->id];
                if ($enrolledSubject->grade !== null && $enrolledSubject->grade !== '' && $enrolledSubject->grade > 0) {
                    $completedSubjects[] = [
                        'id' => $subject->id,
                        'code' => $subject->code,
                        'title' => $subject->title,
                        'units' => $subject->units,
                        'grade' => $enrolledSubject->grade,
                        'academic_year' => $subject->academic_year,
                        'semester' => $subject->semester,
                    ];
                } else {
                    $ongoingSubjects[] = [
                        'id' => $subject->id,
                        'code' => $subject->code,
                        'title' => $subject->title,
                        'units' => $subject->units,
                        'academic_year' => $subject->academic_year,
                        'semester' => $subject->semester,
                    ];
                }
            } else {
                $incompleteSubjects[] = [
                    'id' => $subject->id,
                    'code' => $subject->code,
                    'title' => $subject->title,
                    'units' => $subject->units,
                    'academic_year' => $subject->academic_year,
                    'semester' => $subject->semester,
                ];
            }
        }

        return Inertia::render('Subjects/Index', [
            'completedSubjects' => $completedSubjects,
            'ongoingSubjects' => $ongoingSubjects,
            'incompleteSubjects' => $incompleteSubjects,
            'course' => $course, // Pass the course information
        ]);
    }
} 