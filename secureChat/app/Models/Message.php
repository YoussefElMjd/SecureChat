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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Allows to insert a new message in the data base
     * @param $user_sender the user id of the sender
     * @param $user_recipient the user id of the recipient
     * @param $message the message
     */
    public static function insertMessage($user_sender, $user_recipient, $message)
    {
        DB::insert("INSERT INTO MESSAGES(user_id,userRecipient_id,message) values(?,?,?)", [$user_sender, $user_recipient, $message]);
    }

    /**
     * Allows you to get the whole conversation with a person
     *  @param $user_sender the user id of the sender
     *  @param $user_recipient the user recipient id
     * @return all the conversation
     */
    public static function getAllMessages($user_sender, $user_recipient)
    {
        $allMessage = DB::select("SELECT m.user_id, m.userRecipient_id, u.name, m.message FROM MESSAGES m join users u on u.id = m.user_id WHERE m.user_id = ? and m.userRecipient_id = ?  or m.userRecipient_id = ? and m.user_id = ? order by m.id", [$user_sender, $user_recipient, $user_sender, $user_recipient]);
        return $allMessage;
    }
    /**
     * Allows to get a name of a user with id
     *  @param $user_name the name of the user
     * @return all the id
     */
    public static function getIdByName($user_name)
    {
        $user_id = DB::select("SELECT id FROM users WHERE name = ?", [$user_name]);
        return $user_id;
    }
    /**
     * Allows to return all member in the database
     * @return all all members
     */
    public static function getAllMembers()
    {
        return DB::select("SELECT u.id , u.name from users u where u.name != ?", [Auth::user()->name]);
    }
    /**
     * Allows to send a invitation to a user if the invitation has not been send
     *  @param $user_sender the user id of the sender
     *  @param $user_recipient the user recipient id
     */
    public static function addInvitation($user_sender, $user_recipient)
    {
        if (!DB::table('invitation')->where([['user_sender', '=', $user_sender], ['user_recipient', '=', $user_recipient]])->exists()) {
            DB::insert("INSERT INTO invitation values(?,?)", [$user_sender, $user_recipient]);
        }
    }
    /**
     * Allows you to receive all invitations received
     * @return all all invitations
     */
    public static function getInvitation()
    {
        return DB::select("SELECT u.name from invitation i join users u on i.user_sender = u.id where i.user_recipient = ?", [Message::getIdByName(Auth::user()->name)[0]->id]);
    }
    /**
     * ALlows to accept a invvitation
     * @param $user_sender the user id of the sender
     *  @param $user_recipient the user recipient id
     */
    public static function acceptInvitation($user_sender, $user_recipient)
    {
        DB::delete("DELETE from invitation where user_recipient = ? and user_sender = ?", [$user_sender, $user_recipient]);
        if (!DB::table('contacts')->where([['user_id', '=', $user_sender], ['userRecipient_id', '=', $user_recipient]])->exists()) {
            Db::insert("INSERT into contacts values(?,?,?)", [$user_sender, $user_recipient, false]);
            Db::insert("INSERT into contacts values(?,?,?)", [$user_recipient, $user_sender, false]);
        }
    }
    /**
     * ALlows to denied a invvitation
     * @param $user_sender the user id of the sender
     *  @param $user_recipient the user recipient id
     */
    public static function deniedInvitation($user_sender, $user_recipient)
    {
        DB::delete("DELETE from invitation where user_sender = ? and user_recipient = ?", [$user_sender, $user_recipient]);
    }
    /**
     * Allows to get all contacts/friends
     * @return all contact in JSON
     */
    public static function getAllContacts($user)
    {
        return DB::select("SELECT u.name, c.connect from contacts c join users u on u.id = c.userRecipient_id where user_id = ?", [$user]);
    }
    /**
     * ALlows to set a user to connected or not
     * @param $connected the value of connect
     *  @param $user the user recipient id
     */
    public static function setConnected($connected, $user)
    {
        DB::update("UPDATE contacts set connect = ? where userRecipient_id = ?", [$connected, $user]);
    }
    /**
     * Allow to remove a contact/friend
     * @param $user_sender the user id of the sender
     *  @param $user_recipient the user recipient id
     */
    public static function removeContact($user_sender, $user_recipient)
    {
        DB::delete("DELETE from contacts where user_id = ? and userRecipient_id = ?", [$user_sender, $user_recipient]);
        DB::delete("DELETE from contacts where userRecipient_id = ? and user_id= ?", [$user_sender, $user_recipient]);
    }
    /**
     * ALlows to know if a user is connected or not
     *  @param $user the user recipient id
     * @return all the value of connect
     */
    public static function isConnected($user)
    {
        return DB::table('contacts')->where("userRecipient_id", $user)->value('connect');
    }
    /**
     * ALlows to check if a user name exist
     *  @param $user the user recipient name
     * @return boolean true if the name exist false otherwhise
     */
    public static function existName($user_recipient)
    {
        return DB::table('users')->where('name', $user_recipient)->exists();
    }
    /**
     * Allow to update the public key in the database
     * @param $publicKey the new public key 
     */
    public static function updateKey($publicKey)
    {
        DB::update("UPDATE users set publicKey = ? where name = ?", [$publicKey, Auth::User()->name]);
    }
    /**
     * Allows to get the public key from a user
     * @param $user the user name
     * @return string public key
     */
    public static function getPublicKey($user)
    {
        return DB::table('users')->where('name', $user)->value('publicKey');
    }
}
