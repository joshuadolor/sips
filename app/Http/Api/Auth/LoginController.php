<?php

namespace App\Http\Api\Auth;

use App\Http\Api\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (!(Auth::attempt(['email' => $request->email, 'password' => $request->password]))) {
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        if (!$user->is_active) {
            return $this->sendError('Account not active.', ['error' => 'Account not active'], 400);
        }

        $data = [
            'token' => $user->createToken(config('app.name'))->plainTextToken,
            'user' => $user,
            'is_admin' => $user->is_admin,
        ];

        return $this->sendResponse($data, 'User login successfully.');

    }
}
