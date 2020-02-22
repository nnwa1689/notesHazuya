<?php
include_once("SQLC.inc.php");
$pagefilename = basename(__FILE__, ".php");//取得本頁檔名
//檢查引數，否則回傳NULL
//正規化
if(preg_match("/^([0-9A-Za-z]+)$/",$_GET["id"])){
    $pageId = $_GET["id"];
    $sql_P = "SELECT * FROM Page where Page.PageId='$pageId'";
    $result_P = mysqli_query($sqli,$sql_P);
    $List_P = mysqli_fetch_row($result_P);
}?>
<?include "hz_include/themes/def/header.php"?>
<body>
<div id="top">
    <div class="top_contant"><?include_once("hz_include/themes/def/header_top.php") ?></div>
</div>
<?include_once ("hz_include/themes/def/breadtraiil.php") ?>
<div id="contant">
    <div id="PageTitle">
        <?echo "$List_P[2]<br>"; ?>
    </div>
    <?php
    if ($List_P[1] == "public") :
        echo $List_P[3];
    endif; ?>
    <p>&nbsp;</p>
</div>
<div id="copyr">
    <?include_once "hz_include/themes/def/footer.php"; ?>
</div>
</body>
</html>
