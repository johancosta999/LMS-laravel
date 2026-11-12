<?php

namespace App\Livewire;

use App\Models\Student;
use App\Models\User;
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

    protected function rules()
    {
        return [
            'reg_no' => 'required|unique:students,reg_no',
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

        $this->reset();

        session()->flash('success', 'Student registered successfully');

        $this->dispatch('student-registered');
    }

    public function render()
    {
        return view('livewire.student-reg-form');
    }
}
