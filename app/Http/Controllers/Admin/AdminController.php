<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendPushNotification;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
use App\Models\Event;
use App\Models\Job;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\Order;
use App\Models\DeleteAccount;


use Illuminate\Validation\Rules\File;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Datatables;


class AdminController extends Controller
{
    public function dashboard() {

        $users         = User::where('id', '!=', 1)->count();
        $events        = Event::count();
        $jobs          = Job::count();
        $products      = Product::where('type', 'Product')->count();
        $services      = Product::where('type', 'Service')->count();
        $subscriptions = Subscription::count();
        $orders        = Order::count();
        $subs_earning  = Subscription::sum('plan_amount');
        $order_earning = Order::sum('total');
        $earnings      = (int) $subs_earning + $order_earning;

        return view('admin.dashboard', compact('users', 'events', 'jobs', 'products', 'services', 'subscriptions', 'orders', 'earnings'));
    }

    public function account_request() {
        return view('admin.deleteaccount');

    }

    public function testNotification(Request $request) {

        $title     = "Side Hustle Test Notification";
        $message   = "Side Hustle Test Message";
        $fcmTokens = [0 => $request->fcmToken];
        Notification::send(null,new SendPushNotification($title,$message, $fcmTokens));
        
    }

    public function updateToken(Request $request){
        try{
            $request->user()->update(['fcm_token'=>$request->token]);
            return response()->json([
                'success'=>true
            ]);
        }catch(\Exception $e){
            report($e);
            return response()->json([
                'success'=>false
            ],500);
        }
    }

