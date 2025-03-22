<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use DB;
use Auth;
class ChatController extends Controller
{
    public function getChats() {
        try {

            DB::beginTransaction();
            $id = Auth::user()->id;
           
            if($id == null)
                return $this->error('something went wrong!');
            // $chats = Message::select('id', 'chat_id', 'sender_id','receiver_id','sender_model','receiver_model','message_type','type','chat_id','message','is_seen','created_at')
            //                     ->where(function ($q) use($id){
            //                         $q->where('sender_id',$id)->orWhere('receiver_id',$id);
            //                     })
            //                     ->whereIn('id',function ($q2) use($id){
            //                         $q2->select(DB::raw('max(id)'))->from('messages')->where(function ($q3) use($id){
            //                             $q3->where('sender_id',$id)->orWhere('receiver_id',$id);
            //                         });
            //                     })
            //                     ->orderBy('id','desc')
            //                     ->get();

            $chats = DB::table('chats')
                        ->leftJoin('messages', function ($join) {
                            $join->on('chats.id', '=', 'messages.chat_id')
                                ->whereRaw('messages.id = (select max(id) from messages where chat_id = chats.id)');
                        })
                        ->select('chats.*', 'messages.message as last_message','messages.message_type','messages.sender_model', 'messages.receiver_model', 'messages.sender_id', 'messages.receiver_id','messages.chat_id', 'messages.type', 'messages.is_seen', 'messages.created_at')
                        ->where(function ($query) use($id) {
                            $query->where('user_one', $id)
                                ->orWhere('user_two', $id);
                        })
                        ->where('is_blocked', 0)
                        ->get();
                      
            $chatUsers = [];
            foreach ($chats as $k => $chat) {
            
                $sender = null; $receiver = null;$user = null;
                if($chat->sender_model == 'Buyer'){
                    $sender = User::find($chat->sender_id);
                }
                else if($chat->sender_model == 'Seller'){
                    $sender = User::find($chat->sender_id);
                }

                if($chat->receiver_model == 'Buyer'){
                    $receiver = User::find($chat->receiver_id);
                }
                else if($chat->receiver_model == 'Seller'){
                    $receiver = User::find($chat->receiver_id);
                }
                
                if($sender->id != $id) {
                    $user = $sender;
                } else {
                    $user = $receiver;
                }
                // $get_model_id  = Chat::where('id', $chat->chat_id)->first();
                $chatUsers[] = [

                    "model_id"     => $chat->model_id,
                    "model_name"   => $chat->model_name,
                    'id'           => $chat->id,
                    "chat_id"      => $chat->chat_id,
                    'sender_id'    => $chat->sender_id,
                    'receiver_id'  => $chat->receiver_id,
                    'sender_model' => $chat->sender_model,
                    'message_type' => $chat->message_type,
                    'type'         => $chat->type,
                    'message'      => $chat->last_message,
                    'is_seen'      => $chat->is_seen,
                    'created_at'   => $chat->created_at,
                    // 'user'         => $user,
                    'user_id'      => $user->id,
                    'user_name'    => $user->name,
                    'user_image'   => $user->image,

                ];
            }
            DB::commit();

            return $this->success($chatUsers, "Chat List");

        } catch (\Exception $exception){
            DB::rollBack();
            return $this->error($exception->getMessage());
        }
    }

