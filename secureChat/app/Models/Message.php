<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    public static function getAllMessages($user_sender, $user_recipient){
        $allMessage = DB::select("SELECT m.user_id, m.userRecipient_id, u.name, m.message FROM MESSAGES m join users u on u.id = m.user_id WHERE m.user_id = ? and m.userRecipient_id = ?  or m.userRecipient_id = ? and m.user_id = ? order by m.id", [$user_sender,$user_recipient,$user_sender,$user_recipient]);
        return $allMessage;
    }

    public static function getIdByName($user_name){
        $user_id = DB::select("SELECT id FROM users WHERE name = ?", [$user_name]);
        return $user_id;
    }
    
    public static function getAllMembers(){
        return DB::select("SELECT u.id , u.name from users u where u.name != ?", [Auth::user()->name]);
        //  return DB::select("SELECT u.id , u.name from users u 
        //  join contacts c on u.id = c.user_id 
        //  join users u2 on u2.name = ?
        //  where u.name != ? and c.userRecipient_id != u2.id;", [Auth::user()->name,Auth::user()->name]);
    }

    public static function addInvitation($user_sender,$user_recipient){
        if(!DB::table('invitation')->where([['user_sender' ,'=',$user_sender],['user_recipient','=', $user_recipient]])->exists()){
            DB::insert("INSERT INTO invitation values(?,?)",[$user_sender,$user_recipient]);
        }
    }

    public static function getInvitation(){
        return DB::select("SELECT u.name from invitation i join users u on i.user_sender = u.id where i.user_recipient = ?",[Message::getIdByName(Auth::user()->name)[0]->id]);
    }

    public static function acceptInvitation($user_sender,$user_recipient){
        DB::delete("DELETE from invitation where user_recipient = ? and user_sender = ?",[$user_sender, $user_recipient]);
        if(!DB::table('contacts')->where([['user_id' ,'=',$user_sender],['userRecipient_id','=', $user_recipient]])->exists()){
            Db::insert("INSERT into contacts values(?,?,?)",[$user_sender,$user_recipient,false]);
            Db::insert("INSERT into contacts values(?,?,?)",[$user_recipient,$user_sender,false]);
        }
    }
    public static function deniedInvitation($user_sender,$user_recipient){
        DB::delete("DELETE from invitation where user_sender = ? and user_recipient = ?",[$user_sender,$user_recipient]);
    }

    public static function getAllContacts($user){
        return DB::select("SELECT u.name, c.connect from contacts c join users u on u.id = c.userRecipient_id where user_id = ?",[$user]);
    }

    public static function setConnected($connected, $user){
        DB::update("UPDATE contacts set connect = ? where userRecipient_id = ?",[$connected,$user]);
    }

    public static function removeContact($user_sender, $user_recipient){
        DB::delete("DELETE from contacts where user_id = ? and userRecipient_id = ?", [$user_sender,$user_recipient]);
        DB::delete("DELETE from contacts where userRecipient_id = ? and user_id= ?", [$user_sender,$user_recipient]);

    }

    public static function isConnected($user){
        return DB::table('contacts')->where("userRecipient_id",$user)->value('connect');
    }

    public static function existName($user_recipient){
        return DB::table('users')->where('name',$user_recipient)->exists();
    }
}
