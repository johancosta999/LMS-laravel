@extends('app')

@push('title')
    Module List
@endpush

@section('content')
<div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-person-plus-fill"></i>Modules management</div>
                <h1 class="page-title">Registered modules</h1>
                <p class="page-subtitle">Information of all lecturers registered.</p>
            </div>
            <a href="{{ route('admin.dasboard') }}" class="registration-back-link">
                <i class="bi bi-arrow-left"></i>
                <span>Back to dashboard</span>
            </a>
        </div>

        <div class="col-12">
            <div class="card registration-form-card">
                <div class="registration-form-heading">
                    <div>
                        <span class="registration-section-label">Module details</span>
                        <h2>Module information</h2>
                    </div>
                    
                </div>
                <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Module Id</th>
                                <th scope="col">Name</th>
                                <th scope="col"># of Lectures</th>
                                <th scope="col">Assigned Lecturers</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modules as $module)
                                <tr>
                                <td>{{ $module->id }}</td>
                                <td>{{ $module->module_id }}</td>
                                <td>{{ $module->name }}</td>
                                <td>{{ $module->lectures_count }}</td>
                                <td>{{ $module->assigned_lecturers }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>


    </div>
@endsection