<?php

namespace Modules\Api\V1\Authentication\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SmsCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\Api\V1\Authentication\app\Http\Resources\AuthenticationResource;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return errorResponse(422, $validator->messages());
        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'provider_name' => 'manual',
        ]);

        $token = $user->createToken('myApp')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);

        return successResponse([
            'data' => new AuthenticationResource($user),
            'token' => $token,
        ], 200, 'حساب کاربری با موفقیت ایجاد شد.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return errorResponse(401, 'کاربر یافت نشد!');
        }

        if (!Hash::check($request->password, $user->password)) {
            return errorResponse(401, 'رمز عبور اشتباه است!');
        }

        $token = $user->createToken('myApp')->plainTextToken;

        return successResponse([
            'data' => new AuthenticationResource($user),
            'token' => $token,
        ], 200, 'کاربر با موفقیت وارد شد.');

    }

    public function sendSms(Request $request)
    {
        $phone_number = $request->input('phone_number');
        $checkLastSms = SmsCode::checkTwoMinute($phone_number);
        if ($checkLastSms == null) {
            $code = rand(111111, 999999);
            SmsCode::query()->create([
                'phone_number' => $phone_number,
                'code' => $code,
            ]);

            return response()->json([
                'resault' => true,
                'message' => "اس ام اس ارسال شد",
                'data' => [
                    'phone_number' => $phone_number,
                    'code' => $code,

                ],
            ], 201);

            return successResponse([
                'data' => [
                    'phone_number' => $phone_number,
                    'code' => $code,

                ],
            ], 200, 'حساب کاربری با موفقیت ایجاد شد.');

        } else {
            return response()->json([
                'resault' => false,
                'message' => "اس ام اس ارسال نشد",
                'data' => [],
            ], 403);

        }
    }

    // public function logout()
    // {
    //     auth()->user()->tokens()->delete();
    //     return successResponse(new AuthenticationResource(auth()->user()), 200, 'کاربر با موفقیت خارج شد.');
    // }
}