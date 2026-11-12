@extends('app')

@push('title')
    Enter Marks
@endpush

@section('content')
    <div class="container-fluid registration-page px-0">
        <div class="page-header registration-page-header">
            <div>
                <div class="registration-eyebrow"><i class="bi bi-pencil-square"></i> Exam marks</div>
                <h1 class="page-title">{{ $exam->title }}</h1>
                <p class="page-subtitle">
                    {{ $exam->subject->name ?? '-' }} • {{ $exam->exam_date }} • Out of {{ $exam->total_marks }}
                </p>
            </div>
            <a href="{{ route('exam.exam_list') }}" class="registration-back-link">
                <i class="bi bi-arrow-left"></i>
                <span>Back to exams</span>
            </a>
        </div>

        <div class="card registration-form-card">
            <div class="registration-form-heading">
                <div>
                    <span class="registration-section-label">Students</span>
                    <h2>Enter marks</h2>
                </div>
                <span class="registration-required-note">Leave empty if the student did not sit the exam</span>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">Marks must be between 0 and {{ $exam->total_marks }}.</div>
            @endif

            @if ($students->isEmpty())
                <p class="text-muted mb-0">No students registered yet.</p>
            @else
                <form action="{{ route('exam.saveMarks', $exam->id) }}" method="post">
                    @csrf
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Register No</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" style="width: 180px;">Marks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr>
                                        <td>{{ $student->reg_no }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="{{ $exam->total_marks }}"
                                                name="marks[{{ $student->id }}]"
                                                value="{{ old('marks.' . $student->id, $marks[$student->id] ?? '') }}"
                                                class="form-control @error('marks.' . $student->id) is-invalid @enderror">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="submit" class="btn btn-primary">Save marks</button>
                </form>
            @endif
        </div>
    </div>
@endsection
