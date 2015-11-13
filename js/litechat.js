// STARTUP ONLOAD
// --------------
// once the browser is done run this once
$(document).ready(function() {
	// init the chat in the backgroud
	initChat();
	// init the enter key to send a message
	$("input[name=input-msg]").keypress(function(e){
        if(e.which == 13){
			//Trigger send message event
            $('#send-chat').click();
        }
    });
	// run the update once every 2s to append comments
	window.setInterval(function() {
	 	update();
	}, 2000);
});

// MAIN FUNCTIONS
// --------------
// list the current users that still have posts
function currentUsers(){
	$('div.online').load('html/chat.php',{"action": "users"}, function(data) {
		// reserved for a defined success action
	});
}
// start the chat console witht he latest posts
function initChat(){
	$.post('html/chat.php',{"action": "init"}, function(data) {
		// prepend the newest entries
		$('blockquote.chat-msgs').html(data);
		// update the currently active users
		currentUsers();
	});		
}
// update only the latest posts
function update(){
	$.post('html/chat.php',{"action": "update"}, function(data) {
		// append the newest entries
		$('blockquote.chat-msgs').prepend(data);
		// update the currently active users
		currentUsers();
	});		
}
// kill session and remove any old posts 
function logout(){
	// kill the current username
	$.post('html/chat.php',{"action": "logout"}, function(data) {
		// reload the init chat pane to blank
		$("input[name=input-username]").val('');
		// open the modal window for reauth
		$("#myModal").modal();
		// reload the chat pane after pruning
		initChat();
	});
}

// MAIN ACTIONS
// ------------
// validate the user against the latest users in chat
$(document).on("click","button#validate-chat", function () {
	// get the value of the username
	var myusername = $("input[name=input-username]").val();
	// lets try to validate our new chat user
	$.post('html/chat.php',{ "action": "join", "myusername": myusername}, function(data) {
		if(data.status === 'success'){
			// success close modal and prepare chat interface
			$('#myModal').modal('hide');
			// init the chat console fresh on successful login
			initChat();
		}else{
			// failed show a warning message
			$("div.modal-alert").html(data.msg);
		}
	});
});
// send a new message to the database
$(document).on("click","button#send-chat", function () {
	// get the value of the message
	var mymsg = $("input[name=input-msg]").val();
	// lets try to send a new message
	$.post('html/chat.php',{ "action": "send", "mymsg": mymsg}, function(data) {
		// success
		if(data.status === 'success'){
			// update the msg alert with our success
			$("div.msg-alert").html(data.msg);
			// clear out the value of the input
			$("input[name=input-msg]").val('');
			// post new update to the chat console
			update();
		// fail something happened, no session?
		}else{ 
			// open modal to start session
			$("#myModal").modal();
			// notify user something went bad
			$("div.modal-alert").html('Opps, do you want to join chat?');
		}
	});
});
// close chat and do nothing
$(document).on("click","button#close-chat", function () {
	// reserved for a callback
});