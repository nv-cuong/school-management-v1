<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login()
    {
        if (!empty(Auth::check()))
        {
            if(Auth::user()->user_type == 1)
            {
                return redirect('admin/dashboard');
            }
            else if(Auth::user()->user_type == 2)
            {
                return redirect('teacher/dashboard');
            }
            else if(Auth::user()->user_type == 3)
            {
                return redirect('student/dashboard');
            }
            else if(Auth::user()->user_type == 4)
            {
                return redirect('parent/dashboard');
            }
        }
        return view('auth.login');
    }

    public function authLogin(Request $request)
    {
        $remember = !empty($request->remember) ? true : false;

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], true)) 
        {
            if(Auth::user()->user_type == 1)
            {
                return redirect('admin/dashboard');
            }
            else if(Auth::user()->user_type == 2)
            {
                return redirect('teacher/dashboard');
            }
            else if(Auth::user()->user_type == 3)
            {
                return redirect('student/dashboard');
            }
            else if(Auth::user()->user_type == 4)
            {
                return redirect('parent/dashboard');
            }
        } 
        else 
        {
            return redirect()->back()->with('error', 'Tài khoản hoặc mật khẩu không chính xác.');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url(''));
    }

    public function forgotPassword()
    {
        return view('auth.forgot');
    }

    public function changePassword(Request $request)
    {
        $user = User::getEmailSingle($request->email);
        if(!empty($user))
        {
            $user->remember_token = Str::random(30);
            $user->save();

            Mail::to($user->email->send(new ForgotPasswordMail($user)));

            return redirect()->back()->with('success', "Hãy kiểm tra mail của bạn và thay đổi mật khẩu!");
        }
        else
        {
            return redirect()->back()->with('error', "Không tìm thấy địa chỉ email!");
        }
    }

    public function reset($remember_token)
    {
        $user = User::getTokenSingle($remember_token);
        if(!empty($user))
        {
            $data['user'] = $user;
            return view('auth.reset', $data);
        }
        else
        {
            abort(404);
        }
    }

    public function postReset($token, Request $request)
    {
        if($request->password == $request->confirm_password)
        {
            $user = User::getTokenSingle($token);
            $user->password = Hash::make($request->password);
            $user->remember_token = Str::random(30);
            $user->save();

            return redirect(url(''))->with('success', 'Thay đổi mật khẩu thành công.');
        }
        else
        {
            return redirect()->back()->with('error', 'Mật khẩu và mật khẩu nhập lại không trùng khớp.');
        }
    }
}