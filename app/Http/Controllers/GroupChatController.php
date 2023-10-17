<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupChat;
use App\Events\GroupMessageEvent;

class GroupChatController extends Controller
{
    public function groupChat(Request $request){
        $chat = GroupChat::create([
          'sender_id' => $request->sender_id,
          'group_id' => $request->group_id,
          'message' => $request->message
        ]);
        if($chat->save()){
          event(new GroupMessageEvent($chat));
          return response()->json(['success'=>true , 'data'=> $chat]);
        }else{
          dd($chat);
          return response()->json(['success'=>false , 'message'=> 'Error Occurred']);
        }
      }

    public function loadGroupChat(Request $request){
        try{
          $chats = GroupChat::where('group_id' , $request->group_id)->get();
          return response()->json(["success"=>true , "data"=>$chats]);
        }catch(Exception $e){
          return response()->json(["success"=>false , "msg"=>$e->getMessage()]);
        }
      }
}
