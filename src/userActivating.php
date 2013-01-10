<?php

session_start();
$title = '用户激活';

// 是否注册成功
if (isset($_GET['signup']) && $_GET['signup'] == "true") {
    $signup = true;    
} elseif ( isset($_GET['activate']) && $_GET['activate'] == 'true' ) { // 用户点击链接激活
    $email = $_GET['email'];    
    $emailHash = $_GET['activeHash'];    
    $activeFlag = false;

    if ( crypt($email, $emailHash) == $emailHash ) {
        include_once 'conn.php';
        // TODO: 设置待审核状态，当注册审核开启后
        $activeQuery = "UPDATE $userTable SET user_status = 'active' WHERE user_email = '$email'";
        //$activeQuery = "UPDATE $userTable SET user_status = 'waitApproved' WHERE user_email = '$email'";
        $activeResult = mysql_query($activeQuery, $mysqlDB);

        if ($activeResult) {
            $activeFlag = true;
        }
    } else {
        echo "wrong parameter!";    
    }
} elseif ( empty($_SESSION['userName']) ) {               // 未登陆
    header("Location:userLogin.php?timeout=true");    
} elseif ( $_SESSION['userStatus'] == 'active' ) {  // 已激活 TODO: when signup=true, this is useless
    header("Location:index.php?timeout=true");    
}

include_once 'templates/user_head.php';
?>

<div class="page-header">
    <h2>用户激活</h2>
</div>


<?php if(isset($signup) && $signup){ // 注册成功?>
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>恭喜</strong> 注册成功！刚刚有一封用户激活邮件已发送到您的注册邮箱，请激活后重新登陆。
</div>
<?php } elseif( isset($activeFlag) ) { 
            // 激活成功
            if ( $activeFlag ) {    
?>
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>激活成功！</strong> 请<a href='userLogin.php'>登陆</a>后开始体验我们的产品吧：）
</div>
<?php       } else {// 激活失败 ?>
<div class="alert alert-error">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>激活失败！</strong> 参数错误。
</div>
<?php 
            }
      } else {// 用户未激活?>
<div class="alert alert-error">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>抱歉！</strong> 用户未激活，请到注册邮箱中激活后才能使用。
</div>
<?php } ?>


<?php
include_once 'templates/user_foot.php';
?>

