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
        $user = Auth::user();

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|integer|between:1,100',
            'reason' => 'required|string|max:255',
            'category' => 'required|string|max:50',
        ], [
            'amount.required' => 'قيمة النقاط مطلوبة.',
            'amount.between' => 'قيمة النقاط يجب أن تتراوح بين 1 و 100 نقطة.',
            'reason.required' => 'سبب النقاط مطلوب.',
        ]);

        $student = StudentProfile::findOrFail($request->student_id);

        if ($user->isServant()) {
            $assignedClassIds = $user->servant_class_ids;

            $isAllowed = in_array($student->class_id, $assignedClassIds) || $student->servant_id === $user->id;
            if (!$isAllowed) {
                abort(403, 'غير مصرح لك بمنح نقاط لطالب غير مسجل بفصول خدمتك.');
            }
        }

        StudentPoint::create([
            'student_id' => $request->student_id,
            'given_by' => $user->id,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'category' => $request->category,
        ]);

        return back()->with('success', 'تم تسجيل النقاط بنجاح.');
    }
}
