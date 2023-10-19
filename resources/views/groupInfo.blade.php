<x-app-layout>
    <x-slot name="header">
      <div class="d-flex">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mr-5">
            {{ __('Group Info') }}
        </h2>
</div>
    </x-slot>

<div class="p-12 text-white">

  <a href="{{ url()->previous() }}">Go Back</a>

  <div class="card text-center w-75" style="margin:auto;">
    <div class="card-header p-3">
       <img src="{{ asset('storage/' . $groupInfo->image) }}" style="width:100px; height:100px; border-radius:50%;margin:auto;"/>  </div>
       <div class="card-body p-3">
       <h5 class="card-title">{{$groupInfo->name}}</h5>
         @if(auth()->user()->name == $creator->name)
         <p class="card-text">Group Admin: You</p>
         <a href="" data-bs-toggle="modal" data-bs-target="#memberModal" id="view-members" data-limit="{{$groupInfo->join_limit}}" data-id="{{$groupInfo->id}}">Add Memebers</a>
         @else
          <p class="card-text">Group Admin: {{$creator->name}}</p>
         @endif
          <p class="card-text">Group Limit:{{$groupInfo->join_limit}}</p>
    </div>
    <div class="card-footer text-body-secondary p-3">
      <p class="card-text">Group Members</p>
        @if(isset($groupMembers) && !empty($groupMembers))
        @foreach($groupMembers as $groupMember)
       <h3>{{$groupMember->users->name}}</h3>
       @endforeach
       @endif  
    </div>
</div>

<div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="memberModalLabel">Members</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addMembers">
         <input type="hidden" name="id" id ="group-id" >
         <input type="hidden" name="join_limit" id ="group-limit" >
        
         <table class="table table-striped" id="membersTable">
            <thead>
               <tr>
                 <th scope="col">Select</th>
                 <th scope="col">Name</th>
               </tr>
            </thead>
            <tbody id="membersTableBody">

            </tbody>
         </table>
     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button id="addMember" type="submit" class="btn btn-success">Add</button>
      </div>
      </form>

    </div>
  </div>
</div>

    </div>

</x-app-layout>
