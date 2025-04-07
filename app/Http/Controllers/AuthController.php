<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'User created successfully',
            'token' => $token,
            'user' => new UserResource($user)
        ], Response::HTTP_OK);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Not Authorized',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken("login-token-{$user->id}")->plainTextToken;

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'User Token',
            'token' => $token
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();;

        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'Logout successful',
        ], Response::HTTP_OK);
    }

    public function recoverPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($user) {
            $user->update(['password' => bcrypt($request->password)]);
            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'Password changed successfully',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'message' => 'Not Authorized',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function user(Request $request)
    {
        return response()->json([
            'status' => Response::HTTP_OK,
            'message' => 'User information',
            'user' => new UserResource($request->user())
        ], Response::HTTP_OK);
    }
}
