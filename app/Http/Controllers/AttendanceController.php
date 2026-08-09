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

    /**
     * QR Code Scanner View for Servants
     */
    public function qrScanner()
    {
        $user = Auth::user();
        if ($user->isServant()) {
            $classes = SchoolClass::where('servant_id', $user->id)->get();
        } else {
            $classes = SchoolClass::all();
        }

        return view('attendance.qr_scanner', compact('classes'));
    }

    /**
     * Handle AJAX QR code scan from servant camera
     */
    public function scanQrCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = trim($request->code);
        $date = Carbon::today()->toDateString();
        $recorderId = Auth::id();

        // Match student profile by code or ID
        $student = StudentProfile::where('code', $code)
            ->orWhere('id', $code)
            ->with(['user', 'schoolClass'])
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'لم يتم العثور على طالب بهذا الكود: ' . $code,
            ], 404);
        }

        // Record attendance as present
        $record = AttendanceRecord::where('student_id', $student->id)
            ->whereDate('date', $date)
            ->first();

        if ($record) {
            $record->update([
                'class_id' => $student->class_id ?? 1,
                'recorded_by' => $recorderId,
                'status' => 'present',
                'notes' => 'تسجيل حضور عبر QR Code',
            ]);
        } else {
            $record = AttendanceRecord::create([
                'student_id' => $student->id,
                'class_id' => $student->class_id ?? 1,
                'date' => $date,
                'recorded_by' => $recorderId,
                'status' => 'present',
                'notes' => 'تسجيل حضور عبر QR Code',
            ]);
        }

        // Award attendance points (+10) if not awarded for today
        $existingPoints = \App\Models\StudentPoint::where('student_id', $student->id)
            ->where('reason', 'حضور كود QR - ' . $date)
            ->first();

        $pointsAwarded = 0;
        if (!$existingPoints) {
            \App\Models\StudentPoint::create([
                'student_id' => $student->id,
                'amount' => 10,
                'reason' => 'حضور كود QR - ' . $date,
                'category' => 'attendance',
                'given_by' => $recorderId,
            ]);
            $pointsAwarded = 10;
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل حضور الطالب بنجاح! (+10 نقاط)',
            'student' => [
                'id' => $student->id,
                'name' => $student->user->name,
                'code' => $student->code,
                'class' => $student->schoolClass?->name ?? 'غير محدد',
                'avatar' => $student->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->user->name),
                'points_awarded' => $pointsAwarded,
            ],
            'time' => Carbon::now()->format('h:i A'),
        ]);
    }
}
