<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


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
            'name' => 'required',
            // 'email' => 'required|email',
            'email' => 'required|email|unique:users,email|min:3',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password|min:8',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
        return $this->sendResponse($success, 'User register successful.');
    }


    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            /** @var \App\Models\User $user **/
            $user = Auth::user();
            // $user->tokens()->delete();
            // $user->remember_token = null; //added
            // $user->save(); // added
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;

            // return $this->sendResponse($success, 'Checking token.');

            $success['name'] =  $user->name;
            $success['avatar'] = null; // added

            // this whole if statement was added, but the return statement after was already there
            if (isset($user->avatar)) {
                $success['avatar'] = $this->getS3Url($user->avatar);
            }
            return $this->sendResponse($success, 'User login successful.');
        } else {
            return $this->sendError('Access Denied.', ['error' => 'Unauthorized']);
        }
    }




    /**
     * Logout api
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $user = User::find($request->id);
        $user->tokens()->where('id', $request->token)->delete();
        $success['id'] =  $request->id;

        return $this->sendResponse($success, 'User logout successful. Token cleared.');
    }

    public function forgotPassword(Request $request)
    {
    }
}
