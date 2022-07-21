<?php

namespace App\Http\Controllers\Admin\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class RegisterController extends Controller
{

    /**
     * Display register page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('admin.auth.register');
    }

    /**
     * Handle account registration request
     *
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // $user = User::create($request->validated());
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            // 'start_date' => $request->start_date
        ]);

        // auth()->login($user);

        return redirect()->back()->with('message', "Account successfully registered.");
    }
}
