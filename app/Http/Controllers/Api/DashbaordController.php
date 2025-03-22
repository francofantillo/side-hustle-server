<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobImage;
use App\Models\Event;
use App\Models\User;
use App\Models\EventImage;
use App\Models\InterestedUser;
use App\Models\Shop;
use App\Models\Setting;
use App\Models\Banner;
use App\Models\AddToFavourite;



use Illuminate\Support\Facades\Auth;

class DashbaordController extends Controller
{
    public function dashboard(Request $request) {

        try {
            //code...
            $zip_code = Auth::user()->zip_code;
            $userid   = Auth::user()->id;
            $search   = $request->search;
    
            if($search ==  null) {
                $shops = Shop::where('user_id', '!=', $userid)->where('zip_code', $zip_code)->get();
            } else {
                $shops = Shop::where('user_id', '!=', $userid)->where('name', 'LIKE', '%'.$search.'%')->where('zip_code', $zip_code)->get();
            }
    
            if(count($shops) > 0) {
                foreach ($shops as $item) {
                    $arr["shops"][] = ["shop_id"=> $item->id, "name"=> $item->name, "image"=>$item->image];
                }
            } else {
                $arr["shops"] = [];
            }
    
            if($search ==  null) {
                $jobs = Job::with('user', 'images')->where('user_id', '!=', $userid)->where('area_code', $zip_code)->get();
            } else {
                $jobs = Job::with('user', 'images')->where('user_id', '!=', $userid)->where('area_code', $zip_code)->where('title', 'LIKE', '%'.$search.'%')->get();
            }

            $favourites = AddToFavourite::where("user_id", $userid)->where("model_name", "Job")->pluck('model_id')->toArray();
            $collection = collect($favourites); 
    
            if(count($jobs) > 0) {
                foreach ($jobs as $item) {
    
                    if(count($item["images"]) > 0) {
                        $image = $item["images"][0]->image;
                    } else {
                        $image = "";
                    }

                    if ($collection->contains($item->id)) {
                        $is_favourite = 1;
                    } else {
                        $is_favourite = 0;
                    }

                    $arr["jobs"][] = [
                                        "is_favourite" => $is_favourite,
                                        "job_id"=> $item->id,
                                        "name"=> $item->title,
                                        "image"=>$image,
                                        "description"=>$item->description,
                                        "price"=> $item->budget,
                                        "bid_amount"=>$item->bid_amount,
                                        "user_detail"=>["userid"=>$item["user"]->id,
                                                        "name"=>$item["user"]->name,
                                                        "image"=>$item["user"]->image,
                                                        "rating"=>$item["user"]->rating
                                                    ]
                                    ];
                }
            } else {
                $arr["jobs"] = [];
            }
    
            if($search ==  null) {
                $events = Event::with("event_images", "event_owner")->where('user_id', '!=', $userid)->get();
            } else {
                $events = Event::with("event_images", "event_owner")->where('user_id', '!=', $userid)->where('name', 'LIKE', '%'.$search.'%')->get();
            }
    
            $favourite_events = AddToFavourite::where("user_id", $userid)->where("model_name", "Event")->pluck('model_id')->toArray();
            $events_collection = collect($favourite_events); 

            if(count($events) > 0) {
                foreach ($events as $item) {
    
                    if(count($item["event_images"]) > 0) {
                        $image = $item["event_images"][0]->image;
                    
                    } else {
                        $image = "";
                    }
                    if ($events_collection->contains($item->id)) {
                        $is_fav_event = 1;
                    } else {
                        $is_fav_event = 0;
                    }

                    $arr["events"][] = [
                                        "is_favourite"=>$is_fav_event,
                                        "event_id"=> $item->id,
                                        "name"=> $item->name,
                                        "image"=>$image,
                                        "purpose"=>$item->purpose,
                                        "price"=>$item->price,
                                        "user_detail"=>[
                                            "userid"=>$item["event_owner"]->id,
                                            "name"=>$item["event_owner"]->name,
                                            "image"=>$item["event_owner"]->image,
                                            "rating"=>$item["event_owner"]->rating
                                        ]
                                    ];
                }
            } else {
                $arr["events"] = [];
            }

            $banners = Banner::where('zip_code', $zip_code)->orderByDESC('id')->get();
            $arr["banners"] = $banners;
    
            return $this->success($arr);
        } catch (\Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }
       

    }

    public function terms_and_conditions() {
        $setting = Setting::find(1);
        $terms   = $setting->terms_and_conditions;
        return $this->success($terms);
    }

    public function united_capitalism() {

        $setting           = Setting::find(1);
        $united_capitalism = $setting->united_capitalism;
        return $this->success($united_capitalism);
    }

    public function privacy_policy() {
        $setting = Setting::find(1);
        $policy   = $setting->privacy_policy;
        return $this->success($policy);
    }

    public function about_us() {
        $setting = Setting::find(1);
        $about   = $setting->about_us;
        return $this->success($about);
    }

    public function how_to_be_hustler() {
        $setting    = Setting::find(1);
        $pdf_file["file"]   = $setting->pdf_file;
        return $this->success($pdf_file);
    }
    
 
}
