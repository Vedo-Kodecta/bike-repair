<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
    }

    public function registerCustomer(RegisterRequest $request)
    {
        $role_id = $request->input('role_id', 1);

        User::create(array_merge($request->validated(), ['role_id' => $role_id]));
    }

    public function registerMechanic(RegisterRequest $request)
    {
        $role_id = $request->input('role_id', 2);

        User::create(array_merge($request->validated(), ['role_id' => $role_id]));
    }
}
