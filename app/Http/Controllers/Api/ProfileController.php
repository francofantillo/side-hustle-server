<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Shop;
use App\Models\Job;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function online_user_profile() {
        $userid = Auth::user()->id;
        $user   = User::find($userid);

        $shop = Shop::where('user_id', $userid)->first();

        if($shop == null) {
            $shop_id = 0;
        } else {
            $shop_id = $shop->id;
        }

        $my_jobs   = Job::where('user_id', $userid)->count('id');
        $comp_jobs = Job::where('user_id', $userid)->where('status', 'Completed')->count('id');
        $my_events = Event::where('user_id', $userid)->count('id');

        $arr["id"]             = $user->id;
        $arr["shop_id"]        = $shop_id;
        $arr["image"]          = $user->image;
        $arr["name"]           = $user->name;
        $arr["email"]          = $user->email;
        $arr["my_jobs"]        = $my_jobs;
        $arr["completed_jobs"] = $comp_jobs;
        $arr["my_events"]      = $my_events;

        return $this->success($arr);
    }

    public function is_notification(Request $request) {
        $validator  = Validator::make($request->all(), [
            "is_notify"       => 'required'
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }

        $update_notify                       = User::find(Auth::user()->id);
        $update_notify->is_push_notification = $request->is_notify;
        $update_notify->save();

        return $this->success($update_notify, 'Status Update Successfully');
    }

    public function your_profile() {
        $userid = Auth::user()->id;
        $user   = User::find($userid);

        $arr["id"]    = $userid;
        $arr["name"]  = $user->name;
        $arr["email"] = $user->email;
        $arr["image"] = $user->image;
        $arr["my_jobs"]        = Job::where('user_id', $user->id)->count();
        $arr["completed_jobs"] = Job::where('user_id', $userid)->where('status', 'Completed')->count('id');
        $arr["my_events"]      = Event::where('user_id', $userid)->count('id');


        return $this->success($arr);


    }

    public function deleteAccount() {

        $user = User::find(Auth::id());
        if ($user != null){
            $user->delete();
            return $this->success([], "Account deleted successfully");
        }
        return $this->error("Unauthorized");
    }
}
