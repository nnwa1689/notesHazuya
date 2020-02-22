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
    <?php echo "<title>媒體庫 - " . $mate[0] . "管理中心</title>"; ?>
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
    </style>
</head>

<body>
<div id="leftnav"> <?php include_once("include/admin-nav.php") ?></div>
<div id="tittle">媒體庫</div>
<div id="right">
    <?PHP
    if ($_SESSION["username"] != null && $userlaw["Law_filem"]==1) {
        $sql = "SELECT * FROM media ORDER BY media.UploadDate DESC";
        $result = mysqli_query($sqli,$sql);
        $List = mysqli_num_rows($result);
        $per = 15;
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
            <div class="card-header"><i class="fas fa-images"></i>媒體庫</div>
            <div class="card-body">
            <p align="right">
                <?php
                if ($predata[5] == "administrator") { ?>
                    <a href="blogm.php">文章管理 </a>｜<?php } ?>批次操作：<select name="editse">
                    <option selected="selected">Delete</option>
                </select>
                ｜<input class="btn btn-primary" name="editsub" type="submit" value="批次編輯"/></a>
            </p>
            <table width="100%" border="0" cellpadding="2" cellspacing="5">
                <tr>
                    <th width="5%" scope="row"><input name="all" type="checkbox" onClick="check_all(this,'mediaid[]')"
                                                      value=""/></th>
                    <td width="60%">檔案路徑</td>
                    <td width="20%">上傳日期</td>
                    <td width="15%">檔案大小</td>
                </tr>
                <?php
                while ($media = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <th scope="row" width="5%">
                            <input name="mediaid[]" type="checkbox" value="<?php print $media["ID"]; ?>"/></th>
                        <td width="60%">
                            <img style="max-height: 400px;max-width: 200px"
                                 src="<?php print $mate[13] . $media["URL"] ?>">
                            <br>
                            <a href="<?php print $mate[13] . $media["URL"] ?>"
                               target="_blank"><?php print $media["URL"] ?></a>
                            <p>TYPE：<?php print $media["Type"] ?></p>

                        </td>
                        <td width="20%"><?php print $media["UploadDate"] ?></td>
                        <td width="15%">
                            <?php print ($media["Cap"] / 1000) . "KB" ?>
                        </td>
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
                $PP = $_POST["mediaid"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $fileinfo = mysqli_fetch_array(mysqli_query($sqli,"SELECT URL FROM media WHERE ID='$value'"));
                        $delfile = $fileinfo["URL"];
                        if (is_file(".." . $delfile)) {//判斷檔案是否存在
                            //如果存在進行檔案刪除，否則直接刪除資料庫
                            $delfilenum = unlink(".." . $delfile);
                        }else{
                            $delfilenum=1;
                        }
                        $sql = "DELETE FROM media WHERE ID='$value'";
                        $result = mysqli_query($sqli,$sql);
                        if ($result && $delfilenum) {
                            include_once("msg/succ_msg.php");
                        } else {//如果刪除失敗代表該資料在資料庫中為非法資料
                            include("msg/error_msg.php");
                            exit();
                        }


                    }

                } else {
                    include("msg/error_msg.php");
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
<p></p>
</body>
</html>