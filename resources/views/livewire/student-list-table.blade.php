<div>
    <div class="card card-primary">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Student information</h3>
            <div style="max-width: 250px;">
                <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm"
                    placeholder="Search name, email, reg no...">
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover w-100">
                    <thead>
                        <tr>
                            <th>Reg No</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr wire:key="student-{{ $student->id }}">
                                <td><strong>{{ $student->reg_no }}</strong></td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->address }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone_number }}</td>
                                <td>
                                    <a href="{{ route('student.edit', $student->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a href="{{ route('student.delete', $student->id) }}"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $students->links() }}
        </div>
    </div>
</div>
