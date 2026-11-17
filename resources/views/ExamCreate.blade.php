@extends('app')

@push('title')
    Create Exam
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-journal-text"></i> Exam management</div>
                <h1 class="page-title">Create an exam</h1>
                <p class="page-subtitle">Add an exam for one of the modules.</p>
            </div>
            <a href="{{ route('exam.exam_list') }}" class="registration-back-link">
                <i class="bi bi-arrow-left"></i>
                <span>Back to exams</span>
            </a>
        </div>

        <div class="row g-4 align-items-stretch">
            <div class="col-xl-4">
                <aside class="registration-intro-card">
                    <div class="registration-icon"><i class="bi bi-journal-plus"></i></div>
                    <h2>New exam</h2>
                    <p>After creating the exam you can enter the students' marks and view the report.</p>
                    <div class="registration-checklist">
                        <div><i class="bi bi-check2-circle"></i><span>Pick the correct module</span></div>
                        <div><i class="bi bi-check2-circle"></i><span>Pass mark must not be higher than total marks</span></div>
                        <div><i class="bi bi-check2-circle"></i><span>Set an enrolment key and a duration</span></div>
                        <div><i class="bi bi-check2-circle"></i><span>Upload a PDF paper or build an online quiz (auto-marked)</span></div>
                    </div>
                </aside>
            </div>

            <div class="col-xl-8">
                <div class="card registration-form-card">
                    <div class="registration-form-heading">
                        <div>
                            <span class="registration-section-label">Exam details</span>
                            <h2>Exam information</h2>
                        </div>
                        <span class="registration-required-note"><span>*</span> Required fields</span>
                    </div>

                    @if ($subjects->isEmpty())
                        <div class="alert alert-warning">No modules found. Ask the admin to add a module first.</div>
                    @endif

                    <form action="{{ route('exam.createExam') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Exam title: *</label>
                                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Mid term exam"
                                        class="form-control" id="title">
                                    @error('title')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="subject_id" class="form-label">Module: *</label>
                                    <select name="subject_id" id="subject_id" class="form-select">
                                        <option value="">Select a module</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>
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
                                    <input type="date" name="exam_date" value="{{ old('exam_date') }}" class="form-control" id="exam_date">
                                    @error('exam_date')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_marks" class="form-label">Full marks: *</label>
                                    <input type="number" name="total_marks" value="{{ old('total_marks', 100) }}" class="form-control" id="total_marks">
                                    @error('total_marks')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="pass_marks" class="form-label">Pass mark: *</label>
                                    <input type="number" name="pass_marks" value="{{ old('pass_marks', 40) }}" class="form-control" id="pass_marks">
                                    @error('pass_marks')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Rules / instructions:</label>
                                    <textarea name="description" id="description" rows="4" class="form-control"
                                        placeholder="One rule per line, e.g.&#10;Answer all questions&#10;No calculators allowed">{{ old('description') }}</textarea>
                                    <div class="form-text">Each line is shown to students as a numbered rule before they start.</div>
                                </div>
                            </div>

                            @include('component.exam-delivery-fields', ['exam' => null])

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Create exam</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
