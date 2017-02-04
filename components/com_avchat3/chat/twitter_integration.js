function shareOnTwitter(URL,message){
	newwindow=window.open('https://twitter.com/intent/tweet?text='+message+" "+URL,'Share on Twitter','height=425,width=640');
	if (window.focus) {newwindow.focus()}
}

function loginWithTwitter(){
		
	var cb = new Codebird;
	
	if (!twitterKey || twitterKey == '') {
		alert('Error! Twitter Consumer Key was not found!'); return false;
	}
	if (!twitterSecret || twitterSecret == '') {
		alert('Error! Twitter Consumer Secret was not found!'); return false;
	}
	if (!chatPathTwitter || chatPathTwitter == '') {
		alert('Error! Chat path was not found!'); return false;
	}
	
	var consumerKey = twitterKey;
	var consumerSecret = twitterSecret;
	var pathToAVChat = chatPathTwitter;
	
	window.open(pathToAVChat + '/ajax-loading.gif','Twitter Login','height=678,width=624');
	
	cb.setConsumerKey(consumerKey, consumerSecret);
	
	setCookie("consumerKey",consumerKey,7);
	setCookie("consumerSecret",consumerSecret,7);
	
	//get a request token
	cb.__call(
		"oauth_requestToken",
		{oauth_callback: pathToAVChat + "/?twitter=true"},
		function (reply) {
			// store it
			setCookie("requestToken",reply.oauth_token,7);
			setCookie("requestTokenSecret",reply.oauth_token_secret,7);
			
			cb.setToken(getCookie("requestToken"), getCookie("requestTokenSecret"));
			
			// get the authorize screen URL
			cb.__call(
				"oauth_authorize",
				{},
				function (auth_url) {
					twitterWindow = window.open(auth_url,'Twitter Login','height=678,width=624');
					
				}
			);
			
		}
	);
}



window.onload = function onTwitterCallBack(){
	
	var current_url = location.toString();
		
	if(current_url.indexOf("oauth_verifier") != -1){
	
		var cb          = new Codebird;
	
		var consumerKey = getCookie("consumerKey");
		var consumerSecret = getCookie("consumerSecret");
		
		var theMatch    = current_url.match(/\?(.+)$/) + "";
		var query       = theMatch.split("&");
		var parameters  = {};
		var parameter;
		alert(consumerKey + '-' + consumerSecret);
		cb.setConsumerKey(consumerKey, consumerSecret);
	
		for (var i = 0; i < query.length; i++) {
			parameter = query[i].split("=");
			if (parameter.length === 1) {
				parameter[1] = "";
			}
			parameters[decodeURIComponent(parameter[0])] = decodeURIComponent(parameter[1]);
			//alert(parameters[decodeURIComponent(parameter[0])]);	
		}
	
						
	// check if oauth_verifier is set
		if (typeof parameters.oauth_verifier !== "undefined") {
			// assign stored request token parameters to codebird here
			// ...
			//alert("THE TOKENS " + getCookie("requestToken") + " " + getCookie("requestTokenSecret"));
			cb.setToken(getCookie("requestToken"), getCookie("requestTokenSecret"));
			
			cb.__call(
				"oauth_accessToken",
				{
					oauth_verifier: parameters.oauth_verifier
				},
				function (reply) {
					cb.setToken(reply.oauth_token, reply.oauth_token_secret);
					alert("REPLY " + reply.request + " " +reply.error + " " + reply.httpstatus + " " +reply.oauth_token + " "+ reply.user_id + " " + reply.screen_name);
					/*for(var propertyName in reply){
						alert("REPLY FINAL " + propertyName);
					}*/
					//alert("Window " + window.opener);
					//return false;
					cb.__call(
						"users_show",
						{screen_name: reply.screen_name},
						function (user){
							
							//alert(user.profile_image_url + " " +reply.user_id + " " + reply.screen_name);
							window.opener.setTwitterValues(reply.user_id,reply.screen_name,user.profile_image_url);
							window.close();
						}
					)
				}
			);
		}
	}
}


//this function is valid only in the parent window
function setTwitterValues(userId,screenName,profileImageURL){
	//alert(userId + " " + screenName + " " + profileImageURL);
			
	var flashObj = document.getElementById('index_embed');
	
	//alert(flashObj);
	
	flashObj.afterTwitterLogin(userId,screenName,profileImageURL);
}


// cookie functions
function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function getCookie(c_name)
{
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + c_name + "=");
	
	if (c_start == -1){
		c_start = c_value.indexOf(c_name + "=");
	}
	if (c_start == -1){
	  c_value = null;
	}
	else{
		  c_start = c_value.indexOf("=", c_start) + 1;
		  var c_end = c_value.indexOf(";", c_start);
		  if (c_end == -1){
			c_end = c_value.length;
		  }
		c_value = unescape(c_value.substring(c_start,c_end));
		}
	return c_value;
}