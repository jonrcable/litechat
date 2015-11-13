<?php
// LITE CHAT MODULE v0.1 (beta) - Sample Code Base
// (c) All rights reserved 05/29/13
// Jon Cable - me@joncable.com

// CHAT CLASS FUNCTIONS

class LiteChat{
	
	// DB CHECK
	// check the database and create a flat file is one does not exit
	static function dbcheck(){
		// open OR create a flat file database if one does not exist
		$db = new SQLite3('../db/litechat');
		// make sure that the log table is active, if not create one
		$db->exec("CREATE TABLE IF NOT EXISTS 'log' (
		'cID' INTEGER PRIMARY KEY AUTOINCREMENT,
		'username' VARCHAR(255) ,
		'comment' VARCHAR(255) ,
		'date' DATETIME 
		)");
		// return the active database
		return $db;
	}
	
	// INIT CHAT
	// init the chat by grabing all value from the log that have not been pruned
	static function init(){
		// check to make sure db exists
		$db = self::dbcheck();
		$msgs = array();
		// find the all the latest entries to start that chat window
		$result = $db->query("SELECT * FROM log ORDER BY `cID` DESC");
		// put the latest into an array
		while($row = $result->fetchArray()){
			$msgs[] = $row;
		}
		// store the latest cID autoinc in the session, if there is one
		if(count($msgs) > 0){
			$_SESSION['mychat']['latest'] = $msgs[0]['cID'];
		}
		// return all comments as any array
		return $msgs;
	}
	
	// UPDATE CHAT
	// init the chat by grabing all value from the log that have not been pruned
	static function update(){
		// check to make sure db exists
		$db = self::dbcheck();
		$msgs = array();
		// the session latest must be set to continue if not do not update
		if(isset($_SESSION['mychat']['latest'])){
			// find the all the latest entries from the last update point
			$result = $db->query("SELECT * FROM log WHERE `cID` > ".$_SESSION['mychat']['latest']." ORDER BY `cID` DESC");
			// put the latest into an array
			while($row = $result->fetchArray()){
				$msgs[] = $row;
			}
			// if there is at least one new msg store the latest autoinc
			if(count($msgs) > 0){
			// store the latest cID autoinc in the session 
				$_SESSION['mychat']['latest'] = $msgs[0]['cID'];
			}
			// return all comments as any array
		}
		return $msgs;
	}	
	
	// CHECK USERNAME
	// check to make sure that the username selected is unique
	static function checkuser($username){
		// regex to remove any nonword nonspace chars
		$username = preg_replace('/[^ \w]+/', '', $username);
		// get the length of the username
		$length = strlen($username);
		// only if the length is greater then (5) can we accept it
		if($length >= 5){
			// check for unique username
			// get all users
			$users = self::listusers();
			// search to make sure the username is unique
			if(!in_array($username, $users)){
				// if we succeed then we need to update the chat log
				$msg = $username." has joined chat!";
				// check to make sure db exists
				$db = self::dbcheck();
				// format EPOC timestamp in a nice formated way
				$now = date("Y-m-d H:i:s"); 
				// insert the log message to the database								
				self::send($msg, $username);
				$response = array('status'=>'success','msg'=>'you have now joined the chat!');
				// store the username into our user session
				$_SESSION['mychat']['username'] = $username;
			// username is NOT unique
			}else{
				$response = array('status'=>'fail','msg'=>'Opps, must be a unqiue name!');
			}
		// length of the username is invalid
		}else{
			$response = array('status'=>'fail','msg'=>'Opps, must be at least 5 chars!');
		}
		return $response;	
	}		
	
	// SEND MESSAGE
	// send a chat message to the log
	static function send($msg, $username){
		// check to make sure db exists
		$db = self::dbcheck();
		// format EPOC timestamp in a nice formated way
		$now = date("Y-m-d H:i:s");
		// if the msg and username are valid continue to post the msg 
		if($msg != '' && $username != ''){
			// insert the log message to the database
			$db->exec("INSERT INTO 'log' (`username`,`comment`,`date`) VALUES ('".$username."','".$msg."','".$now."')");
			$response = array('status'=>'success','msg'=>'Thanks for sharing!');
		// if the username is blank we must not be logged in, fail
		}elseif($username == ''){
			$response = array('status'=>'fail','msg'=>'Opps, logout to join!');
		// appears that the msg sent was blank, notify user
		}else{
			$response = array('status'=>'success','msg'=>'Opps, was your message blank?');	
		}
		return $response;
	}
	
	// LIST ALL USERS
	// list all distinict usernames currently in chat
	static function listusers(){
		// check to make sure db exists
		$db = self::dbcheck();
		$users = array();
		// run a distinct username query against the log table
		$result = $db->query("SELECT DISTINCT(`username`) FROM `log`");
		// put the latest into an array
		while($row = $result->fetchArray()){
			$users[] = $row['username'];
		}
		// return the list of users back to the array
		return $users;
	}
	
	// PRUNE CHAT DB
	// prune everything from the table except for the last 100 rows
	static function prunechat(){
		// check to make sure db exists
		$db = self::dbcheck();
		// run prune query to clean up anything older then 100 rows
		$result = $db->exec("DELETE FROM `log` WHERE `cID` < (SELECT `cID` FROM `log` ORDER BY `cID` DESC LIMIT 100,1)");
		// always return null
		return;
	}
	
	// CLEANUP USER
	// prune off the comments made by the user
	static function prunemsgs(){
		// check to make sure db exists
		$db = self::dbcheck();
		// run prune query to clean up anything older then 100 rows
		$result = $db->exec("DELETE FROM `log` WHERE `username` = '".$_SESSION['mychat']['username']."'");
		// always return null
		return;
	}	
	
	// HANDLE SESSION
	// check if a seesion exists if not open one
	static function session(){
		// Open a session if one does not exist
		if(!isset($_SESSION)){
			session_start();
		}
	}
	
	// KILL SESSION
	// check if a seesion exists kill it
	static function logout(){
		// remove any of our messages from this user not already in cache
		self::prunemsgs();
		// kill our session array
		if(isset($_SESSION['mychat'])){
			unset($_SESSION['mychat']);
		}
		// help keep the table lean, clean old rows
		self::prunechat();
	}	
}
?>