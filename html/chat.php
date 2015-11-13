<?php
// LITE CHAT MODULE v0.1 (beta) - Sample Code Base
// (c) All rights reserved 05/29/13
// Jon Cable - me@joncable.com

// CHAT CALLBACK EVENTS

// Include the chat class::functions
include('../functions/chat.php');

// In order to use this API we first need a valid action
if(isset($_POST["action"])){
	// call our action and open the session
	$action = $_POST["action"];
	LiteChat::session();
	// look for valid callback actions
	switch($action){
		// new user request to join chat
		case 'join':
			// check the username is unique and min char length
			$return = LiteChat::checkuser($_POST['myusername']);
			// return the result as a json encoded response (success/fail)
			header('Content-Type: application/json');
			$return = json_encode($return);
			break;
		// send a new message to the database			
		case 'send':
			// get the username from the session
			$username = $_SESSION['mychat']['username'];
			// send the new msg to the database
			$return = LiteChat::send($_POST['mymsg'], $username);
			// return the result as a json encoded response (success/fail)
			header('Content-Type: application/json');
			$return = json_encode($return);
			break;
		// init the chat windows with all comments
		case 'init':
			// init the chat and gather all the active rowschat
			$msgs = LiteChat::init();
			// return the rows as a plain html response
			$return = '';
			foreach($msgs as $msg){
				$return .= "<p>".$msg['comment']." </p><small>".$msg['username']."</small>";	
			}
			// if the latest autoinc is set them store it for later
			if(isset($msgs[0]['cID'])){
				$_SESSION['mychat']['latest'] = $msgs[0]['cID'];
			}
			break;		
		// update the chat window with the latest
		case 'update':
			// update the chat console with the latest
			$msgs = LiteChat::update();
			// return the rows as a plain html response
			$return = '';
			foreach($msgs as $msg){
				$return .= "<p>".$msg['comment']." </p><small>".$msg['username']."</small>";	
			}			
			break;			
		// list all users currently active
		case 'users':
			// find all distinct users that are active
			$users = LiteChat::listusers();
			// return the rows as a plain html response
			$return = "<ul>";
			foreach($users as $user){
				$return .= "<li>".$user."</li>";
			}
			$return .= "<ul>";
			break;
		// kill the session and prune any old data								
		case 'logout':
			// kill, die, dead
			$return = LiteChat::logout();
			break;
		// no valid action is found default to error msg
		default:
			// we should NOT be here, FAIL
			$return = array('status'=>'fail','msg'=>'Action Not Found');
			header('Content-Type: application/json');
			$return = json_encode($return);	
			break;
	}

// no action was supplied default to error sg	
}else{
	$return = array('status'=>'fail','msg'=>'No Action Specified');
	$return = json_encode($return);		
}
// Return the server response from the user authentication
echo $return;
?>