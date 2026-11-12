@extends('app')
@push('title')
    Lecturer Dashboard
@endpush

@section('content')
        <!-- START: Dashboard Header Banner -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Welcome, {{ Auth::user()->name }}</h1>
                <p class="page-subtitle">Your lecturer dashboard.</p>
            </div>
        </div>
        <!-- END: Dashboard Header Banner -->

        <div class="row g-4">

            <!-- TOP AREA: Stat Cards Row -->
            <div class="col-12">
                <div class="row g-4">
                    <!-- Card 1: Green Banner -->
                    <div class="col-md-4">
                        <div class="card alert-green-card">
                            <div class="position-relative z-index-2">
                                <span class="alert-green-badge">Lecturer</span>
                                <div class="alert-green-date">{{ date('M jS Y') }}</div>
                                <div class="alert-green-text">Have a great day of teaching!</div>
                            </div>

                            <svg class="alert-green-bg-shape" viewBox="0 0 100 100" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g transform="translate(50,50)">
                                    <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6" fill="#B4F105" />
                                    <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6" fill="#B4F105"
                                        transform="rotate(60)" />
                                    <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6" fill="#B4F105"
                                        transform="rotate(120)" />
                                </g>
                            </svg>
                        </div>
                    </div>

                    <!-- Card 2: Lecturer Id -->
                    <div class="col-md-4">
                        <div class="card card-stat">
                            <div class="card-header">
                                <span class="stat-label">Lecturer Id</span>
                            </div>
                            <div class="stat-value">{{ $teacher->lecturer_id ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Card 3: Exams -->
                    <div class="col-md-4">
                        <div class="card card-stat">
                            <div class="card-header">
                                <span class="stat-label">My Exams</span>
                                <a href="{{ route('exam.exam_list') }}" class="card-more-btn" title="My exams">
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </div>
                            <div class="stat-value">{{ $examCount }}</div>
                        </div>
                    </div>

                    <!-- Recent Exams -->
                    <div class="col-12">
                        <div class="card mb-0">
                            <div class="card-header">
                                <h2 class="card-title">Recent Exams</h2>
                                <a href="{{ route('exam.exam_create') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-plus-lg"></i> Create exam
                                </a>
                            </div>

                            @if ($exams->isEmpty())
                                <p class="text-muted mb-0">No exams yet. Create your first exam to start adding marks.</p>
                            @else
                                <div class="transaction-list">
                                    @foreach ($exams as $exam)
                                        <div class="transaction-item">
                                            <div class="transaction-icon bg-forest-light text-lime">
                                                <i class="bi bi-journal-text"></i>
                                            </div>
                                            <div class="transaction-info">
                                                <div class="transaction-name">{{ $exam->title }}</div>
                                                <div class="transaction-date">{{ $exam->subject->name ?? '-' }} • {{ $exam->exam_date }}</div>
                                            </div>
                                            <div>
                                                <a href="{{ route('exam.marks', $exam->id) }}" class="btn btn-primary btn-sm">Marks</a>
                                                <a href="{{ route('exam.report', $exam->id) }}" class="btn btn-success btn-sm">Report</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: TOP AREA -->

            <!-- LEFT AREA: Profile Details -->
            <div class="col-xl-9 col-lg-8">
                <div class="card h-100">
                    <div class="card-header">
                        <h2 class="card-title">My Details</h2>
                    </div>

                    @if ($teacher)
                        <div class="transaction-list">
                            <div class="transaction-item">
                                <div class="transaction-icon bg-forest-light text-lime">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="transaction-info">
                                    <div class="transaction-name">Name</div>
                                    <div class="transaction-date">{{ $teacher->name }}</div>
                                </div>
                            </div>

                            <div class="transaction-item">
                                <div class="transaction-icon bg-forest-light text-lime">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div class="transaction-info">
                                    <div class="transaction-name">Email</div>
                                    <div class="transaction-date">{{ $teacher->email }}</div>
                                </div>
                            </div>

                            <div class="transaction-item">
                                <div class="transaction-icon bg-forest-light text-lime">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div class="transaction-info">
                                    <div class="transaction-name">Phone Number</div>
                                    <div class="transaction-date">{{ $teacher->phone_number }}</div>
                                </div>
                            </div>

                            <div class="transaction-item">
                                <div class="transaction-icon bg-forest-light text-lime">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="transaction-info">
                                    <div class="transaction-name">Address</div>
                                    <div class="transaction-date">{{ $teacher->address }}</div>
                                </div>
                            </div>

                            <div class="transaction-item">
                                <div class="transaction-icon bg-forest-light text-lime">
                                    <i class="bi bi-journal-bookmark"></i>
                                </div>
                                <div class="transaction-info">
                                    <div class="transaction-name">Modules</div>
                                    <div class="transaction-date">{{ $teacher->subjects }}</div>
                                </div>
                            </div>

                            <div class="transaction-item">
                                <div class="transaction-icon bg-forest-light text-lime">
                                    <i class="bi bi-hourglass"></i>
                                </div>
                                <div class="transaction-info">
                                    <div class="transaction-name">Age</div>
                                    <div class="transaction-date">{{ $teacher->age }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">No lecturer record found for this account.</p>
                    @endif
                </div>
            </div>

            <!-- RIGHT AREA: Banner -->
            <div class="col-xl-3 col-lg-4">
                <div class="d-flex flex-column gap-4 h-100">
                    <div class="card text-center mb-0">
                        <h2 class="card-title">{{ Auth::user()->name }}</h2>
                        <p class="text-muted mb-0">Lecturer</p>
                    </div>

                    <div class="promo-banner-card">
                        <svg class="promo-banner-bg-shape" viewBox="0 0 100 100" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g transform="translate(50,50)">
                                <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6" fill="#B4F105" />
                                <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6" fill="#B4F105"
                                    transform="rotate(60)" />
                                <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6" fill="#B4F105"
                                    transform="rotate(120)" />
                            </g>
                        </svg>

                        <h3 class="promo-title">Inspire your students.</h3>
                        <p class="promo-desc">Create exams, enter marks and check the results.</p>
                        <a href="{{ route('exam.exam_create') }}" class="btn-promo d-inline-block text-decoration-none">Create an exam</a>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
