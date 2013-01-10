<?php
if (empty($_GET['postType']) || $_GET['postType'] == "modify") {
    $postType = "modify";      
    if (empty($_GET['id'])) {
        exit("No post id!");
    } else {
        $postId = $_GET['id'];
    }
} elseif($_GET['postType'] == "new"){
    $postType = "new";      
    $postId = "";
}

$title = "编辑";
$adminMenu = "postEdit". $postType;
include_once "templates/admin_head.php";

if ($postType == "modify") {
include_once "conn.php";
include_once "class_htmlHandler.php";

$postQuery = "SELECT * FROM  $artTable WHERE id = $postId";
$dataRow = mysql_fetch_array(mysql_query($postQuery, $mysqlDB));
$title = $dataRow['post_title'];
$htmlHandler = new htmlHandler();
$htmlLoc = $htmlHandler->getHtmlLoc($dataRow['post_category'], $dataRow['post_fileName'], $postId); 
}

include_once 'templates/admin_leftNav.php';
?>
<!-- kindeditor  -->
<script type="text/javascript" src="includes/kindeditor-4.1.2/kindeditor-min.js"></script>
<script type="text/javascript" src="includes/kindeditor-4.1.2/lang/zh_CN.js"></script>

<div class="span10 content">
    <div class="page-header">
        <h2>内容编辑</h2>
    </div>

    <div class="">
        <form action="postProcess.php" id="post-content" class="form-vertical" method="post">
            <input name="postType" value="<?php echo $postType;?>" type="hidden" />
            <input name="id" value="<?php echo $postId;?>" type="hidden" />

            <div class="span8 mg-l-no post-content-edit">
                <div class="control-group">
                    <label for="postTitle" class="control-label">标题：</label>
                    <div class="controls">
                        <input type="text" name="title" class="span8" id="postTitle" value="<?php if($postType == "modify"){echo $dataRow['post_title'];}?>" placeholder="Post Title" />
                    </div>
                </div>
                <div class="control-group">
                    <label for="" class="control-label"><i class="icon-file"></i> HTML文件路径 文件名：</label>
                    <div class="controls">
                        <select class="input-medium" name="category" id="postCategory" >
                            <option value="/">/</option>
                            <?php
                            $taxoResult = mysql_query("SELECT * FROM $taxonomyTable WHERE type = 'category'", $mysqlDB);
                            while ($taxoRow = mysql_fetch_array($taxoResult)) {
                                if(isset($dataRow['post_category']) && $taxoRow['taxonomy'] == $dataRow['post_category']) {
                                    echo '<option value="'. $taxoRow['taxonomy']. '" selected="selected">'. $taxoRow['taxonomy']. "</option>";   
                                } else {
                                    echo '<option value="'. $taxoRow['taxonomy']. '">'. $taxoRow['taxonomy']. "</option>";   
                                }
                            }
                            ?>
                        </select>
                        <input type="text" name="fileName" class="input-large" id="postFileName" value="<?php if($postType == "modify"){echo $dataRow['post_fileName'];}?>" placeholder="Post File Name" />
                        <span class="label label-important hide" id="fileName-explain"></span>
                    </div>
                </div>
                <div class="control-group">
                    <label for="postContent" class="fm-label"><i class="icon-book"></i> 内容：(<span class="muted">上次编辑时间：<?php if($postType == "modify"){echo $dataRow['post_date'];}?></span>)</label>
                    <div class="controls">
                        <textarea rows="20" id="postContent" name="content" class="span8" ><?php if($postType == "modify"){echo $dataRow['post_content'];}?></textarea>
                    </div>
                </div>

                <div class="control-group">
                    <label for="postExcerpt" class="fm-label"><i class="icon-list-alt"></i> 摘要：</label>
                    <div class="controls">
                        <textarea rows="5" id="postExcerpt" name="excerpt" class="span8" ><?php if($postType == "modify"){echo $dataRow['post_excerpt'];}?></textarea>
                    </div>
                    <span class="help-block "><em class="text-warning">(摘要将显示在文件列表index.htm页面中，若不填写则文件不出现在列表中 )</em></span>
                </div>

                <div class="control-group">
                    <label for="pageTitle" class="control-label">页面TITLE：</label>
                    <div class="controls">
                        <input type="text" name="metaTitle" class="span8" id="pageTitle" value="<?php if($postType == "modify"){echo $dataRow['post_metaTitle'];}?>" placeholder="Html Page Title" />
                    </div>
                    <span class="help-block"><em class="text-warning">(网页Title，不填写则默认为标题 )</em></span>
                </div>

            </div>
            <!-- /.post-content-edit -->

            <div class="span2 post-content-function">
                <h4>预览和发布</h4>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-info" id="btnSubmit">保存并发布</button>
                    </div>
                </div>
                <?php if ($postType == "modify") {?>
                <div class="btn-group">
                    <a href="<?php echo "$htmlHandler->preview?id=$postId";?>" target="_blank" class="btn btn-info" >预览草稿</a>
                </div>
                <p class="muted"><em><?php echo "$htmlHandler->preview?id=$postId";?></em></p>

                <div class="btn-group">
                    <a href="<?php echo $htmlLoc;?>" class="btn btn-success" target="_blank">预览HTML</a>
                </div>
                <p class="muted"><em><?php echo $htmlLoc;?></em></p>

                <div class="btn-group">
                    <a href="htmlGenerate.php?id=<?php echo $postId;?>" class="btn btn-danger " target="_blank">发布HTML</a>
                </div>
                <?php }?>

            </div>
            <!-- /.post-content-function -->
        </form>
    </div>

    
    
</div>

<script>
    var kindeditor;
    KindEditor.ready(function(K) {
        kindeditor = K.create('#postContent', {
            filterMode : false,
            //wellFormatMode : false,
            cssPath : ["/assets/css/global.css", "/assets/lib/bootstrap/css/bootstrap.css"]
        });
    });

    seajs.use('jquery', function($){
        $("document").ready(function(){

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
include_once "templates/admin_foot.php";
?>


