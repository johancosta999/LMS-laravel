<?php

namespace App\Livewire;

use App\Models\Student;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class StudentListTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    // runs automatically whenever $search changes -> go back to page 1
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // StudentRegForm dispatches 'student-registered'; calling this method re-renders the table
    #[On('student-registered')]
    public function refreshList()
    {
        $this->resetPage();
    }

    public function render()
    {
        $students = Student::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('reg_no', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.student-list-table', ['students' => $students]);
    }
}
