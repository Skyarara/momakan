<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function login()
    {
        if (!request()->has('fcm_token')) return response()->json(['errors' => 'Parameter tidak valid'], 400);
        $status = false;
        $message = "";
        $result = null;
        $check_is_valid_email = filter_var(request('email_phone'), FILTER_VALIDATE_EMAIL);
        if ($check_is_valid_email) {
            if (Auth::attempt(['email' => request('email_phone'), 'password' => request('password')])) {
                $user = Auth::user();
                $user_tb            = User::find(Auth::user()->id);
                $user_tb->fcm_token = request('fcm_token');
                $user_tb->save();

                $token = $user->createToken('MyApp')->accessToken;
                $photo_profile = Auth::user()->photo_profile ? url("/storage/images/photo_profile/" . Auth::user()->photo_profile) : '';
                $user = ([
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone_number' => Auth::user()->phone_number,
                    'role_id' => Auth::user()->role_id,
                    'photo_profile' => $photo_profile,
                ]);

                $result['access_token'] = $token;
                $result['user'] = $user;

                $status = true;
                $message = "Successfully logged in user";

                return response()->json([
                    'status' => $status,
                    'message' => $message,
                    'data' => $result
                ]);
            } else {
                return response()->json(['error' => 'Email / Nomor HP dan password tidak valid'], 401);
            }
        } else {
            if (Auth::attempt(['phone_number' => request('email_phone'), 'password' => request('password')])) {
                $user = Auth::user();

                $user_tb            = User::find(Auth::user()->id);
                $user_tb->fcm_token = request('fcm_token');
                $user_tb->save();

                $token = $user->createToken('MyApp')->accessToken;
                $photo_profile = Auth::user()->photo_profile ? url("/storage/images/photo_profile/" . Auth::user()->photo_profile) : '';
                $user = ([
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone_number' => Auth::user()->phone_number,
                    'role_id' => Auth::user()->role_id,
                    'photo_profile' => $photo_profile,
                ]);

                $result['access_token'] = $token;
                $result['user'] = $user;

                $status = true;
                $message = "Successfully logged in user";

                return response(json_encode([
                    'status' => $status,
                    'message' => $message,
                    'data' => $result
                ], JSON_UNESCAPED_SLASHES))->header('Content-Type', "application/json");
            } else {
                return response()->json(['error' => 'Email / Nomor HP dan password tidak valid'], 401);
            }
        }
    }
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
        return response()->json(['success' => $success], $this->successStatus);
    }
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
}
