@extends('layouts.app')

@section('content')
    <style>
        .toast-success {
            background-color: #007bff !important;
            color: white !important;
        }
    </style>
    <div class="container">
        <a href="{{ route('students.create') }}" class="btn btn-primary mb-3">Add Student</a>
        <table class="table table-bordered" id="studentTable">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Admission Date</th>
                    <th>Yearly Fees</th>
                    <th>Class Teacher</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td>{{ $student->student_name }}</td>
                        <td>{{ $student->class }}</td>
                        <td>{{ $student->admission_date }}</td>
                        <td>{{ $student->yearly_fees }}</td>
                        <td>{{ $student->teacher->teacher_name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $student->id }}"
                                data-name="{{ $student->student_name }}">
                                Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <strong id="studentName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $('#studentTable').DataTable({
            "processing": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true
        });
    </script>
   
    <script>
        $(document).ready(function() {
            $('.delete-btn').on('click', function() {
                const studentId = $(this).data('id');
                const studentName = $(this).data('name');

                $('#studentName').text(studentName);

                $('#confirmDeleteBtn').data('id', studentId);

                $('#deleteModal').modal('show');
            });

            $('#confirmDeleteBtn').on('click', function() {
                const studentId = $(this).data('id');

                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                if (!csrfToken) {
                    console.error('CSRF token not found!');
                    return;
                }

                $.ajax({
                    url: `/students/${studentId}`,
                    method: 'DELETE',
                    data: {
                        _token: csrfToken
                    },
                    success: function(data) {
                        if (data.status_code == 200) {
                            $('#deleteModal').modal('hide');

                            $(`button[data-id="${studentId}"]`).closest('tr').remove();

                            toastr.success("Student deleted successfully!");
                        } else {
                            toastr.error("Error deleting the student.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        toastr.error("There was an error processing the request.");
                    }
                });
            });
        });
    </script>
@endsection
