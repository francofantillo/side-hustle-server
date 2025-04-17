<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\SendPushNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Notifications;
use App\Models\User;
use App\Models\Review;
use App\Models\Job;



class ReviewController extends Controller
{
    public function add_review(Request $request) {

        $validator  = Validator::make($request->all(), [
            "model_id"   => 'required', 
            "model_name" => 'required', 
            "tasker"     => 'required', 
            "rating"     => 'required', 
            "review"     => 'required', 
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }
        $review = Review::create([

            "model_id"   => $request->model_id,
            "model_name" => $request->model_name,
            "task_giver" => Auth::user()->id,
            "tasker"     => $request->tasker,
            'rating'     => $request->rating,
            'review'     => $request->review,
        ]);

        $avg_rating    = Review::where('tasker', $request->tasker)->avg('rating');

        $update_review_count         = User::find($request->tasker);
        $update_review_count->rating = number_format($avg_rating, 1);
        $update_review_count->save();

        $check         = User::find($request->tasker);

        if($check->is_push_notification == 1) {
                    
            $title      = "Review";
            $message    = Auth::user()->name.' add review on your job.';
            $fcmTokens  = User::where('id',$request->tasker)->pluck('fcm_token')->first();
            Notification::send(null,new SendPushNotification($title,$message,[$fcmTokens]));

            $notify               = new Notifications();
            $notify->sender_id    = Auth::user()->id;
            $notify->receiver_id  = $request->tasker;
            $notify->title        = $title;
            $notify->notification = $message;
            $notify->is_read      = 0;
            $notify->save();
        }

        $w_jobs = Job::with('assign_user', 'images')->where('user_id', Auth::user()->id)->where('status', 'Completed')->get();

                
        if(count($w_jobs) > 0) {
            foreach ($w_jobs as $item) {

                $check_review = Review::where("model_name", "Job")->where("model_id", $item->id)->where("task_giver", auth()->user()->id)->first();

                if($check_review == null) {
                    $is_reviewed = 0;
                } else {
                    $is_reviewed = 1;
                }

                if(count($item['images']) > 0) {
                    $image = $item["images"][0]->image; 
                } else {
                    $image = "";
                }
                $arr[] = [
                    "job_id"      => $item->id,
                    "image"       => $image,
                    "title"       => $item->title,
                    "description" => $item->description,
                    "budget"      => $item->budget,
                    "image"       => $image,
                    "is_reviewed" => $is_reviewed,
                    "user_detail" => ["userid"=>$item["assign_user"]->id, "image"=>$item["assign_user"]->image, "name"=>$item["assign_user"]->name]
                ];
            }
        } else {
            $arr = [];
        }

        return $this->success($arr, 'Review Added Successfully');
    }
}
