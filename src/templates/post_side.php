
<div class="content-recom ">
    <p>
        some words here.
    </p>
    <div class="well content-recom-list">
        <ul class="nav nav-list">
            <li class="nav-header"><i class="icon-folder-open"></i> 分类</li>
            <?php
            while (list($key,) = each($taxoCateArray)) {
            ?>
                <li><a href="<?php echo $key;?>/"><?php echo getTaxonomyDesc($key);?></a></li>
            <?php
            }
            ?>
            <li class="nav-header"><i class="icon-file"></i> 推荐日志</li>
            
            <?php
            while (list($key,$val) = each($relatedArtArray)) {
            ?>
                <li class="l-style-disc"><a href="<?php echo $val;?>"><?php echo $key;?></a></li>
            <?php
            }

            while (list($key,$val) = each($randomArtArray)) {
            ?>
                <li class="l-style-disc"><a href="<?php echo $val;?>"><?php echo $key;?></a></li>
            <?php
            }
            ?>
            <li class="nav-header"><i class="icon-plus-sign"></i> 圈子</li>
            <li><a href="">前端</a></li>
        </ul>
    </div>
</div>
<!--/.content-recom -->
