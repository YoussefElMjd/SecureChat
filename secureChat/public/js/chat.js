function refresh() {
    const name_recipient = $("#recipient").val();
    if (name_recipient != "") {
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
                                        <div>${data[i]['message'].replace(/</g, "&lt;").replace(/>/g, "&gt;")}</div>
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
                                        <div>${data[i]['message'].replace(/</g, "&lt;").replace(/>/g, "&gt;")}</div>
                                    </div>
                                </div>
                            </div>`)
                    }
                }
            }
        });
    }
}

function refreshAllFriend() {
    $("#allActiveFriend").empty();
    $.ajax({
        type: 'GET',
        url: '/chat/contacts',
        dataType: 'json',
        success: function(data, success) {
            for (let i = 0; i < data.length; i++) {
                $("#allActiveFriend").append(`<button id="${data[i]['name'].replace(/</g, "&lt;").replace(/>/g, "&gt;")}" class="flex flex-row items-center hover:bg-gray-100 rounded-xl p-2">
                        <div class="flex items-center justify-center h-8 w-8 ${Boolean(data[i]['connect']) ? "bg-green-300" : "bg-red-300"} rounded-full">
                        ${String(data[i]['name']).charAt(0)}
                        </div>
                        <div class="ml-2 text-sm font-semibold">${data[i]['name'].replace(/</g, "&lt;").replace(/>/g, "&gt;")}</div>
                    </button>`)
                $("#allActiveFriend button").click(function(e) {
                    $("#recipient").val(e.target.id);
                    $.ajax({
                        type: 'GET',
                        dataType: "json",
                        url: `/chat/${e.target.id}/messages`,
                        success: function(data, status, xhr) {
                            $("#conversation").html('');
                            for (let i = 0; i < data.length; i++) {
                                if (data[i]['name'] != e.target.id) {
                                    $("#conversation").append(`<div class="col-start-1 col-end-8 p-3 rounded-lg">
                                <div class="flex flex-row items-center">
                                    <div id="userRecipient" class="flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500 flex-shrink-0">
                                    ${String(data[i]['name']).charAt(0)}
                                    </div>
                                    <div class="relative ml-3 text-sm bg-white py-2 px-4 shadow rounded-xl">
                                        <div>${data[i]['message'].replace(/</g, "&lt;").replace(/>/g, "&gt;")}</div>
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
                                        <div>${data[i]['message'].replace(/</g, "&lt;").replace(/>/g, "&gt;")}</div>
                                    </div>
                                </div>
                            </div>`)
                                }
                            }
                        }
                    });
                });
            }

        }
    })
}
refresh();
refreshAllFriend();
setInterval(refresh, 5000);
setInterval(refreshAllFriend, 10000);
$("#newMessage").on('submit', function(event) {
    $("#message").val($("#message").val().replace(/</g, "&lt;").replace(/>/g, "&gt;"));
    event.preventDefault();
    $.ajax({
        type: 'post',
        url: '/chat/store',
        data: $("#newMessage").serialize(),
        success: function(data, status, xhr) {
            $("#message").val("");
            refresh();
        }
    });
});