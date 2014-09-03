$(document).ready(function($){
    var ulogintoken = getCookie("ulogintoken");
    if (ulogintoken != '')
        uloginauth(ulogintoken);
});

function uloginauth(token) {
    if(typeof ulogintoken == "undefined")
        setCookie("ulogintoken", token, "Mon, 01-Jan-2018 00:00:00 GMT", "/");
        
    $.getJSON("//ulogin.ru/token.php?host=" + encodeURIComponent(location.toString()) + "&token=" + token + "&callback=?", function(data){
        data = $.parseJSON(data.toString());
        if(!data.error){
            console.log(data);
            //alert("Привет, "+data.first_name+" "+data.last_name+"!");
            //$("select[name=city]").val(data.city);
            $(".welcome_msg").html("Добро пожаловать, "+data.first_name+"!");
            $("#uLogin1").hide();
            $(".upload_block").show();

            $("input[name=first_name]").val(data.first_name);
            $("input[name=last_name]").val(data.last_name);
            $("input[name=profile]").val(data.profile);
            $("input[name=city]").val(data.city);
            $("input[name=sex]").val(data.sex);
            $("input[name=photo_big]").val(data.photo_big);
            $("input[name=bdate]").val(data.bdate);

        } else {
            // Token expired
            console.log(data);
        }
    });
}

function setCookie (name, value, expires, path, domain, secure) {
    document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}

function getCookie(name) {
	var cookie = " " + document.cookie;
	var search = " " + name + "=";
	var setStr = null;
	var offset = 0;
	var end = 0;
	if (cookie.length > 0) {
		offset = cookie.indexOf(search);
		if (offset != -1) {
			offset += search.length;
			end = cookie.indexOf(";", offset)
			if (end == -1) {
				end = cookie.length;
			}
			setStr = unescape(cookie.substring(offset, end));
		}
	}
	return(setStr);
}