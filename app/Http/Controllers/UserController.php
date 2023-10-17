<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
   public function loadDash(){
    $users = User::whereNotIn('id', [Auth::id()])->get();
    return view('dashboard' , ['users' => $users]);
   }
}
