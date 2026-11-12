@extends('app')
@push('title')
    Admin Dashboard
@endpush

@section('content')
        <!-- START: Dashboard Header Banner -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Overview of students, lecturers and modules.</p>
            </div>
        </div>
        <!-- END: Dashboard Header Banner -->

        <div class="row g-4">

            <!-- TOP AREA: Stat Cards Row -->
            <div class="col-12">
                <div class="row g-4">
                    <!-- Card 1: Green Banner (today + this month summary) -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card alert-green-card">
                            <div class="position-relative z-index-2">
                                <span class="alert-green-badge">This month</span>
                                <div class="alert-green-date">{{ now()->format('M jS Y') }}</div>
                                <div class="alert-green-text">
                                    {{ $newStudents }} new {{ Str::plural('student', $newStudents) }} registered this month
                                </div>
                            </div>
                            <a href="{{ route('student.student_list') }}" class="alert-green-link z-index-2">
                                <span>View students</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>

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

                    <!-- Card 2: Students -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card card-stat">
                            <div class="card-header">
                                <span class="stat-label">Total Students</span>
                                <a href="{{ route('student.student_list') }}" class="card-more-btn" title="Student list">
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </div>
                            <div class="stat-value">{{ $studentCount }}</div>
                            <div class="trend-badge trend-up">
                                <i class="bi bi-plus-lg"></i>
                                <span>{{ $newStudents }} this month</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: Lecturers -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card card-stat">
                            <div class="card-header">
                                <span class="stat-label">Total Lecturers</span>
                                <a href="{{ route('teacher.teacher_list') }}" class="card-more-btn" title="Lecturer list">
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </div>
                            <div class="stat-value">{{ $teacherCount }}</div>
                            <div class="trend-badge trend-up">
                                <i class="bi bi-plus-lg"></i>
                                <span>{{ $newTeachers }} this month</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4: Modules -->
                    <div class="col-md-6 col-xl-3">
                        <div class="card card-stat">
                            <div class="card-header">
                                <span class="stat-label">Total Modules</span>
                                <a href="{{ route('subject.module_list') }}" class="card-more-btn" title="Module list">
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>
                            </div>
                            <div class="stat-value">{{ $moduleCount }}</div>
                            <div class="trend-badge trend-up">
                                <i class="bi bi-plus-lg"></i>
                                <span>{{ $newModules }} this month</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: TOP AREA -->

            <!-- LEFT AREA: Recent lists -->
            <div class="col-xl-9 col-lg-8">
                <div class="row g-4">

                    <!-- Recent Students (full width) -->
                    <div class="col-12">
                        <div class="card mb-0">
                            <div class="card-header">
                                <h2 class="card-title">Recently Added Students</h2>
                                <a href="{{ route('student.student_list') }}" class="btn btn-sm btn-outline-secondary">View all</a>
                            </div>

                            @if ($recentStudents->isEmpty())
                                <p class="text-muted mb-0">No students registered yet.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">Student</th>
                                                <th scope="col">Register No</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Phone</th>
                                                <th scope="col">Added</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recentStudents as $student)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <img src="{{ asset('storage/' . $student->image) }}" alt=""
                                                                class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;"
                                                                onerror="this.src='{{ asset('assets/images/avatar.png') }}'">
                                                            <span>{{ $student->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $student->reg_no }}</td>
                                                    <td>{{ $student->email }}</td>
                                                    <td>{{ $student->phone_number }}</td>
                                                    <td class="text-muted">{{ $student->created_at?->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Lecturers -->
                    <div class="col-md-6 d-flex flex-column">
                        <div class="card h-100 flex-grow-1">
                            <div class="card-header">
                                <h2 class="card-title">Recently Added Lecturers</h2>
                                <a href="{{ route('teacher.teacher_list') }}" class="btn btn-sm btn-outline-secondary">View all</a>
                            </div>

                            @if ($recentTeachers->isEmpty())
                                <p class="text-muted mb-0">No lecturers registered yet.</p>
                            @else
                                <div class="transaction-list">
                                    @foreach ($recentTeachers as $teacher)
                                        <div class="transaction-item">
                                            <div class="transaction-icon bg-forest-light text-lime">
                                                <i class="bi bi-person-badge"></i>
                                            </div>
                                            <div class="transaction-info">
                                                <div class="transaction-name">{{ $teacher->name }}</div>
                                                <div class="transaction-date">{{ $teacher->lecturer_id }} • {{ $teacher->subjects }}</div>
                                            </div>
                                            <div class="transaction-amount text-muted fs-xs">{{ $teacher->created_at?->diffForHumans() }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Modules -->
                    <div class="col-md-6 d-flex flex-column">
                        <div class="card h-100 flex-grow-1">
                            <div class="card-header">
                                <h2 class="card-title">Recently Added Modules</h2>
                                <a href="{{ route('subject.module_list') }}" class="btn btn-sm btn-outline-secondary">View all</a>
                            </div>

                            @if ($recentModules->isEmpty())
                                <p class="text-muted mb-0">No modules added yet.</p>
                            @else
                                <div class="transaction-list">
                                    @foreach ($recentModules as $module)
                                        <div class="transaction-item">
                                            <div class="transaction-icon bg-forest-light text-lime">
                                                <i class="bi bi-journal-bookmark"></i>
                                            </div>
                                            <div class="transaction-info">
                                                <div class="transaction-name">{{ $module->name }}</div>
                                                <div class="transaction-date">{{ $module->module_id }} • {{ $module->lectures_count }} lectures</div>
                                            </div>
                                            <div class="transaction-amount text-muted fs-xs">{{ $module->created_at?->diffForHumans() }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <!-- RIGHT AREA: Quick actions + key numbers -->
            <div class="col-xl-3 col-lg-4">
                <div class="right-panel-wrapper d-flex flex-column gap-4 h-100">

                    <!-- Quick Actions -->
                    <div class="card mb-0">
                        <div class="card-header">
                            <h2 class="card-title">Quick Actions</h2>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('student.student_create') }}" class="btn btn-outline-secondary text-start">
                                <i class="bi bi-person-plus me-2"></i> Register Student
                            </a>
                            <a href="{{ route('teacher.teacher_create') }}" class="btn btn-outline-secondary text-start">
                                <i class="bi bi-person-badge me-2"></i> Register Lecturer
                            </a>
                            <a href="{{ route('subject.module_create') }}" class="btn btn-outline-secondary text-start">
                                <i class="bi bi-journal-plus me-2"></i> Add Module
                            </a>
                            <a href="{{ route('student.idCard') }}" class="btn btn-outline-secondary text-start">
                                <i class="bi bi-person-vcard me-2"></i> Student ID Cards
                            </a>
                        </div>
                    </div>

                    <!-- Key Numbers -->
                    <div class="card flex-grow-1 mb-0">
                        <div class="card-header">
                            <h2 class="card-title">Key Numbers</h2>
                        </div>

                        <div class="progress-container">
                            <div class="progress-label-row">
                                <span class="progress-label">Students per lecturer</span>
                                <span class="progress-value">{{ $studentsPerTeacher }}</span>
                            </div>
                        </div>

                        <div class="progress-container">
                            <div class="progress-label-row">
                                <span class="progress-label">New students this month</span>
                                <span class="progress-value">{{ $newStudents }} / {{ $studentCount }}</span>
                            </div>
                            <div class="progress" role="progressbar" aria-label="New students this month">
                                <div class="progress-bar bg-lime-accent"
                                    style="width: {{ $studentCount > 0 ? round($newStudents / $studentCount * 100) : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="progress-container">
                            <div class="progress-label-row">
                                <span class="progress-label">New lecturers this month</span>
                                <span class="progress-value">{{ $newTeachers }} / {{ $teacherCount }}</span>
                            </div>
                            <div class="progress" role="progressbar" aria-label="New lecturers this month">
                                <div class="progress-bar bg-lime-accent"
                                    style="width: {{ $teacherCount > 0 ? round($newTeachers / $teacherCount * 100) : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="progress-container">
                            <div class="progress-label-row">
                                <span class="progress-label">New modules this month</span>
                                <span class="progress-value">{{ $newModules }} / {{ $moduleCount }}</span>
                            </div>
                            <div class="progress" role="progressbar" aria-label="New modules this month">
                                <div class="progress-bar bg-brand-orange"
                                    style="width: {{ $moduleCount > 0 ? round($newModules / $moduleCount * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- START: Footer Component -->
        <footer class="footer-custom">
            <div class="footer-left">
                <span class="footer-logo">
                    <i class="bi bi-asterisk"></i> LMS
                </span>
                <span class="footer-separator">|</span>
                <span class="footer-copy">&copy; {{ date('Y') }} LMS • Template by
                    <a href="https://sparkadminpro.gumroad.com/" target="_blank">Spark Admin</a>
                    • Distributed by <a href="https://www.themewagon.com/" target="_blank">ThemeWagon</a>
                </span>
            </div>
        </footer>
        <!-- END: Footer Component -->

    </div>

@endsection
