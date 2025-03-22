<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;


class AuthController extends Controller
{
    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, $validator->errors());
        }

        $user = User::where('email', $request->email)->where('is_verified', 1)->first();
        
        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {
                Auth::login($user);
                $user->api_token =  auth()->user()->createToken('API Token')->plainTextToken;
                $user->save();
                return $this->success($user);

            } else {
                
                return $this->error("Invalid Login Credentials..!!");
            }
        } else {
            
            $check_user_email = User::where('email', $request->email)->first();

            if ($check_user_email != null) {
                $digits   = 6;
                // $otpToken = rand(1000, 9999);
                // $otpToken = rand(pow(10, $digits-1), pow(10, $digits)-1);
                // $otpToken = 123456;
                $otpToken = rand(1000, 9999);
                $otpToken = rand(pow(10, $digits-1), pow(10, $digits)-1);
                $token    = $check_user_email->createToken('API Token')->plainTextToken;

                try {
                    $messageBody = env('APP_NAME')."\nNew OTP token is:$otpToken";
                    $this->sendMessageToClient($check_user_email->phone, $messageBody);
    
                } catch (\Exception $ex){
                    return $this->error($ex->getMessage());
                }
    
                $check_user_email->api_token = $token;
                $check_user_email->otp       = $otpToken;
                $check_user_email->save();
    
                return $this->success(array("is_verified"=>0, "otp" => $otpToken, "api_token" => $token, "user_id" => $check_user_email->id), "Your account is not verified yet..!!");
            } else {
               
                return $this->error("Email and Password is incorrect.!!");

            }
        }
      
    }

    public function register(Request $request) {

        try {
            //code...
            $check_user = User::where('phone', $request->phone)->orWhere('email', $request->email)->first();
            $validator  = Validator::make($request->all(), [
                "first_name" => 'required',
                'last_name'  => 'required',
                'phone'      => 'required',
                'email'      => 'required',
                'password'   => 'required',
                'confirm_password' => 'required',
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 202, [], $validator->errors());
            }

            if($check_user == null) {
    
                $digits   = 6;
                $otpToken = rand(1000, 9999);
                $otpToken = rand(pow(10, $digits-1), pow(10, $digits)-1);
                // $otpToken =  123456;
    
                $user = User::create([
                    "role_id"     => 2,
                    "first_name"  => $request->first_name,
                    "last_name"   => $request->last_name,
                    "name"        => $request->first_name.' '.$request->last_name,
                    "phone"       => $request->phone,
                    "email"       => $request->email,
                    "password"    => Hash::make($request->password),
                    "otp"         => $otpToken,
                    "zip_code"    => $request->zip_code,
                    "country"     => $request->country,
                    "is_push_notification" => 1,

                ]);
                $token           = $user->createToken('API Token')->plainTextToken;
                $user->api_token = $token;
                $user->save();

                try {
                    $messageBody = env('APP_NAME')."\nOTP token is:$otpToken";
                    $this->sendMessageToClient($user->phone, $messageBody);
    
                } catch (\Exception $ex){
                    return $this->error($ex->getMessage());
                }
    
                return $this->success(array("otp" => $otpToken, "api_token" => $token,"user_id" => $user->id));
            } else {
                return $this->error('Phone Number or Email is already exist...!!', 202);
            }
        } catch (\Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }

    }

     public function socialLogin(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required',
            'provider_id' => 'required',
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }

        // search for a user in our server with the specified provider id and provider name
        $user      = User::where('email', $request->email)->first();
        $firstName = null;
        $lastName  = null;
        if (str_contains($request->name, ' ')){
            $name = explode(' ', $request->name);
            $firstName = $name[0];
            $lastName = $name[1];
        }
        // if there is no record with these data, create a new user
        if($user == null) {
            $user = User::create([
                "role_id"       => $request->user_type,
                "name"          => $request->name,
                "first_name"    => $firstName,
                "last_name"     => $lastName,
                "email"         => $request->email,
                "password"      => Hash::make("12345"),
                "image"         => $request->image,
                'provider_name' => $request->provider_name,
                'provider_id'   => $request->provider_id,
            ]);

            // $title                = "Register";
            // $message              = $user->name.' creates an account through social login';
            // $notify               = Notification::createNotification($user->id,1, $title, $message, 0);
        }

        $user->api_token =  $user->createToken('API Token')->plainTextToken;
        $user->save();

        // // create a token for the user, so they can login
        // $token = $user->createToken('API Token')->plainTextToken;

        return $this->success($user);
    }

    
    public function verifyToken(Request $request) {

        $validator = Validator::make($request->all(), [
            'otp_token' => 'required',
            'api_token' => 'required'
        ]);

        if ($validator->fails()){
            return $this->error('Validation Error', 429, [], $validator->errors());
        }
        if ($request->has('otp_token')) {
            $user = User::where("api_token", $request->api_token)->first();
            if (isset($user->otp) && $user->otp == $request->otp_token) {
                $user->api_token = $user->createToken('API Token')->plainTextToken;
                $user->status    = 1;
                $user->is_verified = 1;
                $user->save();
                // Auth::login($user);
                return $this->success($user, 'Token Verified Successfully.');
            } else {
                return $this->error('Invalid OTP Token',202);
            }
        } else {
            return $this->error('OTP Token Required', 422);
        }
    }

    public function resendOtpToken(Request $request) {

        $validator = Validator::make($request->all(), [
            'api_token' => 'required'
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }

        $user = User::where("api_token", $request->api_token)->first();
        if ($user != null) {
            $digits = 6;
            $otpToken = rand(1000, 9999);
            $otpToken = rand(pow(10, $digits-1), pow(10, $digits)-1);
            // $otpToken =  123456;
            $user->otp = $otpToken;
            $user->save();

            try {
                $messageBody = env('APP_NAME')."\nNew OTP token is:$otpToken";
                $this->sendMessageToClient($user->phone, $messageBody);

            } catch (\Exception $ex){
                return $this->error($ex->getMessage());
            }

            return $this->success(array("otp" => $otpToken), "OTP resent successfully");
      
        } else {
            return $this->error('Invalid User');
        }
    }

    public function setPassword(Request $request) {

        $validator = Validator::make($request->all(), [

            'password'         => 'min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:6',
            'api_token'        => 'required'
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 429, [], $validator->errors());
        }

        $user = User::where("api_token", $request->api_token)->first();
        if($user != null) {
            $user->api_token = $user->createToken('API Token')->plainTextToken;
            $user->password  = Hash::make($request->password);
            $user->save();
            Auth::login($user);
            return $this->success($user, 'Password Set Successfully.');
        } else {
            return $this->error("Please enter valid API token.");
        }
    }

    public function updateFcmToken(Request $request) {

        try {
            //code...
            $validator = Validator::make($request->all(), [
                'fcm_token' => 'required'
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 429, [], $validator->errors());
            }
            $user            = Auth::user();
            $user->fcm_token = $request->fcm_token;
            $user->save();
            return $this->success($user, 'FCM Token Updated Successfully.');
        } catch (\Exception $e) {
           return $this->error($e->getMessage());
        }

    }

    public function updateUserLocation(Request $request) {
        
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'location' => 'required',
                'lat'      => 'required',
                'lng'      => 'required',
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 429, [], $validator->errors());
            }
            $user            = Auth::user();
            $user->location  = $request->location;
            $user->lat       = $request->lat;
            $user->lng       = $request->lng;
            $user->save();
            
            return $this->success($user, 'Location Updated.');
        } catch (\Exception $e) {
           return $this->error($e->getMessage());
        }
    }


    public function logout(Request $request) {

        Auth::user()->tokens()->delete();
        return response()->json([
            'status'  => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function forgotPassword(Request $request) {

        $validator = Validator::make($request->all(), [
            'phone'      => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 200, [], $validator->errors());
        }

        $user = User::where('phone', $request->phone)->first();
        if ($user != null) {

            $digits          = 6;
            $otpToken        = rand(pow(10, $digits-1), pow(10, $digits)-1);
            // $otpToken        = 123456;
            $user->api_token = $user->createToken('API Token')->plainTextToken;
            $user->otp       = (string)$otpToken;
            //$user->api_token = $user->createToken('API Token')->plainTextToken;
            $user->save();
            try {
                $messageBody = env('APP_NAME')."\nOTP token is:$otpToken";
                $this->sendMessageToClient($user->phone, $messageBody);

            } catch (\Exception $ex){
                return $this->error($ex->getMessage());
            }

            // $data = array('otp' => $user->otp, 'token' => $user->api_token);
            return $this->success($user, 'OTP has been sent on your phone.');
        }else {
            return $this->error('Your Phone is not registered. Please Signup', 200);
        }
    }

    // public function resetPassword(Request $request) {

    //     $validator = Validator::make($request->all(), [
    //         'old_password'     => 'required',
    //         'new_password'     => 'min:6|required_with:confirm_password|same:confirm_password',
    //         'confirm_password' => 'min:6'
    //     ]);
    //     if ($validator->fails()){
    //         return $this->error('Validation Error', 429, [], $validator->errors());
    //     }
    //     $user           = Auth::user();

    //     if (Hash::check($request->old_password, $user->password)) {

    //         $user->password = Hash::make($request->new_password);
    //         $user->save();
    //         return $this->success([], 'Password Updated Successfully');
    //     } else {
    //         return $this->error("Please enter correct old password");
    //     }

    // }

    public function changePassword(Request $request) {

        $validator = Validator::make($request->all(),[
            "old_password"     => "required",
            'new_password'     => 'required',
            'confirm_password' => 'required'
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 429, [], $validator->errors());
        }
        $user = Auth::user();
     
        if(Hash::check($request->old_password, $user->password)) {

            $user->password = Hash::make($request->new_password);
            $user->save();

            return $this->success([], 'Password Updated Successfully');
        } else {
            return $this->error("Please enter old password correctly..!!");
        }
    }

    public function unauthenticatedUser() {
        return $this->error('Unauthorized', 401);
    }
}
