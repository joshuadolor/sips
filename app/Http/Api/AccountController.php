<?php

namespace App\Http\Api;

use App\Http\Api\BaseController;
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
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        $data = $request->user()->update(['password' => Hash::make($request->new_password)]);

        return $this->sendResponse($data);
    }

}
