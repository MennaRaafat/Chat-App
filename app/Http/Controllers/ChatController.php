<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Events\MessageEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class ChatController extends Controller
{
    public function saveChat(Request $request){
      $chat = Chat::create([
        'sender_id' => $request->sender_id,
        'receiver_id' => $request->receiver_id,
        'message' => $request->message
      ]);
      if($chat->save()){
        event(new MessageEvent($chat));
        return response()->json(['success'=>true , 'data'=> $chat]);
      }else{
        dd($chat);
        return response()->json(['success'=>false , 'message'=> 'Error Occurred']);
      }
    }

    public function loadChat(Request $request){
      try{
        $lastRead = Carbon::parse($request->last_read)->toDateTimeString();
        Chat::where('receiver_id', Auth::id())
        ->whereNull('last_read')
        ->update(['last_read' => $lastRead, 'status' => 1]);
        $chats = Chat::where(function($query)use($request){
          $query -> where('sender_id' , '=' ,$request->sender_id)
                 ->orWhere('sender_id' , '=' , $request->receiver_id);
       })->where(function($query)use($request){
        $query -> where('receiver_id' , '=' , $request->receiver_id)
               ->orWhere('receiver_id' , '=' ,$request->sender_id);
              })->get();
        return response()->json(["success"=>true , "data"=>$chats]);
      }catch(Exception $e){
        return response()->json(["success"=>false , "msg"=>$e->getMessage()]);
      }
    }


    public function getUnRead(){
        $userId = Auth::id();
        $unreaded_messages = Chat::where('receiver_id' , $userId)
             ->whereNull('last_read')
             ->get();
        return response()->json(["success"=>true , "data"=>$unreaded_messages]);
    }


}
