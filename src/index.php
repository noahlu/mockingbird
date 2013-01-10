<?php
$title = "控制台";
$adminMenu = "index";

include_once "templates/admin_head.php";
include_once "conn.php";
include_once "class_htmlHandler.php";
$htmlHandler = new HtmlHandler();

if (isset($_GET['action'])) {
    if ($_GET['action'] == "del") {
        $postId = $_GET['id'];
        $postRows = mysql_fetch_array(mysql_query("SELECT * FROM $artTable WHERE id = '$postId'", $mysqlDB));

        if ($postRows && mysql_query("DELETE FROM $artTable WHERE id = '$postId'", $mysqlDB)) {
            $htmlHandler->removeHTML($postRows['post_category'], $postRows['post_fileName'], $postRows['id']);
            $resultMsg = "文件删除成功！";
        } else {
            $errorMsg = "文件删除失败！参数错误。";    
        }
    }
}

include_once 'templates/admin_leftNav.php';
?>

<div class="span10 content">
    <div class="page-header">
        <h2>控制台首页</h2>
    </div>

    <a href="htmlGenerate.php?generateAll=true" target="_blank" class="btn btn-warning pull-right">生成全站HTML</a>
    <h3>文件管理</h3>

    <div id="top-msg-area">
        <?php
            if (isset($errorMsg)) {
        ?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $errorMsg;?>
        </div>
        <?php
            } elseif (isset($resultMsg)) {
        ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <?php echo $resultMsg;?>
        </div>
        <?php
            }
        ?>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>修改</th>
                <th>HTML文件</th>
                <th>修改时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            
            // 分页
            $numRow = mysql_fetch_array(mysql_query("SELECT count(*) FROM $artTable", $mysqlDB));
            $numTotal = $numRow[0];
            
            // 每页显示信息条数
            $pagesize = 5;

            // 总页数
            $pages = ceil( $numTotal / $pagesize );

            // 当前页数
            $page = isset($_GET['page']) ? intval( $_GET['page'] ) : 1;

            // 当前页id起点
            $offset = ( $page - 1 ) * $pagesize;

            $artQuery = "SELECT * FROM $artTable ORDER BY post_date DESC LIMIT $offset, $pagesize";
            $result = mysql_query($artQuery, $mysqlDB);
            while ($dataRow = mysql_fetch_array($result)) {
                $htmlLoc = $htmlHandler->getHtmlLoc($dataRow['post_category'], $dataRow['post_fileName'], $dataRow['id']);
                $previewUrl = $htmlHandler->getPreviewUrl($dataRow['id'], "",false , false);
        ?>
            <tr>
                <td><a href="postEdit.php?id=<?php echo $dataRow['id'];?>" >
                    <i class="icon-pencil"></i> 
                    <?php echo $dataRow['post_title'];?></a>
                </td>
                <td><a href="<?php echo $htmlLoc;?>" target="_blank">
                    <i class="icon-share"></i> 
                    <?php echo $htmlLoc;?></a>
                </td>
                <td><?php echo $dataRow['post_date'];?></td>
                <td>
                <div class="btn-group">
                    <a href="#" class="btn btn-mini btn-primary dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="index.php?action=del&id=<?php echo $dataRow['id'];?>"><i class="icon-remove"></i>删除</a></li>
                        <li><a href="<?php echo $htmlHandler->preview."?id=".$dataRow['id'];?>" target="_blank"><i class="icon-eye-open"></i>PHP预览</a></li>
                        <li><a href="htmlGenerate.php?id=<?php echo $dataRow['id'];?>" target="_blank"><i class=" icon-share-alt"></i>发布HTML</a></li>
                    </ul>
                </div>
                </td>
            </tr>
        <?php     
            }
        ?>
        </tbody>
    </table>

    <div class="pagination pagination-centered">
        <ul>
            <?php 
                if ($page > 1) {
                    echo '<li><a href="index.php?page='. ($page - 1) .'">«</a></li>';
                } else {
                    echo '<li class="disabled"><span>«</span></li>';
                }    

                for ($i = 1; $i <= $pages; $i++) {
                    if ($i == $page){
                        echo '<li class="active"><span>'. $i .'</span></li>';
                    } else {
                        echo '<li><a href="index.php?page='. $i . '">'. $i .'</a></li>';
                    }
                }

                if ($page < $pages) {
                    echo '<li><a href="index.php?page='. ($page + 1) .'">»</a></li>';
                } else {
                    echo '<li class="disabled"><span>»</span></li>';
                }
            ?>
        </ul>
    </div>
    <!-- /.pagination -->

</div>

<?php
include_once "templates/admin_foot.php";
?>
