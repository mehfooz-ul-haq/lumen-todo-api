<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\User;

class AuthController extends Controller
{
    
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $user;
    
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(User $user) {
        $this->user = $user;
    }


    /**
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 24 * 60 * 60
        ];
        
        return JWT::encode($payload, env('JWT_SECRET'));
    }
    
    
    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     * 
     * @param  \App\User   $user 
     * @return mixed
     */
    public function login(Request $request) {
        
        $this->validate($request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        // Find the user by email
        $user = User::where('email', $request->input('email'))->first();
        if (!$user) {
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check( $request->input('password') , $user->password)) {
            return response()->json([
                'status' => true,
                'message' => 'Login successfull',
                'token' => 'Bearer ' . $this->jwt($user),
                'Accept' => 'application/json',
            ], 200);
        }

        // Bad Request response
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }
    


     /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'mobile_number' => 'required',
            'gender' => 'required',
            'birthday' => 'required|date_format:Y-m-d',
        ]);

        try {
            $this->user->first_name = $request->input('first_name');
            $this->user->last_name = $request->input('last_name');
            $this->user->email = $request->input('email');
            $this->user->password = Hash::make($request->input('password'));
            $this->user->mobile_number = $request->input('mobile_number');
            $this->user->gender = $request->input('gender');
            $this->user->birthday = $request->input('birthday');
            $this->user->save();

            return response()->json([
                'status' => true,
                'message' => 'Login successfull',
                'token' => 'Bearer ' . $this->jwt($this->user),
                'Accept' => 'application/json',
            ], 200);
                        
            return response()->json(['user' => $this->user, 'message' => 'User registered successfully.'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }

    }

}
