<head>
<link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet" id="bootstrap-css">
<script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('node_modules/socket.io/node_modules/socket.io-client/socket.io.js');?>"></script>
<script>
var socket = io.connect( 'http://'+window.location.hostname+':3000' );
var sender = 'agus';
var recipient = 'all';
$(document).ready(function(){
	ask_name();
});

function ask_name() {
    var txt;
    var person = prompt("Please enter your name:", "");
  
    sender = person;
}

socket.on( 'new_chat', function( data ) {
	if(data.recipient==sender || data.recipient=='all'){
		insertChat(data.sender,data.chatvalue,0);
	}
});

function send_chat()
{
    var date = formatAMPM(new Date());
	var chat_value = $('#chat_value').val();
	socket.emit('send_chat', { 
		 sender : sender,
		 recipient : recipient,
		 chatvalue	: chat_value,
		 created_at	: date
	});
	$('#chat_value').val('');
}

var me = {};

var you = {};

function formatAMPM(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
}            

//-- No use time. It is a javaScript effect.
function insertChat(who, text, time = 0){
    var control = "";
    var date = formatAMPM(new Date());
    
    if (who == sender){
        control = '<li tabindex="1" style="width:100%;">' +
                        '<div class="msj-rta macro">' +
                            '<div class="text text-r">' +
                                '<p><strong>'+who+'</strong>:'+text+'</p>' +
                                '<p><small>'+date+'</small></p>' +
                            '</div>' +
                        '<div class="avatar" style="padding:0px 0px 0px 10px !important"></div>' +                                
                  '</li>';
                      
    }else{
        control = '<li tabindex="1" style="width:100%">' +
                        '<div class="msj macro">' +
                            '<div class="text text-l">' +
                                '<p><strong>'+who+'</strong>:'+ text +'</p>' +
                                '<p><small>'+date+'</small></p>' +
                            '</div>' +
                        '</div>' +
                    '</li>';      
    }
    setTimeout(
        function(){                        
            $("ul").append(control);
			$("ul").animate({scrollTop: $("ul").prop("scrollHeight")}, 500);

        }, time);
    
}

function resetChat(){
    $("ul").empty();
}

$(".mytext").on("keyup", function(e){
    if (e.which == 13){
        var text = $(this).val();
        if (text !== ""){
            insertChat("me", text);              
            $(this).val('');
        }
    }
});

//-- Clear Chat
resetChat();

//-- Print Messages
/*
insertChat("me", "Hello Tom...", 0);  
insertChat("you", "Hi, Pablo", 1500);
insertChat("me", "What would you like to talk about today?", 3500);
insertChat("you", "Tell me a joke",7000);
insertChat("me", "Spaceman: Computer! Computer! Do we bring battery?!", 9500);
insertChat("you", "LOL", 12000);
var i;
for (i = 0; i < 100; i++) { 
	insertChat("me", "Hi, Pablo"+i, 1500);
}
*/
//-- NOTE: No use time on insertChat.
</script>
<!------ Include the above in your HEAD tag ---------->
<style>
.mytext{
    border:0;padding:10px;background:whitesmoke;
}
.text{
    width:75%;display:flex;flex-direction:column;
}
.text > p:first-of-type{
    width:100%;margin-top:0;margin-bottom:auto;line-height: 13px;font-size: 12px;
}
.text > p:last-of-type{
    width:100%;text-align:right;color:silver;margin-bottom:-7px;margin-top:auto;
}
.text-l{
    float:left;padding-right:10px;
}        
.text-r{
    float:right;padding-left:10px;
}
.avatar{
    display:flex;
    justify-content:center;
    align-items:center;
    width:25%;
    float:left;
    padding-right:10px;
}
.macro{
    margin-top:5px;width:85%;border-radius:5px;padding:5px;display:flex;
}
.msj-rta{
    float:right;background:whitesmoke;
}
.msj{
    float:left;background:white;
}
.frame{
    background:#e0e0de;
    height:100%;
    overflow:hidden;
    padding:0;
}
.frame > div:last-of-type{
    position:absolute;bottom:0;width:100%;display:flex;
}
body > div > div > div:nth-child(2) > span{
    background: whitesmoke;padding: 10px;font-size: 21px;border-radius: 50%;
}
body > div > div > div.msj-rta.macro{
    margin:auto;margin-left:1%;
}
ul {
    width:100%;
    list-style-type: none;
    padding:18px;
    position:absolute;
    bottom:47px;
    display:flex;
    flex-direction: column;
    top:0;
    overflow-y:scroll;
}
.msj:before{
    width: 0;
    height: 0;
    content:"";
    top:-5px;
    left:-14px;
    position:relative;
    border-style: solid;
    border-width: 0 13px 13px 0;
    border-color: transparent #ffffff transparent transparent;            
}
.msj-rta:after{
    width: 0;
    height: 0;
    content:"";
    top:-5px;
    left:14px;
    position:relative;
    border-style: solid;
    border-width: 13px 13px 0 0;
    border-color: whitesmoke transparent transparent transparent;           
}  
input:focus{
    outline: none;
}        
::-webkit-input-placeholder { /* Chrome/Opera/Safari */
    color: #d4d4d4;
}
::-moz-placeholder { /* Firefox 19+ */
    color: #d4d4d4;
}
:-ms-input-placeholder { /* IE 10+ */
    color: #d4d4d4;
}
:-moz-placeholder { /* Firefox 18- */
    color: #d4d4d4;
}  


</style>
</head>
<!DOCTYPE html>
<html>
    <body>
        <div class="col-sm-12 frame">
            <ul></ul>
            <div>
                <div class="msj-rta macro" style="width:95%">                        
                    <div class="text text-r" style="background:whitesmoke !important">
                        <input id="chat_value" class="mytext" placeholder="Type a message"  onkeydown="if (event.keyCode == 13) send_chat(1);"/>
                    </div> 
                </div>
                <div style="padding:10px;">
                    <span class="glyphicon glyphicon-share-alt" onclick="send_chat()"></span>
                </div>                
            </div>
        </div>       
    </body>
</html>

