<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserhResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;

class AuthenticationController extends Controller
{

    public function register(StoreUserRequest $request)
    {
        $dataValidated = $request->validated();
        $user = User::create(Arr::except($dataValidated, 'file'));
        if ($request->file) {
            $user->addMediaFromRequest('file')->toMediaCollection('usersImages');
        }
        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'user' => new UserhResource($user),
        ], 200);
    }


    public function login(LoginUserRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], 401);
        } else {
            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => true,
                'message' => 'User Login  Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'user' => new UserhResource($user),
            ], 200);
        }
    }


    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $dataValidated = $request->validated();
        if ($request->file) {
            $user->media()->delete();
            $user->addMediaFromRequest('file')->toMediaCollection('usersImages');
        }
        $user->update(Arr::except($dataValidated, 'file'));
        return response()->json([
            'status' => true,
            'message' => 'User Update Successfully',
            'user' => new UserhResource($user),
        ], 200);
    }


    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->only('email'))->first();
        $status = Password::sendResetLink(
            $request->only('email')
        );
        $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
        if ($status === 'passwords.sent') {
            return response()->json([
                'status' => true,
                'message' => 'Sent Email Successfully , check your email and change your password',
                'user' => new UserhResource($user)
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => $status,
            ], 500);
        }
    }


    public function socialiteLogin($provider)
    {
        if ($provider === 'facebook' || $provider === 'google' || $provider === 'github') {
            try {
                $Url = Socialite::with($provider)->stateless()->redirect()->getTargetUrl();
                return response()->json([
                    'status' => true,
                    'Url' => $Url,
                ], 200);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Provider must be github or facebook or google',
            ], 401);
        }
    }


    public function socialiteRedirect($provider)
    {
        $socialite = Socialite::driver($provider)->stateless()->user();
        $user = User::where('email', $socialite->getEmail())->first();
        if (!$user) {
            $user = User::updateOrCreate([
                'provider' => $provider,
                'provider_id' => $socialite->getId(),
            ], [
                'name' => $socialite->getName(),
                'email' => $socialite->getEmail(),
                'bio' => $socialite->getEmail(),

            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'login ' . $provider . ' Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'user' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'user logged out',
        ], 200);
    }


    public function delete(Request $request)
    {
        $request->user()->delete();
        return response()->json([
            'status' => true,
            'message' => 'delete account successfully',
        ], 200);
    }
}