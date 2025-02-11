<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Exports\StudentsExport;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $students = Student::with('teacher')->get();
        return view('index', compact('students'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::all();
        return view('student_create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
   

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'class' => 'required|string|max:100',
            'admission_date' => 'required|date',
            'yearly_fees' => 'required|numeric|min:0',
            'class_teacher_id' => 'required|exists:teachers,id',
        ]);

        $student = new Student();
        $student->student_name = $validated['student_name'];
        $student->class = $validated['class'];
        $student->admission_date = $validated['admission_date'];
        $student->yearly_fees = $validated['yearly_fees'];
        $student->class_teacher_xid = $validated['class_teacher_id']; // Assign to correct column
        $student->save();

        return response()->json(['success' => true, 'message' => 'Student added successfully!'], 200);
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $teachers = Teacher::all();
        return view('student_edit', compact('student', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'class_teacher_id' => 'required|exists:teachers,id',
            'class' => 'required|string|max:50',
            'admission_date' => 'required|date',
            'yearly_fees' => 'required|numeric|min:0',
        ]);

        $student = Student::findOrFail($id);
        $student->update($request->all());

        return redirect()->route('students')->with('success', 'Student updated successfully!');
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if ($student) {
            $student->delete();
            return response()->json([
                'status_code' => 200,
                'message' => 'Student deleted successfully!'
            ], 200);
        }

        return response()->json(['message' => 'Student not found'], 404);
    }

    public function exportStudents()
    {
        $exporter = new StudentsExport();
        return $exporter->export();
    }
}
