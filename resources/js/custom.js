$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    }
})


$(document).ready(function(){

    $.ajax({
        "url":"/get-unread",
        "type":"GET",
        success:function(response){
            console.log(response);
            for(var i =0; i<response.data.length; i++){
                console.log(response.data[i].last_read);
                console.log(response.data[i].status);
                if(response.data[i].last_read == null && response.data[i].status == 0){
                    $("#msg-"+response.data[i].sender_id).addClass('unreaded-msg');
                }
            }
        }
    })

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
            console.log(response);
            var message = response.data.message;
            var html = '<div class="current-user-chat"id="'+ response.data.id+'-chat"> <h4>'+ message +'</h4><p class="user-name">You</p></div>';
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

 var currentDate = new Date();
 var last_read = currentDate.toISOString();
    $.ajax({
        "url":"/load-chat",
        "type":"POST",
        "data":{
            sender_id:sender_id,
            receiver_id:receiver_id,
            last_read:last_read
        },
        success:function(response){
        $("#chat-container").empty();
        $("#chat-name").empty();
          let chat = response.data[1];
          var addClass = '';
          var html = '';
          var receiver_user = response.data[0][0];
          var sender_user = response.data[2][0];
          console.log(response.data[2][0])
          var userChat = '<img src="https://ui-avatars.com/api/?name=' + receiver_user.name + '&background=0D8ABC" style="width:50px; height:50px; border-radius:50%;"><h1>' + receiver_user.name + '</h1>';
          $("#chat-name").append(userChat);
          var user_name = '';
          for(var i=0; i<chat.length; i++){
            if(chat[i].sender_id == sender_id){
               addClass = 'current-user-chat';
               user_name = "You";
            }else{
                addClass = 'distant-user-chat';
                user_name = receiver_user.name;
            }
            html += ` <div class = "${addClass}"> 
               <h4>${chat[i].message}</h4>
               <p class="user-name">${user_name}</p>
            </div>
            `;
            $("#msg-"+chat[i].sender_id).removeClass('unreaded-msg');
          }
          $("#chat-container").append(html);
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
        $("#msg-"+data.chat.sender_id).addClass('unreaded-msg');
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
            var html = '<div class="current-user-chat" id="'+ response.data.id+'-chat"> <h4>'+ message +'</h4><p class="user-name">You</p></div></div>';
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
          var user_name='';
          var imageHTML = '';
          for(var i=0; i<chat.length; i++){
            if(chat[i].sender_id == sender_id){
               addClass = 'current-user-chat';
               user_name = 'You';
               imageHTML = '';
            }else{
                addClass = 'distant-user-chat';
                user_name = chat[i].users.name;}
                if (addClass === 'distant-user-chat') {
                    imageHTML = `<img src="https://ui-avatars.com/api/?name=${chat[i].users.name}&background=0D8ABC" style="width:30px; height:30px; border-radius:50%;margin-top:-130%;margin-left:-100%;">`;
                  }           
            html += ` <div class = "${addClass} d-flex"> 
            <div>
            ${imageHTML}
            </div>
            <div>
               <h4>${chat[i].message}</h4>
               <p class="user-name">${user_name}</p>
            </div>
            </div>
            `;
          }
          $("#group-chat-container").append(html);
        }
    })
}
