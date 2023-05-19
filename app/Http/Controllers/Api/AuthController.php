<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param RegisterUserRequest $request The HTTP request
     *
     * @return json response
     */
    public function register(RegisterUserRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['password'] = bcrypt($validatedData['password']);
            $createdUser = User::create($validatedData);

            return ResponseHelper::sendResponse(['user' => $createdUser], 200, 'Account Created, please login', false, []);

        } catch (\Exception $e){
            return ResponseHelper::sendResponse([], 500, $e->getMessage(), true, []);
        }
    }

    /**
     * Login a user.
     *
     * @param LoginRequest $request The HTTP request
     *
     * @return json response
     */
    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                return ResponseHelper::sendResponse([], 400, 'Invalid email or password!', true, []);
            }

            $accessToken = Auth::user()->createToken('accessToken');

            return ResponseHelper::sendResponse([
                'user'      => auth()->user(),
                'token'     => $accessToken->plainTextToken
            ], 200, null, false, []);

        } catch (\Exception $e){
            return ResponseHelper::sendResponse([], 500, $e->getMessage(), true, []);
        }
    }

    /**
     * Logout a user.
     *
     * @param Request $request The HTTP request
     *
     * @return json response
     */
    public function logout(Request $request)
    {
        @Auth::user()->tokens()->delete();
        return ResponseHelper::sendResponse([], 204);
    }

}
