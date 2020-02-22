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
    <?php echo "<title>文章管理 - " . $mate[0] . "管理中心</title>"; ?>
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
<div id="tittle">文章管理</div>
<div id="right">
    <?PHP
    if ($_SESSION["username"] != null && ($userlaw["Law_PostBlog"]>0)) {
        if ($_GET["classID"] != null) {//利用分類或發文者查詢文章
            $classID = $_GET["classID"];
            $sql = "SELECT * FROM Blog WHERE ClassId = '$classID' ORDER BY Blog.PostDate DESC";
        } elseif ($_GET["postUser"] != null) {
            $postUser = $_GET["postUser"];
            $sql = "SELECT * FROM Blog WHERE UserID = '$postUser' ORDER BY Blog.PostDate DESC";

        } else {
            $sql = "SELECT * FROM Blog ORDER BY Blog.PostDate DESC";
        }
        $result = mysqli_query($sqli,$sql);
        $List = mysqli_num_rows($result);
        $per = 10;
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
            <div id="rightcontant">
            <div class="card bg-light mb-3" style="width: 100%;">
            <div class="card-header"><i class="fas fa-th-list"></i>文章管理</div>
            <div class="card-body">
            <p align="right">
                <?php
                if ($userlaw["Law_BlogClass"]==1) { ?>
                    <a href="classesm.php">文章分類管理 </a>｜<?php } ?>批次操作：<select name="editse">
                    <option selected="selected">Delete</option>
                    <option>Set Public</option>
                    <option>Set Protect</option>
                    <option>Set Private</option>
                    <option>Set Reply Yes</option>
                    <option>Set Reply No</option>
                </select>
                ｜<input class="btn btn-primary" name="editsub" type="submit" value="批次編輯"/>｜<a href="blogedit.php?id=0">新增文章</a>
            </p>
            <table width="100%" border="0" cellpadding="2" cellspacing="5">
                <tr>
                    <th width="5%" scope="row"><input name="all" type="checkbox" onClick="check_all(this,'postid[]')"
                                                      value=""/></th>
                    <td width="25%">ID/文章標題</td>
                    <td width="10%">發表日期</td>
                    <td width="10%">編輯者</td>
                    <td width="10%">分類</td>
                    <td width="10%">公開權限</td>
                    <td width="10%">允許回覆</td>
                </tr>
                <?php
                while ($OPost = mysqli_fetch_assoc($result)) {
                    //取得分類名稱
                    $classID = $OPost["ClassId"];
                    $sqlClassName = "SELECT * FROM `BClasses` WHERE ClassId = '$classID'";
                    $resultClassName = mysqli_query($sqli,$sqlClassName);
                    $ClassNameArray = mysqli_fetch_array($resultClassName);
                    $ClassName = $ClassNameArray["ClassName"]; ?>
                    <tr>
                        <th scope="row" width="5%">
                            <?php
                            if ($userlaw["Law_PostBlog"]==1 && $OPost["UserID"] != $b) {
                            } else {
                                ?>
                                <input name="postid[]" type="checkbox"
                                       value="<?php print $OPost["PostId"]; ?>" /><?php } ?></th>
                        <td>
                            <a href="<?php print "blogedit.php?id=" . $OPost["PostId"] ?>"><?php print $OPost["PostId"] . ". / " . $OPost["PostTittle"] ?> </a>
                        </td>
                        <td width="25%"><?php print $OPost["PostDate"] ?></td>
                        <td width="10%"><a
                                    href="?postUser=<?php print $OPost["UserID"] ?>"><?php print $OPost["User"] ?></td>
                        <td width="10%"><a
                                    href="?classID=<?php print $OPost["ClassId"] ?>"><?php print $ClassName ?></a></td>
                        <td width="10%"><?php print $OPost["Competence"] ?></td>
                        <td width="10%"><?php print $OPost["Reply"] ?></td>
                    </tr>
                <?php } ?></table>
            <br/>
            <?php
            for ($i = 1; $i <= $pages; $i++) {
                if ($_GET["classID"] != null) {//利用分類或發文者查詢文章
                    echo "<a href=?classID=" . $classID . "&page=" . $i . ">" . $i . "</a> ";
                } elseif ($_GET["postUser"] != null) {
                    echo "<a href=?postUser=" . $postUser . "&page=" . $i . ">" . $i . "</a> ";

                } else {
                    echo "<a href=?page=" . $i . ">" . $i . "</a> ";
                }
            }
        } elseif ($editdoing != null) {
            ?>

            <?php

            if ($editdoing == "Delete") {
                $PP = $_POST["postid"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $sql = "DELETE FROM Blog WHERE PostId='$value'";
                        //刪除該篇回復
                        $sqlr="DELETE FROM reply WHERE PostId='$value'";
                        //刪除該篇的所有收藏
                        $sqlf="DELETE FROM favpost WHERE postid='$value'";
                        //刪除該篇的所有喜歡
                        $sqll="DELETE FROM likepost WHERE postid='$value'";
                        $result = (mysqli_query($sqli,$sql) && mysqli_query($sqli,$sqlr) && mysqli_query($sqli,$sqlf) && mysqli_query($sqli,$sqll));
                    }
                    if ($result) {
                        include("msg/succ_msg.php");

                    }
                } else {
                    include("msg/error_msg.php");
                }
            } else if ($editdoing == "Set Public") {
                $PP = $_POST["postid"];

                if (count($PP) > 0) {
                    $comp = "public";
                    foreach ($PP as $value) {
                        $sql = "UPDATE Blog SET Competence = '$comp' WHERE `Blog`.`PostId` = $value";
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
                        $sql = "UPDATE Blog SET Competence = '$comp' WHERE `Blog`.`PostId` = $value";
                        $result = mysqli_query($sqli,$sql);
                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }

            } else if ($editdoing == "Set Protect") {
                $PP = $_POST["postid"];

                if (count($PP) > 0) {
                    $comp = "protect";
                    foreach ($PP as $value) {
                        $sql = "UPDATE Blog SET Competence = '$comp' WHERE `Blog`.`PostId` = $value";
                        $result = mysqli_query($sqli,$sql);
                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }

            } else if ($editdoing == "Set Reply Yes") {
                $PP = $_POST["postid"];

                if (count($PP) > 0) {
                    $Reply = "Yes";
                    foreach ($PP as $value) {
                        $sql = "UPDATE Blog SET Reply = '$Reply' WHERE `Blog`.`PostId` = $value";
                        $result = mysqli_query($sqli,$sql);
                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }

            } else if ($editdoing == "Set Reply No") {
                $PP = $_POST["postid"];

                if (count($PP) > 0) {
                    $Reply = "No";
                    foreach ($PP as $value) {
                        $sql = "UPDATE Blog SET Reply = '$Reply' WHERE `Blog`.`PostId` = $value";
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