<?php

include_once '../class_mailHandler.php';

$mailHandler = new MailHandler();
date_default_timezone_set('Asia/Chongqing');

$userName = "陆华";
$to = "hua.lu@alipay.com";
//$to = "noahseo@gmail.com";
$subject = "BlogDev Project 邮件测试";
$content = "恭喜你，$userName 注册成功！<br/>". date("Y/M/D");

$result = $mailHandler->send($to, $subject, $content);
if ($result) {
    echo "Mail Sent!<br/>". 
         "to : $to";    
} else {
    echo "Mail Fail!";    
}

?>
