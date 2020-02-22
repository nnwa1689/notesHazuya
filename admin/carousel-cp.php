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
$b = $_SESSION["username"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo "<title>首頁輪播carousel - " . $mate[0] . "管理中心</title>"; ?>
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
<div id="tittle"> 首頁輪播carousel</div>
<div id="right">
    <?PHP
    if ($_SESSION["username"] != null && $userlaw["Law_mods"]==1) {
        $sql = "SELECT * FROM Carousel ORDER BY OrderNumber ASC";
        $result = mysqli_query($sqli,$sql);
        ?>
        <br/>
        <?php
        $editdoing = $_POST["editse"];
        if ($editdoing == null) { ?>
            <div class="alert alert-dark" role="alert">
                批次操作均需要勾選項目，包含更新資料
            </div>
            <form action="" method="post">
            <p align="right">批次操作：<select name="editse">
                    <option selected="selected">Update</option>
                    <option>Create New</option>
                    <option>Delete</option>
                </select>
                ｜<input class="btn btn-primary" name="editsub" type="submit" value="批次編輯"/></p>
            <table width="100%" border="0" cellpadding="2" cellspacing="5">
                <tr>
                    <th width="5%" scope="row"><input name="all" type="checkbox" onClick="check_all(this,'postid[]')"
                                                      value=""/></th>
                    <td width="25%">名稱</td>
                    <td width="30%">圖片連結</td>
                    <td width="30%">連結</td>
                    <td width="10%">順序</td>
                </tr>
                <?php
                while ($OPost = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <th scope="row" width="5%"><input name="postid[]" type="checkbox"
                                                          value="<?php print $OPost["ID"]; ?>"/></th>
                        <td width="25%"><input name="cName[<?php print $OPost["ID"]; ?>]" type="text"
                                               value="<?php print $OPost["Name"] ?>"/></td>
                        <td width="30%"><input name="cImgURL[<?php print $OPost["ID"]; ?>]" type="text"
                                               value="<?php print $OPost["ImageURL"] ?>"/></td>
                        <td width="30%"><input name="cURL[<?php print $OPost["ID"]; ?>]" type="text"
                                               value="<?php print $OPost["URL"] ?>"/></td>
                        <td width="10%"><input name="cOrder[<?php print $OPost["ID"]; ?>]" type="text"
                                               value="<?php print $OPost["OrderNumber"] ?>"/></td>
                    </tr>
                <?php } ?></table>
            <br/>

            Create New(*每次只能新增一個，完成後請點上方的批次編輯送出)<br/>
            順序
            <input name="inputId" type="text" size="5" maxlength="2" value=""/>
            名稱
            <input name="inputName" type="text" value=""/>
            圖片連結
            <input name="inputimageURL" type="text" value=""/>
            連結
            <input name="inputURL" type="text" value=""/>

            <?php
        } elseif ($editdoing != null) {
            ?>


            <?php

            if ($editdoing == "Delete") {
                $PP = $_POST["postid"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $sql = "DELETE FROM Carousel WHERE ID ='$value'";
                        $result = mysqli_query($sqli,$sql);
                    }
                    if ($result) {
                        include("msg/succ_msg.php");

                    }
                } else {
                    include("msg/error_msg.php");
                }
            } else if ($editdoing == "Update") {
                $PP = $_POST["postid"];
                $cname = $_POST["cName"];
                $curl = $_POST["cURL"];
                $cimgurl = $_POST["cImgURL"];
                $corder = $_POST["cOrder"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $cname[$value] = addslashes($cname[$value]);
                        $sql = "UPDATE Carousel SET Name = '$cname[$value]' WHERE `Carousel`.`ID` = $value";
                        $result = mysqli_query($sqli,$sql);
                        $sql = "UPDATE Carousel SET URL = '$curl[$value]' WHERE `Carousel`.`ID` = $value";
                        $result = mysqli_query($sqli,$sql);
                        $sql = "UPDATE Carousel SET ImageURL = '$cimgurl[$value]' WHERE `Carousel`.`ID` = $value";
                        $result = mysqli_query($sqli,$sql);
                        $sql = "UPDATE Carousel SET OrderNumber = '$corder[$value]' WHERE `Carousel`.`ID` = $value";
                        $result = mysqli_query($sqli,$sql);

                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }
            } else if ($editdoing == "Create New") {
                $cname = addslashes($_POST["inputName"]);
                $curl = $_POST["inputURL"];
                $cimgurl = $_POST["inputimageURL"];
                $corder = $_POST["inputId"];
                $sql = "INSERT INTO Carousel (ImageURL,URL,OrderNumber,Name) VALUES ( '$cimgurl','$curl','$corder','$cname' )";
                $result = mysqli_query($sqli,$sql);
                if ($result) {
                    include("msg/succ_msg.php");
                } else {
                    include("msg/error_msg.php");
                }
            }


        }
        ?>

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