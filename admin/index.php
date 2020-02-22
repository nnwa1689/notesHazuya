<?php
session_start();
$pagefilename = basename(__FILE__, ".php");//取得本業面的黨名
include_once("../SQLC.inc.php");
$sql = "SELECT * FROM web ORDER BY `ID` ASC";
$result = mysqli_query($sqli,$sql);
$List = mysqli_num_rows($result);
$mate = array("", "", "", "");
for ($i = 0; $i < $List; $i++) {
    $search = mysqli_fetch_assoc($result);
    $mate[$i] = $search["tittle"];
}
//****************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo "<title>首頁 - " . $mate[0] . "管理中心</title>"; ?>
    <link href="include/style.css" rel="stylesheet" type="text/css"/>
    <link rel="icon" type="image/png" href="../favicon.png"/>
    <script type="text/javascript">
        function check_all(obj, cName) {
            var checkboxs = document.getElementsByName(cName);
            for (var i = 0; i < checkboxs.length; i++) {
                checkboxs[i].checked = obj.checked;
            }
        }
    </script>
</head>

<body>
<div id="leftnav"> <?php include_once("include/admin-nav.php") ?></div>
<div id="tittle">首頁</div>
<div id="right">
    <div id="rightcontant">
        <h1>管理中心 - 首頁 </h1>
        <?php if ($userlaw["Law_admin"] == 1) { ?>
            <h3>盡情個人化您的網站 </h3>
        <?php } else if ($userlaw["Law_PostBlog"] > 0) { ?>
            <h3>享受創作的樂趣！ </h3>
        <?php } ?>
        <hr>
        <h4>
            <?php

            if ($_SESSION["username"] != null){
            /**公告數量**/
            $sql = "SELECT * FROM `HomePost` ORDER BY `HomePost`.`PostId` DESC";
            $result = mysqli_query($sqli,$sql);
            $List = mysqli_num_rows($result);
            $newHP = mysqli_fetch_assoc($result);
            /*************************************/
            ?></h4>
        <div class="card bg-light mb-3" style="width: 100%;">
            <div class="card-header"><? echo "目前本網站之首頁公告總數為 : " . $List;?></div>
            <div class="card-body">
                <p class="card-text">
                <h2>最新發布公告</h2>
                <a href="edit.php?id=<?php print $newHP["PostId"] ?>"><?php print $newHP["PostDate"] . "  " . $newHP["PostTittle"] ?></a>

                </p>
            </div>
        </div>
        <br>

            <?php
            /**部落格**/
            $sql = "SELECT * FROM Blog ORDER BY Blog.PostDate DESC";
            $result = mysqli_query($sqli,$sql);
            $List = mysqli_num_rows($result);
            $newBlog = mysqli_fetch_assoc($result);
            ?>
        <div class="card bg-light mb-3" style="width: 100%;">
            <div class="card-header"><? echo "目前本網站之部落格文章總數為 : " . $List;?></div>
            <div class="card-body">
                <p class="card-text">
                <h2>最新發表文章</h2>
                <?php if ($userlaw["Law_PostBlog"] == 2) { ?>
                    <a href="blogedit.php?id=<?php print $newBlog["PostId"] ?>"><?php print $newBlog["PostDate"] . "  " . $newBlog["PostTittle"] . "－" . $newBlog["User"] ?></a>
                <?php } else if ($userlaw["Law_PostBlog"] == 1) { ?>
                    <a href="../blog.php?id=<?php print $newBlog["PostId"] ?>"><?php print $newBlog["PostDate"] . "  " . $newBlog["PostTittle"] . "－" . $newBlog["User"] ?></a>
                <?php } ?>

                </p>
            </div>
        </div>


    </div>


    <?php
    /*************************************/
    }
    else {
        include("msg/error_msg.php");
    }
    ?>


</div>
</body>
</html>