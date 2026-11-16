@extends('app')

@push('title')
    My Modules
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-collection"></i> Assigned modules</div>
                <h1 class="page-title">My modules</h1>
                <p class="page-subtitle">Modules you are enrolled in.</p>
            </div>
            <a href="{{ route('student.modules') }}" class="registration-back-link">
                <i class="bi bi-plus-lg"></i>
                <span>Enrol in a module</span>
            </a>
        </div>

        <div class="card registration-form-card">
            <div class="registration-form-heading">
                <div>
                    <span class="registration-section-label">Modules</span>
                    <h2>Enrolled modules</h2>
                </div>
            </div>

            @if ($modules->isEmpty())
                <p class="text-muted mb-0">
                    You are not enrolled in any modules yet.
                    <a href="{{ route('student.modules') }}">Browse modules</a> to enrol.
                </p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Module Id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Lecturers</th>
                                <th scope="col">Enrolled on</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modules as $module)
                                <tr>
                                    <td>{{ $module->module_id }}</td>
                                    <td>{{ $module->name }}</td>
                                    <td>{{ $module->assigned_lecturers }}</td>
                                    <td>{{ $module->pivot->created_at?->format('M j, Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('student.view_module', $module->id) }}"
                                            class="btn btn-primary btn-sm">Go to module</a>
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
