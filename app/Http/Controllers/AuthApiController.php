<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AuthApiController extends BaseApiController
{
    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return $this->error($validator->errors()->first());
            }

            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            DB::commit();

            return $this->success($user, 'User successfully registered!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return $this->error($validator->errors()->first());
            }

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->error('Invalid credentials');
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->success([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 'Login successful');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function logout()
    {
        $user = User::findOrFail(auth()->user()->id);
        $user->currentAccessToken()->delete();

        return $this->success([], 'Logout successful!');
    }

    public function changePassword(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'password' => 'required|string|min:8',
                'new_password' => 'required|string|min:8',
            ]);

            $user = User::findOrFail(auth()->user()->id);

            if (!Hash::check($request->password, $user->password)) {
                return $this->error('Invalid password');
            }

            $user->update([
                'password' => $request->new_password,
            ]);

            DB::commit();

            return $this->success([], 'Password updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
