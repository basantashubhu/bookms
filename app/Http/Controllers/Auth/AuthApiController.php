<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repo\UserRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    public function login(Request $request, UserRepo $userRepo): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
        }
        $user = $userRepo->findByEmail($request->input('email'));
        if (hash('sha256', $request->input('password')) !== $user->password) {
            return response()->json(['message' => 'Email or password did not match.', 'p' => hash('sha256', $request->input('password'))], 422);
        }
        $api_key = sha1($user->email . time());
        $userRepo->save(['api_token' => $api_key]);

        return response()->json([
            'api_token' => $api_key
        ]);
    }

    public function register(Request $request, UserRepo $userRepo): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid data', 'errors' => $validator->errors()], 422);
        }
        $user = $userRepo->save($request->only(['first_name', 'last_name', 'email', 'phone', 'password']));

        $api_key = sha1($user->email . time());
        $userRepo->save(['api_token' => $api_key]);

        return response()->json([
            'api_token' => $api_key
        ]);
    }
}
