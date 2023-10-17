<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'group_id'
    ];

    public function groups(){
        return $this->belongsTo(Group::class , 'group_id');
    }
    
}
