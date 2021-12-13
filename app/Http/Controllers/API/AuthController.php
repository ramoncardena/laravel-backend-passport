<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Laravel\Passport\Exceptions\OAuthServerException;




class AuthController extends Controller
{
    /**
     * Register User
     *
     * @param Request $request
     * @return void
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return $this->validationErrors($validator->errors());
        }

        $user =   User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(["status" => "success", "error" => false, "message" => "Success! User registered.", "user" => $user], 201);
    }


    /**
     * User Login
     *
     * @param Request $request
     * @return void
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required|min:3"
        ]);

        if ($validator->fails()) {
            return $this->validationErrors($validator->errors());
        }

        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

                /** @var \App\Models\MyUserModel $user **/
                $user = Auth::user();

                $token = $user->createToken('token')->accessToken;
                return response()->json(
                    [
                        "status" => "success",
                        "error" => false,
                        "message" => "Success! you are logged in.",
                        "token" => $token,
                        "user" => $user,
                    ]
                );
            }
            return response()->json(["status" => "failed", "message" => "Failed! invalid credentials."], 404);
        } catch (Exception $e) {
            return response()->json(["status" => "failed", "message" => $e->getMessage()], 404);
        }
    }

    /**
     * Logged User Data Using Auth Token
     *
     * @return void
     */
    public function user()
    {
        try {
            $user = Auth::user();
            return response()->json(["status" => "success", "error" => false, "data" => $user], 200);
        } catch (OAuthServerException $exception) {
            return response()->json(["status" => "failed", "error" => $exception], 401);
        }
    }

    /**
     * Logout Auth User
     *
     * @param Request $request
     * @return void
     */
    public function logout()
    {

        if (Auth::check()) {
            /** @var \App\Models\MyUserModel $user **/
            $user = Auth::user();
            $user->token()->revoke();
            return response()->json(["status" => "success", "error" => false, "message" => "Success! You are logged out."], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed! You are already logged out."], 403);
    }
}
