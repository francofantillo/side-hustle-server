<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Stripe;
use Illuminate\Support\Facades\Stripe\StripeClient;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationYear;
use LVR\CreditCard\CardExpirationMonth;
use Illuminate\Support\Facades\Auth;
use App\Models\UserCard;
use App\Models\Plan;



class CardController extends Controller
{
    private $stripe;
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    }

    public function plans() {
        
       $plans = Plan::all();

       return $this->success($plans);
    }

    public function cards() {

        $cards = UserCard::where('user_id', Auth::user()->id)->get();
        return $this->success($cards);
    }
    // public function add_card(Request $request){
    //     try{

    //         $validator  = Validator::make($request->all(), [
    //             "card_holder" => 'required',
    //             "last4" => 'required',
    //             "token" => 'required',
    //             // "card_number" => 'required',
    //             // "exp_month"   => 'required',
    //             // "exp_year"    => 'required',
    //             // "cvv"         => 'required',
    //         ]);
    //         if ($validator->fails()){
    //             return $this->error('Validation Error', 200, [], $validator->errors());
    //         }
    //         // $token = $this->stripe->tokens->create(array(
    //         //     "card" => array(
    //         //         'name'      => $request['card_holder'],
    //         //         "number"    => $request['card_number'],
    //         //         "exp_month" => $request['exp_month'],
    //         //         "exp_year"  => $request['exp_year'],
    //         //         "cvc"       => $request['cvv']
    //         //     )
    //         // ));
    //         $customer = $this->stripe->customers->create([
    //             'source'      => $request->token,
    //             'email'       => Auth::user()->email,
    //             'description' => Auth::user()->name.' card'
    //         ]);

    //         // $last4 = substr($request->card_number, -4);
    //         UserCard::createCard($customer['id'], $customer['default_source'], $request->last4, 'visa', $request->card_holder, 0);
    //         $cards = UserCard::where('user_id', Auth::user()->id)->get();
    //         return $this->success($cards, 'Card added successfully');
    //     }catch (\Exception $ex){
    //         return $this->error($ex->getMessage());
    //     }

    // }

    public function add_card(Request $request) {

        try{
            $validator  = Validator::make($request->all(), [
                "card_holder" => 'required',
                "last4"       => 'required',
                "token"       => 'required',
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }
            $userid = Auth::user()->id;

            $check_customer = UserCard::where('user_id', $userid)->first();

            if($check_customer == null) {

                $customer = $this->stripe->customers->create([
                    'name'        => Auth::user()->name,
                    'email'       => Auth::user()->email,
                    'description' => Auth::user()->name.' card',
                ]);
                $customerId = $customer["id"];
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    
                $stripeCard = $stripe->paymentMethods->attach(
                                        $request->token,
                                    ['customer' => $customerId]
                                );

                $stripe->customers->update(
                    $customerId,
                    ['invoice_settings' => ['default_payment_method' => $request->token]]
                  );
                                
                UserCard::createCard($customer['id'], $stripeCard['id'], $request->last4, 'visa', $request->card_holder, 1);
            } else {

                $customerId = $check_customer->customer_id;
                $stripe     = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    
                $stripeCard = $stripe->paymentMethods->attach(
                                        $request->token,
                                    ['customer' => $customerId]
                                );
                                
                UserCard::createCard($customerId, $stripeCard['id'], $request->last4, 'visa', $request->card_holder, 0);
            }

            $cards = UserCard::where('user_id', $userid)->get();
            return $this->success($cards,'Card added successfully');

        } catch (\Exception $ex){
            return $this->error($ex->getMessage());
        }
    }

    public function set_default_card(Request $request) {

        try {
            //code...
            $validator  = Validator::make($request->all(), [
                "card_id" => 'required',
            ]);
            if ($validator->fails()){
                return $this->error('Validation Error', 200, [], $validator->errors());
            }
    
            $user_card = UserCard::find($request->card_id);

            if($user_card->is_default == 0) {
                $customerId = $user_card->customer_id;
                $stripe     = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        
                $stripe->customers->update(
                    $customerId,
                    ['invoice_settings' => ['default_payment_method' => $user_card->card_id]]
                );

                $prev_default_card = UserCard::where('user_id', Auth::user()->id)->where('is_default',1)->first();

                if($prev_default_card != null) {
                    $prev_default_card->is_default = 0;
                    $prev_default_card->save();
                }

                $user_card->is_default = 1;
                $user_card->save();

                $cards = UserCard::where('user_id', Auth::user()->id)->get();

                return $this->success($cards, 'This Card set to as default');
            } else {

                $cards = UserCard::where('user_id', Auth::user()->id)->get();
                return $this->error('OOps,This card is already set to as default.',200, $cards);
            }

        }catch (\Exception $ex){
            return $this->error($ex->getMessage());
        }
                        
    }


    public function retrieveCard(Request $request) {
        
        $cardId   = $request->id;
        $userCard = UserCard::find($cardId);
        if ($userCard != null) {
            try {
                $cardDetail = $this->stripe->customers->retrieveSource($userCard->customer_id, $userCard->card_id);
                return $this->success(array(
                    'name'      => $cardDetail->name,
                    'last4'     => $cardDetail->last4,
                    'exp_month' => $cardDetail->exp_month,
                    'exp_year'  => $cardDetail->exp_year,
                    'brand'     => $cardDetail->brand,
                ));

            } catch (\Exception $ex) {
                return $this->error($ex->getMessage());
            }
        }
        return $this->error('Record not found');
    }

}
