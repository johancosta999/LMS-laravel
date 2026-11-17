@extends('app')

@push('title')
    {{ $module->name }}
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-journal-bookmark"></i> {{ $module->module_id }}</div>
                <h1 class="page-title">{{ $module->name }}</h1>
                <p class="page-subtitle">Lecturers: {{ $module->assigned_lecturers }}</p>
            </div>
            <a href="{{ route('student.assigned_modules') }}" class="registration-back-link">
                <i class="bi bi-arrow-left"></i>
                <span>Back to my modules</span>
            </a>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card card-stat">
                    <div class="card-header">
                        <span class="stat-label">Number of lectures</span>
                    </div>
                    <div class="stat-value">{{ $module->lectures_count }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-stat">
                    <div class="card-header">
                        <span class="stat-label">Exams</span>
                    </div>
                    <div class="stat-value">{{ $exams->count() }}</div>
                </div>
            </div>

            <div class="col-12">
                <div class="card registration-form-card">
                    <div class="registration-form-heading">
                        <div>
                            <span class="registration-section-label">Exams</span>
                            <h2>Exams &amp; my results</h2>
                        </div>
                    </div>

                    @if ($exams->isEmpty())
                        <p class="text-muted mb-0">No exams have been scheduled for this module yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Total / Pass</th>
                                        <th scope="col">My marks</th>
                                        <th scope="col">Result</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($exams as $exam)
                                        @php $mark = $marks[$exam->id] ?? null; @endphp
                                        <tr @if ($exam->isNew()) class="table-warning" @endif>
                                            <td>
                                                {{ $exam->title }}
                                                @if ($exam->isNew())
                                                    <span class="badge bg-danger ms-1">New</span>
                                                @endif
                                            </td>
                                            <td>{{ $exam->exam_date }}</td>
                                            <td>{{ $exam->total_marks }} / {{ $exam->pass_marks }}</td>
                                            <td>{{ $mark ?? '-' }}</td>
                                            <td>
                                                @if ($mark === null)
                                                    <span class="badge bg-secondary">Not marked</span>
                                                @elseif ($mark >= $exam->pass_marks)
                                                    <span class="badge bg-success">Pass</span>
                                                @else
                                                    <span class="badge bg-danger">Fail</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
