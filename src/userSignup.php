<?php
session_start();
$title = '注册';

// sign表示是否是表单提交
if (isset($_POST['sign']) && $_POST['sign'] == "true"){

    //echo "form submit";
    $sign = true;

    // 标识参数是否正确
    $pass = true;
    
    // 以下是表单校验
    // TODO: 完善校验规则
    if (!empty($_POST['username'])) {
        $userName = $_POST['username'];
    } else {
        echo "user name required! <br/>";    
        $pass = false;
    }

    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        echo "email required! <br/>";    
        $pass = false;
    }

    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
    } else {
        echo "password required! <br/>";    
        $pass = false;
    }

    // 如果表单数据校验都通过了，数据入库
    if ($pass) {
        include_once "conn.php";
        date_default_timezone_set('Asia/Chongqing');
        $date = date('Y-m-d H:i:s');
        $password = crypt($password);

        $userQuery = "INSERT INTO $userTable (id, user_name, user_pass, user_email, date_created) VALUES('', '$userName', '$password', '$email', '$date')";
        mysql_query($userQuery, $mysqlDB);

        // 发送激活邮件
        $sitehost = ((!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http"). "://". $_SERVER ['HTTP_HOST'];
        $activeLink = "$sitehost/admin/userActivating.php?activate=true&email=$email&activeHash=". crypt($email);
        $content = "亲爱的 $userName ：<br/>". 
                   "您的注册已经完成，请点击下面的链接激活：<br/>".
                   "<a href=\"$activeLink\" target=\"_blank\">$activeLink</a><br/>".
                   "$date <br/>";
        //echo $content. "<br/>";

        include_once 'class_mailHandler.php';
        $mailHandler = new MailHandler();
        $result = $mailHandler->send($email, '用户激活邮件', $content);
        //echo "send result:".$result;
        //exit;

        header("Location:userActivating.php?signup=true");
    }
}

include_once 'templates/user_head.php';
?>

<div class="page-header">
    <h2>用户注册</h2>
</div>    

<form class="form-horizontal" action="userSignup.php" method="post">
    <input name="sign" value="true" type="hidden"/>

    <div class="control-group">
        <label class="control-label" for="inputUserName">User Name</label>
        <div class="controls">
            <input type="text" name="username" <?php if(isset($userName)){echo "value=\"$userName\"";} ?> id="inputUserName" placeholder="User Name">
            <span class="help-inline" ><em id="userNameExplain">*required</em></span>
            <span class="help-block"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputEmail">Email</label>
        <div class="controls">
            <input type="text" name="email" id="inputEmail" placeholder="Email" <?php if(isset($email)){echo "value=\"$email\"";} ?>>
            <span class="help-inline" ><em id="userEmailExplain">*required</em></span>
            <span class="help-block"></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPassword">Password</label>
        <div class="controls">
            <input type="password" name="password" id="inputPassword" placeholder="Password">
            <span class="help-inline "><em>*required</em></span>
            <span class="help-block"></span>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn">Sign Up</button>
        </div>
    </div>
</form>

<script type="text/javascript">
seajs.use('jquery', function($){
    $("document").ready(function(){

        var Validator;


        // validator
        Validator = function(options){
            this.triggerMethod = options.triggerMethod || "blur";
            this.

                
        }


        // 用户名和邮箱校验
        $("#postCategory").add($("#postFileName")).on('change',function(event){
            $("#postFileName").val($("#postFileName").val().trim());

            if ($("#postFileName").val()) {
                $.ajax({
                    url: 'ajax_request.php',    
                    dataType: 'json',
                    data: {
                        "action": "file_name_check",
                        "fileName": $("#postFileName").val(),
                        "fileCategory": $("#postCategory").val(),
                    },
                    success: function(rsp){
                        if (rsp.stat == "ok") {
                            if (!rsp.error){
                                $("#fileName-explain").removeClass('label-important').addClass('label-success');    
                                $("#fileName-explain").html(rsp.msg).show();    
                                $("#fileName-explain").html(rsp.msg).fadeOut(3000);    
                            } else {
                                $("#fileName-explain").removeClass('label-success').addClass('label-important');    
                                $("#fileName-explain").html(rsp.msg).show();    
                            }
                        }
                    }
                })
                // ajax end
            }
        });
       
    })
        // end dom ready
})
// end seajs.use

</script>

<?php
include_once 'templates/user_foot.php';
?>


