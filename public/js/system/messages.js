/*  Author: Grapheme Group
 *  http://grapheme.ru/
 */
var showMessage = showMessage || {}
showMessage.title = 'Операция выполнена успешно';
showMessage.content = '';
showMessage.constructor = function(title,content){
	showMessage.title = title;
	showMessage.content = content;
}
showMessage.bigSuccess = function(){
	$.bigBox({title : showMessage.title,content : showMessage.content,color : "#739E73",timeout: 5000,icon : "fa fa-shield fadeInLeft animated"});
}
showMessage.bigError = function(){
	$.bigBox({title : showMessage.title,content : showMessage.content,color : "#C46A69",timeout: 5000,icon : "fa fa-warning shake animated"});
}
showMessage.bigInfo = function(){
	$.bigBox({title : showMessage.title,content : showMessage.content,color : "#3276B1",timeout: 5000,icon : "fa fa-bell swing animated"});
}
showMessage.smallSuccess = function(){
	$.smallBox({title : showMessage.title,content : showMessage.content,color : "#739E73",timeout: 5000,iconSmall : "fa fa-bell"});
}
showMessage.smallError = function(){
	$.smallBox({title : showMessage.title,content : showMessage.content,color : "#C46A69",timeout: 5000,iconSmall : "fa fa-warning shake animated"});
}
showMessage.smallInfo = function(){
	$.smallBox({title : showMessage.title,content : showMessage.content,color : "#3276B1",timeout: 5000,iconSmall : "fa fa-info-circle"});
}