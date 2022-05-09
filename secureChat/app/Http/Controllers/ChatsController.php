<?php

namespace App\Http\Controllers;

use App\Events\ChatsMessageEvent;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;


class ChatsController extends Controller
{
    //Add the below functions
    public function __construct()
    {
        $this->middleware('auth');
    }
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('/chat/chat');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id_sender = Message::getIdByName(Auth::user()->name)[0]->id;
        $id_recipient = Message::getIdByName($request->userRecipient_id)[0]->id;     
        Message::insertMessage($id_sender,$id_recipient,$request->message);
        // return redirect()->back()->with('status', 'Message correctement ajouté !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getMessages($userRecipient_id){
        $id_sender = Message::getIdByName(Auth::user()->name)[0]->id;
        $id_recipient = Message::getIdByName($userRecipient_id)[0]->id; 
        $result = Message::getAllMessages($id_sender,$id_recipient,Auth::user()->name,$userRecipient_id);
        return json_encode($result);
        // echo "<script>console.log($result)</script>";
    }
    // public function fetchMessages()
    // {
    //     return Message::with('user')->get();
    // }

    // public function sendMessage(Request $request)
    // {
    //     $user = Auth::user();
    //     $message = $user->messages()->create([
    //         'message' => $request->input('message')
    //     ]);
    //     return ['status' => 'Message Sent!'];
    // }
}
