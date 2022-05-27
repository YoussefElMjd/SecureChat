<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;
use Pikirasa\RSA;

class ChatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Allows to set a user to connected and return the home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_sender = Message::getIdByName(Auth::user()->name)[0]->id;
        Message::setConnected(true, $id_sender);
        return view('/chat/chat');
    }

    /**
     * Allows to store a encrypted message.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $id_recipient = Message::getIdByName($request->userRecipient_id)[0]->id;
        if (Message::isConnected($id_recipient)) {
            $id_sender = Message::getIdByName(Auth::user()->name)[0]->id;
            Message::insertMessage($id_sender, $id_recipient, Crypt::encrypt($request->message), Crypt::encrypt($request->copyMessage));
        }
    }

    /**
     * Allows you to get the whole conversation with a person
     *  @param $userRecipient_id the user recipient id
     * @return JSON the conversation
     */
    public function getMessages($userRecipient_id)
    {
        $id_recipient = Message::getIdByName($userRecipient_id)[0]->id;
        if (Message::isConnected($id_recipient)) {
            $id_sender = Message::getIdByName(Auth::user()->name)[0]->id;
            $result = Message::getAllMessages($id_sender, $id_recipient, Auth::user()->name, $userRecipient_id);
            foreach ($result as $value) {
                $value->message = Crypt::decrypt($value->message);
                $value->copy = Crypt::decrypt($value->copy);
            }
            return json_encode($result);
        }
    }
    /**
     * Allows to return all member in the database
     * @return JSON member in JSON
     */
    public function getAllMembers()
    {
        return json_encode(Message::getAllMembers());
    }

    /**
     * Allows to send a invitation to a user
     *  @param $userRecipient_id the user recipient id
     */
    public function addInvitation($userRecipient_id)
    {
        if (Message::existName($userRecipient_id) && $userRecipient_id != Auth::user()->name) {
            $id_sender = Message::getIdByName(Auth::user()->name)[0]->id;
            $id_recipient = Message::getIdByName($userRecipient_id)[0]->id;
            Message::addInvitation($id_sender, $id_recipient);
        } else {
            return ['status' => 'No one by that name!'];
        }
    }

    /**
     * Allows you to receive all invitations received
     * @return all the view
     */
    public function getInvitation()
    {
        return view('/chat/invitation', ['allInvitation' => Message::getInvitation()]);
    }

    /**
     * ALlows to accept a invvitation
     * @param $userRecipient_id the user recipient id
     */
    public function acceptInvitation($userRecipient_id)
    {
        $id_sender = Message::getIdByName(Auth::user()->name)[0]->id;
        $id_recipient = Message::getIdByName($userRecipient_id)[0]->id;
        Message::acceptInvitation($id_sender, $id_recipient);
    }
    /**
     * ALlows to denied a invvitation
     * @param $userRecipient_id the user recipient id
     */
    public function deniedInvitation($userRecipient_id)
    {
        $id_sender = Message::getIdByName(Auth::user()->name)[0]->id;
        $id_recipient = Message::getIdByName($userRecipient_id)[0]->id;
        Message::deniedInvitation($id_sender, $id_recipient);
    }
    /**
     * Allows to get all contacts/friends
     * @return JSON contact in JSON
     */
    public static function getAllContacts()
    {
        $id_sender = Message::getIdByName(Auth::user()->name)[0]->id;
        return json_encode(Message::getAllContacts($id_sender));
    }

    /**
     * Allows to get all contacts/friends
     * @return view view of contact
     */
    public function getContactFriends()
    {
        $id_sender = Message::getIdByName(Auth::user()->name)[0]->id;
        return view("/chat/contact", ['allContacts' => Message::getAllContacts($id_sender)]);
    }

    /**
     * Allow to remove a contact/friend
     * @param $userRecipient_id the user recipient id
     */
    public function removeContact($userRecipient_id)
    {
        $id_sender = Message::getIdByName(Auth::user()->name)[0]->id;
        $id_recipient = Message::getIdByName($userRecipient_id)[0]->id;
        Message::removeContact($id_sender, $id_recipient);
    }

    /**
     * Allow to update the public key in the database
     * @param $publicKey the new public key 
     */
    public function updateKey($publicKey)
    {
        Message::updateKey($publicKey);
    }
    /**
     * Allows to get the public key from a user
     * @param $user the user name
     * @return string public key
     */
    public function getPublicKeyFromUser($user)
    {
        return Message::getPublicKey($user);
    }

    public function test()
    {
        // dump(session('test'));
        // $pathToPublicKey = 'C:\\Users\\DarkW\\Desktop\\secg4-project-54314-56172\\secureChat\\storage\\app\\public\\pbkey.pem';
        // $pathToPrivateKey = 'C:\\Users\\DarkW\\Desktop\\secg4-project-54314-56172\\secureChat\\storage\\app\\public\\pvkey.pem';
        dump(session('private_key'));
        dump(session('public_key'));

        // $data = 'my secret data';
        // $publicKey =  PublicKey::fromString($publicKey,"hello");
        // $encryptedData = $publicKey->encrypt($data); // returns something unreadable
        // dump($encryptedData);
        // $privateKey = PrivateKey::fromString($privateKey,"hello");
        // $decryptedData = $privateKey->decrypt($encryptedData); // returns 'my secret data'
        // dump($decryptedData);
        // $publicKey = null;
        // $privateKey = null;
        // $rsa = new RSA($publicKey, $privateKey, 'see');
        // $rsa->create();
        // dump($rsa);
        // $data = 'abc123';
        // $encrypted = $rsa->encrypt($data);
        // var_dump($encrypted);
        // $decrypted = $rsa->decrypt($encrypted);
        // var_dump($decrypted); // 'abc123'
        // dump(Auth::user());
    }
}
