@extends('app')

@push('title')
    Exam Report
@endpush

@push('css')
    <style>
        /* only print the report itself */
        @media print {
            .sidebar-wrapper, .navbar-custom, .no-print { display: none !important; }
            .main-wrapper { margin: 0 !important; padding: 0 !important; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-bar-chart"></i> Exam report</div>
                <h1 class="page-title">{{ $exam->title }}</h1>
                <p class="page-subtitle">
                    {{ $exam->subject->name ?? '-' }} • {{ $exam->exam_date }} •
                    Total {{ $exam->total_marks }} • Pass mark {{ $exam->pass_marks }}
                </p>
            </div>
            <div class="d-flex gap-2 no-print">
                <a href="{{ route('exam.marks', $exam->id) }}" class="registration-back-link">
                    <i class="bi bi-pencil-square"></i>
                    <span>Edit marks</span>
                </a>
                <button type="button" onclick="window.print()" class="registration-back-link border-0">
                    <i class="bi bi-printer"></i>
                    <span>Print</span>
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success no-print">{{ session('success') }}</div>
        @endif

        <!-- Summary cards -->
        <div class="row g-4 mb-4">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card card-stat h-100">
                    <span class="stat-label">Students marked</span>
                    <div class="stat-value">{{ $results->count() }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card card-stat h-100">
                    <span class="stat-label">Average</span>
                    <div class="stat-value">{{ $average }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card card-stat h-100">
                    <span class="stat-label">Highest</span>
                    <div class="stat-value">{{ $highest + 0 }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card card-stat h-100">
                    <span class="stat-label">Lowest</span>
                    <div class="stat-value">{{ $lowest + 0 }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card card-stat h-100">
                    <span class="stat-label">Passed</span>
                    <div class="stat-value text-success">{{ $passed }}</div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card card-stat h-100">
                    <span class="stat-label">Failed</span>
                    <div class="stat-value text-danger">{{ $failed }}</div>
                </div>
            </div>
        </div>

        <!-- Results table -->
        <div class="card registration-form-card">
            <div class="registration-form-heading">
                <div>
                    <span class="registration-section-label">Results</span>
                    <h2>Student results</h2>
                </div>
                @if ($results->count() > 0)
                    <span class="registration-required-note">Pass rate {{ round($passed / $results->count() * 100) }}%</span>
                @endif
            </div>

            @if ($results->isEmpty())
                <p class="text-muted mb-0">No marks entered yet.
                    <a href="{{ route('exam.marks', $exam->id) }}" class="no-print">Enter marks</a>
                </p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Rank</th>
                                <th scope="col">Register No</th>
                                <th scope="col">Name</th>
                                <th scope="col">Marks</th>
                                <th scope="col">Percentage</th>
                                <th scope="col">Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $result)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $result->student->reg_no ?? '-' }}</td>
                                    <td>{{ $result->student->name ?? '-' }}</td>
                                    <td>{{ $result->marks + 0 }} / {{ $exam->total_marks }}</td>
                                    <td>{{ round($result->marks / $exam->total_marks * 100) }}%</td>
                                    <td>
                                        @if ($result->marks >= $exam->pass_marks)
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
@endsection
