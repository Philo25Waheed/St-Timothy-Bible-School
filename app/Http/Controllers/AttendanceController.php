<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\StudentProfile;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->isServant()) {
            $classes = SchoolClass::where('servant_id', $user->id)->get();
        } else {
            $classes = SchoolClass::all();
        }

        $selectedClassId = $request->query('class_id', $classes->first()?->id);
        $date = $request->query('date', Carbon::today()->toDateString());

        $students = [];
        $existingRecords = [];

        if ($selectedClassId) {
            $students = StudentProfile::where('class_id', $selectedClassId)->with('user')->get();
            $existingRecords = AttendanceRecord::where('class_id', $selectedClassId)
                ->whereDate('date', $date)
                ->get()
                ->keyBy('student_id');
        }

        return view('attendance.index', compact('classes', 'selectedClassId', 'date', 'students', 'existingRecords'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        $classId = $request->class_id;
        $date = $request->date;
        $recorderId = Auth::id();

        foreach ($request->attendance as $studentId => $status) {
            AttendanceRecord::updateOrCreate(
                [
                    'class_id' => $classId,
                    'student_id' => $studentId,
                    'date' => $date,
                ],
                [
                    'recorded_by' => $recorderId,
                    'status' => $status,
                    'notes' => $request->notes[$studentId] ?? null,
                ]
            );
        }

        return back()->with('success', 'تم حفظ الحضور والغياب بنجاح ✓');
    }
}
