@extends('app')

@push('title')
    Lecturer List
@endpush

@section('content')
<div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-person-plus-fill"></i>Teacher management</div>
                <h1 class="page-title">Registered lecturer</h1>
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
                        <span class="registration-section-label">Lecturer details</span>
                        <h2>Lecturer information</h2>
                    </div>
                    
                </div>
                <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Lecturer Id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">Email</th>
                                <th scope="col">Address</th>
                                <th scope="col">Age</th>
                                <th scope="col">Subjects</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teachers as $teacher)
                                <tr>
                                    <td>{{ $teacher->id }}</td>
                                    <td>{{ $teacher->lecturer_id }}</td>
                                    <td>{{ $teacher->name }}</td>
                                    <td>{{ $teacher->phone_number }}</td>
                                    <td>{{ $teacher->email }}</td>
                                    <td>{{ $teacher->address }}</td>
                                    <td>{{ $teacher->age }}</td>
                                    <td>{{ $teacher->subjects }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>


    </div>
@endsection