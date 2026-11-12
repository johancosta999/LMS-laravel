<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function login(){
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('login');
    }

    public function loginCheck(Request $request){
        try{
            $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            // find the user by email, the role tells us which dashboard to open
            $user = User::query()->where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user);
                $request->session()->regenerate();

                return $this->redirectByRole($user->role);
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
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole($role){
        if ($role == 'admin') {
            return redirect()->route('admin.dasboard');
        }

        if ($role == 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        return redirect()->route('student.dashboard');
    }
}
