<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\HelperApi;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Traits\ImageProcessing;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    use HelperApi, ImageProcessing;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register_user','register_company']]);
    }

    public function login(Request $request)
    {
        try {

            $user = User::where('email', $request->email)->first();
            if ($user && (!Hash::check($request->password, $user->password))) {
                return $this->onError(500, 'invalid password');
            }
            if (!$user){
                return $this->onError(500, 'invalid data');
            }

            $token = JWTAuth::fromUser($user);

            if (!$token) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }
            return $this->onSuccessWithToken(200,'login_user', $user, $token);
        } catch (\Throwable $error) {
            return $this->onError(500, 'server_error', $error->getMessage());
        }
    }

    public function register_user(Request $request)
    {
        try {
            $rules = [
                'email' => ['sometimes','required', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'min:6'],
                'phone' => ['regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:15', 'required', 'unique:users,phone'],
                'address' => ['required', 'max:255'],
                'birthday' => ['required', 'date_format:Y-m-d', 'before_or_equal:' . now()],
                'blood_type' => ['required', 'exists:boold_types,id'],
                'name' => ['required', 'string', 'max:255']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->onError(422, 'Validation error', $validator->errors()->first());
            }

            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'birthday' => $request->birthday,
                'blood_type' => $request->boold_type // Corrected field name
            ]);

            $token = JWTAuth::fromUser($user);
            return $this->onSuccessWithToken(200,'create_user', $user, $token);
        } catch (\Throwable $error) {
            return $this->onError(500,'error', $error->getMessage());
        }
    }

    public function register_company(Request $request)
    {
        try {
            $rules = [
                'email' => ['sometimes', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'min:6'],
                'phone' => ['regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10', 'max:15', 'required', 'unique:users,phone'],
                'address' => ['required', 'max:255'],
                'birthday' => ['nullable', 'date_format:Y-m-d', 'before_or_equal:' . now()],
                'name' => ['required', 'string', 'max:255']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->onError(422, 'Validation error', $validator->errors()->first());
            }

            $user = User::create([
                'email' => $request->email,
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'address' => $request->address,
                'birthday' => $request->birthday,
                'account_type'=>0
            ]);

            $token = JWTAuth::fromUser($user);
            return $this->onSuccessWithToken(200,'create_user', $user, $token);
        } catch (\Throwable $error) {
            return $this->onError(500,'error', $error->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            // Invalidate the token by adding it to the blacklist
            JWTAuth::parseToken()->invalidate();
            return $this->onSuccess(200,'Logout successful');
        } catch (JWTException $e) {
            return $this->onError(500, 'Error logging out');

        }
    }

    public function change_password(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $validator = Validator::make($request->all(), [
                'old_password' => 'nullable|string|min:6|max:255',
                'password' => 'nullable|string|min:8|max:255|confirmed',
            ]);

            if ($validator->fails()) {
                return $this->onError(500, 'Validation error', $validator->errors());
            }

            $data = User::where('id', '=', $user->id)->first();





            // Check if the old password matches the current password
            if (!is_null($request->old_password) && !Hash::check($request->old_password, $user->password)) {
                return $this->onError(400, 'Old password does not match the current password');
            }

            // Update the password if a new one is provided
            if (!is_null($request->password)) {
                $data->password = bcrypt($request->password);
            }

            $data->update();

            return $this->onSuccess(200, 'Change Password successfully', $data);
        } catch (\Throwable $e) {
            return $this->onError(500, 'An error occurred. Please try again', $e->getMessage());
        }
    }

}
