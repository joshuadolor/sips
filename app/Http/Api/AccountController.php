<?php

namespace App\Http\Api;

use App\Http\Api\BaseController;
use Illuminate\Http\Request;

class AccountController extends BaseController
{
    public function profile(Request $request)
    {
        $data = $request->user();
        return $this->sendResponse($data);
    }
}
