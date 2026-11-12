@extends('app')

@push('title')
    Update Exam
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-journal-text"></i> Exam management</div>
                <h1 class="page-title">Update exam</h1>
                <p class="page-subtitle">Change the details of {{ $exam->title }}.</p>
            </div>
            <a href="{{ route('exam.exam_list') }}" class="registration-back-link">
                <i class="bi bi-arrow-left"></i>
                <span>Back to exams</span>
            </a>
        </div>

        <div class="card registration-form-card">
            <div class="registration-form-heading">
                <div>
                    <span class="registration-section-label">Exam details</span>
                    <h2>Exam information</h2>
                </div>
                <span class="registration-required-note"><span>*</span> Required fields</span>
            </div>

            <form action="{{ route('exam.update') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $exam->id }}">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="title" class="form-label">Exam title: *</label>
                            <input type="text" name="title" value="{{ old('title', $exam->title) }}" class="form-control" id="title">
                            @error('title')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject_id" class="form-label">Module: *</label>
                            <select name="subject_id" id="subject_id" class="form-select">
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected(old('subject_id', $exam->subject_id) == $subject->id)>
                                        {{ $subject->module_id }} - {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="exam_date" class="form-label">Exam date: *</label>
                            <input type="date" name="exam_date" value="{{ old('exam_date', $exam->exam_date) }}" class="form-control" id="exam_date">
                            @error('exam_date')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="total_marks" class="form-label">Total marks: *</label>
                            <input type="number" name="total_marks" value="{{ old('total_marks', $exam->total_marks) }}" class="form-control" id="total_marks">
                            @error('total_marks')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pass_marks" class="form-label">Pass mark: *</label>
                            <input type="number" name="pass_marks" value="{{ old('pass_marks', $exam->pass_marks) }}" class="form-control" id="pass_marks">
                            @error('pass_marks')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description:</label>
                            <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $exam->description) }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Update exam</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
