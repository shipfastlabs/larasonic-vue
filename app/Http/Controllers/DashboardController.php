<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Students;
use App\Models\Subject;
use App\Models\SubjectEnrolled;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        /** @var User $user */
        $user = Auth::user();

        // Ensure the user is a student.  If not, you might want to redirect or show an error.
        if (! $user->student) {
            // Handle the case where the user is not a student.  Maybe redirect to a different page?
            abort(403, 'Only students can access the dashboard.'); // Or redirect, or show a different view.
        }

        /** @var Students $student */
        $student = $user->student;

        // Get the student's current enrollments.
        $currentSemester = \App\Models\GeneralSettings::first()->semester;
        $currentSchoolYear = \App\Models\GeneralSettings::first()->getSchoolYear();

        $enrollments = SubjectEnrolled::query()
            ->where('student_id', $student->id)
            ->where('semester', $currentSemester)
            ->where('school_year', $currentSchoolYear)
            ->with(['subject', 'class']) // Eager load related data
            ->get();

        // Calculate stats.
        $gpa = SubjectEnrolled::calculateOverallGPA($student->id); // Get the overall GPA
        $totalUnits = $enrollments->sum(fn (SubjectEnrolled $enrollment) => $enrollment->subject->units);
        $attendancePercentage = 100; // Placeholder, needs to be calculated based on attendance records.

        // Get today's classes.
        $today = now()->dayOfWeek; // Get the current day of the week (0 = Sunday, 6 = Saturday).
        $todaysClasses = Classes::query()
            ->whereHas('ClassStudents', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->whereHas('Schedule', function ($query) use ($today) {
                $query->where('day_of_week', $today);
            })
            ->with(['Subject', 'Faculty', 'Schedule', 'ShsSubject'])
            ->get();

        // Find the *current* class.  This is more complex, as we need to check the time.
        $currentTime = now();
        $currentClass = $todaysClasses->first(function (Classes $class) use ($currentTime) {
            foreach ($class->Schedule as $schedule) {
                $startTime = \Carbon\Carbon::parse($schedule->time_start);
                $endTime = \Carbon\Carbon::parse($schedule->time_end);

                if ($currentTime->between($startTime, $endTime)) {
                    return true; // Found the current class!
                }
            }
            return false; // Not this class.
        });

        // Get recent grades (example - last 3 grades)
        $recentGrades = $enrollments->sortByDesc('created_at')->take(3);

        // Prepare data for the view.
        $studentData = [
            'name' => $student->full_name,
            'grade' => $student->academic_year, // Or use a formatted string like "10th Grade"
            'avatarUrl' => $student->profile_url, // Assuming you have a method to get the URL
            'streak' => 5, // Example - you'd likely have a way to calculate this
        ];

        $statsData = [
            ['label' => 'GPA', 'value' => $gpa !== null ? number_format($gpa, 2) : 'N/A'],
            ['label' => 'Attendance', 'value' => $attendancePercentage . '%'],
            ['label' => 'Assignments Due', 'value' => 'Coming Soon'], // Placeholder
            ['label' => 'Upcoming Exams', 'value' => 'Coming Soon'], // Placeholder
        ];

        $todaysClassesData = $todaysClasses->map(fn (Classes $class) => [
            'id' => $class->id,
            'subject' => $class->subject_title,
            'room' => $class->formated_assigned_rooms,
            'time' => collect($class->Schedule)->map(function ($s){
                return $s->time_start . ' - ' . $s->time_end;
            })->join(', '), // Format time.
            'teacher' => $class->faculty_full_name,
        ]);

        $currentClassData = $currentClass ? [
            'id' => $currentClass->id,
            'subject' => $currentClass->subject_title,
            'room' => $currentClass->formated_assigned_rooms,
            'time' => collect($currentClass->Schedule)->map(function ($s){
                return $s->time_start . ' - ' . $s->time_end;
            })->first(), // Format time.
            'teacher' => $currentClass->faculty_full_name,
        ] : null; // Handle case where there's no current class

        $recentGradesData = $recentGrades->map(fn (SubjectEnrolled $enrollment) => [
            'id' => $enrollment->id,
            'subject' => $enrollment->subject->title,
            'assignment' => 'Coming Soon', // Placeholder - you might have an assignment relationship
            'grade' => $enrollment->grade ?? 'N/A', // Display the grade
            'score' => 'Coming Soon', // Placeholder
            'date' => $enrollment->created_at->format('Y-m-d'), // Format the date
        ]);

        return Inertia::render('Dashboard', [
            'student' => $studentData,
            'stats' => $statsData,
            'todaysClasses' => $todaysClassesData,
            'currentClass' => $currentClassData,
            'recentGrades' => $recentGradesData,
            'assignments' => 'Coming Soon', // Placeholder
            'exams' => 'Coming Soon',       // Placeholder
            'announcements' => 'Coming Soon', // Placeholder
            'resources' => 'Coming Soon'
        ]);
    }
}
