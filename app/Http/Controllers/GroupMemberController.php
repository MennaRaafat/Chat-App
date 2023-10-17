<?php

namespace App\Http\Controllers;
use App\Models\GroupMember;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{

   public function addMembers(Request $request){
    $membersNumber= GroupMember::where('group_id' , $request->id)->get();
    $membersNo = count($request->members) + count($membersNumber);
    if(!isset($request->members)){
        return response()->json(['success'=>false , 'msg'=> 'Select At Least One Member']);
    }else if($membersNo > $request->join_limit){
        return response()->json(['success'=>false , 'msg'=> 'You Exceeded The Group Limit '.$request->join_limit]);
    }else{
      $data=[];
      $x=0;
      foreach($request->members as $member){
       $data[$x]=['group_id'=>$request->id,'user_id'=>$member];
       $x++;
      }
      GroupMember::insert($data);
      return response()->json(['success'=>true , 'msg'=> 'Members Added Successfully']);
    }

   }
}
