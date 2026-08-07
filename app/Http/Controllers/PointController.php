<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\StudentPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|integer',
            'reason' => 'required|string|max:255',
            'category' => 'required|string',
        ], [
            'amount.required' => 'قيمة النقاط مطلوبة.',
            'reason.required' => 'سبب النقاط مطلوب.',
        ]);

        StudentPoint::create([
            'student_id' => $request->student_id,
            'given_by' => Auth::id(),
            'amount' => $request->amount,
            'reason' => $request->reason,
            'category' => $request->category,
        ]);

        return back()->with('success', 'تم تسجيل النقاط بنجاح.');
    }
}
