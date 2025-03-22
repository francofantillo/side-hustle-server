<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartDetail;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Message;
use App\Models\Chat;
use App\Models\User;
use App\Models\Shop;



class CartController extends Controller
{
    public function add_cart(Request $request) {

        try{

            $validator  = Validator::make($request->all(), [
                "shop_id"    => 'required',
                "product_id" => 'required',
                "qty"        => 'required',
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }
            $userid     = Auth::user()->id;
            $product_id = $request->product_id;
            $product    = Product::with('product_images')->where('id', $product_id)->first();

            $cart = Cart::where('user_id', $userid)->first();
            
          
            if($cart == null) {

                $add_cart              = new Cart();
                $add_cart->user_id     = $userid;
                $add_cart->owner_id    = $product->user_id;
                $add_cart->shop_id     = $request->shop_id;
                $add_cart->sub_total   = 0;
                $add_cart->total_items = 0;
                $add_cart->address     = $request->address;
                $add_cart->street      = $request->street;
                $add_cart->appartment  = $request->appartment;
                $add_cart->lat         = $request->lat;
                $add_cart->lng         = $request->lng;
                $add_cart->save();

                if(count($product["product_images"]) > 0) {
                    $pro_image = $product["product_images"][0]->image; 
                } else {
                    $pro_image = ""; 
                }

                $amount = 0;
                $qty    = 0;

                $detail                = new CartDetail();
                $detail->cart_id       = $add_cart->id;
                $detail->type          = $product->type;
                $detail->product_id    = $product_id;
                $detail->product_name  = $product->name;
                $detail->product_image = $pro_image;

                if($product->type == "Service") {
                    $detail->price        = $product->hourly_rate;
                    $detail->service_type = $product->service_type;

                    $detail->service_date   = $request->service_date;
                    $detail->hours_required = $request->hours_required;
                    $detail->start_time     = $request->start_time;
                    $detail->end_time       = $request->end_time;

                    $amount += $product->hourly_rate * $request->qty;
                    ++$qty;

                } else {
                    $detail->price         = $product->price;
                    $detail->delivery_type = $product->delivery_type;

                    $amount += $product->price * $request->qty;
                    ++$qty;
                }

                $detail->description = $product->description;
                $detail->qty         = $qty;
                $detail->save();

                $update_cart = Cart::find($add_cart->id);
                $update_cart->sub_total   = $amount;
                $update_cart->total_items = $qty;
                $update_cart->save(); 

                $items = Cart::with('cart_details')->where('user_id', $userid)->first();
                $cart_items = CartDetail::where('cart_id', $add_cart->id)->get();

                $is_address = 0;
                foreach ($cart_items as $item) {
        
                    if($item->service_type != null || $item->delivery_type == "fixed") {
                        $is_address = 1;
                    }
                }
                $items->is_address = $is_address;
                return $this->success($items,'Product added in cart.');
               
            } else {

              
                if($cart->shop_id == $request->shop_id) {
                    
                    $check_product = CartDetail::where('cart_id', $cart->id)->where('product_id', $product_id)->first();
                    if($check_product == null) {
                        $detail                = new CartDetail();
                        $detail->cart_id       = $cart->id;
                        $detail->type          = $product->type;
                        $detail->product_id    = $product_id;
                        $detail->product_name  = $product->name;

                        if( count($product["product_images"]) > 0) {
                            $detail->product_image = $product["product_images"][0]->image;
                        }
                        if($product->type == "Service") {
                           
                            $detail->price        = $product->hourly_rate;
                            $detail->service_type = $product->service_type;

                            $detail->service_date   = $request->service_date;
                            $detail->hours_required = $request->hours_required;
                            $detail->start_time     = $request->start_time;
                            $detail->end_time       = $request->end_time;

                            // $amount = $product->hourly_rate * $request->qty;

                        } else {
                            $detail->price         = $product->price;
                            $detail->delivery_type = $product->delivery_type;

                            // $amount = $product->price * $request->qty;
                        }
        
                        $detail->description   = $product->description;
                        $detail->qty           = $request->qty;
                        $detail->save();

                        $cart_items       = CartDetail::where('cart_id', $cart->id)->get();
                        $cart_items_count = CartDetail::where('cart_id', $cart->id)->count();

                        $amount = 0;
                        $qty    = 0;
                        foreach ($cart_items as $item) {
                            $amount += $item->price * $item->qty;
                            ++$qty;
                        }

                        $update_cart = Cart::find($cart->id);
                        $update_cart->sub_total   = $amount;
                        $update_cart->total_items = $cart_items_count;
                        $update_cart->save(); 

                        $message = "product added in cart.";

                    } else {
                        return $this->error("Product already in cart");
                    }

                    $cart_items = Cart::with('cart_details')->where('user_id', $userid)->first();

                    return $this->success($cart_items,$message);

                } else {

                    CartDetail::where('cart_id', $cart->id)->delete();
                    Cart::where('user_id', $userid)->delete();

                    $add_cart           = new Cart();
                    $add_cart->user_id  = $userid;
                    $add_cart->owner_id = $product->user_id;
                    $add_cart->shop_id  = $request->shop_id;
                    $add_cart->sub_total   = 0;
                    $add_cart->total_items = 0;
                    $add_cart->address     = $request->address;
                    $add_cart->street      = $request->street;
                    $add_cart->appartment  = $request->appartment;
                    $add_cart->lat         = $request->lat;
                    $add_cart->lng         = $request->lng;
                    $add_cart->save();

                    if(count($product["product_images"]) > 0) {
                        $pro_image = $product["product_images"][0]->image; 
                    } else {
                        $pro_image = ""; 
                    }
                    $amount = 0;
                    $detail                = new CartDetail();
                    $detail->cart_id       = $add_cart->id;
                    $detail->type          = $product->type;
                    $detail->product_id    = $product_id;
                    $detail->product_name  = $product->name;
                    $detail->product_image = $pro_image;
    
                    if($product->type == "Service") {
                        $detail->price        = $product->hourly_rate;
                        $detail->service_type = $product->service_type;
    
                        $detail->service_date   = $request->service_date;
                        $detail->hours_required = $request->hours_required;
                        $detail->start_time     = $request->start_time;
                        $detail->end_time       = $request->end_time;
    
                        $amount += $product->hourly_rate * $request->qty;
    
                    } else {
                        $detail->price         = $product->price;
                        $detail->delivery_type = $product->delivery_type;
    
                        $amount += $product->price * $request->qty;
                    }
    
                    $detail->description   = $product->description;
                    $detail->qty           = $request->qty;
                    $detail->save();
    
    
                    $update_cart = Cart::find($add_cart->id);
                    $update_cart->sub_total   = $amount;
                    $update_cart->total_items = $request->qty;
                    $update_cart->save(); 


                    $items = Cart::with('cart_details')->where('user_id', $userid)->first();
                    $cart_items = CartDetail::where('cart_id', $update_cart->id)->get();
                      
                    $is_address = 0;
                    foreach ($cart_items as $item) {
            
                        if($item->service_type != null || $item->delivery_type == "fixed") {
                            $is_address = 1;
                        }
                    }
                    $items->is_address = $is_address;

                    return $this->success($items,'Product added in cart.');

                }
            }
          
        }catch (\Exception $ex){
            return $this->error($ex->getMessage());
        }
    }

    public function update_cart(Request $request) {
        $validator  = Validator::make($request->all(), [
            "cart_detail_id" => 'required',
            "qty"     => 'required',
        ]);
        if ($validator->fails()){
            return $this->error('Validation Error', 200, [], $validator->errors());
        }
        
        $cart_detail      = CartDetail::find($request->cart_detail_id);
        $cart_detail->qty = $request->qty;
        $cart_detail->save();

        $items  = CartDetail::where('cart_id', $cart_detail->cart_id)->get();
        $userid = Auth::user()->id;


        $cart_items = Cart::with('cart_details')->where('user_id', $userid)->first();

        $amount = 0;
        $qty    = 0;
        $is_address = 0;
        foreach ($items as $item) {
            $amount += $item->price * $item->qty;
            ++$qty;

            if($item->service_type != null || $item->delivery_type == "fixed") {
                $is_address = 1;
            }
        }

        $cart = Cart::find($cart_detail->cart_id);
        $cart->sub_total   = $amount;
        $cart->total_items = $qty;
        $cart->save(); 

        
        $updated_cart_items = Cart::with('cart_details')->where('user_id', $userid)->first();
        $updated_cart_items->is_address = $is_address;
        return $this->success($updated_cart_items,'Quantity updated');

    }

    public function view_cart() {

        $userid     = Auth::user()->id;
        $cart_items = Cart::with('cart_details')->where('user_id', $userid)->first();

        if($cart_items != null) {
            $items      = CartDetail::where('cart_id', $cart_items->id)->get();
    
            $is_address = 0;
            foreach ($items as $item) {


                if($item->type == "Service") {
                    $is_address = 1;
                } elseif($item->type == "Product" && $item->delivery_type == "Fixed" ) {
                    $is_address = 1;
                }
                // if($item->service_type != null || $item->delivery_type == "fixed") {
                // }
            }
            $cart_items->is_address = $is_address;
            return $this->success($cart_items);
        } else {
            return $this->error("No products to show",200, null);
        }
    }

    public function place_order(Request $request) {

        try {

            $validator  = Validator::make($request->all(), [
                "cart_id"    => 'required',
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }
            $userid   = Auth::user()->id;
            $cartid   = $request->cart_id;

            $cart     = Cart::find($cartid);
            $today    = date("Ymd");
            $rand     = strtoupper(substr(uniqid(sha1(time())), 0, 4));
            $order_no = $today . $rand;

            $shop = Shop::find($cart->shop_id);
    
            $order                   = new Order();
            $order->order_no         = $order_no;
            $order->owner_id         = $cart->owner_id;
            $order->user_id          = $userid;
            $order->customer_name    = Auth::user()->name;
            $order->customer_email   = Auth::user()->email;
            $order->sub_total        = $cart->sub_total;
            $order->total            = $cart->sub_total;
            $order->items_total      = $cart->total_items;
            $order->delivery_address = $cart->address;
            $order->street           = $cart->street;
            $order->appartment       = $cart->appartment;
            $order->lat              = $cart->lat;
            $order->lng              = $cart->lng;
            $order->order_status     = "Pending";
            $order->save();

            $cart_details  = CartDetail::where('cart_id', $cartid)->get();
            $product_count = 0;
            $is_service    = 0;
            $is_fixed      = 0;
            foreach ($cart_details as $item) {

                if($item->type == "Service") {
                    $is_service =1;
                }

                if($item->type == "Product" && $item->delivery_type == "Fixed") {
                    $is_fixed = 1;
                }

                ++$product_count;

                $order_detail                         = new OrderDetail();
                $order_detail->order_id               = $order->id;
                $order_detail->shop_id                = $cart->shop_id;
                $order_detail->product_id             = $item->product_id;
                $order_detail->type                   = $item->type;
                $order_detail->product_name           = $item->product_name;
                $order_detail->delivery_type          = $item->delivery_type;
                $order_detail->service_type           = $item->service_type;
                $order_detail->product_per_price      = $item->price;
                $order_detail->product_qty            = $item->qty;
                $order_detail->product_subtotal_price = $item->price * $item->qty;
                $order_detail->product_image          = $item->product_image;
                $order_detail->save();
            }

            // $get_chat = Chat::where('model_id', $order->id)->where('model_name','Order')->first();
            $orderid = $order->id;
            $user_id = Auth::user()->id;
            $cust_id = $cart->owner_id;

            $customer = User::find($cust_id);
         
            $get_chat   = Chat::where(function ($q) use($orderid, $user_id, $cust_id) {
                                $q->where( function ($e) use($user_id, $cust_id) {
                                    $e->where(['user_one'=>$user_id,'user_two'=>$cust_id])
                                    ->orWhere(['user_one' => $cust_id, 'user_two' => $user_id]);
                                });
                                // ->where('model_id', $orderid)->where('model_name', 'Order');
                            })->first();

            if($get_chat == null) {

                $newChat = Chat::create([
                    "model_id"       => $orderid,
                    "model_name"     => "Order",
                    "user_one"       => $user_id,
                    'user_two'       => $cust_id,
                    'user_one_model' => "Buyer",
                    'user_two_model' => "Seller",
                ]);  

                if($is_service == 1) {
                    $pro_type = "Service";
                } else {
                    $pro_type = "Product";
                }

                if($is_fixed == 1) {
                    $pro_dev_type = "Fixed";
                } else {
                    $pro_dev_type = "Pickup";
                }

                $message = Message::create([
                    "chat_id"        => $newChat->id,
                    "sender_id"      => $user_id,
                    "receiver_id"    => $cust_id,
                    'sender_model'   => "Buyer",
                    'receiver_model' => "Seller",
                    'product_count'  => $product_count,
                    "message"        => $customer->name.", I'd like to buy this product from your shop, here is my address",
                    "product_type"   => $pro_type,
                    "name"           => $cart_details[0]->product_name,
                    "price"          => $cart_details[0]->price,
                    "shop_name"      => $shop->name,
                    "delivery_type"  => $pro_dev_type,
                    "service_date"   => $cart_details[0]->service_date,
                    "start_time"     => $cart_details[0]->start_time,
                    "end_time"       => $cart_details[0]->end_time,
                    "image"          => $cart_details[0]->product_image,
                    "location"       => $cart->address,
                    "lat"            => $cart->lat,
                    "lng"            => $cart->lng,
                    "description"    => $cart_details[0]->description,
                ]);  
                $message = Message::create([
                    "chat_id"        => $newChat->id,
                    "sender_id"      => $cust_id,
                    "receiver_id"    => $user_id,
                    'sender_model'   => "Seller",
                    'receiver_model' => "Buyer",
                    "product_type"   => NULL,
                    "message"        => "Thank you for showing your interest, I'll prepare your order and will deliver it in 24-28 Hours.",
                ]);  
                $messages = Message::where('chat_id',$newChat->id)->orderBy('id','asc')->get();

            } else {

                $message = Message::create([
                    "chat_id"        => $get_chat->id,
                    "sender_id"      => $user_id,
                    "receiver_id"    => $cust_id,
                    'sender_model'   => "Buyer",
                    'receiver_model' => "Seller",
                    'product_count'  => $product_count,
                    "message"        => $customer->name.", I'd like to buy this product from your shop, here is my address",
                    "product_type"   => $cart_details[0]->type,
                    "name"           => $cart_details[0]->product_name,
                    "price"          => $cart_details[0]->price,
                    "shop_name"      => $shop->name,
                    "delivery_type"  => $cart_details[0]->delivery_type,
                    "service_date"   => $cart_details[0]->service_date,
                    "start_time"     => $cart_details[0]->start_time,
                    "end_time"       => $cart_details[0]->end_time,
                    "image"          => $cart_details[0]->product_image,
                    "location"       => $cart->address,
                    "lat"            => $cart->lat,
                    "lng"            => $cart->lng,
                    "description"    => $cart_details[0]->description,
                ]);  
                
                $message = Message::create([
                    "chat_id"        => $get_chat->id,
                    "sender_id"      => $cust_id,
                    "receiver_id"    => $user_id,
                    'sender_model'   => "Seller",
                    'receiver_model' => "Buyer",
                    "type"           => 3,
                    "message_type"   => 3,
                    "product_type"   => NULL,
                    "message"        => "Thank you for showing your interest, I'll prepare your order and will deliver it in 24-28 Hours.",
                ]);  

                $messages = Message::where('chat_id',$get_chat->id)->orderBy('id','asc')->get();
            }
            CartDetail::where('cart_id', $cartid)->delete();
            Cart::where('id', $cartid)->where('user_id', $userid)->delete();

            $arr = [
                    "model_id"    => $orderid,
                    "model_name"  => "Order",
                    "customer_id" => $cust_id,
                    "messages"    => $messages
                ]; 

            return $this->success($arr, "Order places successfully.");

        } catch (\Exception $ex){
            return $this->error($ex->getMessage());
        }
    }

    public function update_address(Request $request) {
        try{

            $validator  = Validator::make($request->all(), [
                "cart_id"        => 'required',
                "address"        => 'required',
                "lat"            => 'required',
                "lng"            => 'required',
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }
            $userid     = Auth::user()->id;

            $update_add             = Cart::find($request->cart_id);
            $update_add->address    = $request->address;
            $update_add->lat        = $request->lat;
            $update_add->lng        = $request->lng;
            $update_add->street     = $request->street;
            $update_add->appartment = $request->appartment;
            $update_add->save();

            $cart_items = Cart::with('cart_details')->where('user_id', $userid)->first();


            if($cart_items != null) {
                
                $items      = CartDetail::where('cart_id', $cart_items->id)->get();
                $is_address = 0;
                foreach ($items as $item) {

                    if($item->type == "Service") {
                        $is_address = 1;
                    } elseif($item->type == "Product" && $item->delivery_type == "Fixed" ) {
                        $is_address = 1;
                    }
                }
                $cart_items->is_address = $is_address;
            } else {
                return $this->error("No products to show",200, null);
            }
            
            return $this->success($cart_items,"Address updated.");
            
        } catch (\Exception $ex){
            return $this->error($ex->getMessage());
        }
    }

    public function order_detail(Request $request) {
        $details = OrderDetail::where('order_id', $request->order_id)->get();
        return $this->success($details);
    }
}
