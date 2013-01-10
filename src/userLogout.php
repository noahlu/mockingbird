<?php
session_start();
setcookie('PHPSESSID','',time()-3600, '/');
unset($_SESSION['userName']) ;
session_destroy();
header("Location:userlogin.php");     
?>
