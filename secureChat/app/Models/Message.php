<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['message'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function insertMessage($user_sender,$user_recipient,$message){
        DB::insert("INSERT INTO MESSAGES(user_id,userRecipient_id,message) values(?,?,?)",[$user_sender,$user_recipient,$message]);
    }

    public static function getAllMessages($user_sender, $user_recipient,$user_sender_name,$user_recipient_name){
        $allMessage = DB::select("SELECT m.user_id, m.userRecipient_id, u.name, m.message FROM MESSAGES m join users u on u.id = m.user_id WHERE m.user_id = ? and m.userRecipient_id = ?  or m.userRecipient_id = ? and m.user_id = ? order by m.id", [$user_sender,$user_recipient,$user_sender,$user_recipient]);
        return $allMessage;
    }

    public static function getIdByName($user_name){
        $user_id = DB::select("SELECT id FROM users WHERE name = ?", [$user_name]);
        return $user_id;
    }
    
}
