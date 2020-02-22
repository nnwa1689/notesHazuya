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
    <?php echo "<title>首頁公告管理 - " . $mate[0] . "管理中心</title>"; ?>
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
<div id="tittle">公告與廣播</div>
<div id="right">
    <?PHP
    if ($_SESSION["username"] != null && $userlaw["Law_web"]==1) {
    $sql = "SELECT PostId,PostTittle,PostDate,Competence FROM HomePost ORDER BY PostDate DESC";
    $result = mysqli_query($sqli,$sql);
    ?>
    <?php
    $editdoing = $_POST["editse"];
    if ($editdoing == null) { ?>

    <form action="" method="post">
        <div id="rightcontant">
            <div class="card bg-light mb-3" style="width: 100%;">
                <div class="card-header"><i class="fas fa-bullhorn"></i>網站公告</div>
                <div class="card-body">
                    <p align="right">批次操作：<select name="editse">
                            <option selected="selected">Delete</option>
                            <option>Set Public</option>
                            <option>Set Private</option>
                        </select>
                        ｜<input class="btn btn-primary" name="editsub" type="submit" value="批次編輯"/>｜<a
                                href="edit.php?id=0">新增首頁公告</a>｜<a
                                href="broadcast.php">站內信廣播</a>｜<a
                                href="broadcast-mail">Email廣播</a></p>
                    <table width="100%" border="0" cellpadding="2" cellspacing="5">
                        <tr>
                            <th width="5%" scope="row"><input name="all" type="checkbox"
                                                              onClick="check_all(this,'postid[]')"
                                                              value=""/></th>
                            <td width="45%">ID/公告標題</td>
                            <td width="25%">公告日期</td>
                            <td width="25%">公開權限</td>
                        </tr>
                        <?php
                        $List = mysqli_num_rows($result);
                        for ($i = 0; $i < $List; $i++) {
                            $OPost = mysqli_fetch_assoc($result);
                            ?>
                            <tr>
                                <th scope="row" width="5%"><input name="postid[]" type="checkbox"
                                                                  value="<?php print $OPost["PostId"]; ?>"/></th>
                                <td width="45%"><a
                                            href="<?php print "edit.php?id=" . $OPost["PostId"] ?>"><?php print $OPost["PostId"] . ". / " . $OPost["PostTittle"] ?> </a>
                                </td>
                                <td width="25%"><?php print $OPost["PostDate"] ?></td>
                                <td width="25%"><?php print $OPost["Competence"] ?></td>
                            </tr>

                        <?php } ?></table>
                    <?php print "<br>目前本網站之首頁公告總數為 : " . $List;
                    } elseif ($editdoing != null) {
                        ?>
                        <?php

                        if ($editdoing == "Delete") {
                            $PP = $_POST["postid"];
                            if (count($PP) > 0) {
                                foreach ($PP as $value) {
                                    $sql = "DELETE FROM HomePost WHERE PostId='$value'";
                                    $result = mysqli_query($sqli,$sql);
                                }
                                if ($result) {
                                    $sql_del = "ALTER TABLE `HomePost` DROP COLUMN `PostId`";
                                    $result_del = mysqli_query($sqli,$sql_del);
                                    if ($result_del) {
                                        $sql_rest = "ALTER TABLE `HomePost` ADD `PostId` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;";
                                        $result_rest = mysqli_query($sqli,$sql_rest);
                                        if ($result_rest) {
                                            include("msg/succ_msg.php");
                                        } else {
                                            include("msg/error_msg.php");
                                        }
                                    } else {
                                        include("msg/error_msg.php");
                                    }

                                } else {
                                    include("msg/error_msg.php");
                                }
                            } else {
                                include("msg/error_msg.php");
                            }
                        } else if ($editdoing == "Set Public") {
                            $PP = $_POST["postid"];

                            if (count($PP) > 0) {
                                $comp = "public";
                                foreach ($PP as $value) {
                                    $sql = "UPDATE HomePost SET Competence = '$comp' WHERE `HomePost`.`PostId` = $value";
                                    $result = mysqli_query($sqli,$sql);
                                }
                                if ($result) {
                                    include("msg/succ_msg.php");
                                } else {
                                    include("msg/error_msg.php");
                                }
                            }

                        } else if ($editdoing == "Set Private") {
                            $PP = $_POST["postid"];

                            if (count($PP) > 0) {
                                $comp = "private";
                                foreach ($PP as $value) {
                                    $sql = "UPDATE HomePost SET Competence = '$comp' WHERE `HomePost`.`PostId` = $value";
                                    $result = mysqli_query($sqli,$sql);
                                }
                                if ($result) {
                                    include("msg/succ_msg.php");
                                } else {
                                    include("msg/error_msg.php");
                                }
                            }

                        }
                    }
                    ?>
                </div>
            </div>
        </div>
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