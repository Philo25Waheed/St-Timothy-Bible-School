<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Stage;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;

class AcademicController extends Controller
{
    // Academic Years
    public function years()
    {
        $years = AcademicYear::latest()->get();
        return view('admin.academic.years', compact('years'));
    }

    public function storeYear(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        if ($request->boolean('is_current')) {
            AcademicYear::query()->update(['is_current' => false]);
        }
        AcademicYear::create($request->only('name', 'is_current', 'start_date', 'end_date'));
        return back()->with('success', 'تم إضافة السنة الدراسية بنجاح.');
    }

    // Stages
    public function stages()
    {
        $stages = Stage::withCount(['grades', 'students'])->orderBy('order')->get();
        return view('admin.academic.stages', compact('stages'));
    }

    public function storeStage(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        Stage::create($request->only('name', 'description', 'order'));
        return back()->with('success', 'تم إضافة المرحلة الدراسية بنجاح.');
    }

    // Grades
    public function grades()
    {
        $stages = Stage::all();
        $grades = Grade::with('stage')->withCount(['classes', 'students'])->orderBy('order')->get();
        return view('admin.academic.grades', compact('stages', 'grades'));
    }

    public function storeGrade(Request $request)
    {
        $request->validate(['stage_id' => 'required|exists:stages,id', 'name' => 'required|string']);
        Grade::create($request->only('stage_id', 'name', 'order'));
        return back()->with('success', 'تم إضافة الصف الدراسي بنجاح.');
    }

    // Classes
    public function classes()
    {
        $grades = Grade::with('stage')->get();
        $servants = User::where('role', 'servant')->get();
        $classes = SchoolClass::with(['grade.stage', 'servants'])->withCount('students')->get();
        return view('admin.academic.classes', compact('grades', 'servants', 'classes'));
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'name' => 'required|string',
        ]);
        $class = SchoolClass::create($request->only('grade_id', 'name', 'room'));
        if ($request->has('servant_ids')) {
            $class->servants()->sync($request->servant_ids);
        }
        return back()->with('success', 'تم إنشاء الفصل وتعيين الخدام بنجاح.');
    }

    public function updateClass(Request $request, SchoolClass $class)
    {
        $class->update($request->only('name', 'room'));
        $servantIds = array_filter($request->input('servant_ids', []));
        $class->servants()->sync($servantIds);
        return back()->with('success', 'تم تحديث تعيين الخدام للفصل بنجاح.');
    }
}
