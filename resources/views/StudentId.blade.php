@extends('app')
@push('title')
    Student List
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="row">
                @foreach ($students as $student)
                    <div class="col-3 mb-5">
                        <div class="card" style="width: 250px;">
                            <img class="card-img-top student-id-image" src="{{asset('storage/'.$student ->image)}}" alt="Student photo">
                            <div class="card-body">
                                <h5 class="card-title">{{ $student->reg_no }}</h5>
                                <h5 class="card-title">{{ $student->name }}</h5>
                                <p class="card-text">{{ $student->phone_number }}</p>
                                <p class="card-text">{{ $student->birth_date }}</p>
                                <a href="#" class="btn btn-primary">Print</a>
                            </div>
                        </div>
                    </div>  
                @endforeach


            </div>
        </div>
    </div>
@endsection