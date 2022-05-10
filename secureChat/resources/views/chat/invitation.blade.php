<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@0.3.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>Invitations</title>
</head>
<!-- <div class="w-8 h-10 text-center py-1">
                  <p class="text-3xl p-0 text-grey-dark">&bull;</p>
                </div> -->
<body>
<div class="w-full max-w-screen-xl mx-auto p-6">
  <div class="relative rounded overflow-hidden border border-grey-light mb-8 bg-white">
    <div class="border-b border-grey-light p-4 bg-grey-lighter py-8">
      <div class="bg-white mx-auto max-w-sm shadow-lg rounded-lg overflow-hidden">
        <div class="sm:flex sm:items-center px-2 py-4">
          <div class="flex-grow">
            <h3 class="font-normal px-2 py-3 leading-tight">Invitations</h3>
            <div id="allInvitation" class="w-full">
                @foreach($allInvitation as $invite)
                <div class="flex cursor-pointer my-1 hover:bg-blue-lightest rounded">
                <div class="w-4/5 h-10 py-3 px-1">
                  <p class="hover:text-blue-dark">{{$invite->name}}</p>
                </div>
                <button id="Accept" value="{{$invite->name}}" class="w-1/5 h-10 text-right p-3 hover:bg-green-lightest text-sm text-green-dark">
                 Accept
                </button>
                <button id="Denied" value="{{$invite->name}}" class="w-1/5 h-10 text-right p-3 hover:bg-red-lightest text-sm text-red-dark">
                Denied
                </button>
              </div>
                @endforeach

            </div>
          </div>
        </div>
        <div class="sm:flex bg-grey-light sm:items-center px-2 py-4">
          <div class="flex-grow text-right">
            <a href="/home"class="bg-blue hover:bg-blue-dark text-white py-2 px-4 rounded">
              Return to Chat
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function(){
        $("#Accept").click(function(e){
            $.ajax({
                type: 'GET',
                url: `/chat/invitation/${e.target.value}/accept`,
            })
        })

        $("#Denied").click(function(e){
            $.ajax({
                type: 'GET',
                url: `/chat/invitation/${e.target.value}/denied`,
            })
        })
    })
</script> 
</body>

</html>