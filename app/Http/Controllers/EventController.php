<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Stage;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['stage', 'schoolClass'])->latest()->get();
        return view('events.index', compact('events'));
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
            'event_type' => 'required|string',
            'start_time' => 'required|date',
        ]);

        Event::create($request->only(
            'title', 'description', 'event_type', 'start_time',
            'end_time', 'location', 'stage_id', 'class_id'
        ));

        return redirect()->route('events.index')->with('success', 'تم إضافة الفعالية للتقويم بنجاح.');
    }
}
