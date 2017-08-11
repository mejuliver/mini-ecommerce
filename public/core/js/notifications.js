var socket = io('http://localhost:3000');
$(document).ready(function() {
    $('#notification-dp').on('hide.bs.dropdown', function () {
      //remove the notification
      $.ajax({
        url:site_link+'/notification/cleared',
        type:'post',
        data:{id:username},
        dataType:'json',
        success:function(e){
            if(e.success){
                $('#notification-badge span').remove();
            }
        }
      });
    });
    socket.on('notifications', function(data) {
        //remove the excess notifications if notifications count is more than 20
        if ($('#notifications-holder li').length > 20) {
            $('.itsme:gt(20)').remove();
        }
        //check the event if message/notification
        if (data.event === "notification") {
            if (checkUser(data.to) && data.sender !== username) {
            	//.map(function(value) { return String(value); })
                //render the notification to the notification container
                $("#notifications-holder li.empty-notification").remove();
                //set up the badge counter
                if (!$("#notification-container").is(":visible")) {
                    if (!$("#notification-badge span.badge").length) {
                        $("#notification-badge").append('<span class="badge">0</span>');
                    }
                    $("#notification-badge span.badge").text(parseInt($("#notification-badge span.badge").text()) + 1);
                } else {
                    $("#notification-badge span.badge").remove();
                }

                $("#notifications-holder").prepend('<li>' + data.contents + '</li>');
                //if incoming event is message
                if(data.type==='message'){
                	global_spinner_conf = false;
                	$("#reload-messages").trigger("submit");
                }else if(data.type==='item request'){
                	global_spinner_conf = false;
                	$("#reload-request-table").trigger("submit");
                }else if(data.type==='inventory'){
                	global_spinner_conf = false;
                	$("#reload-inventory-table").trigger("submit");
                }


            }
            
        } //end if data event is notification
 
    });
    $("#notification-badge").click(function(e) {
        if(!$("#notification-container").is(':visible')&&!$("#notifications-holder li.empty-notification").length){
        	//ajax clearing the notifications
        	$.ajax({
        		url : $("body").attr("data-link")+'/notifications/clear',
        		type : 'post',
        		dataType : 'json',
        		success: function(e){
        			if(e.success){
        				$("#notification-badge span.badge").remove();
        			}
        		}
        	});
        }
        e.preventDefault();
    });
    $("#send-message").click(function(e) {
        notification('<strong>SEND MESSAGE</strong>', $("#send-message-container").html());
        e.preventDefault();
    });
});

function send_message(e) {}

//check permission
function checkUser(e){
	if($.inArray(username, e)!==-1){
		return true;
	}else{
		return false;
	}
}