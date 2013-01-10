<?php
$title = "分类管理";
$adminMenu = "taxonomy";
include_once "templates/admin_head.php";
include_once "class_htmlHandler.php";
$htmlHandler = new HtmlHandler();

// POST请求:新增或修改
if (isset($_POST['sign']) && $_POST['sign'] == "true") {
    
    $pass = true;

    // 表单校验
    if (!empty($_POST['taxonomy'])) {
        $taxonomy = "/". $_POST['taxonomy'];
    } else {
        echo "no taxonomy!";    
        $pass = false;
    }  

    $description = !empty($_POST['description']) ?  $_POST['description'] : "";
    $type = !empty($_POST['type']) ?  $_POST['type'] : "";

    if ($pass) {
        if ($_POST['newOrModify'] == "modify") {

            $id = $_POST['id'];
            $sqlQuery = "UPDATE $taxonomyTable SET taxonomy = '$taxonomy', description = '$description', type = '$type' WHERE id = '$id'";
            mysql_query($sqlQuery, $mysqlDB) or die(mysql_error());

            // 如果分类名称有修改
            if ($_POST['_taxonomy'] != $_POST['taxonomy']) {
                $orgCategory = "/".$_POST['_taxonomy'];
                $newCategory = "/".$_POST['taxonomy'];

                // 修改日志数据库分类名
                mysql_query("UPDATE $artTable SET post_category = '$newCategory' WHERE post_category = '$orgCategory'", $mysqlDB);

                $postResult = mysql_query("SELECT * FROM $artTable WHERE post_category = '$newCategory'", $mysqlDB);
                while ($postRows = mysql_fetch_array($postResult)) {

                    // 删除原日志
                    $htmlHandler->removeHTML($orgCategory, $postRows['post_fileName'], $postRows['id']);

                    // 创建新日志和目录
                    $htmlHandler->generateHTML($newCategory, $postRows['post_fileName'], $postRows['id']);
                }

                // 删除原目录和index文件，创建新目录index文件
                $htmlHandler->removeHTML($orgCategory, 'index', "");
                $htmlHandler->generateHTML($newCategory, 'index', "");
            }

        } else {
            $sqlQuery = "INSERT INTO $taxonomyTable (id, taxonomy, description, type) VALUES ('', '$taxonomy', '$description', '$type')";   
            mysql_query($sqlQuery, $mysqlDB) or die(mysql_error());
        }
    }
} elseif (isset($_GET['action'])) { // 如果是get请求
    if ($_GET['action'] == "del" && isset($_GET['taxonomy'])) {
        $taxonomy = $_GET['taxonomy'];
        $artResult = mysql_query("SELECT * FROM $artTable WHERE post_category = '$taxonomy'", $mysqlDB);
        $taxoResult = mysql_fetch_array(mysql_query("SELECT * FROM $taxonomyTable WHERE taxonomy = '$taxonomy'", $mysqlDB));

        if (!$taxoResult) {
            $errorMsg = "删除失败！分类错误。";
        } elseif (mysql_fetch_array($artResult)) {
            $errorMsg = "删除失败！分类下还有文件，您可以先尝试删除该分类下文件或修改分类。";
        } else {
            if (mysql_query("DELETE FROM $taxonomyTable WHERE taxonomy = '$taxonomy'", $mysqlDB)) {
                $htmlHandler->removeHTML($taxonomy, 'index', "");
                $resultMsg = "分类删除成功。";     
            }    
        }
            
    }
}    

include_once 'templates/admin_leftNav.php';
?>

