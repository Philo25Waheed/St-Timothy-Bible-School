<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Stage;
use App\Models\SchoolClass;
use App\Models\EventRegistration;
use App\Models\EventPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['stage', 'schoolClass', 'registrations', 'photos'])
            ->latest()
            ->get();

        $user = Auth::user();
        $userRegistrations = EventRegistration::where('user_id', $user->id)
            ->get()
            ->keyBy('event_id');

        return view('events.index', compact('events', 'userRegistrations'));
    }

    public function create()
    {
        $stages = Stage::all();
        $classes = SchoolClass::all();
        return view('events.create', compact('stages', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'event_type' => 'required|string|max:50',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'stage_id' => 'nullable|exists:stages,id',
            'class_id' => 'nullable|exists:classes,id',
        ]);

        Event::create($request->only(
            'title', 'description', 'event_type', 'start_time',
            'end_time', 'location', 'stage_id', 'class_id'
        ));

        return redirect()->route('events.index')->with('success', 'تم إضافة الفعالية للتقويم بنجاح.');
    }

    /**
     * Register current user / student for event or trip
     */
    public function register(Request $request, Event $event)
    {
        $request->validate([
            'notes' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $student = $user->studentProfile;

        EventRegistration::updateOrCreate(
            [
                'event_id' => $event->id,
                'user_id' => $user->id,
                'student_id' => $student?->id,
            ],
            [
                'status' => 'registered',
                'notes' => $request->notes ?? 'حجز عبر المنصة',
            ]
        );

        return back()->with('success', 'تم تأكيد حجزك في الفعالية/الرحلة بنجاح! 🚌🎉');
    }

    /**
     * Cancel event registration
     */
    public function cancelRegistration(Event $event)
    {
        $user = Auth::user();

        EventRegistration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->delete();

        return back()->with('success', 'تم إلغاء الحجز.');
    }

    /**
     * Photo and Video Gallery view
     */
    public function gallery()
    {
        $photos = EventPhoto::with('event')->latest()->get();
        $events = Event::all();

        return view('events.gallery', compact('photos', 'events'));
    }

    /**
     * Store new event photo
     */
    public function storePhoto(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'title' => 'required|string|max:255',
            'image_url' => 'required|url|regex:/^https:\/\/.+/i',
            'caption' => 'nullable|string|max:500',
        ], [
            'image_url.regex' => 'رابط الصورة يجب أن يبدأ ببروتوكول آمن (HTTPS).',
        ]);

        EventPhoto::create([
            'event_id' => $request->event_id,
            'title' => $request->title,
            'image_url' => $request->image_url,
            'caption' => $request->caption,
        ]);

        return back()->with('success', 'تم إضافة الصورة لمعرض الذكريات بنجاح 🖼️');
    }
}
