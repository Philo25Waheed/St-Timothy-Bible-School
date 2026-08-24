<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SpiritualJournal;
use App\Models\PrayerRequest;

class SpiritualJournalController extends Controller
{
    /**
     * Student view for journal & prayer requests
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->studentProfile;

        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'حسابك ليس حسام طالب.');
        }

        $journals = SpiritualJournal::where('student_id', $student->id)
            ->latest()
            ->get();

        $prayers = PrayerRequest::where('student_id', $student->id)
            ->with('servant')
            ->latest()
            ->get();

        return view('journal.index', compact('journals', 'prayers', 'student'));
    }

    /**
     * Store new student private journal entry
     */
    public function storeJournal(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'mood' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        $student = $user->studentProfile;

        if (!$student) {
            return back()->with('error', 'غير مسموح كطالب فقط.');
        }

        SpiritualJournal::create([
            'student_id' => $student->id,
            'title' => $request->title,
            'content' => $request->content,
            'mood' => $request->mood,
        ]);

        return back()->with('success', 'تم حفظ التأمل الروحي بنجاح في دفترك الخاص ✨');
    }

    /**
     * Store new student confidential prayer request
     */
    public function storePrayer(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
        ]);

        $user = Auth::user();
        $student = $user->studentProfile;

        if (!$student) {
            return back()->with('error', 'غير مسموح.');
        }

        PrayerRequest::create([
            'student_id' => $student->id,
            'servant_id' => $student->servant_id,
            'title' => $request->title,
            'details' => $request->details,
            'status' => 'pending',
        ]);

        return back()->with('success', 'تم إرسال طلبة الصلاة بنجاح إلى خادم الفصل. نصلّي معك ولأجلك 🙏');
    }

    /**
     * Servant view for class prayer requests
     */
    public function servantIndex()
    {
        $user = Auth::user();

        // Get class IDs assigned to servant if servant, or all if admin
        if ($user->isAdmin()) {
            $prayers = PrayerRequest::with(['student.user', 'student.schoolClass'])
                ->latest()
                ->get();
        } else {
            $classIds = $user->servant_class_ids;

            $prayers = PrayerRequest::whereHas('student', function ($q) use ($classIds) {
                $q->whereIn('class_id', $classIds);
            })
            ->orWhere('servant_id', $user->id)
            ->with(['student.user', 'student.schoolClass'])
            ->latest()
            ->get();
        }

        return view('servants.prayers', compact('prayers'));
    }

    /**
     * Update prayer request status and servant note
     */
    public function updatePrayer(Request $request, PrayerRequest $prayerRequest)
    {
        $user = Auth::user();
        $prayerRequest->load('student');

        if (!$user->isAdmin()) {
            $allowedClassIds = $user->servant_class_ids;

            $isAssigned = $prayerRequest->student && (
                in_array($prayerRequest->student->class_id, $allowedClassIds) ||
                $prayerRequest->student->servant_id === $user->id ||
                $prayerRequest->servant_id === $user->id
            );

            if (!$isAssigned) {
                abort(403, 'غير مصرح لك بالرد على طلبة صلاة لطالب خارج فصول خدمتك.');
            }
        }

        $request->validate([
            'status' => 'required|in:pending,praying,answered',
            'servant_notes' => 'nullable|string|max:1000',
        ]);

        $prayerRequest->update([
            'status' => $request->status,
            'servant_notes' => $request->servant_notes,
            'servant_id' => $user->id,
        ]);

        return back()->with('success', 'تم تحديث حالة طلبة الصلاة والرد عليها بنجاح 🙏');
    }
}
