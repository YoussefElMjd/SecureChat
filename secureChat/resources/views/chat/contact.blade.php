<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@0.3.0/dist/tailwind.min.css" rel="stylesheet">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <title>Contacts</title>
</head>

<body>
  <div class="w-full max-w-screen-xl mx-auto p-6">
    <div class="relative rounded overflow-hidden border border-grey-light mb-8 bg-white">
      <div class="border-b border-grey-light p-4 bg-grey-lighter py-8">
        <div class="bg-white mx-auto max-w-sm shadow-lg rounded-lg overflow-hidden">
          <div class="sm:flex sm:items-center px-2 py-4">
            <div class="flex-grow">
              <h3 class="font-normal px-2 py-3 leading-tight">Contacts</h3>
              <div class="flex flex-row items-center mt-3 mx-auto">
                <input id="userName" type="text" placeholder="Search members" class="my-2 w-full text-sm bg-grey-light text-grey-darkest rounded h-10 p-3 focus:outline-none">
                <button id="invite" type="submit" class="bg-blue hover:bg-blue-dark text-white my-2 h-10 p-3 rounded">
                  Invite
                </button>
              </div>
              <div id="allInvitation" class="w-full">
                @foreach($allContacts as $contact)
                <div class="flex cursor-pointer my-1 hover:bg-blue-lightest rounded">
                  <div class="w-4/5 h-10 py-3 px-1">
                    <p class="hover:text-blue-dark">{{$contact->name}}</p>
                  </div>
                  <button id="Remove" value="{{$contact->name}}" class="w-1/5 h-10 text-right p-3 hover:bg-red-lightest text-sm text-red-dark">
                    Remove
                  </button>
                </div>
                @endforeach

              </div>
            </div>
          </div>
          <div class="sm:flex bg-grey-light sm:items-center px-2 py-4">
            <div class="flex-grow text-right">
              <a href="/home" class="bg-blue hover:bg-blue-dark text-white py-2 px-4 rounded">
                Return to Chat
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      /**
       * Allows to remove a friend
       */
      $("#Remove").click(function(e) {
        $.ajax({
          type: 'GET',
          url: `/chat/contactFriends/${e.target.value}/remove`,
        })
      })
      /**
       * Allows to invite a user
       */
      $("#invite").click(function() {
        event.preventDefault();
        if ($("#userName").val() != "") {
          $.ajax({
            type: 'GET',
            url: `/chat/${$("#userName").val()}/invite`,
            success: function(data, status) {
              if (data['status']) {
                alert(data['status']);
              }
            }
          });
        }
      });
    })
  </script>
</body>

</html>