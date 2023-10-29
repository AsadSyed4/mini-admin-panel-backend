<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Contracts\ResponseData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Utilities\HttpCodes;
use App\Services\Utilities\HttpResponse;
use App\Services\Utilities\HttpMessages;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    /**
     * Authenticate admin and generate a token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AdminLoginRequest $request):JsonResponse
    {

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return HttpResponse::json(new ResponseData(HttpMessages::Unauthorized->value, HttpCodes::UnAuthorized, []));
        }

        $admin = User::where('email', $request->email)->first();

        $admin->tokens()->delete();

        $token = $admin->createToken('API Token')->plainTextToken;

        $admin->token=$token;

        return HttpResponse::json(new ResponseData(HttpMessages::LoggedIn->value, HttpCodes::Ok, $admin->toArray()));
    }

    /**
     * Logout the authenticated admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request):JsonResponse
    {
        $request->user()->tokens()->delete();

        return HttpResponse::json(new ResponseData(HttpMessages::LoggedOut->value, HttpCodes::Ok, []));
    }
}
