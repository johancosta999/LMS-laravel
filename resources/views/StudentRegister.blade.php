@extends('app')
@push('title')
    Student Register
@endpush
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h2>Student Registration</h2>
                <hr>
                <div>
                    <form>
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
                            <input type="date" name="birth_date" placeholder="Enter birth_date" class="form-control" id="birth_date">
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputPassword1" class="form-label">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection