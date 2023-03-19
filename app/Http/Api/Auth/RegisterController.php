<?php

namespace App\Http\Api\Auth;

use App\Http\Api\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => '',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $data = [
            'token' => $user->createToken(config('app.name'))->plainTextToken,
            'user' => $user,
        ];

        return $this->sendResponse($data, 'User register successfully.');
    }
}
