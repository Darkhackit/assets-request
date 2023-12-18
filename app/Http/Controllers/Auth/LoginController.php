<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->validate($request,[
            'email' => ['required'],
            'password' => ['required']
        ]);

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['errors' => [
                'email' => ['Email or Password is not correct']
            ]], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'accessToken' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 2000,
            'user' => new UserResource(auth()->user())
        ],Response::HTTP_OK);

    }
}
