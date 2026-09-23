@extends('app')
@push('title')
    Student List
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-person-plus-fill"></i> Student management</div>
                <h1 class="page-title">Registered students</h1>
                <p class="page-subtitle">Information of all students registered.</p>
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
                        <span class="registration-section-label">Student details</span>
                        <h2>Student information</h2>
                    </div>
                    
                </div>
                <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Registration No</th>
                                <th scope="col">Phone No</th>
                                <th scope="col">Email</th>
                                <th scope="col">Address</th>
                                <th scope="col">Date of Birth</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                            <tr>
                                <td>{{ $student ->id }}</td>
                                <td>{{ $student ->reg_no }}</td>
                                <td>{{ $student ->phone_number }}</td>
                                <td>{{ $student ->email }}</td>
                                <td>{{ $student ->address }}</td>
                                <td>{{ $student ->birth_date }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>


    </div>



@endsection