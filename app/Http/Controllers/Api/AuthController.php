<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','confirmed','min:5']
        ]);


        $user = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => Hash::make($validate['password'])
        ]);


        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' =>[
                'ar'=>'تم إنشاء الحساب بنجاح',
                'en'=>'The account has been created successfully',
            ],
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token
            ]
        ]);
    }

    public function login(Request $request)
    {
        $validate = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        $user = User::where('email', $validate['email'])->first();

        if (!$user || !Hash::check($validate['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' =>[
                    'ar'=>'بيانات الدخول غير صحيحة',
                    'en'=>'Login data is incorrect',
                ]
            ]);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' =>[
                'ar'=>'تم تسجيل الدخول بنجاح',
                'en'=>'You have been logged in successfully',
            ],
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token
            ]
        ]);
    }
}
