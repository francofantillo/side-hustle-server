<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ProductImage;
use App\Models\AddToFavourite;
use App\Models\Event;
use App\Models\Job;
use App\Models\Subscription;
use App\Models\UserCard;
use App\Models\Plan;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Illuminate\Support\Carbon;


class ProductController extends Controller
{
    public function products(Request $request) {

        $search = $request->search;
        $type   = $request->type;

        if($search == null) {

            $ser = Product::with('product_images')->where('type',$type)->where('user_id', '!=', Auth::user()->id)->get();
            // $pro = Product::with('product_images')->where('type','Product')->where('user_id', '!=', Auth::user()->id)->get();
        } else {
            $ser = Product::with('product_images')->where('name','LIKE','%'.$search.'%')->where('type',$type)->where('user_id', '!=', Auth::user()->id)->get();
            // $pro = Product::with('product_images')->where('name','LIKE','%'.$search.'%')->where('type','Product')->where('user_id', '!=', Auth::user()->id)->get();
        }

        if(count($ser) > 0) {
            foreach ($ser as $service) {

                if(count($service["product_images"])) {
                    $image = $service["product_images"][0]->image;
                } else {
                    $image = null;
                }

                if($type == "Product") {
                    $arr[] = [
                        "id"            => $service->id,
                        "shop_id"       => $service->shop_id,
                        "name"          => $service->name,
                        "price"         => $service->price,
                        "description"   => $service->description,
                        "image"         => $image,
                        "delivery_type" => $service->delivery_type
                    ];
                } else {
                    $arr[] = [
                        "id"           => $service->id,
                        "shop_id"      => $service->shop_id,
                        "name"         => $service->name,
                        "price"        => $service->hourly_rate,
                        "description"  => $service->description,
                        "image"        => $image,
                        "service_type" => $service->service_type
                    ];
                }
            }
        } else {
            $arr = [];
        }

        // if(count($pro) > 0) {
        //     foreach ($pro as $product) {
        //         $arr["products"][] = [
        //             "id"=>$product->id,
        //             "name"=> $product->name,
        //             "price"=> $product->price,
        //             "description"=>$product->description,
        //             "image"=>$product["product_images"][0]->image
        //         ];
        //     }
        // } else {
        //     $arr["products"] = [];
        // }

       return $this->success($arr);
    }

    public function product_detail(Request $request) {

        try {
            //code...
            $validator  = Validator::make($request->all(), [
                "id" => 'required',
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 202, [], $validator->errors());
            }

            $product = Product::with('shop','product_owner','product_images')->where('id',$request->id)->first();
            if($product == null) {
                return $this->error("No product exist with this id.");
            } else {

                $arr["images"]        = $product["product_images"];
                $arr["product_id"]    = $product->id;
                $arr["shop_id"]       = $product->shop_id;
                $arr["shop_name"]     = $product["shop"]->name;
                $arr["lat"]           = $product["shop"]->lat;
                $arr["lng"]           = $product["shop"]->lng;
                $arr["name"]          = $product->name;
                $arr["description"]   = $product->description;
                $arr["additional_information"]   = $product->additional_information;

                $arr["zip_code"]      = $product->zip_code;
                if($product->type == "Service") {
                    $arr["service_type"] = $product->service_type;
                    $arr["price"]         = $product->hourly_rate;
                } else {
                    $arr["delivery_type"] = $product->delivery_type;
                    $arr["price"]         = $product->price;
                }
                $arr["product_owner"] = [
        
                    "userid"=> $product["product_owner"]->id,
                    "name"  => $product["product_owner"]->name,
                    "image" => $product["product_owner"]->image,
                ];
        
        
                // $arr["name"] = $product->name;
                // $arr["name"] = $product->name;
        
                return $this->success($arr);
            }


        } catch (\Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }
    }

