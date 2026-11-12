<!-- ==========================================
             START: Sidebar Component
             Highly polished, dark-green sticky navigation
             ========================================== -->
@php
  $role = Auth::user()->role ?? '';
@endphp
<div class="sidebar-wrapper" id="sidebar">
  <!-- Brand Logo / Identity -->
  <a href="{{ route('login') }}" class="sidebar-brand">
    <i class="bi bi-asterisk"></i>
    <span>LMS</span>
  </a>

  <!-- Navigation Menu -->
  <div class="flex-grow-1 overflow-y-auto">

    @if ($role == 'admin')
      <!-- Group: Menu -->
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Menu</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="{{ route('admin.dasboard') }}" class="sidebar-menu-link active" id="menu-overview" title="Overview">
              <i class="bi bi-grid-fill"></i>
              <span>Admin Dashboard</span>
            </a>
          </li>
        </ul>
      </div>

      <!-- Group: Components -->
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Components</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="{{ route('student.student_create') }}" class="sidebar-menu-link" id="menu-basictables" title="Basic Tables">
              <i class="bi bi-table"></i>
              <span>Student Register</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('student.student_list') }}" class="sidebar-menu-link" id="menu-uiforms" title="Forms and Input">
              <i class="bi bi-input-cursor-text"></i>
              <span>Student List</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('student.idCard') }}" class="sidebar-menu-link" id="menu-uiforms" title="Forms and Input">
              <i class="bi bi-input-cursor-text"></i>
              <span>Student IDs</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('teacher.teacher_create') }}" class="sidebar-menu-link" id="menu-uibuttons" title="Buttons">
              <i class="bi bi-menu-button-wide-fill"></i>
              <span>Lecturer Register</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('teacher.teacher_list') }}" class="sidebar-menu-link" id="menu-uibuttons" title="Buttons">
              <i class="bi bi-menu-button-wide-fill"></i>
              <span>Lecturer List</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('subject.module_create') }}" class="sidebar-menu-link" id="menu-uibuttons" title="Buttons">
              <i class="bi bi-menu-button-wide-fill"></i>
              <span>Module Register</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('subject.module_list') }}" class="sidebar-menu-link" id="menu-uibuttons" title="Buttons">
              <i class="bi bi-menu-button-wide-fill"></i>
              <span>Module List</span>
            </a>
          </li>
        </ul>
      </div>
    @endif

    @if ($role == 'teacher')
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Menu</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="{{ route('teacher.dashboard') }}" class="sidebar-menu-link active" title="Dashboard">
              <i class="bi bi-grid-fill"></i>
              <span>Lecturer Dashboard</span>
            </a>
          </li>
        </ul>
      </div>

      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Exams</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="{{ route('exam.exam_create') }}" class="sidebar-menu-link" title="Create Exam">
              <i class="bi bi-journal-plus"></i>
              <span>Create Exam</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('exam.exam_list') }}" class="sidebar-menu-link" title="My Exams & Reports">
              <i class="bi bi-bar-chart"></i>
              <span>My Exams & Reports</span>
            </a>
          </li>
        </ul>
      </div>
    @endif

    @if ($role == 'student')
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Menu</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="{{ route('student.dashboard') }}" class="sidebar-menu-link active" title="Dashboard">
              <i class="bi bi-grid-fill"></i>
              <span>Student Dashboard</span>
            </a>
          </li>
        </ul>
      </div>
    @endif

  </div>

  <!-- Sidebar Profile Card (Dynamic Footer) -->
  <div class="sidebar-profile">
    <img src="{{asset('assets/images/avatar.png')}}" alt="Profile" class="sidebar-profile-img"
      onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop'">
    <div class="sidebar-profile-info">
      <div class="sidebar-profile-name">{{ Auth::user()->name ?? 'Guest' }}</div>
      <div class="sidebar-profile-email">{{ Auth::user()->email ?? '' }}</div>
    </div>
  </div>
</div>
<!-- ==========================================
             END: Sidebar Component
             ========================================== -->
