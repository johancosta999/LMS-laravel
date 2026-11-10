@extends('app')

@push('title')
    Student List
@endpush

@push('page_header_title')
    Students
@endpush

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
@endpush

@push('script')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable({
                pageLength: 5,
                lengthMenu: [5, 10, 25, 50, 100],
                ordering: true,
                searching: true,
                columnDefs: [{ orderable: false, searchable: false, targets: -1 }]
            });
        });
    </script>
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-person-plus-fill"></i> Student management</div>
                <h1 class="page-title">@stack('page_header_title')</h1>
                <p class="page-subtitle">Information of all students registered.</p>
            </div>
            <div class="registration-header-actions">
                <a href="{{ route('admin.dasboard') }}" class="registration-back-link">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to dashboard</span>
                </a>
                <a href="{{ route('student.idCard') }}" class="registration-back-link">
                    <i class="bi"></i>
                    <span>View Student IDs</span>
                </a>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Student information</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="studentsTable" class="table table-striped table-bordered table-hover w-100">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->address }}</td>
                                    <td>{{ $student->phone_number }}</td>
                                    <td>
                                        <a href="{{ route('student.edit', $student->id) }}"
                                            class="btn btn-warning btn-sm">Update</a>
                                        <a href="{{ route('student.delete', $student->id) }}"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
