<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;


//test


class AuthController extends Controller
{

    public function register(Request $req)
    {

        $data =  $req->only('name', 'email', 'password');


        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'code' => 2,
                'message' =>  $this->getFirstErrorMessage($validator->errors()),
            ], 200);
        }else{
            $user =  User::create([
                'name'=> $req->name,
                'email'=> $req->email, 
                'password'=>  bcrypt($req->password)
            ]);
    
            if($user)
            {
                //User created, return success response
                return response()->json([
                    'success' => true,
                    'code' => 1,
                    'message' => 'User created successfully',
                    'data' => $user
                ], Response::HTTP_OK);
    
            }else{
                  //User not created, return error message
                  return response()->json([
                    'success' => false,
                    'code' => 2,
                    'message' => 'Something went wrong',
                ]);
            }
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'code' => 2,
                'message' =>  $this->getFirstErrorMessage($validator->errors()),
            ], 200);
        }else{

        //Request is validated
        //Create token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'code' => 2,
                	'message' => 'Login credentials are invalid.',
                ], 200);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                    'code' => 2,
                	'message' => 'Could not create token.',
                ], 200);
        }
 	
 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'code' => 1,
            'message' => 'Login Successfully',
            'token' => $token,
            'user_details' => $credentials['email']
        ]);
        }

       
    }


    public function logout(Request $request)
    {
       
        if(empty($request->token))
        {
            return response()->json([
                'success' => false,
                'code' => 2,
                'message' => 'Token is required'
            ]);
        }

		//Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'code' => 1,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => true,
                'code' => 1,
                'message' => 'err : ' . $exception->getMessage(),
            ]);
        }
    }

    public function delete(Request $request, string $userId)
    {

        $userModel = new User();

        $del_result = $userModel -> deleteUser($userId);

        if($del_result)
        {
            return response()->json([
                'success' => true,
                'code' => 1,
                'message' => 'User Deleted'
            ]);
        }

        else
        {
            return response()->json([
                'code' => 2,
                'message' => 'Error while Deleting user'
            ]);
        }

    }
    public function deleteTestUser(Request $request, string $email )
    {

        $userModel = new User();

        // $del_result = $userModel -> deleteTestUser($email);
        $del_result = $userModel -> deleteTestUser('john@example.com');

        if($del_result)
        {
            return response()->json([
                'success' => true,
                'code' => 1,
                'message' => 'User Deleted'
            ]);
        }

        else
        {
            return response()->json([
                'code' => 2,
                'message' => 'Error while Deleting user'
            ]);
        }

    }


    public function getFirstErrorMessage($errors)
    {
        $firstProperty = reset($errors);
        $firstValue = current($firstProperty);
    
        return $firstValue[0];
    }


}
