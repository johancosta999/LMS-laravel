@extends('app')

@push('title')
    My Exams
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-journal-text"></i> Exam management</div>
                <h1 class="page-title">My exams</h1>
                <p class="page-subtitle">Enter marks and view the report for each exam.</p>
            </div>
            <a href="{{ route('exam.exam_create') }}" class="registration-back-link">
                <i class="bi bi-plus-lg"></i>
                <span>Create exam</span>
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card registration-form-card">
            <div class="registration-form-heading">
                <div>
                    <span class="registration-section-label">Exams</span>
                    <h2>Exam list</h2>
                </div>
            </div>

            @if ($exams->isEmpty())
                <p class="text-muted mb-0">You have not created any exams yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Module</th>
                                <th scope="col">Date</th>
                                <th scope="col">Total / Pass</th>
                                <th scope="col">Marked</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($exams as $exam)
                                <tr>
                                    <td>{{ $exam->title }}</td>
                                    <td>{{ $exam->subject->name ?? '-' }}</td>
                                    <td>{{ $exam->exam_date }}</td>
                                    <td>{{ $exam->total_marks }} / {{ $exam->pass_marks }}</td>
                                    <td>{{ $exam->results_count }} students</td>
                                    <td>
                                        <a href="{{ route('exam.marks', $exam->id) }}" class="btn btn-primary btn-sm">Marks</a>
                                        <a href="{{ route('exam.report', $exam->id) }}" class="btn btn-success btn-sm">Report</a>
                                        <a href="{{ route('exam.edit', $exam->id) }}" class="btn btn-warning btn-sm">Update</a>
                                        <a href="{{ route('exam.delete', $exam->id) }}" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this exam and all its marks?')">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
