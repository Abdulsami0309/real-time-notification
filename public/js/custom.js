

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
var audio = new Audio("message-alert-tune.mp3");

$(document).ready(function () {
    $(".single-user").on("click", function () {
        receiver_id = $(this).data("id");
        var username = $("#user-" + receiver_id + "-name").text();
        $("#set-username").html("Chat With " + "<b>" + username + "</b>");
        loadOldChats(receiver_id);
    });

    $(document).on("click", ".delete-message", function () {
        var messageBody = $(this).data("message-body");
        var messageId = $(this).data("message-id");
        $("#message-id").val(messageId);
        $("#message-area").html(
            "Do You Want To Delete Your Chat ? <br> <b>'" +
                messageBody +
                "'</b>"
        );
        $("#message-confirm-popup").modal("show");
    });


    $(document).on("click", ".update-message", function () {
        
        var messageId = $(this).data("message-id");
        var messageBody = $("#message-"+messageId).text();
        $("#message-id").val(messageId);
        $("#update-chat-message").val(messageBody);
        $("#update-message-confirm-popup").modal("show");
    });

    $("#send-message-form").on("submit", function (e) {
        e.preventDefault();
        var message = $("#chat-input").val();

        $.ajax({
            type: "POST",
            url: "/save-chat",
            data: {
                sender_id,
                receiver_id,
                message,
            },
            success: function (response) {
                // console.log(response);
                if (response.success) {
                    var message = response.data.message;
                    var html = `<div id="chat-${response.data.id}" class="current-user-message">
                    <div  class="current-user-info-box">
                        <div class="content">
                            <p id="message-${response.data.id}" class="message-content" >${message}</p>
                            <p class="message-date">${response.data.created_at}</p>
                        </div>
                        <div class="img">
                            <img width="50"
                        src="http://127.0.0.1:8000/dummy-user.png" alt="User Image">
                        </div>
                        <div>
                        <i  style="cursor:pointer" data-message-id="${response.data.id}" class="fa fa-trash text-danger delete-message" id="chat-${response.data.id}" data-message-body="${response.data.message}" > </i>
                        <i  style="cursor:pointer" data-message-id="${response.data.id}" class="fa fa-edit text-success update-message" data-message-body="${response.data.message}" > </i>
                        </div>
                    </div>

                </div>`;

                    autoChatScroll();
                    $("#chat-messages").append(html);
                }
            },
        });
    });

    $("#message-delete-form").on("submit", function (e) {
        e.preventDefault();
        var message_id = $("#message-id").val();
        $.ajax({
            type: "POST",
            url: "/delete-current-user-message",
            data: {
                message_id,
            },
            success: function (res) {
                if (res.success) {
                    $("#chat-" + message_id).remove();
                    $("#message-confirm-popup").modal("hide");
                    toastr.success(res.data);
                } else {
                    console.log(res.data);
                }
            },
        });
    });


    $("#update-message-form").on("submit", function (e) {
        e.preventDefault();
        var message_id = $("#message-id").val();
        var update_chat_message = $("#update-chat-message").val();
        $.ajax({
            type: "POST",
            url: "/update-current-user-message",
            data: {
                message_id,
                update_chat_message,
            },
            success: function (res) {
                if (res.success) {
                    // $("#chat-" + message_id).text();
                    $("#update-message-confirm-popup").modal("hide");
                    toastr.success(res.data);
                } else {
                    console.log(res.data);
                }
            },
        });
    });
});

Echo.join("status-update")
    .here((users) => {
        for (let x = 0; x < users.length; x++) {
            $("#user-" + users[x]["id"] + "-status").removeClass(
                "offline-status"
            );
            $("#user-" + users[x]["id"] + "-status").addClass("online-status");
            // $("#user-" + users[x]["id"] + "-status").text("Online");
        }
    })
    .joining((user) => {
        $("#user-" + user.id + "-status").removeClass("offline-status");
        $("#user-" + user.id + "-status").addClass("online-status");
        // $("#user-" + user.id + "-status").text("Online");
    })
    .leaving((user) => {
        $("#user-" + user.id + "-status").addClass("offline-status");
        $("#user-" + user.id + "-status").removeClass("online-status");
        // $("#user-" + user.id + "-status").text("Offline");
    })
    .listen("UserStatusEvent", (e) => {
        // console.log("hhh" + e);
    });

Echo.private("get-message").listen(".getMessage", (data) => {
    if (data.messageData.receiver_id == sender_id) {
        var html = `<div id="chat-${data.messageData.id}" class="recepient-user-message">
        <div class="current-user-info-box">
            <div class="img">
                <img width="50"
            src="http://127.0.0.1:8000/dummy-user.png" alt="User Image">
            </div>
            <div class="content">
                <p class="message-content" >${data.messageData.message}</p>
                <p class="">${data.messageData.created_at}</p>
            </div>
            
        </div>
    </div>`;
        $("#chat-messages").append(html);
        autoChatScroll();
        toastr.success("New Message Notification");
        audio.play();
    }
});

Echo.private("delete-single-message").listen("MessageDeleteEvent", (data) => {
    $("#chat-" + data.data).remove();
});

Echo.private('update-message').listen('UpdateMessageEvent',(data)=>{
$("#message-"+data.data.id).text(data.data.message)

});

function loadOldChats(receiver_id) {
    $.ajax({
        type: "POST",
        url: "/load-receiver-old-chats",
        data: {
            receiver_id,
        },
        success: function (response) {
            // $("#chat-messages").html('');
            $("#chat-messages").html("");
            $("#chat-with-user-area").show();
            $("#chat-messages").append(response.data);
        },
    });
}

function autoChatScroll() {
    $("#chat-messages").animate(
        {
            scrollTop: $("#chat-messages")[0].scrollHeight,
        },
        "slow"
    );
}
