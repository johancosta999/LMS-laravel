@extends('app')
@push('title')
    Student Register
@endpush
@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-person-plus-fill"></i> Student management</div>
                <h1 class="page-title">Register a student</h1>
                <p class="page-subtitle">Create a student profile and keep their academic records organised.</p>
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
                    <h2>New student profile</h2>
                    <p>Enter the student's details below. You can update their information later from the student list.</p>
                    <div class="registration-checklist">
                        <div><i class="bi bi-check2-circle"></i><span>Use the student's official details</span></div>
                        <div><i class="bi bi-check2-circle"></i><span>Keep contact information current</span></div>
                        <div><i class="bi bi-shield-check"></i><span>Information is stored securely</span></div>
                    </div>
                </aside>
            </div>

            <div class="col-xl-8">
                <div class="card registration-form-card">
                    <div class="registration-form-heading">
                        <div>
                            <span class="registration-section-label">Student details</span>
                            <h2>Personal information</h2>
                        </div>
                        <span class="registration-required-note"><span>*</span> Required fields</span>
                    </div>

                    <form>
                        <div class="row g-4">
                            <div class="col-md-6">
                        <div class="mb-3">
                            <label for="reg_no" class="form-label">Register No: </label>
                            <input type="text" name="reg_no" placeholder="Enter registration number" class="form-control" id="reg_no">
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name: </label>
                            <input type="text" name="name" placeholder="Enter name with initials" class="form-control" id="name">
                        </div>

                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Enter phone number : </label>
                            <input type="text" name="phone_number" placeholder="Enter phone number" class="form-control" id="phone_number">
                        </div>
                            </div>

                            <div class="col-md-6">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Enter address: </label>
                            <input type="text" name="address" placeholder="Enter address" class="form-control" id="address">
                        </div>

                        <div class="mb-3">
                            <label for="birth_date" class="form-label">Enter birth date: </label>
                            <input type="date" name="birth_date" placeholder="Enter birth date" class="form-control" id="birth_date">
                        </div>
                            </div>

                            <div class="col-12">
                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>

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