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
    <?php echo "<title>文章分類管理 - " . $mate[0] . "管理中心</title>"; ?>
    <link href="include/style.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript">
        function check_all(obj, cName) {
            var checkboxs = document.getElementsByName(cName);
            for (var i = 0; i < checkboxs.length; i++) {
                checkboxs[i].checked = obj.checked;
            }
        }
    </script>
    <style type="text/css">
        body {
            margin-left: 0px;
            margin-top: 0px;
            margin-bottom: 0px;
            font-family: "微軟正黑體";
            background-color: #E9E9E9;
        }
    </style>
</head>

<body>
<div id="leftnav"> <?php include_once("include/admin-nav.php") ?></div>
<div id="tittle">文章分類管理</div>
<div id="right">
    <?PHP
    if ($_SESSION["username"] != null && $userlaw["Law_BlogClass"]==1) {
        $sql = "SELECT * FROM BClasses ORDER BY `BClasses`.`ClassId` ASC";
        $result = mysqli_query($sqli,$sql);
        ?>
        <?php
        $editdoing = $_POST["editse"];
        if ($editdoing == null) { ?>

            <form action="" method="post">
            <div id="rightcontant">
            <div class="card bg-light mb-3" style="width: 100%;">
            <div class="card-header">文章分類</div>
            <div class="card-body">

            <p align="right">
                <a href="../upload.php?id=categoryImg" target="_blank">上傳圖片</a>
                批次操作：<select name="editse">
                    <option>Create New</option>
                    <option>Update</option>
                    <option>Delete</option>
                    <option>Set show</option>
                    <option>Set hide</option>
                </select>
                ｜<input class="btn btn-primary" name="editsub" type="submit" value="批次編輯"/></p>
            <?php
            $List = mysqli_num_rows($result);
            for ($i = 0; $i < $List; $i++) {
                $ONav = mysqli_fetch_assoc($result);
                ?>
                <input name="ClassId[]" type="checkbox" value="<?php print $ONav["ClassId"]; ?>">
                順次
                <a><input name="inputorder[<?php print $ONav["ClassId"] ?>]" type="text"
                          value="<?php print $ONav["OrderID"]; ?>"/></a>&nbsp;
                分類名稱
                <a><input name="inputClassName[<?php print $ONav["ClassId"] ?>]" type="text"
                          value="<?php print $ONav["ClassName"]; ?>"/><?php print "(" . $ONav["SorH"] . ")" ?></a>
                封面圖片
                <a><input name="inputImg[<?php print $ONav["ClassId"] ?>]" type="text"
                          value="<?=$ONav["Img"]; ?>"/></a>&nbsp;
                <?php print "<hr>";
            }
            ?>
            <input name="all" type="checkbox" onClick="check_all(this,'ClassId[]')" value=""/>全選/全不選
            <br/>
            Create New(*每次只能新增一個，完成後請點上方的批次編輯送出)
            <br/>
            順次
            <a><input name="inputorder[0]" type="text" value=""/></a>&nbsp;
            分類名稱
            <input name="inputClassName[0]" type="text" value="" maxlength="14"/>
            <?php
        } elseif ($editdoing != null) {
            if ($editdoing == "Delete") {
                $PP = $_POST["ClassId"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        //先刪除選定文章分類之文章
                        $sqlDELPOST = "DELETE FROM Blog WHERE ClassId='$value'";
                        //查詢該篇文章ID
                        $postId=mysqli_fetch_row(mysqli_query($sqli,"SELECT PostId FROM Blog WHERE ClassId='$value'"));
                        //刪除該分類文章的回覆
                        $sqlr="DELETE FROM reply WHERE PostId='$postId[0]'";
                        //刪除該篇的所有收藏
                        $sqlf="DELETE FROM favpost WHERE postid='$postId[0]'";
                        //刪除該篇的所有喜歡
                        $sqll="DELETE FROM likepost WHERE postid='$postId[0]'";
                        $resultDELPOST = (mysqli_query($sqli,$sqlDELPOST) && mysqli_query($sqli,$sqlr) && mysqli_query($sqli,$sqlf) && mysqli_query($sqli,$sqll));
                        //再刪除所選分類
                        $sql = "DELETE FROM BClasses WHERE ClassId='$value'";
                        $result = mysqli_query($sqli,$sql);
                    }
                    if ($result && $resultDELPOST) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                } else {
                    include("msg/error_msg.php");
                }
            } else if ($editdoing == "Create New") {
                $List = mysqli_num_rows($result);
                $NindexId = $List + 1;
                $NNameP = $_POST["inputClassName"];
                $NNameP[0] = addslashes($NNameP[0]);
                $Norder=$_POST["inputorder"];
                $sql = "INSERT INTO BClasses (ClassName,SorH,OrderID,Img) VALUES ( '$NNameP[0]','show','$Norder[0]','' )";
                $result = mysqli_query($sqli,$sql);
                if ($result) {
                    include("msg/succ_msg.php");
                } else {
                    include("msg/error_msg.php");
                }
            } else if ($editdoing == "Update") {
                $PP = $_POST["ClassId"];
                $NIDP = $_POST["inputClassId"];
                $NNameP = $_POST["inputClassName"];
                $Norder=$_POST["inputorder"];
                $Img=$_POST["inputImg"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $NNameP[$value] = addslashes($NNameP[$value]);
                        $result_Name = mysqli_query($sqli,"UPDATE BClasses SET ClassName = '$NNameP[$value]' WHERE `BClasses`.`ClassId` = '$value'");
                        $resut_ordeer=mysqli_query($sqli,"UPDATE BClasses SET OrderID = '$Norder[$value]' WHERE `BClasses`.`ClassId` = '$value'");
                        $resut_Img=mysqli_query($sqli,"UPDATE BClasses SET Img = '$Img[$value]' WHERE `BClasses`.`ClassId` = '$value'");
                    }
                    if ($result_Name && $resut_ordeer) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }

            } else if ($editdoing == "Set show") {
                $PP = $_POST["ClassId"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $sql_show = "UPDATE BClasses SET SorH = 'show' WHERE `BClasses`.`ClassId` = '$value'";
                        $result_show = mysqli_query($sqli,$sql_show);

                    }
                    if ($result_show) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }

            } else if ($editdoing == "Set hide") {
                $PP = $_POST["ClassId"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $sql_hide = "UPDATE BClasses SET SorH = 'hide' WHERE `BClasses`.`ClassId` = '$value'";
                        $result_hide = mysqli_query($sqli,$sql_hide);

                    }
                    if ($result_hide) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }
            }
        }

        ?>
        <div class="alert alert-warning" role="alert">
            新增的分類顯示狀態預設為show，若要設為hide請新增後再更改
        </div>
        <div class="alert alert-danger" role="alert">
            刪除分類將會一併刪除文章，請謹慎操作!
        </div>
        <div class="alert alert-warning" role="alert">
            第一個分類為預設分類，不建議刪除
        </div>
        <div class="alert alert-warning" role="alert">
            批次操作均需要勾選項目，包含更新資料
        </div>
        </div>
        </div>
        </div>
        <p> </p>
        </form>
        <?php
    } else {
        include("msg/error_msg.php");
        ?>
        <p>&nbsp;</p>
    <?php } ?>

</div>
</body>
</html>