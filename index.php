<?php
// LITE CHAT MODULE v0.1 (beta) - Sample Code Base
// (c) All rights reserved 05/29/13
// Jon Cable - me@joncable.com

// CHAT UI INTERFACE

// Open a session if one does not exist
if(!isset($_SESSION)){
	session_start();
}
// Set the defaut timezone to CST
date_default_timezone_set("America/Chicago");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="libs/flowplayer/flowplayer-3.2.10.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--- browser-scaling | scale the browsers view on init --->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--- call-css | include the minified Twitter bootstrap style sheets --->
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
<!--- custom-css | styles not included in the bootstrap --->
<style>
.chat-console {  
    height: 400px !important;
	width: 100%;
	padding: 2px;
    overflow-y: scroll;
	overflow-x: hidden;
}
.chat-sidebar {  
    height: 502px !important;
	width: 300px;
    overflow-y: hidden;
	overflow-x: hidden;
}
.modal-alert{
	width: 240px;
	float: right;	
}
.msg-alert{
	float: right;	
}
</style>
<title>Liteweight Chat Module</title>
</head>
<body>
<!--- main-container | open the main container --->
<div class="container-fluid">

	<div class="row-fluid">
   		<!--- sidebar-widgets ---->
    	<div class="well span3 chat-sidebar">
			<!--- video-player ---->
           	<h5>Now Playing: <a href="http://www.everydayjunglist.com" target="_blank">EverydayJunglist TV</a></h5>
           	<div class="mywidget">
           		<!--- video-player | embed flowplayer ---->
    			<a href="http://everydayjunglist.com:8081/stream.flv" style="display:block;width:250px;height:175px" id="player"></a>
				<!--- video-player | init live stream ---->
				<script type="text/javascript">
                    flowplayer("player", "libs/flowplayer/flowplayer-3.2.11.swf", {wmode:"opaque"});
                </script>           
           	</div>
           <!--- online-users | dynamically loaded ---->
           <h5>Users Online</h5>
           <div class="online"></div> 
        </div>
        <!--- chat-widgets ---->
        <div class="well span9">
        	<!--- chat-input ---->
            <div class="input-append">
                <input class="input-xlarge" type="text" placeholder="What do you think of these tunes?" name="input-msg">
                <div class="btn-group">
                   <button class="btn" id="send-chat">Send</button>
                   <button class="btn" id="send-chat" onclick="logout();">Logout</button>
                </div>
            </div>
            <!--- chat-alert ---->
            <div class="alert msg-alert" id="msg-alert">Enter a message to join in!</div>
            <!--- chat-console ---->
        	<div class="chat-console" id="chat-console">
				<blockquote class="chat-msgs" id="chat-msgs">
                <!--- chat-messages | loaded dynamically ---->
                </blockquote>
            </div>                                 
        </div>         
	</div>   
     
    <!-- modal-container | hidden by default -->
    <div id="myModal" class="modal hide fade" role="dialog">
        <div class="modal-header">
            <h3 id="myModalLabel">Do you want to join chat while you watch?</h3>
        </div>
        <!--- username-input ---->
        <div class="modal-body">
        	<input class="input-large" type="text" placeholder="Enter a username to join..." name="input-username">
       		<div class="alert modal-alert" id="modal-alert">must be unique &amp; at least 5 chars!</div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" id="close-chat">Just Let Me Watch</button>
            <button class="btn btn-primary" id="validate-chat">Start Chatting</button>
        </div>
    </div>
	<!--- footer-container --->
	<footer>
    	<p><center>Lite Chat Module v0.1 (beta) &copy; Jon Cable 2013</center></p>
    </footer>
      
<!--- main-container | close the main container --->
</div>
    
<!--- call-js | include the minified core jQuery and Twitter bootstrap libs --->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/litechat.js"></script>
<?php
// If we do NOT have a username prompt for one
if(!isset($_SESSION['mychat']['username'])){
	print '<script> $(document).ready(function() { $("#myModal").modal(); }); </script>';
}
?>
</body>
</html>