<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthenticatedTokenController extends Controller
{
    /**
     * Handle an incoming authentication request.
     * @throws ValidationException
     */
    public function store(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->authenticate();

            $user = User::where('email', $request->email)->first();
        } catch (\Exception $e) {
           throw $e;
        }

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'user' => $user,
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ]);
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout Successfully!']);
    }
}
