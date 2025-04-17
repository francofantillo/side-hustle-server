<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use App\Models\User;
use App\Models\EventImage;
use App\Models\InterestedUser;
use App\Models\AddToFavourite;
use App\Models\Subscription;
use App\Models\UserCard;
use App\Models\Job;
use Illuminate\Support\Carbon;
use App\Models\Plan;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use App\Notifications\SendPushNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\Notifications;
use Stripe\StripeClient;

class EventController extends Controller
{
    public function my_events(Request $request) {

        $status = $request->status; 
        $currentDate = Carbon::now()->toDateString();

        $upd_event_to_ongoing = Event::whereDate('date', $currentDate)->get();

        if(count($upd_event_to_ongoing) > 0) {
            foreach ($upd_event_to_ongoing as $item) {
                $updatee         = Event::find($item->id);
                $updatee->status = "Ongoing";
                $updatee->save();
            }
        }

        $upd_event_to_ongoing = Event::whereDate('date', '<', $currentDate)->get();

        if(count($upd_event_to_ongoing) > 0) {
            foreach ($upd_event_to_ongoing as $item) {
                $updatee         = Event::find($item->id);
                $updatee->status = "Completed";
                $updatee->save();
            }
        }

        if($status == "Scheduled") {
            $events = Event::with('event_owner', 'event_images')->where('user_id', Auth::user()->id)->where('status', 'Scheduled')->get();
        } elseif($status == "Ongoing") {
            $events = Event::with('event_owner', 'event_images')->where('user_id', Auth::user()->id)->where('status','Ongoing')->get();
        } elseif($status == "Completed") {
            $events = Event::with('event_owner', 'event_images')->where('user_id', Auth::user()->id)->where('status','Completed')->get();
        } else {
            $events = [];
        }

        if(count($events) > 0) {
            foreach ($events as $item) {
                if(count($item["event_images"]) > 0) {
                    // $arr[]["image"] = $item["event_images"]->image; 
                    $image = $item["event_images"][0]->image; 
                } else {
                    // $arr[]["image"] = ""; 
                    $image = ""; 
                }
                $arr[] = ["image"=>$image, "event_id" => $item->id, "name"=>$item->name, "location"=>$item->location, "price"=> $item->price, "event_owner_detail" => [
                        "user_id"=>$item["event_owner"]->id,
                        "name" =>$item["event_owner"]->name,
                        "image"=>$item["event_owner"]->image,
                        "rating"=>$item["event_owner"]->rating
                    ]
                ];
            }
        } else {
            $arr = [];
        }
        return $this->success($arr);
    }
    
    public function events(Request $request) {

        $search = $request->search; 
        $currentDate = Carbon::now()->toDateString();
        if($search == null) {
            $events = Event::with('event_owner', 'event_images')->where('date', '>=', $currentDate)->where('user_id', '!=', Auth::user()->id)->where('status', 'Scheduled')->get();
        } else {
            $events = Event::with('event_owner', 'event_images')->where('date', '>=', $currentDate)->where('user_id', '!=', Auth::user()->id)->where('name','LIKE','%'.$search.'%')->where('status', 'Scheduled')->get();
        }

        if(count($events) > 0) {
            foreach ($events as $item) {

                $check_fav = AddToFavourite::where('model_name', "Event")->where('model_id', $item->id)->where('user_id', auth()->user()->id)->first();

                if($check_fav == null) {
                    $is_fav = 0;
                } else {
                    $is_fav = 1;
                }
                
                if(count($item["event_images"]) > 0) {
                    $image = $item["event_images"][0]->image; 
                } else {
                    $image = ""; 
                }
                $arr[] = ["is_favourite"=> $is_fav, "image"=>$image, "event_id" => $item->id, "name"=>$item->name, "location"=>$item->location, "price"=> $item->price, "event_owner_detail" => [
                        "user_id"=>$item["event_owner"]->id,
                        "name" =>$item["event_owner"]->name,
                        "image"=>$item["event_owner"]->image,
                        "rating"=>$item["event_owner"]->rating
                    ]
                ];
            }
        } else {
            $arr = [];
        }
        return $this->success($arr);
    }

