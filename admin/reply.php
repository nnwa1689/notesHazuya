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
    <?php echo "<title>回覆管理 - " . $mate[0] . "管理中心</title>"; ?>
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
<div id="tittle"> 回覆管理</div>
<div id="right">
    <?PHP
    if ($_SESSION["username"] != null && $userlaw["Law_reply"]==1) {
        $sql = "SELECT * FROM `reply` ORDER BY `reply`.`ReplyId` DESC";
        $result = mysqli_query($sqli,$sql);
        $List = mysqli_num_rows($result);
        $per = 20;
        $pages = ceil($List / $per);
        if (!isset($_GET["page"])) {
            $page = 1;
        } else {
            $page = intval($_GET["page"]);

        }
        $start = ($page - 1) * $per;
        $result = mysqli_query($sqli,$sql . ' LIMIT ' . $start . ',' . $per) or die("ERROR");//分業關鍵
        ?>
        <br/>

        <?php
        $editdoing = $_POST["editse"];
        if ($editdoing == null) { ?>

            <form action="" method="post">
            <p align="right">批次操作：<select name="editse">
                    <option selected="selected">Delete</option>
                    <option>Set Public</option>
                    <option>Set Private</option>
                </select>
                ｜<input class="btn btn-primary" name="editsub" type="submit" value="批次編輯"/></p>
            <table width="100%" border="0" cellpadding="2" cellspacing="5">
                <tr>
                    <th width="5%" scope="row"><input name="all" type="checkbox" onClick="check_all(this,'replyid[]')"
                                                      value=""/></th>
                    <td width="30%">回覆內容</td>
                    <td width="15%">發表日期</td>
                    <td width="20%">發表者</td>
                    <td width="15%">回覆文章</td>
                    <td width="15%">公開權限</td>

                </tr>
                <?php
                while ($reply = mysqli_fetch_assoc($result)) {
                    $postId = $reply["PostId"];
                    $postSql = "SELECT * FROM Blog WHERE PostId='$postId'";
                    $resultpostSql = mysqli_query($sqli,$postSql);
                    $OPost = mysqli_fetch_row($resultpostSql);
                    ?>
                    <tr>
                        <th scope="row" width="5%"><input name="replyid[]" type="checkbox"
                                                          value="<?php print $reply["ReplyId"]; ?>"/></th>
                        <td width="30%"><?php print $reply["ReplyContant"] ?></td>
                        <td width="15%"><?php print $reply["ReplyDate"] ?></td>
                        <td width="20%"><?php print $reply["ReplyUserName"] ?></td>
                        <td width="20%"><a href="blogedit.php?id=<?php print $OPost[0] ?>"><?php print $OPost[2] ?></a>
                        </td>
                        <td width="15%"><?php print $reply["Competence"] ?></td>

                    </tr>
                <?php } ?></table>
            <br/>
            <?php
            for ($i = 1; $i <= $pages; $i++) {
                echo "<a href=?page=" . $i . ">" . $i . "</a> ";
            }
        } elseif ($editdoing != null) {
            ?>


            <?php

            if ($editdoing == "Delete") {
                $PP = $_POST["replyid"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $sql = "DELETE FROM reply WHERE 	ReplyId='$value'";
                        $result = mysqli_query($sqli,$sql);
                    }
                    if ($result) {
                        include("msg/succ_msg.php");

                    }
                } else {
                    include("msg/error_msg.php");
                }
            } else if ($editdoing == "Set Public") {
                $PP = $_POST["replyid"];
                if (count($PP) > 0) {
                    $comp = "public";
                    foreach ($PP as $value) {
                        $sql = "UPDATE reply SET Competence = '$comp' WHERE `reply`.`ReplyId` = $value";
                        $result = mysqli_query($sqli,$sql);
                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }

            } else if ($editdoing == "Set Private") {
                $PP = $_POST["replyid"];

                if (count($PP) > 0) {
                    $comp = "private";
                    foreach ($PP as $value) {
                        $sql = "UPDATE reply SET Competence = '$comp' WHERE `reply`.`ReplyId` = $value";
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