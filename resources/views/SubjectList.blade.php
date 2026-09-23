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
                                <th scope="col">First</th>
                                <th scope="col">Last</th>
                                <th scope="col">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>@mdo</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Jacob</td>
                                <td>Thornton</td>
                                <td>@fat</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Larry</td>
                                <td>the Bird</td>
                                <td>@twitter</td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        </div>


    </div>
@endsection