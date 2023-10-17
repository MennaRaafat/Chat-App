$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    }
})


$(document).ready(function(){
    $(".user-list").click(function(){
        var userId = $(this).attr('data-id');
        receiver_id = userId;
        $(".start-head").hide();
        $(".chat-section").show();
        loadChat();
    })
    $("#chat-form").submit(function(e){
        e.preventDefault();
       var message = $("#message").val();
       $.ajax({
        "url":"/save-chat",
        "type":"POST",
        "data":{
            sender_id:sender_id,
            receiver_id:receiver_id,
            message:message
        },
        success:function(response){
            var message = response.data.message;
            var html = '<div class="current-user-chat"id="'+ response.data.id+'-chat"> <h5>'+ message +'</h5></div>';
            $("#message").val("");
            $("#chat-container").append(html);
        }
       })
    })
    $("#group-form").submit(function(e){
        e.preventDefault();
        var groupName = $("#groupName").val();
        var groupImage = $("#groupImage")[0].files[0];
        var groupLimit = $("#groupLimit").val();

        var formData = new FormData(); 
        formData.append('name', groupName);
        formData.append('image', groupImage);
        formData.append('join_limit', groupLimit);
        $.ajax({
            "url":"/save-group",
            "type":"POST",
            "data": formData,
            "contentType": false,
            "processData": false,          
            success:function(res){
              
            }
        });

    });

})

$(document).ready(function(){
    $("#view-members").click(function(){

        var id = $(this).attr('data-id');
        var limit = $(this).attr('data-limit');

        $("#group-id").val(id);
        $("#group-limit").val(limit);

        $.ajax({
            "url":"/view-members",
            "type":"GET",
            "data": {id:id},
            success:function(res){
                if(res.success){
                    var html="";
                    var users = res.data;
                    for(var i=0; i<users.length; i++){
                        html+=`<tr><td><input type="checkbox" name="members[]" value="${users[i]['id']}"></td><td>`+users[i]['name']+`</td></tr>`;
                    }
                    $("#membersTableBody").html(html);
                }
            }
        });

        $("#addMembers").submit(function(e){
            e.preventDefault();
            var formData = $(this).serialize();
    
            $.ajax({
                url:"/add-members",
                type:"POST",
                data: formData,         
                success:function(res){
                  if(res.success){
                    $("#memberModal").modal('hide');
                    // $("#addMembers")[0].reset();
                    // bootbox.alert(res.msg)
                    alert(res.msg);
                  }
                }
            })
        })
    })
})

function loadChat(){
    $.ajax({
        "url":"/load-chat",
        "type":"POST",
        "data":{
            sender_id:sender_id,
            receiver_id:receiver_id,
        },
        success:function(response){
        $("#chat-container").empty();
          let chat = response.data;
          var addClass = '';
          var html = '';
          for(var i=0; i<chat.length; i++){
            if(chat[i].sender_id == sender_id){
               addClass = 'current-user-chat';
            }else{
                addClass = 'distant-user-chat';
            }
            html += ` <div class = "${addClass}"> 
               <h5>${chat[i].message}</h5>
            </div>
            `;
            $("#chat-container").append(html);
          }
        }
    })
}

Echo.join('status-update')
    .here((users)=>{
        for(let i=0; i<users.length; i++){
            if(sender_id != users[i]['id']){
                $('#'+users[i]['id']+'-status').removeClass("offline");
                $('#'+users[i]['id']+'-status').addClass("online");
                $('#'+users[i]['id']+'-status').text("Online");
            }
        }
    })
    .joining((user)=>{
        $('#'+user.id+'-status').removeClass("offline");
        $('#'+user.id+'-status').addClass("online");
        $('#'+user.id+'-status').text("Online");
    })
    .leaving((user)=>{
        $('#'+user.id+'-status').addClass("offline");
        $('#'+user.id+'-status').removeClass("online");
        $('#'+user.id+'-status').text("Offline");
    })
    .listen('UserStatusEvent' , (e)=>{

    })

   Echo.private('chat-message')
       .listen('MessageEvent' , (data)=>{
        if(sender_id == data.chat.receiver_id && receiver_id == data.chat.sender_id){
            var html = '<div class="distant-user-chat"> <h5>'+ data.chat.message +'</h5></div>';
            $("#message").val("");
            $("#chat-container").append(html); 
        }
      })

$(document).ready(function(){
    $(".group-list").click(function(){
        var groupId = $(this).attr('data-id');
        global_group_id = groupId;
        $(".group-start-head").hide();
        $(".group-chat-section").show();
        loadGroupChat();
    })

    $("#group-chat-form").submit(function(e){
        e.preventDefault();
       var message = $("#group-message").val();
       $.ajax({
        "url":"/group-chat",
        "type":"POST",
        "data":{
            sender_id:sender_id,
            group_id:global_group_id,
            message:message
        },
        success:function(response){
            var message = response.data.message;
            var html = '<div class="current-user-chat" id="'+ response.data.id+'-chat"> <h5>'+ message +'</h5></div>';
            $("#group-message").val("");
            $("#group-chat-container").append(html);
        }
       })
    })
})

Echo.private('group-message')
.listen('GroupMessageEvent' , (data)=>{
    console.log(data);
 if(sender_id != data.chat.sender_id && global_group_id == data.chat.group_id){
     var html = '<div class="distant-user-chat"> <h5>'+ data.chat.message +'</h5></div>';
     $("#group-message").val("");
     $("#group-chat-container").append(html); 
 }
})


function loadGroupChat(){
    $.ajax({
        "url":"/load-group-chat",
        "type":"POST",
        "data":{
            sender_id:sender_id,
            group_id:global_group_id,
        },
        success:function(response){
        $("#group-chat-container").empty();
          let chat = response.data;
          var addClass = '';
          var html = '';
          for(var i=0; i<chat.length; i++){
            if(chat[i].sender_id == sender_id){
               addClass = 'current-user-chat';
            }else{
                addClass = 'distant-user-chat';
            }
            html += ` <div class = "${addClass}"> 
               <h5>${chat[i].message}</h5>
            </div>
            `;
          }
          $("#group-chat-container").append(html);

        }
    })
}