    public function add_event(Request $request) {
        try {
            //code...
            $validator  = Validator::make($request->all(), [
    
                "name"        => 'required',
                "location"     => 'required',
                "lat"          => 'required',
                "lng"          => 'required',
                "date"         => 'required',
                "price"        => 'required',
                "payment_type" => 'required',
                // "plan_id"      => 'required'
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }

            // if($request->payment_type == "card") {

                try {
                        $check_subscription = Subscription::where('user_id', Auth::user()->id)->where('model_name', 'Event')->first();
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
                                    // $subs_user_id         = $input['subs_user_id']; //Firebase User ID
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
                                        'model_name'             => "Event",
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

                                    $event                        = new Event();
                                    $event->user_id               = Auth::user()->id;
                                    $event->name                  = $request->name;
                                    $event->location              = $request->location;
                                    $event->lat                   = $request->lat;
                                    $event->lng                   = $request->lng; 
                                    $event->purpose               = $request->purpose;
                                    $event->theme                 = $request->theme;
                                    $event->vendors_list          = $request->vendors_list;
                                    $event->price                 = $request->price;
                                    $event->date                  = date('Y-m-d', strtotime($request->date));
                                    $event->start_time            = $request->start_time;
                                    $event->end_time              = $request->end_time;
                                    $event->available_attractions = $request->available_attractions;
                                    $event->payment_type = $request->payment_type;
                                    $event->save();
                        
                                    $fileName = "";
                                    $dirPath  = "uploads/images/events/";
                                    if($request->file('images'))
                                    {
                                        foreach($request->file('images') as $image)
                                        {
                                            $fileName = $dirPath.time().'-'.$image->getClientOriginalName();
                                            $image->move(public_path($dirPath), $fileName);
                                            EventImage::create([
                                                'event_id' => $event->id,                    
                                                'image'    => asset($fileName),
                                            ]);
                                        }
                                    }
                                    return $this->success(["id"=>$event->id], 'Event Created Successfully');

                                } else {
                                    return $this->error($e->getMessage());
                                }
                            }
                        } else {
                                $event                        = new Event();
                                $event->user_id               = Auth::user()->id;
                                $event->name                  = $request->name;
                                $event->location              = $request->location;
                                $event->lat                   = $request->lat;
                                $event->lng                   = $request->lng; 
                                $event->purpose               = $request->purpose;
                                $event->theme                 = $request->theme;
                                $event->vendors_list          = $request->vendors_list;
                                $event->price                 = $request->price;
                                $event->date                  = date('Y-m-d', strtotime($request->date));
                                $event->start_time            = $request->start_time;
                                $event->end_time              = $request->end_time;
                                $event->available_attractions = $request->available_attractions;
                                $event->payment_type = $request->payment_type;
                                $event->save();
                    
                                $fileName = "";
                                $dirPath  = "uploads/images/events/";
                                if($request->file('images'))
                                {
                                    foreach($request->file('images') as $image)
                                    {
                                        $fileName = $dirPath.time().'-'.$image->getClientOriginalName();
                                        $image->move(public_path($dirPath), $fileName);
                                        EventImage::create([
                                            'event_id' => $event->id,                    
                                            'image'    => asset($fileName),
                                        ]);
                                    }
                                }
                                return $this->success(["id"=>$event->id], 'Event Created Successfully');
                        }
                } catch (\Exception $e) {
                    return $this->error($e->getMessage());
                }
            // }

        } catch (\Exception $e) {
           
            return $this->error($e->getMessage());
        }
    }

    public function edit_event(Request $request) {

        $event = Event::find($request->id);
        $arr   = [];
       
        $arr["event_id"]     = $event->id;
        $arr["event_name"]   = $event->name;
        $arr["location"]     = $event->location;
        $arr["lat"]          = $event->lat;
        $arr["lng"]          = $event->lat;

        $arr["start_date"]   = $event->date;
        $arr["start_time"]   = $event->start_time;
        $arr["end_time"]     = $event->end_time;
        $arr["purpose"]      = $event->purpose;
        $arr["theme"]        = $event->theme;
        $arr["price"]        = $event->price;
        $arr["payment_type"] = $event->payment_type;
       
        $vend = [];
        if(count($event["event_images"]) > 0) {
            foreach ($event["event_images"] as $value) {
                $arr["images"][] = ["image"=>$value->image];
            }
        } else {
            $arr["images"] = [];
        }

        if($event->vendors_list != "") {
            $vendors  = explode(",", $event->vendors_list);
            foreach ($vendors as $vendor) {
                array_push($vend, $vendor);
                // $arr["vendors_list"][] = ["attr"=>$vendor];
            }
            $arr["vendors_list"] = $vend;
        } else {
            $arr["vendors_list"] = null;
        }

        if($event->available_attractions != "") {
            $avb_attr = explode(",", $event->available_attractions);
            foreach ($avb_attr as $attraction) {
                $arr["available_attractions"][] = ["attr"=>$attraction];
            }
        } else {
            $arr["available_attractions"] = null;
        }
       
        
        return $this->success($arr);
    }

    public function update_event(Request $request) {

        $validator  = Validator::make($request->all(), [
            "event_id" => 'required',
        ]);
        if ($validator->fails()) {
            return $this->error('Validation Error', 200, [], $validator->errors());
        }

        $event                   = Event::find($request->event_id);
        $event->name             = $request->name;
        $event->location         = $request->location;
        $event->lat              = $request->lat;
        $event->lng              = $request->lng; 
        $event->purpose          = $request->purpose;
        $event->theme            = $request->theme;
        $event->vendors_list     = $request->vendors_list;
        $event->price            = $request->price;
        $event->date             = date('Y-m-d', strtotime($request->date));
        $event->start_time       = $request->start_time;
        $event->end_time         = $request->end_time;
        $event->available_attractions = $request->available_attractions;
        $event->save();

        $fileName = "";
        $dirPath  = "uploads/images/events/";
        if($request->file('images'))
        {
            foreach($request->file('images') as $image)
            {
                $fileName = $dirPath.time().'-'.$image->getClientOriginalName();
                $image->move(public_path($dirPath), $fileName);

                $update_image = EventImage::firstOrNew([
                    "event_id" => $event->id, 
                    'image'  => asset($fileName),
                ]);
                $update_image->save();

                // EventImage::create([
                //     'event_id' => $event->id,                    
                //     'image'    => asset($fileName),
                // ]);
            }
        }


        $arr   = [];
        if(count($event["event_images"]) > 0) {
            foreach ($event["event_images"] as $value) {
                $arr["images"][] = ["image"=>$value->image];
            }
        } else {
            $arr["images"] = [];
        }
        if($event->user_id != Auth::user()->id) {
            $arr["event_owner_detail"]    = ["is_owner"=>0, "id"=>$event["event_owner"]->id, "name" =>$event["event_owner"]->name, "image"=>$event["event_owner"]->image];
        } else {
            $arr["event_owner_detail"]    = ["is_owner"=>1];
        }
        $arr["event_id"]   = $event->id;
        $arr["event_name"] = $event->name;
        $arr["price"]      = $event->price;
        $arr["location"]   = $event->location;
        $arr["start_date"] = $event->date;
        $arr["start_time"] = $event->start_time;
        $arr["end_time"]   = $event->end_time;
        $arr["purpose"]    = $event->purpose;
        $arr["theme"]      = $event->theme;
        $arr["payment_type"]    = $event->payment_type;
       
        $vend = [];

         if($event->vendors_list != "") {
            $vendors  = explode(",", $event->vendors_list);
            foreach ($vendors as $vendor) {
                array_push($vend, $vendor);
                // $arr["vendors_list"][] = ["attr"=>$vendor];
            }
            $arr["vendors_list"] = $vend;
        } else {
            $arr["vendors_list"] = null;
        }

        if($event->available_attractions != "") {
            $avb_attr = explode(",", $event->available_attractions);
            foreach ($avb_attr as $attraction) {
                $arr["available_attractions"][] = ["attr"=>$attraction];
            }
        } else {
            $arr["available_attractions"] = null;
        }
       

        $check = InterestedUser::where('user_id', Auth::user()->id)->where('event_id', $event->id)->first();
        if($check != null) {
            $arr["is_interested"] = 1;
        } else {
            $arr["is_interested"] = 0;
        } 

        return $this->success($arr, 'Event Updated Successfully');
    }

    public function event_detail(Request $request) {

        try {
            //code...
            $event = Event::with('event_images', 'event_owner')->find($request->id);
            $arr   = [];
            if(count($event["event_images"]) > 0) {
                foreach ($event["event_images"] as $value) {
                    $arr["images"][] = ["image"=>$value->image];
                }
            } else {
                $arr["images"] = [];
            }
            if($event->user_id != Auth::user()->id) {
                $arr["event_owner_detail"]    = ["is_owner"=>0, "id"=>$event["event_owner"]->id, "name" =>$event["event_owner"]->name, "image"=>$event["event_owner"]->image];
            } else {
                $arr["event_owner_detail"]    = ["is_owner"=>1];
            }
            $arr["event_id"]   = $event->id;
            $arr["event_name"] = $event->name;
            $arr["price"]      = $event->price;
            $arr["location"]   = $event->location;
            $arr["start_date"] = $event->date;
            $arr["start_time"] = $event->start_time;
            $arr["end_time"]   = $event->end_time;
            $arr["purpose"]    = $event->purpose;
            $arr["theme"]      = $event->theme;
            $arr["payment_type"]    = $event->payment_type;
           
            $vend = [];

            if($event->vendors_list != "") {
                $vendors  = explode(",", $event->vendors_list);
                foreach ($vendors as $vendor) {
                    array_push($vend, $vendor);
                    // $arr["vendors_list"][] = ["attr"=>$vendor];
                }
                $arr["vendors_list"] = $vend;
            } else {
                $arr["vendors_list"] = null;
            }
    
            if($event->available_attractions != "") {
                $avb_attr = explode(",", $event->available_attractions);
                foreach ($avb_attr as $attraction) {
                    $arr["available_attractions"][] = ["attr"=>$attraction];
                }
            } else {
                $arr["available_attractions"] = null;
            }
           
            $check = InterestedUser::where('user_id', Auth::user()->id)->where('event_id', $event->id)->first();
            if($check != null) {
                $arr["is_interested"] = 1;
            } else {
                $arr["is_interested"] = 0;
            } 

            return $this->success($arr);
        } catch (\Exception $e) {   
         return $this->error($e->getMessage());
        }
    }

    public function interested_in_event(Request $request) {
        try {
            //code...
            $validator  = Validator::make($request->all(), [
                "event_id" => 'required',
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }

            $check = InterestedUser::where('user_id', Auth::user()->id)->where('event_id', $request->event_id)->first();

            if($check == null) {

                $event           = new InterestedUser();
                $event->user_id  = Auth::user()->id;
                $event->event_id = $request->event_id;
                $event->save();

                $event = Event::with('event_images', 'event_owner')->find($request->event_id);
                $arr   = [];
                if(count($event["event_images"]) > 0) {
                    foreach ($event["event_images"] as $value) {
                        $arr["images"][] = ["image"=>$value->image];
                    }
                } else {
                    $arr["images"] = [];
                }
                if($event->user_id != Auth::user()->id) {
                    $arr["event_owner_detail"]    = ["is_owner"=>0, "id"=>$event["event_owner"]->id, "name" =>$event["event_owner"]->name, "image"=>$event["event_owner"]->image];
                } else {
                    $arr["event_owner_detail"]    = ["is_owner"=>1];
                }
                $arr["event_id"]   = $event->id;
                $arr["event_name"] = $event->name;
                $arr["price"]      = $event->price;
                $arr["location"]   = $event->location;
                $arr["start_date"] = $event->date;
                $arr["start_time"] = $event->start_time;
                $arr["end_time"]   = $event->end_time;
                $arr["purpose"]    = $event->purpose;
                $arr["theme"]      = $event->theme;
            
                $vend = [];

                if($event->vendors_list != "") {
                    $vendors  = explode(",", $event->vendors_list);
                    foreach ($vendors as $vendor) {
                        array_push($vend, $vendor);
                        // $arr["vendors_list"][] = ["attr"=>$vendor];
                    }
                    $arr["vendors_list"] = $vend;
                } else {
                    $arr["vendors_list"] = null;
                }
        
                if($event->available_attractions != "") {
                    $avb_attr = explode(",", $event->available_attractions);
                    foreach ($avb_attr as $attraction) {
                        $arr["available_attractions"][] = ["attr"=>$attraction];
                    }
                } else {
                    $arr["available_attractions"] = null;
                }
               
                $arr["payment_type"]  = $event->payment_type;
                $arr["is_interested"] = 1;

                $check = User::find($event->user_id);

                if($check->is_push_notification == 1) {
                
                    $title     = "Interested in Event";
                    $message   = Auth::user()->name." is interested in event for ".$event->name;
                    $fcmTokens = User::where('id',$event->user_id)->pluck('fcm_token')->first();
                    Notification::send(null,new SendPushNotification($title,$message,[$fcmTokens]));
    
                    $notify               = new Notifications();
                    $notify->sender_id    = Auth::user()->id;
                    $notify->receiver_id  = $event->user_id;
                    $notify->title        = $title;
                    $notify->notification = $message;
                    $notify->is_read      = 0;
                    $notify->save();
                }
    

                return $this->success($arr, "success");
            } else {
                return $this->error('You already showed interest in this event.');
            }



        } catch (\Exception $e) {
           
            return $this->error($e->getMessage());
        }
    }

    public function attendees(Request $request) {

        try {
            //code...
            $validator  = Validator::make($request->all(), [
                "event_id" => 'required',
                "type"     => 'required',
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }


            if($request->type == "Interested") {
                $users = InterestedUser::with('user')->where('event_id', $request->event_id)->where('status', 'Pending')->get();
            } else {
                $users = InterestedUser::with('user')->where('event_id', $request->event_id)->where('status', 'Confirmed')->get();
            }

            if(count($users) > 0) {
                foreach ($users as $item) {
                    $arr[] = ["id"=>$item->id, "userid"=>$item->user_id, "event_id" => $item->event_id, "username"=>$item["user"]->name, "image"=>$item["user"]->image, "status"=>$item->status];
                }
            } else {
                $arr = [];
            }
            return $this->success($arr);

        } catch (\Exception $e) {
           
            return $this->error($e->getMessage());
        }
    }

    public function update_attendees_status(Request $request) {
        try {
            //code...
            $validator  = Validator::make($request->all(), [
                "id"       => "required",
                "event_id" => "required",
                "status"   => 'required',  // Confirmed or Rejected

            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }

            $event         = InterestedUser::find($request->id);
            $event->status = $request->status;
            $event->save();

            $users = InterestedUser::with('user')->where('event_id', $request->event_id)->where('status', 'Pending')->get();
            if(count($users) > 0) {
                foreach ($users as $item) {
                    $arr[] = ["id"=>$item->id, "userid"=>$item->user_id, "event_id" => $item->event_id, "username"=>$item["user"]->name, "image"=>$item["user"]->image, "status"=>$item->status];
                }
            } else {
                $arr = [];
            }


            $check         = User::find($event->user_id);

            if($check->is_push_notification == 1) {
                
                $title     = "Event Attend Status";
                $message   = Auth::user()->name." confirmed your request for ".$event->name;
                $fcmTokens = User::where('id',$event->user_id)->pluck('fcm_token')->first();
                Notification::send(null,new SendPushNotification($title,$message,[$fcmTokens]));

                $notify               = new Notifications();
                $notify->sender_id    = Auth::user()->id;
                $notify->receiver_id  = $event->user_id;
                $notify->title        = $title;
                $notify->notification = $message;
                $notify->is_read      = 0;
                $notify->save();
            }


            return $this->success($arr, 'Status updated Successfully');
           

        } catch (\Exception $e) {
           
            return $this->error($e->getMessage());
        }
    }

    public function view_profile(Request $request) {
        $user = User::find($request->id);

        $arr["user_id"]  = $user->id;
        $arr["image"]    = $user->id;
        $arr["username"] = $user->id;
        $arr["email"]    = $user->id;
        $arr["jobs_posted"]   = Job::where('user_id', $user->id)->count();
        $arr["events_posted"] = Event::where('user_id', $user->id)->count();

        return $this->success($arr);
    }

    public function add_to_favourite(Request $request) {

        $check_fav = AddToFavourite::where('user_id', auth()->user()->id)->where('model_id', $request->model_id)->where('model_name', $request->model_name)->first();
        if($check_fav == null) {
            $favourite             = new AddToFavourite();
            $favourite->user_id    = Auth::user()->id;
            $favourite->model_id   = $request->model_id;
            $favourite->model_name = $request->model_name;
            $favourite->save();
            return $this->success(["is_favourite"=>1], "success");
        } else {
            return $this->error("This item is already in favourites");
        }
    }

    public function remove_from_favourite(Request $request) {

        try {    
            
            $validator  = Validator::make($request->all(), [
                "model_id"   => "required",
                "model_name" => "required",
    
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }


            $remove_fav = AddToFavourite::where('user_id', auth()->user()->id)->where('model_id', $request->model_id)->where('model_name', $request->model_name)->delete();


            $arr = [];
            if($request->model_name == "Event") {

                $favourites = AddToFavourite::where('user_id', Auth::user()->id)->where('model_name', $request->model_name)->get();
                foreach ($favourites as $item) {
                    $event = Event::with('event_owner', 'event_images')->where('id', $item->model_id)->first();
                    if(count($event['event_images']) > 0){
                        $image = $event["event_images"][0]->image;
                    } else {
                        $image = "";
                    }
                    $arr[] = ["id"=>$event->id,"name"=>$event->name, "image"=> $image,"price"=>$event->price, "location" => $event->location, "owner_detail" => ["id"=>$event["event_owner"]->id,"name"=>$event["event_owner"]->name, "image"=> $event["event_owner"]->image,"rating"=>$event["event_owner"]->rating]];
                }
    
            } elseif($request->model_name == "Job") {

                $favourites = AddToFavourite::with('job')->where('user_id', Auth::user()->id)->where('model_name', $request->model_name)->get();
                foreach ($favourites as $item) {
                    $job = Job::with('images', 'user')->where('id', $item->model_id)->first();

                    if(count($job['images']) > 0){
                        $image = $job["images"][0]->image;
                    } else {
                        $image = "";
                    }
                    $arr[] = ["id"=>$job->id,"name"=>$job->title, "image"=> $image,"price"=>$job->budget, "location" => $job->location, "owner_detail" => ["id"=>$job["user"]->id,"name"=>$job["user"]->name, "image"=> $job["user"]->image,"rating"=>$job["user"]->rating]];
                }
                
    
            } elseif($request->type == "Shop") {

                $favourites = AddToFavourite::with('shop')->where('user_id', Auth::user()->id)->where('model_name', $request->model_name)->get();
                foreach ($favourites as $item) {
                    $shop = Shop::with('user')->where('id', $item->model_id)->first();
                    $total_products = Product::where('shop_id', $shop->id)->where('type','Product')->count('id');
                    $total_services = Product::where('shop_id', $shop->id)->where('type','Service')->count('id');

                    $arr[] = ["id"=>$shop->id,"name"=>$shop->name, "image"=> $shop->image,"location" => $shop->location, "total_products"=>$total_products, "total_services"=>$total_services, "owner_detail" => ["id"=>$shop["user"]->id,"name"=>$shop["user"]->name, "image"=> $shop["user"]->image,"rating"=>$shop["user"]->rating]];
                }   

            } else {
                $arr = [];
            }

    
            return $this->success($arr, "removed");

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }


     

    }

    public function user_events(Request $request) {

        $validator  = Validator::make($request->all(), [
            "user_id" => 'required',
        ]);

        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }

        $events = Event::with('event_owner', 'event_images')->where('user_id', $request->user_id)->where('status', 'Scheduled')->get();

        if(count($events) > 0) {
            foreach ($events as $item) {

                if(count($item["event_images"]) > 0) {
                    $image = $item["event_images"][0]->image; 
                } else {
                    $image = ""; 
                }
                $arr[] = ["image"=>$image, "event_id" => $item->id, "name"=>$item->name, "location"=>$item->location, "price"=> $item->price, "event_owner_detail" => [
                        "user_id"=>$item["event_owner"]->id,
                        "name" =>$item["event_owner"]->name,
                        "image"=>$item["event_owner"]->image,
                        "rating"=>$item["event_owner"]->rating
                    ]
                ];
            }
        } else {
            $arr = [];
        }
        return $this->success($arr);

    }

}
