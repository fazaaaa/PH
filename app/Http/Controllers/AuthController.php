<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function index(){

        $user = Auth::user();

        if ($user){
            if($user->level =='admin'){
                return redirect()->intended('admin');
            }
            elseif ($user->level =='user') {
                return redirect()->intended('user');
                # code...
            }
        }
        return view('login');
    }

    public function proses_login (Request $request){

        $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);

        $crediantial = $request->only('username','password');

        if(Auth::attempt($crediantial)){
            $user = Auth::user();
             if($user->level == 'admin'){
                return redirect()->intended('admin');
             }
             else if($user->level == 'user'){
                return redirect()->intended('user');
             }

             return redirect()->intended('/');
        }
        return redirect('login')
        ->withInput()
        ->withErrors(['login_gagal'=>'These credentials does not match our records']);
    }

    public function register() {
        return view('register');
    }

    public function proses_register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'username'=>'required|unique:users',
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if ($validator->fails()) {
            return redirect('/register')
            ->withErrors($validator)
            ->withInput();
        }

        $request['level']='user';
        $request['password'] = bcrypt($request->password);

        User::create($request->all());

        return redirect()->route('login');
    }

    public function logout(Request $request){
        $request->session()->flush();

        Auth::logout();
        return redirect('login');
    }
}
