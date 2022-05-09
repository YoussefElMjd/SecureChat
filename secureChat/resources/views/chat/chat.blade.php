<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@1.9.6/dist/tailwind.min.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Chat</title>
</head>

<body>
    <div id='app'>
        <div>
            <a class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 rounded-xl text-white px-4 py-1 flex-shrink-0" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
        <div class="flex h-screen antialiased text-gray-800">
            <div class="flex flex-row h-full w-full overflow-x-hidden">
                <div class="flex flex-col py-8 pl-6 pr-2 w-64 bg-white flex-shrink-0">
                    <div class="flex flex-row items-center justify-center h-12 w-full">
                        <div class="flex items-center justify-center rounded-2xl text-indigo-700 bg-indigo-100 h-10 w-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                        </div>
                        <div class="ml-2 font-bold text-2xl">SecureChat</div>
                    </div>
                    <div class="flex flex-col items-center bg-indigo-100 border border-gray-200 mt-4 w-full py-6 px-4 rounded-lg">
                        <div class="h-20 w-20 rounded-full border overflow-hidden">
                            <img src="https://w7.pngwing.com/pngs/81/570/png-transparent-profile-logo-computer-icons-user-user-blue-heroes-logo-thumbnail.png" alt="Avatar" class="h-full w-full" />
                        </div>
                        <div id="userName" class="text-sm font-semibold mt-2">{{ Auth::user()->name }}</div>
                        <div class="flex flex-row items-center mt-3">
                            <div class="flex flex-col justify-center h-4 w-8 bg-indigo-500 rounded-full">
                                <div class="h-3 w-3 bg-white rounded-full self-end mr-1"></div>
                            </div>
                            <div class="leading-none ml-1 text-xs">Connected</div>
                        </div>
                    </div>

                    <!-- ACTIVE FRIEND -->
                    <div class="flex flex-col mt-8">
                        <div class="flex flex-row items-center justify-between text-xs">
                            <span class="font-bold">Active Friend</span>
                            <span id="nbActiveFriend" class="flex items-center justify-center bg-gray-300 h-4 w-4 rounded-full">id = nbActiveFriend4</span>
                        </div>
                        <div id="allActiveFriend" class="flex flex-col space-y-1 mt-4 -mx-2 h-48 overflow-y-auto">
                            id = allActiveFriend
                            <button id="DarkWiide" class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2">
                                <div class="flex items-center justify-center h-8 w-8 bg-indigo-200 rounded-full">
                                    D
                                </div>
                                <div class="ml-2 text-sm font-semibold">DarkWiide</div>
                            </button>
                            <button id="Marta Curtis" class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2">
                                <div class="flex items-center justify-center h-8 w-8 bg-gray-200 rounded-full">
                                    M
                                </div>
                                <div class="ml-2 text-sm font-semibold">Marta Curtis</div>
                                <div class="flex items-center justify-center ml-auto text-xs text-white bg-red-500 h-4 w-4 rounded leading-none">
                                    2
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- la conversastion -->
                <div class="flex flex-col flex-auto h-full p-6">
                    <div class="flex flex-col flex-auto flex-shrink-0 rounded-2xl bg-gray-100 h-full p-4">
                        <div class="flex flex-col h-full overflow-x-auto mb-4">
                            <div class="flex flex-col h-full">
                                <div id="conversation" class="grid grid-cols-12 gap-y-2">

                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row items-center h-16 rounded-xl bg-white w-full px-4">
                            <div>
                                <div id="userMe" class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                    Me
                                </div>
                            </div>
                            <form id="newMessage">
                                @csrf
                                <div class="flex-grow ml-4">
                                    <div class="relative w-full">
                                        <input id="message" name="message" type="text" class="flex w-full border rounded-xl focus:outline-none focus:border-indigo-300 pl-4 h-10" />
                                        <input id="recipient" name="userRecipient_id" type="hidden">
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <button id="send" class="flex items-center justify-center bg-indigo-500 hover:bg-indigo-600 rounded-xl text-white px-4 py-1 flex-shrink-0 ">
                                        <span>Send</span>
                                        <span class="ml-2">
                                            <svg class="w-4 h-4 transform rotate-45 -mt-px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function refresh() {
            const name_recipient = $("#recipient").val();
            console.log(name_recipient);
            $.ajax({
                type: 'GET',
                dataType: "json",
                url: `/chat/${name_recipient}/messages`,
                success: function(data, status, xhr) {
                    $("#conversation").empty();
                    for (let i = 0; i < data.length; i++) {
                        if (data[i]['name'] != name_recipient) {
                            $("#conversation").append(`<div class="col-start-1 col-end-8 p-3 rounded-lg">
                                        <div class="flex flex-row items-center">
                                            <div id="userRecipient" class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                            ${String(data[i]['name']).charAt(0)}
                                            </div>
                                            <div class="relative ml-3 text-sm bg-white py-2 px-4 shadow rounded-xl">
                                                <div>${data[i]['message']}</div>
                                            </div>
                                        </div>
                                    </div>`)
                        } else {
                            $("#conversation").append(`<div class="col-start-6 col-end-13 p-3 rounded-lg">
                                        <div class="flex items-center justify-start flex-row-reverse">
                                            <div id="userMe" class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                            ${String(data[i]['name']).charAt(0)}
                                            </div>
                                            <div class="relative mr-3 text-sm bg-indigo-100 py-2 px-4 shadow rounded-xl">
                                                <div>${data[i]['message']}</div>
                                            </div>
                                        </div>
                                    </div>`)
                        }
                    }
                }
            });
        }
        $("#allActiveFriend button").click(function(e) {
            $("#recipient").val(e.target.id);
            $.ajax({
                type: 'GET',
                dataType: "json",
                url: `/chat/${e.target.id}/messages`,
                success: function(data, status, xhr) {
                    console.log(data);
                    $("#conversation").html('');
                    for (let i = 0; i < data.length; i++) {
                        if (data[i]['name'] != e.target.id) {
                            $("#conversation").append(`<div class="col-start-1 col-end-8 p-3 rounded-lg">
                                        <div class="flex flex-row items-center">
                                            <div id="userRecipient" class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                            ${String(data[i]['name']).charAt(0)}
                                            </div>
                                            <div class="relative ml-3 text-sm bg-white py-2 px-4 shadow rounded-xl">
                                                <div>${data[i]['message']}</div>
                                            </div>
                                        </div>
                                    </div>`)
                        } else {
                            $("#conversation").append(`<div class="col-start-6 col-end-13 p-3 rounded-lg">
                                        <div class="flex items-center justify-start flex-row-reverse">
                                            <div id="userMe" class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                            ${String(data[i]['name']).charAt(0)}
                                            </div>
                                            <div class="relative mr-3 text-sm bg-indigo-100 py-2 px-4 shadow rounded-xl">
                                                <div>${data[i]['message']}</div>
                                            </div>
                                        </div>
                                    </div>`)
                        }
                    }
                }
            });
        });

        setInterval(refresh, 3000);
        $("#newMessage").on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                type: 'post',
                url: "/chat/store",
                data: $("#newMessage").serialize(),
                success: function(data, status, xhr) {
                    refresh();
                }
            });
        });
    </script>
</body>

</html>