@extends('app')
@push('title')
    Student Dashboard
@endpush

@section('content')
        <!-- START: Dashboard Header Banner -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Welcome, {{ Auth::user()->name }}</h1>
                <p class="page-subtitle">Your student dashboard.</p>
            </div>
        </div>
        <!-- END: Dashboard Header Banner -->

        <!-- START: New Exam Notice -->
        @if ($newExams->isNotEmpty())
            <div class="exam-dark exam-notice" role="alert">
                <span class="exam-pill is-live"><i class="bi bi-megaphone-fill"></i> Exam notice</span>
                <h2 class="exam-notice-title">
                    {{ $newExams->count() === 1 ? 'A new exam has' : $newExams->count() . ' new exams have' }}
                    been scheduled in your modules
                </h2>
                <ul class="exam-notice-list">
                    @foreach ($newExams as $exam)
                        <li class="exam-notice-item">
                            <span class="badge-new">New</span>
                            <strong>{{ $exam->title }}</strong>
                            <span class="meta">
                                {{ $exam->subject->name ?? 'Unknown module' }}
                                &middot; {{ \Carbon\Carbon::parse($exam->exam_date)->format('M jS Y') }}
                                &middot; {{ $exam->duration_minutes }} min
                            </span>
                            <a href="{{ route('student.exam.show', $exam->id) }}" class="exam-notice-link">
                                View exam <i class="bi bi-arrow-right"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
                @include('component.exam-shape')
            </div>
        @endif
        <!-- END: New Exam Notice -->

        <div class="row g-4">

            <!-- TOP AREA: Stat Cards Row -->
            <div class="col-12">
                <div class="row g-4">
                    <!-- Card 1: Green Banner -->
                    <div class="col-md-4">
                        <div class="card alert-green-card">
                            <div class="position-relative z-index-2">
                                <span class="alert-green-badge">Student</span>
                                <div class="alert-green-date">{{ date('M jS Y') }}</div>
                                <div class="alert-green-text">Have a great day of learning!</div>
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

                    <!-- Card 2: Register No -->
                    <div class="col-md-4">
                        <div class="card card-stat">
                            <div class="card-header">
                                <span class="stat-label">Register No</span>
                            </div>
                            <div class="stat-value">{{ $student->reg_no ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Card 3: Email -->
                    <div class="col-md-4">
                        <div class="card card-stat">
                            <div class="card-header">
                                <span class="stat-label">Email</span>
                            </div>
                            <div class="stat-value fs-5">{{ Auth::user()->email }}</div>
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

                    @if ($student)
                        <div class="transaction-list">
                            <div class="transaction-item">
                                <div class="transaction-icon bg-forest-light text-lime">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="transaction-info">
                                    <div class="transaction-name">Name</div>
                                    <div class="transaction-date">{{ $student->name }}</div>
                                </div>
                            </div>

                            <div class="transaction-item">
                                <div class="transaction-icon bg-forest-light text-lime">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div class="transaction-info">
                                    <div class="transaction-name">Phone Number</div>
                                    <div class="transaction-date">{{ $student->phone_number }}</div>
                                </div>
                            </div>

                            <div class="transaction-item">
                                <div class="transaction-icon bg-forest-light text-lime">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="transaction-info">
                                    <div class="transaction-name">Address</div>
                                    <div class="transaction-date">{{ $student->address }}</div>
                                </div>
                            </div>

                            <div class="transaction-item">
                                <div class="transaction-icon bg-forest-light text-lime">
                                    <i class="bi bi-calendar"></i>
                                </div>
                                <div class="transaction-info">
                                    <div class="transaction-name">Birth Date</div>
                                    <div class="transaction-date">{{ $student->birth_date }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">No student record found for this account.</p>
                    @endif
                </div>
            </div>

            <!-- RIGHT AREA: Photo + Banner -->
            <div class="col-xl-3 col-lg-4">
                <div class="d-flex flex-column gap-4 h-100">
                    <div class="card text-center mb-0">
                        @if ($student && $student->image)
                            <img src="{{ asset('storage/' . $student->image) }}" alt="Profile photo"
                                class="rounded-circle mx-auto mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        @endif
                        <h2 class="card-title">{{ Auth::user()->name }}</h2>
                        <p class="text-muted mb-0">Student</p>
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

                        <h3 class="promo-title">Keep learning every day.</h3>
                        <p class="promo-desc">Check your modules and exams regularly.</p>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
