<?php
$pagefilename = basename(__FILE__, ".php");//取得本業面的
include_once("SQLC.inc.php");
//取得分類名稱
if(preg_match("/^([0-9]+)$/",$_GET["classid"])){
    $classID = $_GET["classid"];
    $sqlClassName = "SELECT * FROM `BClasses` WHERE ClassId = '$classID'";
    $resultClassName = mysqli_query($sqli,$sqlClassName);
    $ClassNameArray = mysqli_fetch_array($resultClassName);
    $ClassName = $ClassNameArray["ClassName"];
}?>
<?include_once "hz_include/themes/def/header.php"; ?>
<? if($ClassNameArray["Img"]!=null): ?>
    <style>
        #PageTitle{
            text-shadow: 0px 0px 15px #000;
            color: #ffffff;
            background-image: url(<?=$ClassNameArray["Img"]?>);
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
<?endif;?>
<body>
<div id="top">
    <div class="top_contant"><?php include("hz_include/themes/def/header_top.php") ?></div>
</div>
<?include_once ("hz_include/themes/def/breadtraiil.php") ?>
<div id="contant">
    <?php
    $sql = "SELECT * FROM Blog where Blog.ClassId='$classID' AND (Blog.Competence='public' OR Blog.Competence='protect') ORDER BY Blog.PostDate DESC";
    $per = $mate[7];//每頁數量
    include_once("hz_include/class-postList.php");?>
    <div id="PageTitle">
        <?php echo $ClassName ?>
        <br/>
    </div>
    <?php
    if ($classID != null) {
        printpost($sql, $per,$pagefilename);
    } ?>
</div>
<div id="copyr">
    <?php include_once "hz_include/themes/def/footer.php"; ?>
</div>
</body>
</html>
