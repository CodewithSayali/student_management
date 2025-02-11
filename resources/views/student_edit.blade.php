@extends('layouts.app')

@section('title', 'Edit Student')

@section('content')
    <style>
        .toast-success {
            background-color: #007bff !important;
            color: white !important;
        }
        .error {
        color: red;
        font-size: 14px;
    }
    </style>
    <div class="container">
        <h2 class="mb-3">Edit Student</h2>

        <div id="alert-message" class="alert d-none"></div>

        <form id="editStudentForm">
            @csrf

            <input type="hidden" id="student_id" name="student_id" value="{{ $student->id }}">

            <div class="mb-3">
                <label for="student_name" class="form-label">Student Name</label>
                <input type="text" class="form-control" id="student_name" name="student_name"
                    value="{{ $student->student_name }}" required>
            </div>

            <div class="mb-3">
                <label for="class" class="form-label">Class</label>
                <input type="text" class="form-control" id="class" name="class" value="{{ $student->class }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="admission_date" class="form-label">Admission Date</label>
                <input type="date" class="form-control" id="admission_date" name="admission_date"
                    value="{{ $student->admission_date }}" required>
            </div>

            <div class="mb-3">
                <label for="yearly_fees" class="form-label">Yearly Fees</label>
                <input type="number" class="form-control" id="yearly_fees" name="yearly_fees"
                    value="{{ $student->yearly_fees }}" required>
            </div>

            <div class="mb-3">
                <label for="class_teacher_id" class="form-label">Class Teacher</label>
                <select class="form-select" id="class_teacher_id" name="class_teacher_id" required>
                    <option value="">Select Teacher</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ $student->class_teacher_xid == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->teacher_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Student</button>
            <a href="{{ route('students') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#editStudentForm").validate({
                rules: {
                    student_name: {
                        required: true,
                        minlength: 3
                    },
                    class: {
                        required: true
                    },
                    admission_date: {
                        required: true,
                        date: true
                    },
                    yearly_fees: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    class_teacher_id: {
                        required: true
                    }
                },
                messages: {
                    student_name: {
                        required: "Student name is required",
                        minlength: "Minimum 3 characters required"
                    },
                    class: {
                        required: "Class is required"
                    },
                    admission_date: {
                        required: "Admission date is required",
                        date: "Enter a valid date"
                    },
                    yearly_fees: {
                        required: "Yearly fees are required",
                        number: "Enter a valid number",
                        min: "Fees must be positive"
                    },
                    class_teacher_id: {
                        required: "Please select a class teacher"
                    }
                },
                submitHandler: function(form) {
                    $.ajax({
                        url: "{{ route('students.update', $student->id) }}",
                        type: "POST",
                        data: $("#editStudentForm").serialize(),
                        beforeSend: function() {
                            $("button[type='submit']").attr("disabled", true).text(
                                "Updating...");
                        },
                        success: function(response) {
                            toastr.success("Student updated successfully!");
                            $("#editStudentForm")[0].reset();
                            setTimeout(() => {
                                window.location.href =
                                    "{{ route('students') }}";
                            }, 2000);
                        },
                        error: function(xhr) {
                            $("button[type='submit']").attr("disabled", false).text(
                                "Update Student");
                            let errors = xhr.responseJSON.errors;
                            let errorMsg = "<ul>";
                            $.each(errors, function(key, value) {
                                errorMsg += "<li>" + value + "</li>";
                            });
                            errorMsg += "</ul>";
                            $("#alert-message").removeClass("d-none alert-success")
                                .addClass("alert-danger").html(errorMsg);
                        }
                    });
                }
            });
        });
    </script>
@endsection
