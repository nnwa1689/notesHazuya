<?php
$pagefilename = basename(__FILE__, ".php");//取得本業面的
include "hz_include/themes/def/header.php"; ?>
<body>
<div id="top">
    <div class="top_contant"><?php include("hz_include/themes/def/header_top.php") ?></div>
</div>
<?include_once ("hz_include/themes/def/breadtraiil.php") ?>
<div id="contant">
    <div id="PageTitle">
        檔案上傳中心
        <br>
    </div>
    <form action="" method="post" enctype="multipart/form-data">

        <p>
            <?php include_once("hz_include/uploadFun.php"); ?>
        </p>
    </form>
    <p>&nbsp;</p>
</div>
<div id="copyr">
    <?php include "hz_include/themes/def/footer.php"; ?>
</div>
</body>
</html>
