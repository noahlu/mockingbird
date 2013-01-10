<?php 
include_once 'check.php';
//echo $_POST['postType'];
if (!empty($_POST['postType'])) {
    $type = $_POST['postType'];
} else {
    exit("no postType declared!");    
}

if (!empty($_POST['id'])) {
    $postId = $_POST['id'];
}    

$fileName = empty($_POST['fileName']) ? "" : trim($_POST['fileName']);
$title = trim($_POST['title']);
$metaTitle = trim($_POST['metaTitle']);
$category = trim($_POST['category']);
$content = mysql_real_escape_string($_POST['content']);
$excerpt = mysql_real_escape_string($_POST['excerpt']);
date_default_timezone_set('Asia/Chongqing');
$date = date('Y-m-d H:i:s');

// connetc db
include_once "conn.php";
include_once "class_htmlHandler.php";

$htmlHandler = new htmlHandler();

if ($type == "modify") {

    $dataRow = mysql_fetch_array(mysql_query("SELECT * FROM $artTable WHERE id = $postId", $mysqlDB));
    
    $query = "UPDATE $artTable SET post_title = '$title', post_metaTitle = '$metaTitle', post_excerpt = '$excerpt', post_content = '$content', post_category = '$category', post_fileName = '$fileName', post_date = '$date' WHERE id = $postId";
    mysql_query($query, $mysqlDB) or die(mysql_error());

    // 重新生成HTML文件
    $htmlHandler->generateHTML($category, $fileName, $postId);
    if (!empty($excerpt)) {
        $htmlHandler->generateHTML($category, 'index', $postId);
    }

    // 如果html文件名或路径有修改，则删除原html文件和目录, $dataRow是修改前的数据
    if ($dataRow['post_category'] != $category || $dataRow['post_fileName'] != $fileName) {
        $htmlHandler->removeHTML($dataRow['post_category'], $dataRow['post_fileName'], $postId); 
        if ($dataRow['post_category'] != "/") {
            $htmlHandler->removeHTML($dataRow['post_category'], 'index', $postId); 
        } else {
            $htmlHandler->generateHTML($dataRow['post_category'], 'index', $postId);
        }
    }

    header("Location:postEdit.php?id=$postId");

} elseif ($type == "new") {
    $query = "INSERT INTO $artTable (id, post_title, post_metaTitle, post_excerpt, post_content, post_category, post_fileName, post_date) VALUES('', '$title', '$metaTitle', '$excerpt', '$content', '$category', '$fileName', '$date')";
    mysql_query($query, $mysqlDB);
    $postId = mysql_insert_id();
    $htmlHandler->generateHTML($category, $fileName, $postId);
    if (!empty($excerpt) && $category != "/") {
        $htmlHandler->generateHTML($category, 'index', $postId);
    }
    header("Location:postEdit.php?id=$postId");
}
?>
