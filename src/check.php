<?php

// session timeout measured in seconds, default 10 hours.
$sessionLefeTime = 86400;
session_start();

$current_url = ((!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http"). "://". $_SERVER ['HTTP_HOST']. $_SERVER['PHP_SELF']. (empty($_SERVER['QUERY_STRING']) ? "" : "?".$_SERVER['QUERY_STRING']);

// 未登录
if (empty($_SESSION['userName'])) {
    header("Location:userlogin.php?timeout=true&goto=". $current_url);     
}

// 登陆超时
if (!empty($_SESSION['timeout'])) {
    $sessionTime = time() - $_SESSION['timeout'];
    if ($sessionTime > $sessionLefeTime) {
        header("Location:userlogin.php?timeout=true&goto=". $current_url);     
    }
}

// 用户未激活
if (empty($_SESSION['userStatus']) || $_SESSION['userStatus'] != 'active') {
    header("Location:userActivating.php");     
}

$_SESSION['timeout'] = time();

?>
