<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupMember;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
        public function loadGroups(){
          $groups = Group::where('creator_id' , Auth::id())->get();
          return view('groups',['groups' => $groups]);
        }

        public function groupChats(){
          $groups = GroupMember::where('user_id' , Auth::id())->get();
          return view('groupChat',['groups' => $groups]);
        }


        public function saveGroup(Request $request){
             $group = new Group();
             $group->creator_id = Auth::id();
             $group->name = $request->name;
             $group->join_limit = $request->join_limit;
             $content=file_get_contents($request -> image);
             $ext = $request -> image ->extension();
             $filename = Str::random(25);
             $file_path = "groups/$filename.$ext";
             Storage::disk('public') -> put( $file_path , $content);
             $group->image = $file_path;
             if($group->save()){
               GroupMember::create([
                'user_id'=> $group->creator_id,
                'group_id'=>$group->id
               ]);
               return response()->json(['success'=>true , 'data'=> $group]);
             }else{
              return response()->json(['success'=>false , 'message'=> 'Error Occurred']);
             }       
    }

    public function viewMembers(Request $request){
      try{
        $members=GroupMember::all();
        $userIds = $members->pluck('user_id')->toArray();
        $users = User::whereNotIn('id' ,$userIds)->get();
        return response()->json(['success'=>true , 'data'=> $users]);
      }catch(Exception $e){
        return response()->json(['success'=>false , 'message'=> $e->getMessage()]);
      }

    }


    public function addMembers(Request $request){
      try{
        dd($request);
        return response()->json(['success'=>true , 'data'=> $users]);
      }catch(Exception $e){
        return response()->json(['success'=>false , 'message'=> $e->getMessage()]);

      }

    }
}