<style type="text/css">
.form-action{visibility:hidden;}
.form-hover .form-action{visibility:visible;}
.form-tr-modify{background-color:#f5f5f5;}
</style>

<div class="span10 content">
    <div class="page-header ">
        <h2>分类管理</h2>
    </div>

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

    <form class="form-inline" action="taxonomy.php" method="post">
        <input name="sign" value="true" type="hidden"/>
        <input name="newOrModify" value="new" type="hidden"/>

        <span>添加分类： </span>

        <input type="text" class="input-medium" name="taxonomy" placeholder="分类标识" />
        <input type="text" class="input-medium" name="description" placeholder="分类名称" />
        <select class="input-small" name="type">
            <option value="">类型选择</option>
            <option value="category">Category</option>
            <option value="tag">Tag</option>
        </select>
        <button type="submit" class="btn btn-info">添加</button>
    </form>

    <table class="table table-hover" id="taxoTable">
        <colgroup>
            <col width="30%" />
            <col width="30%" />
            <col width="10%" />
            <col width="15%" />
            <col width="10%" />
        </colgroup>
        <thead>
            <tr>
                <th>分类标识</th>
                <th>分类名称</th>
                <th>类型</th>
                <th>操作</th>
                <th>HTML</th>
            </tr>
        </thead>
        <tbody>
        <tr class="muted">
        	<td><a href="page.php?home=true" target="_blank"><i class=" icon-share"></i> /</a></td>
        	<td>(站点根目录)</td>
        	<td>category</td>
        	<td>-</td>
        	<td><a href="htmlGenerate.php?home=true" target="_blank" class="btn btn-warning btn-mini"><i class="icon-cog icon-white"></i> 生成</a></td>
        </tr>
        <?php 
        $result = mysql_query("SELECT * FROM $taxonomyTable WHERE id != ''", $mysqlDB);
        while ($dataRow = mysql_fetch_array($result)) {
        ?>
            <tr>
            	<td>
                    <a href="page.php?postTaxonomy=<?php echo strtok($dataRow['taxonomy'],"/");?>" target="_blank"><i class=" icon-share"></i> <?php echo $dataRow['taxonomy'];?></a>
                </td>
            	<td><?php echo $dataRow['description'];?></td>
            	<td><?php echo $dataRow['type'];?></td>
            	<td>
                    <div class="form-action">
                        <a href="#" class="form-action-edit" data="<?php echo "id:".$dataRow['id']. ",taxonomy:". $dataRow['taxonomy']. ",description:". $dataRow['description']. ",type:". $dataRow['type'];?>">编辑</a> 
                        | <a class="text-error" href="taxonomy.php?action=del&taxonomy=<?php echo $dataRow['taxonomy'];?>">删除</a>
                    </div>    
                </td>
            	<td><a href="htmlGenerate.php?postTaxonomy=<?php echo $dataRow['taxonomy'];?>" class="btn btn-warning btn-mini" target="_blank"><i class="icon-cog icon-white"></i> 生成</a></td>
            </tr>
        <?php
        }
        ?>    
        </tbody>
    </table>    

</div>    

<script type="text/template" id="postFormTpl">
<form class="form-inline action="taxonomy.php" method="post" id="form-modify">
    <input name="sign" value="true" type="hidden"/>
    <input name="newOrModify" value="modify" type="hidden"/>
    <input name="id" value="{{id}}" type="hidden"/>
    <input name="_taxonomy" value="{{taxonomy}}" type="hidden"/>
    <input name="_type" value="{{type}}" type="hidden"/>

    <input type="text" class="input-medium" value="{{taxonomy}}" name="taxonomy" placeholder="分类标识" />
    <input type="text" class="input-medium" value="{{description}}" name="description" placeholder="分类名称" />
    <select class="input-small" name="type" >
        <option value="">类型选择</option>
        <option value="category">Category</option>
        <option value="tag">Tag</option>
    </select>
    <button type="submit" class="btn btn-danger" id="btn-modify-submit">修改</button>
    <a href="#" id="btn-modify-cancel">取消</a>
</form>
</script>

<script type="text/javascript">
seajs.use('jquery', function($){

    $("document").ready(function(){
        var oTaxoTable = $("#taxoTable"),
            oTaxoRows = $('#taxoTable tr'),    
            oModifyBtn = $('#taxoTable .form-action-edit'),    
            modifyFormTmpl = $("#postFormTpl").html();

        // 把字符串转化为对象
        function dataSerialize (dataStr) {
            var dataArr = dataStr.split(","),   
                dataArrTmp = [],
                dataObj = {};

            for (i = 0; i < dataArr.length; i++) {
                dataArrTmp = dataArr[i].split(":"); 
                if (dataArrTmp.length < 1) {
                    continue;
                }    
                dataObj[dataArrTmp[0]] = dataArrTmp[1] ? dataArrTmp[1] : "";
            }   

            return dataObj;
        }

        /*
         * 
         * @param dataObj {Object} 当前编辑的信息对象 
         * @return  {JQ Object} 信息修改表单TR元素 
         */
        function createForm (dataObj) {
            var tmplReplaced;

            // 去掉分类名的斜杠/
            dataObj.taxonomy = dataObj.taxonomy.slice(1);

            // 替换模版占位符
            tmplReplaced = modifyFormTmpl.replace(/{{id}}/g, dataObj.id);  
            tmplReplaced = tmplReplaced.replace(/{{taxonomy}}/g, dataObj.taxonomy);  
            tmplReplaced = tmplReplaced.replace(/{{description}}/g, dataObj.description);  
            
            // TODO: 需要优化，下面的这种替换方法有点危险
            if (dataObj.type) {
                tmplReplaced = tmplReplaced.replace("value=\"" + dataObj.type, "value=\"" + dataObj.type +"\" selected=\"selected\"");  
            }
            tmplReplaced = tmplReplaced.replace(/{{type}}/g, dataObj.type);  
                
            return $('<tr class="form-tr-modify" id="formModifyTr"><td colspan="5">' + tmplReplaced + '</td></tr>');
        }

        /*
         * 
         * @param curRow {JQ Object} 当前编辑的行TR元素
         * @param oForm {JQ Object} 需要编辑的表单对象 
         */
        function showForm (curRow, oForm) {
            oForm.insertAfter(curRow);
            curRow.hide();
        }

        /*
         * 
         * @param oRow {JQ Object} 当前隐藏状态的行TR对象 
         * @param oForm {JQ Object} 当前在编辑状态的表单对象 
         */
        function hideForm (oRow, oForm) {
            oRow.show();
            oForm.remove();
        }
            
        oTaxoRows.on('mouseover',function(event){
            $(this).addClass('form-hover');
        });
        
        oTaxoRows.on('mouseout',function(event){
            $(this).removeClass('form-hover');
        });

        oModifyBtn.click(function(event){
            event.preventDefault();
            if ($("#formModifyTr").length) {
                hideForm($("#formModifyTr").prev(), $("#formModifyTr"));
            }
            var curRow = $(this).parents("tr"),
                oData = dataSerialize($(this).attr("data")),
                oForm = createForm(oData);

            showForm(curRow, oForm);
            $("#btn-modify-cancel").click(function(event){
                event.preventDefault();
                hideForm(curRow, oForm);
            });
        });

    });       
    // end domready

})

</script>

<?php 
include_once "templates/admin_foot.php";
?>