    public function getChatMessages(Request $request) {

        try {
                DB::beginTransaction();

                $perPage          = 50;
                $pageNo           = $request->page ?? 1;
                $user_model       = '';
                $other_user_model = '';
                $customer_id      = $request->customer_id;
                $model_id         = $request->model_id;
                $user_id          = Auth::user()->id;
                $cust_id          = $request->customer_id;

                // if(Auth::check()) {
                //     if(Auth::user()->role_id == 2) {

                //         $user_model       = 'Buyer';
                //         $other_user_model = 'Seller';
                    
                //     } elseif(Auth::user()->role_id == 3) {
                //         $user_model       = 'Seller';
                //         $other_user_model = 'Buyer';
                //     }
                // }
                $chat          = Chat::where(function ($q) use($model_id, $user_id, $cust_id) {
                                    $q->where( function ($e) use($user_id, $cust_id) {
                                        $e->where(['user_one'=>$user_id,'user_two'=>$cust_id])
                                        ->orWhere(['user_one' => $cust_id, 'user_two' => $user_id]);
                                    })
                                    ->where('model_id', $model_id);
                                })->first();


                $newChat = $chat;
                if($chat == null) {

                    $newChat = Chat::create([
                        "model_id"       => $model_id,
                        "model_name"     => $request->model_name,
                        "user_one"       => $user_id,
                        'user_two'       => $cust_id,
                        // 'user_one_model' => $user_model,
                        // 'user_two_model' => $other_user_model,
                    ]);
                    $messages = Message::where('chat_id',$newChat->id)->orderBy('id','asc')->get();
                } else {
                    $messages = Message::where('chat_id',$chat->id)->orderBy('id','asc')->get();
                }

                // $messages = Message::where('chat_id',$newChat->id)->limit($perPage)->offset(($pageNo - 1) * $perPage)->orderBy('id','asc')->get();

                if(count($messages) > 0) {
                    $arr["chat_id"]  = $chat->id;
                 
                } else {
                    $arr["chat_id"] = $newChat->id;
                }
                $arr["messages"] = $messages;
                DB::commit();
                return $this->success($arr, 'Chat Messages List');

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage());
        }
    }

    public function chatFileUpload(Request $request) {
        
        try {
                DB::beginTransaction();

                $ext_name = null;
                if($request->chat_id == 0) {
                   
                    $model_id         = $request->model_id;
                    $model_name       = $request->model_name;
                    $user_id          = Auth::user()->id;
                    $customer_id      = $request->receiver_id;
                    $user_model       = $request->sender_model;
                    $other_user_model = $request->receiver_model;
                    
                    $newChat = Chat::create([
                        "model_id"       => $model_id,
                        "model_name"     => $model_name,
                        "user_one"       => $user_id,
                        'user_two'       => $customer_id,
                        'user_one_model' => $user_model,
                        'user_two_model' => $other_user_model,
                    ]);

                    $message                 = new Message();
                    $message->chat_id        = $newChat->id;
                    $message->sender_id      = $request->sender_id;
                    $message->receiver_id    = $request->receiver_id;
                    $message->sender_model   = $request->sender_model;
                    $message->receiver_model = $request->receiver_model;
                    $message->message_type   = 2;

                    if($request->file_type != null) {
                        $ext_name =  $request->file_type; 
                    }
        
                    if ($request->image != "") {
                        $base64_string = $request->image;
                        define('UPLOAD_DIR','uploads/profile/');
                        $image_parts=explode(";base64,",$base64_string);
                        $image_type_aux=explode("image/",$image_parts[0]);
                        $image_type=$image_type_aux[0];
                        $image_base64=base64_decode($image_parts[0]);
                        if($request->file_type != null || $request->file_type != "") {
                            $file= UPLOAD_DIR .uniqid().'.'.$request->file_type;
                        } else {
                            $file= UPLOAD_DIR .uniqid().'.png';
                        }
                        file_put_contents($file,$image_base64);
                    } else {
                        $file = "";
                    }
                    if ($file != null || $file != "") {
                        $message->file_path = asset($file);
                    }
        
                    $message->type    = $request->type;
                    $message->save();
        
                    DB::commit();
            
                    $data = ["file_path"=>$message->file_path, 'chat_id' => $request->chat_id, 'sender_id'=>$request->sender_id, "receiver_id" => $request->receiver_id, "sender_model"=> $request->sender_model, "receiver_model"=>$request->receiver_model];
                    return $this->success($data, 'Successfully uploaded');

                } else {

                    $message                 = new Message();
                    $message->sender_id      = $request->sender_id;
                    $message->receiver_id    = $request->receiver_id;
                    $message->sender_model   = $request->sender_model;
                    $message->receiver_model = $request->receiver_model;
                    $message->message_type   = 2;

                    if($request->file_type != null) {
                        $ext_name =  $request->file_type; 
                    }
        
                    if ($request->image != "") {
                        $base64_string = $request->image;
                        define('UPLOAD_DIR','uploads/profile/');
                        $image_parts=explode(";base64,",$base64_string);
                        $image_type_aux=explode("image/",$image_parts[0]);
                        $image_type=$image_type_aux[0];
                        $image_base64=base64_decode($image_parts[0]);
                        if($request->file_type == null) {
                            $file= UPLOAD_DIR .uniqid().'.png';
                        } else {
                            $file= UPLOAD_DIR .uniqid().'.'.$request->file_type;
                        }
                        file_put_contents($file,$image_base64);
                    } else {
                        $file = "";
                    }
                    if ($file != null || $file != "") {
                        $message->file_path = asset($file);
                    }
                    // $message->file_type = $ext_name;
                    $message->type    = $request->type;
                    $message->chat_id = $request->chat_id;
                    $message->save();
        
                    DB::commit();
                    $data = [
                                "file_type"     => $message->file_type,
                                "file_path"     => $message->file_path,
                                'chat_id'       => $request->chat_id,
                                'sender_id'     => $request->sender_id,
                                "receiver_id"   => $request->receiver_id,
                                "sender_model"  => $request->sender_model,
                                "receiver_model"=>$request->receiver_model
                            ];
                    return $this->success($data, 'Successfully uploaded');
                }
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage());
        }
    }

    public function available_users(Request $request) {
       
        $contractor_id = Auth::user()->id;
        $get_workers   = Job::where('contractor_id', $contractor_id)->where('worker_id', '!=', 0)->pluck('worker_id')->toArray();
        $get_users     = User::whereIn('id', $get_workers)->where('is_online', 1)->get();
        if(count($get_users) > 0) {
            foreach ($get_users as $item) {

                $arr[] = [
                    "id"    => $item->id,
                    "name"  => $item->name,
                    "image" => $item->image
                ];
            }
        } else {
            $arr = [];
        }
        return $this->success($arr);
    }

    public function block_user(Request $request) {
      
        try {
            $chat = Chat::findOrFail($request->chat_id);
            $chat->is_blocked = 1;
            $chat->save();
            return $this->success([], 'User blocked Successfully');

        } catch (Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }
    }

    public function unblock_user(Request $request) {
      
        try {
                $chat             = Chat::findOrFail($request->chat_id);
                $chat->is_blocked = 0;
                $chat->save();
                return $this->success([], 'User Un-Blocked Successfully');

        } catch (Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }
    }

    public function unblock_all(Request $request) {
        try {

            $userid = auth()->user()->id;
            $chats  = Chat::where(function ($query) use($userid) {
                            $query->where('user_one', $userid)
                                    ->orWhere('user_two', $userid);
                            })
                            ->where('is_blocked', 1)
                            ->pluck('id')
                            ->toArray();
            
            if(count($chats) > 0) {
                foreach ($chats as $item) {

                    $chat             = Chat::findOrFail($item);
                    $chat->is_blocked = 0;
                    $chat->save();
                }
            }
            return $this->success([], 'All User Un-Blocked Successfully');

        } catch (Exception $e) {
            //throw $th;
            return $this->error($e->getMessage());
        }
    }

    public function blocked_users(Request $request) {
        try {

            DB::beginTransaction();
            $userid = Auth::user()->id;
           
            if($userid == null)
                return $this->error('something went wrong!');
           
            $chats = DB::table('chats')
                        ->select('chats.*')
                        ->where(function ($query) use($userid) {
                            $query->where('user_one', $userid)
                                ->orWhere('user_two', $userid);
                        })
                        ->where('is_blocked', 1)
                        ->get();
                      
            $chatUsers = [];

           
            foreach ($chats as $k => $chat) {
            
                // $sender = null; $receiver = null;$user = null;
                // if($chat->sender_model == 'Buyer'){
                //     $sender = User::find($chat->sender_id);
                // }
                // else if($chat->sender_model == 'Seller'){
                //     $sender = User::find($chat->sender_id);
                // }

                // if($chat->receiver_model == 'Buyer'){
                //     $receiver = User::find($chat->receiver_id);
                // }
                // else if($chat->receiver_model == 'Seller'){
                //     $receiver = User::find($chat->receiver_id);
                // }
                $message = Message::where('chat_id', $chat->id)->orderByDESC('id')->pluck("message")->first();
                if($chat->user_one == $userid) {
                    $user = User::find($chat->user_two);
                } else {
                    $user = User::find($chat->user_one);
                }
                $chatUsers[] = [

                    "model_id"     => $chat->model_id,
                    "model_name"   => $chat->model_name,
                    // 'id'           => $chat->id,
                    "chat_id"      => $chat->id,
                    'user_id'      => $user->id,
                    'user_name'    => $user->name,
                    'user_image'   => $user->image,
                    "message"      => $message
                ];
            }
            DB::commit();

            return $this->success($chatUsers, "Chat List");

        } catch (\Exception $exception){
            DB::rollBack();
            return $this->error($exception->getMessage());
        }
    }
}