    public function login(Request $request) {
        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            $user = User::where('email', $request->input('email'))->first();
            if ($user != null){
                if (Hash::check($request->input('password'), $user->password)) {
                    Auth::login($user);
                    if($user->role_id != 1) {
                        return redirect(route('admin.userDashboard'));
                    } else {
                        return redirect(route('admin.dashboard'));
                    }
                } else {
                    return back()->withErrors(['password' => 'invalid email or password']);
                }
            }else{
                return back()->withErrors(['password' => 'invalid email or password']);
            }
        }
        return view('admin.login');
    }

    public function deleteAccountRequest(Request $request) {
        if ($request->method() == 'POST') {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
            $user = User::where('email', $request->input('email'))->first();
            if ($user != null){
                if (Hash::check($request->input('password'), $user->password)) {
                
                    Auth::login($user);
                    return redirect(route('admin.userDashboard'));
                  
                }else{
                    return back()->withErrors(['password' => 'invalid email or password']);
                }
            }else{
                return back()->withErrors(['password' => 'invalid email or password']);
            }
        }
        return view('admin.user_login');
    }

    public function deleteAccountReq(Request $request) {

        try {
            $check = DeleteAccount::where("user_id", auth()->user()->id)->first();
            if($check == null) {
                $acc = DeleteAccount::create([
                    "user_id" => auth()->user()->id,
                    "name"  => auth()->user()->name,
                    "image" => auth()->user()->image,
                ]);
                return redirect()->back()->with("success", "Delete account request submitted");

            } else {
                return redirect()->back()->with("error", "You already create request for this.");

            }
        } catch (\Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }  
    }



    public function deleteAccountRequests() {
        $accounts = DeleteAccount::where('status', 0)->orderByDESC('id')->get();
        return view('admin.user_requests', compact('accounts'));
    }

    public function deleteAccount($request_id) {

        $account_req = DeleteAccount::find($request_id);
        $user        = User::find($account_req->user_id);
        if ($user != null) {
            $user->delete();
            $account_req->status = 1;
            $account_req->save();
            
            return redirect()->back()->with("success", "Account deleted successfully");
        }
        return redirect()->back()->with("error","Unauthorized");
    }

    // public function setting(Request $request) {

    //     $content = Setting::firstOrFail();
    //     if ($request->method() == 'POST') {

    //         $content->title             = $request->input('title') ?? '';
    //         $content->email             = $request->input('email');
    //         $content->phone             = $request->input('phone');
    //         $content->address           = $request->input('address');
    //         $content->facebook          = $request->input('facebook');
    //         $content->twitter           = $request->input('twitter');
    //         $content->instagram         = $request->input('instagram');
    //         $content->commission        = $request->input('commission');
    //         $content->stripe_api_key    = $request->input('stripe_api_key') ?? '';
    //         $content->stripe_secret_key = $request->input('stripe_secret_key') ?? '';
    //         $content->paypal_api_key    = $request->input('paypal_api_key') ?? '';
    //         $content->paypal_secret_key = $request->input('paypal_secret_key') ?? '';

    //         if ($request->has('logo')) {

    //             $dir      = "uploads/setting/";
    //             $file     = $request->file('logo');
    //             $fileName = time().'-setting.'.$file->getClientOriginalExtension();
    //             $file->move($dir, $fileName);
    //             $content->logo = $dir.$fileName;
    //         }
    //         $content->save();

    //         return redirect()->back()->with('success', 'Site Setting Updated Successfully');
    //     }
    //     return view('admin.setting', compact('content'));
    // }

    public function changePassword()
    {
        return view('admin.change_password');
    }

    public function updateAdminPassword(Request $request)
    {
        $id = Auth::user()->id;

        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (Hash::check($request->current_password, Auth::User()->password)) {
            $content = User::find($id);
            $content->password = Hash::make($request->password);
            $content->save();
            return redirect()->back()->with('success', 'Password Update Successfully.');
        }else{
            return back()->withErrors(['current_password' => 'Your current Password not recognized']);
        }

    }

    public function privacy_policy(Request $request) {

        $content = Setting::find(1);
        if ($request->method() == 'POST') {

            $content->privacy_policy = $request->input('privacy_policy');
            $content->save();

            return redirect()->back()->with('success', 'Privacy Policy Updated');
        }
        return view('admin.privacy_policy', compact('content'));
    }

    public function terms_and_conditions(Request $request) {

        $content = Setting::find(1);
        if ($request->method() == 'POST') {

            $content->terms_and_conditions = $request->input('terms_and_conditions');
            $content->save();

            return redirect()->back()->with('success', 'Terms & Condition Updated');
        }
        return view('admin.terms_and_conditions', compact('content'));
    }

    public function about_us(Request $request) {

        $content = Setting::find(1);
        if ($request->method() == 'POST') {

            $content->about_us = $request->input('about_us');
            $content->save();

            return redirect()->back()->with('success', 'About Us Updated');
        }
        return view('admin.about_us', compact('content'));
    }

    public function united_capitalism(Request $request) {

        $content = Setting::find(1);
        if ($request->method() == 'POST') {

            $content->united_capitalism = $request->input('united_capitalism');
            $content->save();

            return redirect()->back()->with('success', 'United Captialism!!');
        }
        return view('admin.united_capitalism', compact('content'));
    }
    
    public function pdfFile(Request $request) {
        $this->validate($request, [
            'file' => [
                'required',
                File::types('pdf')
            ],
        ],[
            'file.mimes' => 'The file must be in PDF Format',
        ]);
        $setting = Setting::find(1);
        $dirPath  = "uploads/files/PDF/";
        $fileName = $dirPath.time().'-'.$request->file->getClientOriginalName();
        $request->file->move(public_path($dirPath), $fileName);
        $setting->pdf_file = asset($fileName);
        $setting->save();
        return redirect()->back()->with('success', 'File uploaded successfully');
    }

    public function viewFile() {
        $setting = Setting::find(1);
        $filePath = $setting->pdf_file;
        if ($filePath != null) {
            $file = explode('/', $filePath, 4);
            $fileName = explode('/', $setting->pdf_file)[6];
            return response()->file(public_path($file[3]), [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename=' . $fileName . '',
            ]);
        }
    }
}
