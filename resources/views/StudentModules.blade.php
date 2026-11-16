@extends('app')

@push('title')
    Modules
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-journal-bookmark"></i> Modules</div>
                <h1 class="page-title">All modules</h1>
                <p class="page-subtitle">Enter the enrolment key given by your lecturer to enrol in a module.</p>
            </div>
            <a href="{{ route('student.dashboard') }}" class="registration-back-link">
                <i class="bi bi-arrow-left"></i>
                <span>Back to dashboard</span>
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card registration-form-card">
            <div class="registration-form-heading">
                <div>
                    <span class="registration-section-label">Modules</span>
                    <h2>Module list</h2>
                </div>
            </div>

            @if ($modules->isEmpty())
                <p class="text-muted mb-0">No modules have been registered yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Module Id</th>
                                <th scope="col">Name</th>
                                <th scope="col"># of Lectures</th>
                                <th scope="col">Lecturers</th>
                                <th scope="col">Enrolment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modules as $module)
                                <tr>
                                    <td>{{ $module->module_id }}</td>
                                    <td>{{ $module->name }}</td>
                                    <td>{{ $module->lectures_count }}</td>
                                    <td>{{ $module->assigned_lecturers }}</td>
                                    <td>
                                        @if (in_array($module->id, $enrolledIds))
                                            <span class="badge bg-success">Enrolled</span>
                                        @else
                                            <form action="{{ route('student.enrol', $module->id) }}" method="post"
                                                class="d-flex gap-2">
                                                @csrf
                                                <input type="password" name="enrol_key" placeholder="Enrolment key"
                                                    class="form-control form-control-sm" required>
                                                <button type="submit" class="btn btn-primary btn-sm">Enrol</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
