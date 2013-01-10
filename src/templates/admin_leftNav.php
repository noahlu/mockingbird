<div class="span2 nav-left">
    <div class="tabbable tabs-left " data-spy="affix" data-offset-top="60">
        <ul class="nav nav-tabs">
            <li <?php if ($adminMenu == "postEditnew") {echo "class=\"active\"";}?>><a href="postEdit.php?postType=new"><i class="icon-file"></i> 新增文件</a></li>
            <li <?php if ($adminMenu == "index" || $adminMenu == "postEditmodify") {echo "class=\"active\"";}?>><a href="index.php"><i class="icon-home"></i> 文件管理</a></li>
            <li <?php if ($adminMenu == "users") {echo "class=\"active\"";}?>><a href="users.php"><i class=" icon-user"></i> 用户管理</a></li>
            <li <?php if ($adminMenu == "taxonomy") {echo "class=\"active\"";}?>><a href="taxonomy.php"><i class="icon-folder-open"></i> 分类管理</a></li>
            <li <?php if ($adminMenu == "settings") {echo "class=\"active\"";}?>><a href="settings.php"><i class="icon-wrench"></i> 网站设置</a></li>
        </ul>
    </div>
</div>
