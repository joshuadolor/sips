<?php

namespace App\Http\Api;

use App\Http\Api\Auth\RegisterController;
use App\Http\Api\BaseController;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends BaseController
{
    public function profile(Request $request)
    {
        $data = $request->user();
        return $this->sendResponse($data);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => 'required|min:6',
            'new_confirm_password' => ['same:new_password'],
        ]);

        $data = $request->user()->update(['password' => Hash::make($request->new_password)]);

        return $this->sendResponse($data);
    }

    public function resetPassword(Request $request)
    {
        if (auth()->user()->role !== 2) {
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized'], 401);
        }
        $request->validate([
            'user_id' => ['required'],
        ]);

        $user = User::find($request->user_id);
        if (!$user) {
            return $this->sendError('User not found.', ['error' => 'User not found'], 404);
        }

        $data = $user->update(['password' => Hash::make(config('app.default_password'))]);

        return $this->sendResponse($data);
    }

    public function store(Request $request)
    {
        $registerAccount = new RegisterController();
        $registerAccount->register($request);
    }

}
