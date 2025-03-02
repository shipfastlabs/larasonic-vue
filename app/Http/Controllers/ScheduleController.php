<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Schedule;
use App\Models\Students;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class ScheduleController extends Controller
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
        // dd($student->id);
        // Get the student's classes for the current semester and academic year.
        $currentSemester = \App\Models\GeneralSettings::first()->semester;
        $currentSchoolYear = \App\Models\GeneralSettings::first()->getSchoolYear();
        // dd($currentSemester, $currentSchoolYear);
        $classes = Classes::query()
            ->whereHas('ClassStudents', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->where('semester', $currentSemester)
            ->where('school_year', $currentSchoolYear)
            ->with(['Schedule', 'Subject', 'Faculty', 'ShsSubject']) // Eager load for efficiency
            ->get();
        // dd($classes);
        // Flatten the schedule data.
        $schedules = $classes->flatMap(function (Classes $class) {
            return $class->Schedule->map(function (Schedule $schedule) use ($class) {
                return [
                    'id' => $schedule->id,
                    'day_of_week' => $schedule->day_of_week,
                    'time' => $schedule->time_start->format('g:i A') . ' - ' . $schedule->time_end->format('g:i A'),
                    'subject' => $class->subject_title,
                    'room' => $schedule->room?->name ?? "N/A", // Handle cases where room might be null
                    'teacher' => $class->faculty_full_name,
                    'class_id' => $class->id,
                ];
            });
        })->sortBy('day_of_week')->values()->all(); // Sort by day and re-index
        // dd($schedules);
        return Inertia::render('Schedules/Index', [
            'schedules' => $schedules,
        ]);
    }
} 