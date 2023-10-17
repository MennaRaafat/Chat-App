<x-app-layout>
    <x-slot name="header" class="dark:bg-gray-800">
        <h2 class="dark:bg-gray-800 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-12">
      @if(count($users)>0)
      <div class="grid grid-cols-12"> 
        <div class="col-span-3">
            <ul class="list-group">
              @foreach($users as $user)
                <li class="bg-gray-400 px-7 list-group-item user-list m-3" data-id="{{$user->id}}">
                    <img src="https://img.freepik.com/premium-vector/man-avatar-profile-picture-vector-illustration_268834-538.jpg">
                    {{$user->name}}
                    <b><sub id="{{$user->id}}-status" class="offline">offline</sub></b>
                </li>
              @endforeach
            </ul>
        </div>

        <div class="col-span-9">
            <h5 class="start-head">Click To Start Chat</h5>
            <div class="chat-section">
                <div id="chat-container">
                 
                </div>
                <form action="" id="chat-form">
                    <input lass="w-75 mt-3" type="text" name="message" placeholder="Enter Your Message" id="message">
                    <input type="submit" value="Send Message" class="btn btn-primary px-5">
                </form >
            </div>
        </div>
</div>
      @endif

    </div>
</x-app-layout>
