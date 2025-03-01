<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Students;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApiGuestController extends Controller
{
    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->email;
        $userType = $request->userType;
        // Check if the email exists in User records first
        if (User::where('email', $email)->exists()) {
            return response()->json([
                'error' => 'Email already exists in User records.',
            ]); 
        }

        // Check if the email exists in Student records
        if ($userType === 'student') {
            if (Students::where('email', $email)->exists()) {
                return response()->json([
                    'success' => 'Email is valid.',
                ]);
            } else {
                return response()->json([
                    'error' => 'Email not found in Student records.',
                ]);
            }
        }

        // Check if the email exists in Faculty records
        if ($userType === 'instructor') {
            if (Faculty::where('email', $email)->exists()) {
                return response()->json([
                    'success' => 'Email is valid.',
                ]);
            } else {
                return response()->json([
                    'error' => 'Email not found in Faculty records.',
                ]);
            }
        }

        return response()->json([
            'error' => 'Email not found in records.',
        ]);
    }

    public function checkId(Request $request)
    {
        $request->validate(['id' => 'required']);

        $id = $request->id;
        $userType = $request->userType;

        if ($userType === 'student') {
            $student = Students::where('id', $id)->first();
            if ($student) {
                return response()->json([
                    'success' => 'ID is valid.',
                    'fullName' => $student->getFullNameAttribute(),
                ]);
            } else {
                return response()->json([
                    'error' => 'ID not found in Student records.',
                ]);
            }
        }

        if ($userType === 'instructor') {
            $faculty = Faculty::where('id', $id)->first();
            if ($faculty) {
                return response()->json([
                    'success' => 'ID is valid.',
                    'fullName' => $faculty->getFacultyFullNameAttribute(),
                ]);
            } else {
                return response()->json([
                    'error' => 'ID not found in Faculty records.',
                ]);
            }
        }

        return response()->json([
            'error' => 'ID not found in records.',
        ]);
    }
}
