<?php
$title = "用户管理";
$adminMenu = "users";

include_once "templates/admin_head.php";

include_once 'templates/admin_leftNav.php';
?>

<div class="span10 content">
    <div class="page-header ">
        <h2>用户管理</h2>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>用户名</th>
                <th>用户邮箱</th>
                <th>用户组</th>
                <th>用户状态</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $result = mysql_query("SELECT * FROM $userTable WHERE id != ''", $mysqlDB);
        while ($dataRow = mysql_fetch_array($result)) {
        ?>
            <tr>
            	<td><i class="icon-user"></i> <?php echo $dataRow['user_name'];?></td>
            	<td><i class="icon-envelope"></i> <?php echo $dataRow['user_email'];?></td>
            	<td><i class="icon-star"></i> <?php echo $dataRow['user_group'];?></td>
            	<td><?php echo $dataRow['user_status'];?></td>
            	<td>
                    <div class="btn-group">
                        <a href="#" class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#"><i class="icon-remove"></i>删除</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php
        }
        ?>    

        </tbody>
    </table>

</div>    


<?php 
include_once "templates/admin_foot.php";
?>
