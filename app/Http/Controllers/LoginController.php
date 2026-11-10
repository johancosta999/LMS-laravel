<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class LoginController extends Controller
{
    public function login(){
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.dashboard');
        }

        return view('login');
    }

    public function loginCheck(Request $request){
        try{
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $student = Student::query()->where('email', $request->email)->first();

            if ($student && Hash::check($request->password, $student->password)) {
                Auth::guard('student')->login($student);
                $request->session()->regenerate();

                return redirect()->route('student.dashboard');
            }

            return redirect()->route('login')
                ->withErrors(['email' => 'Invalid email or password'])
                ->withInput($request->only('email'));
        } catch(\Exception $e){
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