    public function addProduct(Request $request) {

        try {
            //code...
            $validator  = Validator::make($request->all(), [
                "type"     => 'required',
                'name'     => 'required',
                // 'location' => 'required'
                
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 202, [], $validator->errors());
            }

            $check_subscription = Subscription::where('user_id', Auth::user()->id)->where('model_name', 'Product')->first();
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
                            'model_name'             => "Product",
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

                        $shop = Shop::where('user_id', Auth::user()->id)->first();
                        if($shop == null) {
                
                            $shop          = new Shop();
                            $shop->user_id = Auth::user()->id;
                            $shop->save();
                        }
                
                        $product                = new Product();
                        $product->user_id       = Auth::user()->id;
                        $product->shop_id       = $shop->id;
                        $product->type          = $request->type;
                        $product->name          = $request->name;
                        if($request->type == "Product") {
                            $product->delivery_type = $request->delivery_type;
                            $product->price         = $request->price;
                        }
                        if($request->type == "Service") {
                            $product->hourly_rate   = $request->hourly_rate;
                            $product->service_type  = $request->service_type;
                        }

                        if($request->is_shop_location == 1) {
                            $product->location      = $shop->location;
                            $product->lat           = $shop->lat;
                            $product->lng           = $shop->lng;
                        } else {
                            $product->location      = $request->location;
                            $product->lat           = $request->lat;
                            $product->lng           = $request->lng;
                        }
                        $product->description   = $request->description;
                        $product->zip_code      = $request->zip_code;
                        $product->additional_information = $request->additional_information;
                        $product->save();
                
                        $fileName = "";
                        $dirPath  = "uploads/images/products/";
                        if($request->hasFile('images'))
                        {
                            foreach($request->file('images') as $image)
                            {
                                $fileName = $dirPath.time().'-'.$image->getClientOriginalName();
                                $image->move(public_path($dirPath), $fileName);
                                ProductImage::create([
                                    'product_id' => $product->id,                    
                                    'image'      => asset($fileName),
                                ]);
                            }
                        }

                        $ser  = Product::with('product_images')->where('type','Service')->where('user_id', Auth::user()->id)->where('shop_id', $shop->id)->get();
                        $pro  = Product::with('product_images')->where('type','Product')->where('user_id', Auth::user()->id)->where('shop_id', $shop->id)->get();
                
                        $arr["shop_detail"] = ["id"=>$shop->id, "name"=>$shop->name, "image"=> $shop->image, "location"=>$shop->location, "lat"=>$shop->lat, "lng"=>$shop->lng];
                
                        if(count($ser) > 0) {
                            foreach ($ser as $service) {

                                if(count($service["product_images"])) {
                                    $image = $service["product_images"][0]->image;
                                } else {
                                    $image = null;
                                }
                                $arr["services"][] = [
                                    "id"=>$service->id,
                                    "user_id"=>$service->user_id,
                                    "name"=> $service->name,
                                    "price"=> $service->price,
                                    "description"=>$service->description,
                                    "image"=>$image,
                                    "service_type" => $service->service_type
                                  
                                ];
                            }
                        } else {
                            $arr["services"] = [];
                        }
                
                        if(count($pro) > 0) {
                            foreach ($pro as $product) {

                                if(count($product["product_images"])) {
                                    $image = $product["product_images"][0]->image;
                                } else {
                                    $image = null;
                                }
                                $arr["products"][] = [
                                    "id"=>$product->id,
                                    "user_id"=>$product->user_id,
                                    "name"=> $product->name,
                                    "price"=> $product->price,
                                    "description"=>$product->description,
                                    "image"=>$image,
                                    "delivery_type" => $product->delivery_type
                                ];
                            }
                        } else {
                            $arr["products"] = [];
                        }
                
                       return $this->success($arr, $request->type." added successfully");
    
                    } else {
                        return $this->error($e->getMessage());
                    }
                }
            } else {

                $shop = Shop::where('user_id', Auth::user()->id)->first();
                if($shop == null) {
        
                    $shop          = new Shop();
                    $shop->user_id = Auth::user()->id;
                    $shop->save();
                }
        
                $product                = new Product();
                $product->user_id       = Auth::user()->id;
                $product->shop_id       = $shop->id;
                $product->type          = $request->type;
                $product->name          = $request->name;
                if($request->type == "Product") {
                    $product->delivery_type = $request->delivery_type;
                    $product->price         = $request->price;
                }
                if($request->type == "Service") {
                    $product->hourly_rate   = $request->hourly_rate;
                    $product->service_type  = $request->service_type;
                }
                // if($request->is_shop_location == 1) {
                //     $product->location      = $shop->location;
                //     $product->lat           = $shop->lat;
                //     $product->lng           = $shop->lng;
                // } else {
                //     $product->location      = $request->location;
                //     $product->lat           = $request->lat;
                //     $product->lng           = $request->lng;
                // }
                $product->description   = $request->description;
                $product->zip_code      = $request->zip_code;
                $product->additional_information = $request->additional_information;
                $product->save();
        
                $fileName = "";
                $dirPath  = "uploads/images/products/";
                if($request->hasFile('images'))
                {
                    foreach($request->file('images') as $image)
                    {
                        $fileName = $dirPath.time().'-'.$image->getClientOriginalName();
                        $image->move(public_path($dirPath), $fileName);
                        ProductImage::create([
                            'product_id' => $product->id,                    
                            'image'      => asset($fileName),
                        ]);
                    }
                }
                $ser  = Product::with('product_images')->where('type','Service')->where('user_id', Auth::user()->id)->where('shop_id', $shop->id)->get();
                $pro  = Product::with('product_images')->where('type','Product')->where('user_id', Auth::user()->id)->where('shop_id', $shop->id)->get();
        
                $arr["shop_detail"] = ["id"=>$shop->id, "name"=>$shop->name, "image"=> $shop->image, "location"=>$shop->location, "lat"=>$shop->lat, "lng"=>$shop->lng];
        
                if(count($ser) > 0) {
                    foreach ($ser as $service) {

                        if(count($service["product_images"])) {
                            $image = $service["product_images"][0]->image;
                        } else {
                            $image = null;
                        }
                        $arr["services"][] = [
                            "id"=>$service->id,
                            "user_id"=>$service->user_id,
                            "name"=> $service->name,
                            "price"=> $service->price,
                            "description"=>$service->description,
                            "image"=>$image,
                            "service_type" => $service->service_type
                        ];
                    }
                } else {
                    $arr["services"] = [];
                }
        
                if(count($pro) > 0) {
                    foreach ($pro as $product) {

                        if(count($product["product_images"])) {
                            $image = $product["product_images"][0]->image;
                        } else {
                            $image = null;
                        }
                        $arr["products"][] = [
                            "id"=>$product->id,
                            "user_id"=>$product->user_id,
                            "name"=> $product->name,
                            "price"=> $product->price,
                            "description"=>$product->description,
                            "image"=>$image,
                            "delivery_type" => $product->delivery_type
                        ];
                    }
                } else {
                    $arr["products"] = [];
                }
        
                return $this->success($arr, $request->type." added successfully");
            }
        } catch (\Exception $e) {
         
            return $this->error($e->getMessage());
        }

    }

    public function editProduct(Request $request) {
        $product = Product::with('product_images')->where('id', $request->id)->first();
        return $this->success($product);
    }


    public function update_product(Request $request) {
        try {

            $validator  = Validator::make($request->all(), [
                "type"     => 'required',
                'name'     => 'required',
                // 'location' => 'required'
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 202, [], $validator->errors());
            }

            $shop = Shop::where('user_id', Auth::user()->id)->first();
        
            $product                = Product::find($request->id);
            $product->user_id       = Auth::user()->id;
            $product->shop_id       = $shop->id;
            $product->type          = $request->type;
            $product->name          = $request->name;
            if($request->type == "Product") {
                $product->delivery_type = $request->delivery_type;
                $product->price         = $request->price;
            }
            if($request->type == "Service") {
                $product->hourly_rate   = $request->hourly_rate;
                $product->service_type  = $request->service_type;
            }
            // if($request->is_shop_location == 1) {
            //     $product->location      = $shop->location;
            //     $product->lat           = $shop->lat;
            //     $product->lng           = $shop->lng;
            // } else {
            //     $product->location      = $request->location;
            //     $product->lat           = $request->lat;
            //     $product->lng           = $request->lng;
            // }
            $product->description   = $request->description;
            $product->zip_code      = $request->zip_code;
            $product->additional_information = $request->additional_information;
            $product->save();
    
            $fileName = "";
            $dirPath  = "uploads/images/products/";
            if($request->hasFile('images'))
            {
                foreach($request->file('images') as $image)
                {
                    $fileName = $dirPath.time().'-'.$image->getClientOriginalName();
                    $image->move(public_path($dirPath), $fileName);
                    ProductImage::create([
                        'product_id' => $product->id,                    
                        'image'      => asset($fileName),
                    ]);
                }
            }
            $ser  = Product::with('product_images')->where('type','Service')->where('user_id', Auth::user()->id)->where('shop_id', $shop->id)->get();
            $pro  = Product::with('product_images')->where('type','Product')->where('user_id', Auth::user()->id)->where('shop_id', $shop->id)->get();
    
            $arr["shop_detail"] = ["id"=>$shop->id, "name"=>$shop->name, "image"=> $shop->image, "location"=>$shop->location, "lat"=>$shop->lat, "lng"=>$shop->lng];
    
            if(count($ser) > 0) {
                foreach ($ser as $service) {

                    if(count($service["product_images"])) {
                        $image = $service["product_images"][0]->image;
                    } else {
                        $image = null;
                    }
                    $arr["services"][] = [
                        "id"=>$service->id,
                        "user_id"=>$service->user_id,
                        "name"=> $service->name,
                        "price"=> $service->price,
                        "description"=>$service->description,
                        "image"=>$image,
                        "service_type" => $service->service_type
                    ];
                }
            } else {
                $arr["services"] = [];
            }
    
            if(count($pro) > 0) {
                foreach ($pro as $product) {

                    if(count($product["product_images"])) {
                        $image = $product["product_images"][0]->image;
                    } else {
                        $image = null;
                    }
                    $arr["products"][] = [
                        "id"=>$product->id,
                        "user_id"=>$product->user_id,
                        "name"=> $product->name,
                        "price"=> $product->price,
                        "description"=>$product->description,
                        "image"=>$image,
                        "delivery_type" => $product->delivery_type
                    ];
                }
            } else {
                $arr["products"] = [];
            }
    
            return $this->success($arr, $request->type." updated successfully");
            
        } catch (\Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }
    }

    public function deleteProduct(Request $request) {

        try {
            //code...
            $validator  = Validator::make($request->all(), [
                "id"       => 'required'
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }

            $shop = Shop::where('user_id', Auth::user()->id)->first();

            ProductImage::where('product_id', $request->id)->delete();
            Product::where('id', $request->id)->delete();

            $ser  = Product::with('product_images')->where('type','Service')->where('user_id', Auth::user()->id)->where('shop_id', $shop->id)->get();
            $pro  = Product::with('product_images')->where('type','Product')->where('user_id', Auth::user()->id)->where('shop_id', $shop->id)->get();
    
            $arr["shop_detail"] = ["id"=>$shop->id, "name"=>$shop->name, "image"=> $shop->image, "location"=>$shop->location, "lat"=>$shop->lat, "lng"=>$shop->lng];
    
            if(count($ser) > 0) {
                foreach ($ser as $service) {

                    if(count($service["product_images"])) {
                        $image = $service["product_images"][0]->image;
                    } else {
                        $image = null;
                    }
                    $arr["services"][] = [
                        "id"=>$service->id,
                        "user_id"=>$service->user_id,
                        "name"=> $service->name,
                        "price"=> $service->hourly_rate,
                        "description"=>$service->description,
                        "image"=>$image,
                        "service_type" => $service->service_type
                    ];
                }
            } else {
                $arr["services"] = [];
            }
    
            if(count($pro) > 0) {
                foreach ($pro as $product) {

                    if(count($product["product_images"])) {
                        $image = $product["product_images"][0]->image;
                    } else {
                        $image = null;
                    }
                    $arr["products"][] = [
                        "id"=>$product->id,
                        "user_id"=>$product->user_id,
                        "name"=> $product->name,
                        "price"=> $product->price,
                        "description"=>$product->description,
                        "image"=>$image,
                        "delivery_type" => $product->delivery_type

                    ];
                }
            } else {
                $arr["products"] = [];
            }
    
            return $this->success($arr," Deleted successfully");

        } catch (\Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }
    }

    public function view_shop(Request $request) {

        try {
            //code...
            $validator  = Validator::make($request->all(), [
                "shop_id" => 'required',
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 202, [], $validator->errors());
            }
    
            $shop = Shop::find($request->shop_id);
            $ser  = Product::with('product_images')->where('type','Service')->where('shop_id', $shop->id)->get();
            $pro  = Product::with('product_images')->where('type','Product')->where('shop_id', $shop->id)->get();
    
            $arr["shop_detail"] = [
                                    "id"=>$shop->id,
                                    "name"=>$shop->name,
                                    "image"=> $shop->image,
                                    "location"=>$shop->location,
                                    "lat"=>$shop->lat,
                                    "lng"=>$shop->lng,
                                    "zip_code"=>$shop->zip_code
                                ];

          
            if(!$ser->isEmpty()) {
                foreach ($ser as $service) {

                    if(count($service["product_images"]) > 0) {
                        $image = $service["product_images"][0]->image; 
                    } else {
                        $image = ""; 
                    }
                    $arr["services"][] = [
                                            "id"=>$service->id,
                                            "user_id"=>$service->user_id,
                                            "name"=> $service->name,
                                            "price"=> $service->hourly_rate,
                                            "description"=>$service->description,
                                            "image"=>$image
                                        ];
                }
            } else {
                $arr["services"] = [];
            }

            if(count($pro) > 0) {
                foreach ($pro as $product) {
                    if(count($product["product_images"]) > 0) {
                        $pro_image = $product["product_images"][0]->image; 
                    } else {
                        $pro_image = ""; 
                    }
                    
                    $arr["products"][] = [
                                            "id"=>$product->id,
                                            "user_id"=>$product->user_id,
                                            "name"=> $product->name,
                                            "price"=> $product->price,
                                            "description"=>$product->description,
                                            "image"=>$pro_image
                                        ];
                }
            } else {
                $arr["products"] = [];
            }
    
           return $this->success($arr);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }


    }

    public function your_shop(Request $request) {

        $userid = Auth::user()->id;
        $shop   = Shop::where('user_id', $userid)->first();

        if($shop == null) {
            return $this->error("You don't have any shop to view");
        } else {

            $ser  = Product::with('product_images')->where('type','Service')->where('user_id', Auth::user()->id)->where('shop_id', $shop->id)->get();
            $pro  = Product::with('product_images')->where('type','Product')->where('user_id', Auth::user()->id)->where('shop_id', $shop->id)->get();
    
            $arr["shop_detail"] = [
                                    "id"=>$shop->id,
                                    "name"=>$shop->name,
                                    "image"=> $shop->image,
                                    "location"=>$shop->location,
                                    "lat"=>$shop->lat,
                                    "lng"=>$shop->lng,
                                    "zip_code"=>$shop->zip_code
                                ];
    
            if(count($ser) > 0) {
                foreach ($ser as $service) {

                    if(count($service["product_images"])) {
                        $image = $service["product_images"][0]->image;
                    } else {
                        $image = null;
                    }

                    $arr["services"][] = [
                        "id"=>$service->id,
                        "shop_id"=>$service->shop_id,
                        "user_id"=>$service->user_id,
                        "name"=> $service->name,
                        "price"=> $service->hourly_rate,
                        "description"=>$service->description,
                        "service_type" => $service->service_type,
                        "image"=>$image
                    ];
                }
            } else {
                $arr["services"] = [];
            }
    
            if(count($pro) > 0) {
                foreach ($pro as $product) {

                    if(count($product["product_images"])) {
                        $image = $product["product_images"][0]->image;
                    } else {
                        $image = null;
                    }

                    $arr["products"][] = [
                        "id"=>$product->id,
                        "shop_id"=>$product->shop_id,
                        "user_id"=>$product->user_id,
                        "name"=> $product->name,
                        "price"=> $product->price,
                        "description"=>$product->description,
                        "delivery_type" => $product->delivery_type,
                        "image"=>$image
                    ];
                }
            } else {
                $arr["products"] = [];
            }
    
           return $this->success($arr);
        }

    }

    public function edit_shop(Request $request) {

        try {
            //code...

            // $validator  = Validator::make($request->all(), [
            //     "shop_id"       => 'required'
            // ]);
            // if ($validator->fails()){
            //     return $this->error('Validation Error', 200, [], $validator->errors());
            // }
            if($request->shop_id == null) {
                
                $edit_shop           = new Shop();
                $edit_shop->user_id  = auth()->user()->id;
                $edit_shop->name     = $request->name;
                $edit_shop->location = $request->location;
                $edit_shop->lat      = $request->lat;
                $edit_shop->lng      = $request->lng;
                $edit_shop->zip_code = $request->zip_code;
                $fileName = "";
                $dirPath = "uploads/images/shop/";
                if($request->hasFile('image'))
                {  
                    $fileName = $dirPath.time().'-'.$request->image->getClientOriginalName();
                    $request->image->move(public_path($dirPath), $fileName);
        
                    $edit_shop->image = asset($fileName);
                    
                }
                $edit_shop->save();

            } else {

                $edit_shop           = Shop::find($request->shop_id);
                $edit_shop->name     = $request->name;
                $edit_shop->location = $request->location;
                $edit_shop->lat      = $request->lat;
                $edit_shop->lng      = $request->lng;
                $edit_shop->zip_code = $request->zip_code;
                $fileName = "";
                $dirPath = "uploads/images/shop/";
                if($request->hasFile('image'))
                {  
                    $fileName = $dirPath.time().'-'.$request->image->getClientOriginalName();
                    $request->image->move(public_path($dirPath), $fileName);
        
                    $edit_shop->image = asset($fileName);
                    
                }
                $edit_shop->save();
            }


            $ser  = Product::with('product_images')->where('type','Service')->where('user_id', Auth::user()->id)->where('shop_id', $edit_shop->id)->get();
            $pro  = Product::with('product_images')->where('type','Product')->where('user_id', Auth::user()->id)->where('shop_id', $edit_shop->id)->get();
    
            $arr["shop_detail"] = [
                                    "id"=>$edit_shop->id,
                                    "name"=>$edit_shop->name,
                                    "image"=> $edit_shop->image,
                                    "location"=>$edit_shop->location,
                                    "lat"=>$edit_shop->lat,
                                    "lng"=>$edit_shop->lng,
                                    "zip_code"=>$edit_shop->zip_code
                                    
                                ];
    
            if(count($ser) > 0) {
                foreach ($ser as $service) {

                    if(count($service["product_images"])) {
                        $image = $service["product_images"][0]->image;
                    } else {
                        $image = null;
                    }

                    $arr["services"][] = [
                        "id"=>$service->id,
                        "user_id"=>$service->user_id,
                        "name"=> $service->name,
                        "price"=> $service->hourly_rate,
                        "description"=>$service->description,
                        "service_type" => $service->service_type,
                        "image"=>$image
                    ];
                }
            } else {
                $arr["services"] = [];
            }
    
            if(count($pro) > 0) {
                foreach ($pro as $product) {

                    if(count($product["product_images"])) {
                        $image = $product["product_images"][0]->image;
                    } else {
                        $image = null;
                    }

                    $arr["products"][] = [
                        "id"=>$product->id,
                        "user_id"=>$product->user_id,
                        "name"=> $product->name,
                        "price"=> $product->price,
                        "description"=>$product->description,
                        "delivery_type" => $product->delivery_type,
                        "image"=>$image
                    ];
                }
            } else {
                $arr["products"] = [];
            }


            return $this->success($arr, 'Shop updated successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
      

    }

    public function view_favourite(Request $request) {  // Event, Job, Shop 

        try {            
            $arr = [];
            if($request->type == "Event") {

                $favourites = AddToFavourite::where('user_id', Auth::user()->id)->where('model_name', $request->type)->orderByDESC('id')->get();
                $currentDate = Carbon::now()->toDateString();
                foreach ($favourites as $item) {
                    $event = Event::with('event_owner', 'event_images')->where('date', '>=', $currentDate)->where('id', $item->model_id)->first();

                    if($event != null) {
                        if(count($event['event_images']) > 0){
                            $image = $event["event_images"][0]->image;
                        } else {
                            $image = "";
                        }
                        $arr[] = ["id"=>$event->id,"name"=>$event->name, "image"=> $image,"price"=>$event->price, "location" => $event->location, "owner_detail" => ["id"=>$event["event_owner"]->id,"name"=>$event["event_owner"]->name, "image"=> $event["event_owner"]->image,"rating"=>$event["event_owner"]->rating]];
                    }
                }
    
            } elseif($request->type == "Job") {

                $favourites = AddToFavourite::with('job')->where('user_id', Auth::user()->id)->where('model_name', $request->type)->orderByDESC('id')->get();
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

                $favourites = AddToFavourite::with('shop')->where('user_id', Auth::user()->id)->where('model_name', $request->type)->orderByDESC('id')->get();
                foreach ($favourites as $item) {
                    $shop = Shop::with('user')->where('id', $item->model_id)->first();
                    $total_products = Product::where('shop_id', $shop->id)->where('type','Product')->count('id');
                    $total_services = Product::where('shop_id', $shop->id)->where('type','Service')->count('id');

                    $arr[] = ["id"=>$shop->id,"name"=>$shop->name, "image"=> $shop->image,"location" => $shop->location, "total_products"=>$total_products, "total_services"=>$total_services, "owner_detail" => ["id"=>$shop["user"]->id,"name"=>$shop["user"]->name, "image"=> $shop["user"]->image,"rating"=>$shop["user"]->rating]];
                }   

            } else {
                $arr = [];
            }

    
            return $this->success($arr);

        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        

       
    }
}
