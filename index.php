<?php
$pagefilename = basename(__FILE__, ".php");//取得本頁檔名
include_once "hz_include/themes/def/header.php";
//取得首頁內容
$sqlHome = "Select PageContant FROM Page where Page.PageId='home'";
$resultHome = mysqli_query($sqli,$sqlHome);
?>
<body>
<div id="top">
    <div class="top_contant">
        <?php
        include_once("hz_include/themes/def/header_top.php")
        ?>
    </div>
</div>
<?include_once ("hz_include/themes/def/breadtraiil.php") ?>
<?php
include_once("hz_include/mods/carousel/carousel.php")
?>
<div id="contant">
    <?php
    include_once("hz_include/themes/def/homepost.php");
    $homeContant = mysqli_fetch_row($resultHome);
    echo $homeContant[0]; ?>
</div>
<div id="copyr">
    <?php
    include_once "hz_include/themes/def/footer.php";
    ?>
</div>
</body>
</html>
