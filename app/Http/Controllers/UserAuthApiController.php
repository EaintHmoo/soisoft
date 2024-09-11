<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class UserAuthApiController extends Controller
{
    public function register(Request $request)
    {
        $registerUserData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:6'
        ]);
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $registerUserData['name'],
                'email' => $registerUserData['email'],
                'password' => Hash::make($registerUserData['password']),
            ]);

            $is_already_exist = DB::table('roles')
                ->where('name', 'supplier')
                ->exists();

            if (!$is_already_exist) {
                Role::create(['name' => 'supplier']);
            }

            $user->assignRole('supplier');
            DB::commit();
            return response()
                ->json([
                    'message' => 'Register Successfully!',
                    'status' => Response::HTTP_CREATED
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()
                ->json([
                    'message' => 'Fail to register',
                    'status' => 500
                ], 500);
        }
    }

    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);
        $user = User::where('email', $loginUserData['email'])->first();
        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return response()->json([
                'message' => 'Email or password wrong!!',
                'status' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (!$user->hasRole('supplier')) {
            return response()->json([
                'message' => 'Login user must be supplier!!',
                'status' => Response::HTTP_UNAUTHORIZED,
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken($user->id . $user->name . '-AuthToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'data' => new UserResource($user),
            'status' => Response::HTTP_ACCEPTED
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "logged out successfully",
            "status" => Response::HTTP_OK
        ]);
    }
}
