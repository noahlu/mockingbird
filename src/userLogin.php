<?php
session_start();
$title = '登陆';

// 是否session超时
if (isset($_GET['timeout']) && $_GET['timeout'] == "true") {
    $timeout = true;    
}

// 是否登陆提交表单
if (isset($_POST['sign']) && $_POST['sign'] == "true") {

    $pass = true;
    
    // 表单校验
    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
        
    } else {
        echo "no email!";    
        $pass = false;
    }    

    if (!empty($_POST['password'])) {
        $password = $_POST['password'];

        if ($pass) {
            include_once 'conn.php';    

            $userQuery = "SELECT * FROM $userTable WHERE user_email = '$email'";
            $dataRow = mysql_fetch_array(mysql_query($userQuery, $mysqlDB));

            // 校验密码
            if (!empty($dataRow)) {

                if(crypt($password, $dataRow['user_pass']) != $dataRow['user_pass']) {
                    $pass = false;
                    echo 'username or password is incorrect!';
                } else {
                    $_SESSION['userName'] = $dataRow['user_name'];
                    $_SESSION['userStatus'] = $dataRow['user_status'];
                    $_SESSION['userEmail'] = $dataRow['user_email'];

                    if ($dataRow['user_status'] != 'active') {
                        $pass = false;
                        echo 'user not activated!';
                        header("Location:userActivating.php");
                        exit;
                    }    
                
                }
                    $pass = true;
            } else {
                $pass = false;
            }
        }
        
    } else {
        echo "no password!";    
    } 

    if ($pass) {
        empty($_POST['goto']) ? header("Location:index.php") : header("Location:".$_POST['goto']);
    }   
}

include_once 'templates/user_head.php';
?>

    <div class="header"></div>

<div class="page-header">
    <h2>登陆</h2>
</div>


<div id="loginInfo">

    <?php if(isset($timeout) && $timeout){ ?>
    <div class="alert alert-error">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <strong>登陆超时</strong> 请重新登陆。
    </div>
    <?php } ?>
</div>    

<form class="form-horizontal" action="userLogin.php" method="post">
    <input name="sign" value="true" type="hidden"/>
    <input name="goto" value="<?php if (isset($_GET['goto'])) {echo $_GET['goto'];}?>" type="hidden"/>

    <div class="control-group">
        <label class="control-label" for="inputEmail">Email</label>
        <div class="controls">
            <input type="text" name="email" id="inputEmail" placeholder="Email" <?php if(isset($email)){echo "value=\"$email\"";} ?>>
            <span class="help-inline "><em></em></span>
            <span class="help-block"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPassword">Password</label>
        <div class="controls">
            <input type="password" name="password" id="inputPassword" placeholder="Password">
            <span class="help-inline "><em></em></span>
            <span class="help-block"></span>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn">Sign In</button>
        </div>
    </div>
</form>

<script type="text/javascript">
seajs.use(['jquery'],function($){
    setTimeout(function(){
        $('#loginInfo').fadeOut(1000);    
    }, 5000);
    
});
</script>

<?php
include_once 'templates/user_foot.php';
?>

