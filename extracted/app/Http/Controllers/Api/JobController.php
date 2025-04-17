<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\SendPushNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Notifications;
use App\Models\Job;
use App\Models\JobImage;
use App\Models\JobRequest;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\UserCard;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use App\Models\User;
use App\Models\Review;
use App\Models\AddToFavourite;


class JobController extends Controller
{
    public function add_job(Request $request) {

        try {
          
            $validator  = Validator::make($request->all(), [
    
                "title"     => 'required',
                "location"  => 'required',
                "lat"       => 'required',
                "lng"       => 'required',
                "budget"    => 'required',
                "area_code" => 'required',
                "job_date"  => 'required',
                "job_time"  => 'required',
                // "plan_id"   => 'required'
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }

           
            $check_subscription = Subscription::where('user_id', Auth::user()->id)->where('model_name', 'Job')->first();
            if($check_subscription == null) {
                
                $product_id = Plan::where('id', $request->plan_id)->pluck('product_id')->first();
                $email      = auth()->user()->email;
                $userid     = auth()->user()->id;
                $usercard   = UserCard::where('user_id', $userid)->first();

                if($usercard == null) {
                    return $this->error("You don't have any card. Please add card fast");
                } else {
                    Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
                    $subscription = \Stripe\Subscription::create(array(
                        "customer" => $usercard->customer_id,
                        "items" => array(
                            array(
                                "plan" => $product_id,
                            ),
                        ),
                    ));
                    $subsData = $subscription->jsonSerialize();
    
                    if ($subsData['status'] == 'active' && $subscription) {
                        // Subscription info
    
                        $subscrID             = $subsData['id'];
                        // $subs_user_id      = $input['subs_user_id']; //Firebase User ID
                        $custID               = $subsData['customer'];
                        $planID               = $subsData['plan']['id'];
                        $planAmount           = ($subsData['plan']['amount'] / 100);
                        $planCurrency         = $subsData['plan']['currency'];
                        $planinterval         = $subsData['plan']['interval'];
                        $planIntervalCount    = $subsData['plan']['interval_count'];
                        $created              = date("Y-m-d H:i:s", $subsData['created']);
                        $current_period_start = date("Y-m-d H:i:s", $subsData['current_period_start']);
                        $current_period_end   = date("Y-m-d H:i:s", $subsData['current_period_end']);
                        $status               = $subsData['status'];
    
                        $subscription = Subscription::create([
                            'user_id'                => $userid,
                            'model_name'             => "Job",
                            'payer_email'            => $email,
                            'stripe_subscription_id' => $subscrID,
                            'stripe_customer_id'     => $custID,
                            'stripe_plan_id'         => $planID,
                            'plan_amount'            => $planAmount,
                            'plan_amount_currency'   => $planCurrency,
                            'plan_interval'          => $planinterval,
                            'plan_period_start'      => $current_period_start,
                            'plan_period_end'        => $current_period_end,
                            'payment_method'         => "Stripe",
                            'status'                 => 1,
                        ]);

                        $job                   = new Job();
                        $job->user_id          = Auth::user()->id;
                        $job->title            = $request->title;
                        $job->location         = $request->location;
                        $job->lat              = $request->lat;
                        $job->lng              = $request->lng; 
                        $job->description      = $request->description;
                        $job->budget           = $request->budget;
                        $job->area_code        = $request->area_code;
                        $job->job_date         = date('Y-m-d', strtotime($request->job_date));
                        $job->job_time         = $request->job_time;
                        $job->end_time         = $request->end_time;
                        $job->total_hours      = $request->total_hours;
                        $job->additional_information = $request->additional_information;
                        $job->save();
            
                        $fileName = "";
                        $dirPath  = "uploads/images/jobs/";
                        if($request->hasFile('images'))
                        {
                            foreach($request->file('images') as $image)
                            {
                                $fileName = $dirPath.time().'-'.$image->getClientOriginalName();
                                $image->move(public_path($dirPath), $fileName);
                                JobImage::create([
                                    'job_id' => $job->id,                    
                                    'image'  => asset($fileName),
                                ]);
                            }
                        }
            
                        return $this->success([], 'Job Created Successfully');
    
                    } else {
                        return $this->error($e->getMessage());
                    }
                }
            } else {

                    $job                   = new Job();
                    $job->user_id          = Auth::user()->id;
                    $job->title            = $request->title;
                    $job->location         = $request->location;
                    $job->lat              = $request->lat;
                    $job->lng              = $request->lng; 
                    $job->description      = $request->description;
                    $job->budget           = $request->budget;
                    $job->area_code        = $request->area_code;
                    $job->job_date         = date('Y-m-d', strtotime($request->job_date));
                    $job->job_time         = $request->job_time;
                    $job->end_time         = $request->end_time;
                    $job->total_hours      = $request->total_hours;
                    $job->additional_information = $request->additional_information;
                    $job->save();
        
                    $fileName = "";
                    $dirPath  = "uploads/images/jobs/";
                    if($request->hasFile('images'))
                    {
                        foreach($request->file('images') as $image)
                        {
                            $fileName = $dirPath.time().'-'.$image->getClientOriginalName();
                            $image->move(public_path($dirPath), $fileName);
                            JobImage::create([
                                'job_id' => $job->id,                    
                                'image'  => asset($fileName),
                            ]);
                        }
                    }
        
                    return $this->success([], 'Job Created Successfully');

            }
        } catch (\Exception $e) {
           
            return $this->error($e->getMessage());
        }
    }

    public function edit_job(Request $request) {

        $validator  = Validator::make($request->all(), [
            "job_id" => 'required',
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }
        $job = Job::with('images')->find($request->job_id);
        return $this->success($job);
    }

    public function update_job(Request $request) {

        try {

            $validator  = Validator::make($request->all(), [
                "job_id" => 'required'
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }

            $job                         = Job::findOrFail($request->job_id);
            $job->title                  = $request->title;
            $job->location               = $request->location;
            $job->lat                    = $request->lat;
            $job->lng                    = $request->lng; 
            $job->description            = $request->description;
            $job->budget                 = $request->budget;
            $job->area_code              = $request->area_code;
            $job->job_date               = date('Y-m-d', strtotime($request->job_date));
            $job->job_time               = $request->job_time;
            $job->end_time               = $request->end_time;
            $job->total_hours            = $request->total_hours;
            $job->additional_information = $request->additional_information;
            $job->save();

            $fileName = "";
            $dirPath  = "uploads/images/jobs/";
            if($request->hasFile('images'))
            {
                foreach($request->file('images') as $image)
                {
                    $fileName = $dirPath.time().'-'.$image->getClientOriginalName();
                    $image->move(public_path($dirPath), $fileName);

                    $update_image = JobImage::firstOrNew([
                        "job_id" => $job->id, 
                        'image'  => asset($fileName),
                    ]);
                    $update_image->save();

                    // JobImage::create([
                    //     'job_id' => $job->id,                    
                    //     'image'  => asset($fileName),
                    // ]);
                }
            }

            return $this->success($job, 'Job Updated Successfully');

        } catch (\Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }
    }

    public function job_detail(Request $request) {

        try {

            $job_id = $request->job_id;
            $job    = Job::with('user','images')->where('id', $job_id)->first();

            $check_applied = JobRequest::where('job_id', $job_id)->where('user_id', Auth::user()->id)->first();
            if($check_applied == null) {
                $arr["is_applied"] = 0;
            } else {
                $arr["is_applied"] = 1;
            }
            
            $arr["title"]       = $job->title;
            $arr["budget"]      = $job->budget; 
            $arr["area_code"]   = $job->area_code; 
            $arr["job_date"]    = $job->job_date; 
            $arr["job_time"]    = $job->job_time; 
            $arr["end_time"]    = $job->end_time;
            $arr["total_hours"] = $job->total_hours; 
            $arr["location"]    = $job->location; 
            $arr["lat"]         = $job->lat; 
            $arr["lng"]         = $job->lng;
            $arr["description"] = $job->description; 
            $arr["additional_information"] = $job->additional_information;
            $arr["status"] = $job->status; 

            $arr["user_detail"] = ["userid"=>$job["user"]->id, "name"=>$job["user"]->name, "image"=>$job["user"]->image];

            if(count($job["images"]) > 0) {
                foreach ($job["images"] as $item) {
                    # code...
                    $arr["images"][] = ["image"=> $item->image];
                }
            } else {
                $arr["images"] = [];
            }

            return $this->success($arr);
        } catch (\Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }
    }

    public function apply_for_job(Request $request) {
        
        try {
           
            $validator  = Validator::make($request->all(), [
                "job_id"     => 'required',
                "bid_amount" => 'required',
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }


            $job = Job::with('user','images')->where('id', $request->job_id)->first();

            // $check_applied = JobRequest::where('job_id', $job->id)->where('user_id', Auth::user()->id)->first();
            // if($check_applied == null) {

            $job_request             = new JobRequest();
            $job_request->user_id    = Auth::user()->id;
            $job_request->owner_id   = $job->user_id;
            $job_request->job_id     = $request->job_id;
            $job_request->bid_amount = $request->bid_amount;
            $job_request->save();

            $arr["title"]       = $job->title;
            $arr["budget"]      = $job->budget; 
            $arr["area_code"]   = $job->area_code; 
            $arr["job_date"]    = $job->job_date; 
            $arr["job_time"]    = $job->job_time; 
            $arr["end_time"]    = $job->end_time;
            $arr["total_hours"] = $job->total_hours; 
            $arr["location"]    = $job->location; 
            $arr["lat"]         = $job->lat; 
            $arr["lng"]         = $job->lng;
            $arr["description"] = $job->description; 
            $arr["additional_information"] = $job->additional_information;
            $arr["status"]      = $job->status; 
            $arr["is_applied"]  = 1;

            $arr["user_detail"] = ["userid"=>$job["user"]->id, "name"=>$job["user"]->name, "image"=>$job["user"]->image];

            if(count($job["images"]) > 0) {
                foreach ($job["images"] as $item) {
                    # code...
                    $arr["images"][] = ["image"=> $item->image];
                }
            } else {
                $arr["images"] = [];
            }

            $check         = User::find($job->user_id);

            if($check->is_push_notification == 1) {
                
                $title     = "Apply For Job";
                $message   = Auth::user()->name." applid on your job ". $job->title;
                $fcmTokens = User::where('id',$job->user_id)->pluck('fcm_token')->first();
                Notification::send(null,new SendPushNotification($title,$message,[$fcmTokens]));

                $notify               = new Notifications();
                $notify->sender_id    = Auth::user()->id;
                $notify->receiver_id  = $job->user_id;
                $notify->title        = $title;
                $notify->notification = $message;
                $notify->is_read      = 0;
                $notify->save();
            }

            return $this->success($arr, 'Job Request sent Successfully');
            // } else {

            //     $job    = Job::with('user','images')->where('id', $job->id)->first();
            //     $job->is_applied = 1;
            //     return $this->error("You already applied for this job",200,$job);
            // }

        } catch (\Exception $e) {
           
            return $this->error($e->getMessage());
        }
    }

    public function job_requests(Request $request) {

        $userid       = Auth::user()->id;
        $job_requests = JobRequest::with('owner', 'applier', 'job')->where('owner_id', $userid)->where("job_id", $request->job_id)->where('status', 'Pending')->get();

        if(count($job_requests) > 0) {
            foreach ($job_requests as $item) {
                $arr[] = [
                            "job_request_id" => $item->id,
                            "job_id"         => $item->job_id,
                            "bid_amount"     => $item->bid_amount,
                            "user_id"        => $item["applier"]->id,
                            "username"       => $item["applier"]->name,
                            "rating"         => $item["applier"]->rating,
                            "image"          => $item["applier"]->image,
                        ];
            }
        } else {
            $arr = [];
        }

        return $this->success($arr);
    }

    public function update_job_request_status(Request $request) {
        try {
           
            $validator  = Validator::make($request->all(), [
                "job_request_id" => 'required',
                "job_id"         => 'required',
                "applier_id"     => 'required',
                "status"         => 'required',
                "bid_amount"     =>  'required'

            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }

            $applier_id = $request->applier_id;

            if($request->status == "Rejected") {

                $update_req         = JobRequest::find($request->job_request_id);
                $update_req->status = $request->status;
                $update_req->save();

                $check         = User::find($applier_id);
                if($check->is_push_notification == 1) {
                    $title     = "Job Request Status";
                    $message   = Auth::user()->name." rejected your job request.";
                    $fcmTokens = User::where('id',$applier_id)->pluck('fcm_token')->first();

                    Notification::send(null,new SendPushNotification($title,$message,[$fcmTokens]));

                    $notify               = new Notifications();
                    $notify->sender_id    = Auth::user()->id;
                    $notify->receiver_id  = $applier_id;
                    $notify->title        = $title;
                    $notify->notification = $message;
                    $notify->is_read      = 0;
                    $notify->save();
                }

            } else if($request->status == "Approved") {

                // dd($request);
                
                $update_req         = JobRequest::find($request->job_request_id);
                $update_req->status = "Approved";
                $update_req->save();
                
                JobRequest::where('job_id', $request->job_id)->where('user_id','!=',$applier_id)->update(['status' => 'Rejected']);

                $update_status                     = Job::find($request->job_id);
                $update_status->assigned_user_id   = $applier_id;
                $update_status->bid_amount         = $request->bid_amount;
                $update_status->status             = $request->status;
                $update_status->save();

                $check         = User::find($applier_id);

                if($check->is_push_notification == 1) {
                    
                    $title     = "Job Request Status";
                    $message   = Auth::user()->name." hired you for the job.";
                    $fcmTokens = User::where('id',$applier_id)->pluck('fcm_token')->first();
                    Notification::send(null,new SendPushNotification($title,$message,[$fcmTokens]));

                    $notify               = new Notifications();
                    $notify->sender_id    = Auth::user()->id;
                    $notify->receiver_id  = $applier_id;
                    $notify->title        = $title;
                    $notify->notification = $message;
                    $notify->is_read      = 0;
                    $notify->save();
                }
            }

            $userid       = Auth::user()->id;
            $job_requests = JobRequest::with('owner', 'applier', 'job')->where('owner_id', $userid)->where('status', 'Pending')->get();

            if(count($job_requests) > 0) {
                foreach ($job_requests as $item) {
                    $arr[] = [
                                "job_request_id" => $item->id,
                                "job_id"         => $item->job_id,
                                "bid_amount"     => $item->bid_amount,
                                "user_id"        => $item["applier"]->id,
                                "username"       => $item["applier"]->name,
                                "rating"         => $item["applier"]->rating,
                                "image"          => $item["applier"]->image,
                            ];
                }
            } else {
                $arr = [];
            }

            return $this->success($arr, 'Job request status updated Successfully');

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function wanted_jobs(Request $request) {

        try {
            //code...
            $arr = [];
            $zip_code = Auth::user()->zip_code;
            $userid   = auth()->user()->id;
            $favourites = AddToFavourite::where("user_id", $userid)->where("model_name", "Job")->pluck('model_id')->toArray();

            $collection = collect($favourites); // Convert array to collection

            if($request->type == "WantedJobs") {
                if($request->search != null) {
                    $w_jobs = Job::with('user', 'images')->where('user_id', '!=', Auth::user()->id)->where('status', 'Pending')->where('area_code', $zip_code)->where('title','LIKE','%'.$request->search.'%')->get();
                } else {
                    $w_jobs = Job::with('user', 'images')->where('user_id', '!=', Auth::user()->id)->where('status', 'Pending')->where('area_code', $zip_code)->get();
                }
                if(count($w_jobs) > 0) {
                    foreach ($w_jobs as $item) {

                        if(count($item['images']) > 0) {
                            $image = $item["images"][0]->image; 
                        } else {
                            $image = "";
                        }
                        if ($collection->contains($item->id)) {
                            $is_favourite = 1;
                        } else {
                            $is_favourite = 0;
                        }
                        $arr[] = [
                            "is_favourite" => $is_favourite,
                            "job_id" => $item->id,
                            "title"  => $item->title,
                            "description" => $item->description,
                            "budget" => $item->budget,
                            "image"  => $image,
                            "user"   => [
                                            "userid"=>$item["user"]->id,
                                            "name"=>$item["user"]->name,
                                            "image"=>$item["user"]->image,
                                            "rating"=>$item["user"]->rating
                                        ]
                        ];
                    }
                } else {
                    $arr = [];
                }
            }
    
            if($request->type == "Applied") {

                if($request->search != null) {
                    $w_jobs = JobRequest::with('job','job.images')->where('user_id', Auth::user()->id)->where('status', 'Pending')->where('title','LIKE','%'.$request->search.'%')->get();
                } else {
                    $w_jobs = JobRequest::with('job', 'job.images')->where('user_id', Auth::user()->id)->where('status', 'Pending')->get();
                }

                if(count($w_jobs) > 0) {
                    foreach ($w_jobs as $item) {
                        if(count($item['job']['images']) > 0) {
                            $image = $item['job']["images"][0]->image; 
                        } else {
                            $image = "";
                        }
                        $arr[] = [
                            "job_id" => $item->job_id,
                            "title"  => $item['job']->title,
                            "description" => $item['job']->description,
                            "budget" => $item->bid_amount,
                            "image"  => $image,
                        ];
                    }
                } else {
                    $arr = [];
                }

            }
    
            if($request->type == "Booked") {
                if($request->search != null) {
                    $w_jobs = Job::with('images')->where('assigned_user_id', Auth::user()->id)->where('status', 'Approved')->where('title','LIKE','%'.$request->search.'%')->get();
                } else {
                    $w_jobs = Job::with('images')->where('assigned_user_id', Auth::user()->id)->where('status', 'Approved')->get();
                }   
                if(count($w_jobs) > 0) {
                    foreach ($w_jobs as $item) {

                        if(count($item['images']) > 0) {
                            $image = $item["images"][0]->image; 
                        } else {
                            $image = "";
                        }
                        $arr[] = [
                            "job_id"      => $item->id,
                            "title"       => $item->title,
                            "description" => $item->description,
                            "budget"      => $item->budget,
                            "image"       => $image,
                        ];
                    }
                } else {
                    $arr = [];
                }
                  return $this->success($arr);
            }

            if($request->type == "Completed") {
                if($request->search != null) {
                    $w_jobs = Job::with('assign_user')->where('user_id', Auth::user()->id)->where('status', 'Completed')->where('title','LIKE','%'.$request->search.'%')->get();
                    $w_jobs = Job::where('assigned_user_id', Auth::user()->id)->where('status', 'Booked')->get();
                } else {
                    // $w_jobs = Job::with('assign_user')->where('user_id', Auth::user()->id)->where('status', 'Completed')->get();
                    $w_jobs = Job::with('assign_user')->where('assigned_user_id', Auth::user()->id)->where('status', 'Completed')->get();

                }

               
                if(count($w_jobs) > 0) {
                    foreach ($w_jobs as $item) {

                        $check_review = Review::with('owner')->where("model_name", "Job")->where("model_id", $item->id)->where("tasker", auth()->user()->id)->first();
                        if($check_review != null) {
                            $review_image = $check_review->owner->image;
                            $owner_name   = $check_review->owner->name;
                            $rating       = $check_review->rating;
                        } else {
                            $review_image = null;
                            $owner_name   = null;
                            $rating       = null;
                        }

                        if(count($item['images']) > 0) {
                            $image = $item["images"][0]->image; 
                        } else {
                            $image = "";
                        }
                        $arr[] = [
                            "job_id"      => $item->id,
                            "title"       => $item->title,
                            "description" => $item->description,
                            "budget"      => $item->budget,
                            "image"       => $image,
                            "review_image" => $review_image,
                            "review_name"  => $owner_name,
                            "rating"       => $rating,
                        ];
                    }
                } else {
                    $arr = [];
                }
                  return $this->success($arr);
            }

            return $this->success($arr);
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    public function update_job_status(Request $request) {
        try {

            $job         = Job::find($request->job_id);
            $job->status = $request->status;
            $job->save();

            $w_jobs = Job::with('images')->where('assigned_user_id', Auth::user()->id)->where('status', 'Approved')->get();
            if(count($w_jobs) > 0) {
                foreach ($w_jobs as $item) {

                    if(count($item["images"]) > 0) {
                        $image = $item["images"][0]->image; 
                    } else {
                        $image = ""; 
                    }

                    $arr[] = [
                        "job_id"      => $item->id,
                        "title"       => $item->title,
                        "description" => $item->description,
                        "budget"      => $item->budget,
                        "image"       => $image,
                    ];
                }
            } else {
                $arr = [];
            }

            return $this->success($arr, 'Job status updated');

        } catch (\Exception $e) {

            return $this->error($e->getMessage());
        }
    }

    public function my_jobs(Request $request) {

        try {
            //code...
            $arr = [];
            
            if($request->type == "Pending") {
     
                $w_jobs = Job::with('user', 'images')->where('user_id', Auth::user()->id)->where('status', 'Pending')->get();
                if(count($w_jobs) > 0) {
                    foreach ($w_jobs as $item) {
                        if(count($item['images']) > 0) {
                            $image = $item["images"][0]->image; 
                        } else {
                            $image = "";
                        }
                        $arr[] = [
                            "job_id" => $item->id,
                            "image"  => $image,
                            "title"  => $item->title,
                            "description" => $item->description,
                            "budget" => $item->budget,
                        ];
                    }
                } else {
                    $arr = [];
                }
            } else if($request->type == "Ongoing") {

                $w_jobs = Job::with('assign_user', 'images')->where('user_id', Auth::user()->id)->where('status', 'Ongoing')->get();
                
                if(count($w_jobs) > 0) {
                    foreach ($w_jobs as $item) {
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
                            "user_detail" => ["userid"=>$item["assign_user"]->id, "image"=>$item["assign_user"]->image, "name"=>$item["assign_user"]->name]
                        ];
                    }
                } else {
                    $arr = [];
                }
            } else if($request->type == "Completed") {

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
            }

            return $this->success($arr);
        } catch (\Exception $e) {
            //throw $th;
        }

    }

    public function user_jobs(Request $request) {
        try {
            //code...
            $arr = [];
            $w_jobs = Job::with('user', 'images')->where('user_id', $request->user_id)->where('status', 'Pending')->get();
            if(count($w_jobs) > 0) {
                foreach ($w_jobs as $item) {
                    if(count($item['images']) > 0) {
                        $image = $item["images"][0]->image; 
                    } else {
                        $image = "";
                    }
                    $arr[] = [

                        "job_id" => $item->id,
                        "image"  => $image,
                        "title"  => $item->title,
                        "description" => $item->description,
                        "budget"   => $item->budget,
                        "user_id"  => $item->user_id,
                        "username" => $item['user']->name,
                        "user_image" => $item['user']->image,
                        "rating"   => $item['user']->rating,
                    ];
                }
            } else {
                $arr = [];
            }

            return $this->success($arr);
        } catch (\Exception $e) {
            //throw $th;
        }
    }

}
