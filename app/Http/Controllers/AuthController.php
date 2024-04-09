<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'password' => 'required|min:8|max:20',
        ], [
            'phone.required' => 'You have not entered your phone number',
            'phoneLogin.regex' => 'You have entered an invalid phone number format',
            'phoneLogin.min' => 'Phone numbers must be at least 10 characters long',
            'password.required' => 'You have not entered your password',
            'password.min' => 'Password must be at least 8 characters long',
            'password.max' => 'Passwords must be no longer than 20 characters',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' => 'Thêm mới không thành công',
                'data' => $validator->errors()
            ]);
        }

        $user = new User();
        $checkUser = $user->userCheck($request->phone);

        if (!$checkUser) {
            $newUser = new User();

            $newUser->phone = $request->phone;
            $newUser->password = $request->password;
            $newUser->user_name = $request->userName;
            $newUser->gender = $request->gender;
            $newUser->date_of_birth = Carbon::parse($request->dateOfBirth)->format('Y-m-d');
            $newUser->role = $request->role;

            $newUser->save();
            $token = $newUser->createToken('auth_token')->plainTextToken;
            return response()->json(
                [
                    'status' => 201,
                    'message' => 'Thêm mới thành công',
                    'data' => $newUser,
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ]
            );
        }

        return response()->json([
            'status' => 404,
            'message' => 'Thêm mới không thành công',
            'data' => null
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('phone', 'password'))) {
            return response()->json([
                'status' => 404,
                'message' => 'Đăng nhập không thành công',
                'data' => null
            ]);
        }
        $user = User::where('phone', $request['phone'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'Chào ' . $user->user_name . '! Chúc an lành',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ['message' => 'Bạn đã thoát ứng dụng và token đã xóa'];
    }
}
