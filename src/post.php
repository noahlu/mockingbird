<?php

$postId = $_GET['id'];

// 没有id参数时，自动跳转到网站首页
if (empty($postId)) {
    header("Location:". ((!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http"). "://". $_SERVER ['HTTP_HOST'] );
    exit;
} 

include_once "conn.php";
include_once "class_htmlHandler.php";

$htmlHandler = new HtmlHandler();

$postQuery = "SELECT * FROM  $artTable WHERE id = $postId";
$dataRow = mysql_fetch_array(mysql_query($postQuery, $mysqlDB));
if (!$dataRow) {
    header("Location:". ((!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? "https" : "http"). "://". $_SERVER ['HTTP_HOST'] );
    exit;
}
$title = $dataRow['post_title'];
$metaTitle = empty($dataRow['post_metaTitle']) ? $title: $dataRow['post_metaTitle'];
$postCate = $dataRow['post_category'];

// taxonomy
$taxoArray = array();
$taxoCateArray = array();
$taxoTagArray = array();
$taxoResult = mysql_query("SELECT * FROM $taxonomyTable WHERE id != ''", $mysqlDB);
while ($taxoRow = mysql_fetch_array($taxoResult)) {
    $taxoArray = array_merge($taxoArray, array($taxoRow['taxonomy'] => $taxoRow));
    if ($taxoRow['type'] == "category") {
        $taxoCateArray = array_merge($taxoArray, array($taxoRow['taxonomy'] => $taxoRow));
    } elseif ($taxoRow['type'] == "tag") {
        $taxoTagArray = array_merge($taxoArray, array($taxoRow['taxonomy'] => $taxoRow));
    }
}

function getTaxonomyDesc ($taxonomy) {
    global $taxoArray;
    return empty($taxoArray[$taxonomy]['description']) ? strtok($taxonomy, "/") : $taxoArray[$taxonomy]['description'];
}    

// 相关文章
$relatedArtArray = array();
$relatedArtResult = mysql_query("SELECT * FROM $artTable WHERE post_category = '".$postCate."'", $mysqlDB);
while ($relatedRow = mysql_fetch_array($relatedArtResult)) {
    if ($relatedRow['id'] != $postId) {
        $relatedHtmlLoc = $htmlHandler->getHTMLLoc($relatedRow['post_category'], $relatedRow['post_fileName'], $relatedRow['id']);
        $relatedArtArray = array_merge($relatedArtArray, array($relatedRow['post_title']=>$relatedHtmlLoc));    
    }
}

// 随机文章
$randomArtArray = array();
$randomArtResult = mysql_query("SELECT * FROM $artTable WHERE post_category != '".$postCate."' order by post_date limit 0,5", $mysqlDB);
while ($randomRow = mysql_fetch_array($randomArtResult)) {
    if (!array_key_exists($randomRow['post_category'], $taxoCateArray)) {
        continue;    
    }
    $randomHtmlLoc = $htmlHandler->getHTMLLoc($randomRow['post_category'], $randomRow['post_fileName'], $randomRow['id']);
    $randomArtArray = array_merge($randomArtArray, array($randomRow['post_title']=>$randomHtmlLoc));    
}

// breadcrumb
$cateStr = strtok($dataRow['post_category'], "/");

// siteinfo
$sqlResult = mysql_query("SELECT * FROM $optionTable WHERE autoload = 'yes'");
while ( $result = mysql_fetch_array($sqlResult)) {

    switch($result['option_name']){
        case 'siteurl':
            $siteUrl = $result['option_value']; 
            break;
        case 'blogname':
            $siteName = $result['option_value']; 
            break;
        case 'blogdescription':
            $siteDesc = $result['option_value']; 
            break;
        // no default;    
    }
}

include_once 'templates/post_head.php';
?>
    <div class="content-post span9">
        <div class="page-header post-head">
            <ul class="breadcrumb">
                <li><a href="/">Home</a> <span class="divider">/</span></li>
                <?php
                $cateLoc = "";
                while ($cateStr !== false) {
                $cateLoc = $cateLoc . "/" . $cateStr;
                ?>
                <li><a href="<?php echo $cateLoc;?>/"><?php echo getTaxonomyDesc($cateLoc);?></a> <span class="divider">/</span></li>
                <?php    
                $cateStr = strtok("/");
                } 
                ?>
            </ul>
            <h1><?php echo $title;?></h1>
            <p class="post-head-date"><?php echo $dataRow['post_date'];?> <a href="#comments">评论</a></p>
            <?php if( isset($_SESSION['userName']) ) {?>
                <a href="/admin/postEdit.php?id=<?php echo $postId;?>"><i class="icon-pencil"></i> 编辑</a>
            <?php }?>
        </div>
        
        <div class="post-body">
            <?php echo $dataRow['post_content'];?>
        </div>
        <!--/.post-body -->
    </div>
    <!--/.content-post -->

    <?php
    include_once 'templates/post_side.php';
    ?>

    <div class="content-comments span9">
        <a name="comments"></a>
        <div id="disqus_thread" class="comments-disqus"></div>
        <!-- disqus script-->
        <script type="text/javascript">
            var disqus_shortname = 'noahuasblog';
            /* * * DON'T EDIT BELOW THIS LINE * * */
            (function() {
                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
        </script>
    </div>
    <!--/.content-comments -->

<?php
include_once 'templates/post_foot.php';
?>
