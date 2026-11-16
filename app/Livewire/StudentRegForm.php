<?php

namespace App\Livewire;

use App\Models\Student;
use App\Models\User;
use App\Http\Controllers\IdGenerator;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class StudentRegForm extends Component
{
    use WithFileUploads;

    public $reg_no = '';
    public $name = '';
    public $email = '';
    public $password = '';
    public $dob = '';
    public $phone = '';
    public $address = '';
    public $image;

    public function mount()
    {
        $this->reg_no = $this->nextRegNo();
    }

    private function nextRegNo()
    {
        return IdGenerator::next('students');
    }

    protected function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:students,email|unique:users,email',
            'password' => 'required|min:6',
            'dob' => 'required|date',
            'phone' => 'required',
            'address' => 'required',
            // "required" (not nullable) because the students.image column is NOT NULL
            'image' => 'required|image|max:2048',
        ];
    }

    public function submit()
    {
        $this->validate();

        // recalculate on save so the browser value can't be tampered with / be stale
        $this->reg_no = $this->nextRegNo();

        // same folder + disk that StudentController (ImageUploader) uses
        $imagePath = $this->image->store('Student/profile', 'public');

        Student::query()->create([
            "reg_no" => $this->reg_no,
            "name" => $this->name,
            "phone_number" => $this->phone,
            "email" => $this->email,
            "address" => $this->address,
            "birth_date" => $this->dob,
            "image" => $imagePath,
            "password" => Hash::make($this->password),
        ]);

        // login account with the student role
        User::query()->create([
            "name" => $this->name,
            "email" => $this->email,
            "role" => "student",
            "password" => Hash::make($this->password),
        ]);

        session()->flash('success', 'Student registered successfully');

        return $this->redirectRoute('student.student_list');
    }

    public function render()
    {
        return view('livewire.student-reg-form');
    }
}
