<?php
function sendMsg($obj){
	file_put_contents('./t', '发送短信成功',FILE_APPEND);
}
function sendEmail($obj){
	file_put_contents('./t', '发送邮件成功',FILE_APPEND);
}		