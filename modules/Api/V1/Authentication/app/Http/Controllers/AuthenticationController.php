<?php

namespace Modules\Api\V1\Authentication\app\Http\Controllers;

use App\Http\Controllers\Controller;
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
            'phone_number' => 'required|unique:users,phone_number',
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
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'provider_name' => 'manual',
        ]);

        $token = $user->createToken('myApp')->plainTextToken;

        return successResponse([
            'data' => new AuthenticationResource($user),
            'token' => $token,
        ], 200, 'حساب کاربری با موفقیت ایجاد شد.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
            'email' => 'required',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }
        $email = $request->email;
        $phone_number = $request->phone_number;

        $user = User::where('phone_number' , $phone_number)->where('email' , $email)->first();

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

    public function loginSms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = User::where('phone_number', $request->phone_number)->first();

        $phone_number = $request->input('phone_number');
        $checkLastSms = User::checkTwoMinute($phone_number);
        $code = rand(111111, 999999);
        $login_token = Hash::make('SDLFKGFfew65');

        if ($checkLastSms == null) {
            if ($user) {
                $user->update([
                    'code' => $code,
                    'login_token' => $login_token,
                ]);
            } else {
                $user = User::Create([
                    'phone_number' => $request->phone_number,
                    'code' => $code,
                    'login_token' => $login_token,
                ]);

            }

            return response()->json([
                'resault' => true,
                'message' => "اس ام اس ارسال شد",
                'data' => [
                    'phone_number' => $phone_number,
                    'code' => $code,
                    'login_token' => $login_token,

                ],
            ], 201);

            return successResponse([
                'data' => [
                    'phone_number' => $phone_number,
                    'code' => $code,
                    'login_token' => $login_token,

                ],
            ], 200, 'حساب کاربری با موفقیت ایجاد شد.');

        } else {
            return response()->json([
                'resault' => false,
                'message' => "اس ام اس ارسال نشد پس از 2 دقیقه مجددا امتحان کنبد",
                'data' => [],
            ], 403);

        }
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }
        $user = User::where('code', $request->code)->firstOrFail();

        $token = $user->createToken('myApp2')->plainTextToken;
        if ($user->code == $request->code) {
            return successResponse([
                'data' => new AuthenticationResource($user),
                'token' => $token,
            ], 200, 'کاربر با موفقیت وارد شد.');

        } else {
            return errorResponse(401, 'کد تایید اشتباه است!');
        }
    }

}
