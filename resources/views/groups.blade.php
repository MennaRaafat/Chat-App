<x-app-layout>
    <x-slot name="header">
      <div class="d-flex">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mr-5">
            {{ __('Groups') }}
        </h2>

        <button type="button" class="text-white ml-5" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Create Group
</button>
</div>
    </x-slot>

    <div class="p-12">
        <!-- Button trigger modal -->


<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Image</th>
      <th scope="col">Name</th>
      <th scope="col"></th>
    </tr>
  </thead>
  <tbody>
    @foreach($groups as $group)
    <tr>
      <td><img src="{{ asset('storage/' . $group->image) }}" style="width:50px; height:50px; border-radius:50%;"/></td>
      <td>{{$group->name}}</td>
      <td><a href="" data-bs-toggle="modal" data-bs-target="#memberModal" id="view-members" data-limit="{{$group->join_limit}}" data-id="{{$group->id}}">Memebers</a></td>
    </tr>
    @endforeach
  </tbody>
</table>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Create Group</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/save-group" method="POST" enctype="multipart/form-data" id="group-form">
            @csrf
           <label>Name</label>
           <input class="form-control" id="groupName"  name="name" type="text">

          <label>Limit</label>
          <input class="form-control" id="groupLimit" name="join_limit" type="number">

          <label>Group Image</label>
          <input class="form-control" id="groupImage" type="file" name="image">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        <button id="saveGroup" type="submit" class="btn btn-success">Create</button>
      </div>
      </form>

    </div>
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
