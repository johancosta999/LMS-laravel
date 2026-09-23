@extends('app')

@push('title')
    Module Register
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-person-plus-fill"></i> Student management</div>
                <h1 class="page-title">Register a module</h1>
                <p class="page-subtitle">Create a module profile and keep its academic records organised.</p>
            </div>
            <a href="{{ route('admin.dasboard') }}" class="registration-back-link">
                <i class="bi bi-arrow-left"></i>
                <span>Back to dashboard</span>
            </a>
        </div>

        <div class="row g-4 align-items-stretch">
            <div class="col-xl-4">
                <aside class="registration-intro-card">
                    <div class="registration-icon"><i class="bi bi-person-vcard"></i></div>
                    <h2>New Module profile</h2>
                    <p>Enter the module's details below. You can update their information later from the module list.</p>
                    <div class="registration-checklist">
                        <div><i class="bi bi-check2-circle"></i><span>Use the module's official details</span></div>
                    </div>
                </aside>
            </div>

            <div class="col-xl-8">
                <div class="card registration-form-card">
                    <div class="registration-form-heading">
                        <div>
                            <span class="registration-section-label">Module details</span>
                            <h2>Module information</h2>
                        </div>
                        <span class="registration-required-note"><span>*</span> Required fields</span>
                    </div>

                    <form action="{{ route('admin.createModule') }}" method="post">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reg_no" class="form-label">Module Id: </label>
                                    <input type="text" name="module_id" placeholder="Enter module id" class="form-control"
                                        id="reg_no">
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Name: </label>
                                    <input type="text" name="name" placeholder="Enter name" class="form-control" id="name">
                                </div>

                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Number of lectures: </label>
                                    <input type="number" name="lectures_count" placeholder="Enter number of lectures"
                                        class="form-control" id="lectures_count">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Assigned lectures: </label>
                                    <input type="text" name="assigned_lecturers" class="form-control"
                                        id="assigned_lecturers" aria-describedby="emailHelp">
                                    <div id="assigned_lecturers" class="form-text">We'll never share your email with anyone
                                        else.
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection