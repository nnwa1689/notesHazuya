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
    <?php echo "<title>頁面管理 - " . $mate[0] . "管理中心</title>"; ?>
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
<div id="tittle">頁面管理</div>
<div id="right">
    <?PHP
    if ($_SESSION["username"] != null && $userlaw["Law_page"]==1) {
        $sql = "SELECT * FROM Page";
        $result = mysqli_query($sqli,$sql);
        $sqlN = "SELECT * FROM Navigate ORDER BY `Navigate`.`NavigateId` ASC";//查詢網站導航
        $resultN = mysqli_query($sqli,$sqlN);
        $ListN = mysqli_num_rows($resultN);//建立導航資料筆數

        ?>
        <?php
        $editdoing = $_POST["editse"];
        if ($editdoing == null) { ?>

            <form action="" method="post">
            <div id="rightcontant">
            <div class="card bg-light mb-3" style="width: 100%;">
            <div class="card-header"><i class="fas fa-clone"></i>頁面管理</div>
            <div class="card-body">
            <p align="right">批次操作：<select name="editse">
                    <option selected="selected">Delete</option>
                    <option>Set Public</option>
                    <option>Set Private</option>
                </select>
                ｜<input class="btn btn-primary" name="editsub" type="submit" value="批次編輯"/>｜<a
                        href="pageedit.php?id=new">新增網站頁面</a></p>
            <table width="100%" border="0" cellpadding="2" cellspacing="5">
                <tr>
                    <th width="5%" scope="row"><input name="all" type="checkbox" onClick="check_all(this,'pageid[]')"
                                                      value=""/></th>
                    <td width="50%">ID/文章標題</td>
                    <td width="45%">頁面權限</td>
                </tr>
                <?php
                $List = mysqli_num_rows($result);
                for ($i = 0; $i < $List; $i++) {
                    $OPage = mysqli_fetch_assoc($result);
                    ?>
                    <tr>
                        <th scope="row" width="5%"><input name="pageid[]" type="checkbox"
                                                          value="<?php print $OPage["PageId"]; ?>"/></th>
                        <td width="50%"><a
                                    href="<?php print "pageedit.php?id=" . $OPage["PageId"] ?>"><?php print $OPage["PageName"] ?> </a>
                        </td>
                        <td width="45%"><a><?php print $OPage["Competence"] ?></a></td>
                    </tr>
                <?php } ?></table>

        <?php } elseif ($editdoing != null) { ?>
            <?php
            if ($editdoing == "Delete") {
                $PP = $_POST["pageid"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $sql = "DELETE FROM Page WHERE PageId='$value'";
                        $result = mysqli_query($sqli,$sql);

                        $serachN = "page.php?id=" . $value;
                        $searchSQL = "SELECT * FROM Navigate WHERE Navigate.URL='$serachN'";
                        $resultN = mysqli_query($sqli,$searchSQL);
                        $pageisSear = mysqli_fetch_row($resultN);

                        $sqlN = "DELETE FROM Navigate WHERE URL='$pageisSear[3]'";
                        $resultN = mysqli_query($sqli,$sqlN);
                    }

                    if ($result && $resultN) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                } else {
                    include("msg/error_msg.php");
                }
            } else if ($editdoing == "Set Public") {
                $PP = $_POST["pageid"];
                if (count($PP) > 0) {
                    $comp = "public";
                    foreach ($PP as $value) {
                        $sql = "UPDATE Page SET Competence = '$comp' WHERE `Page`.`PageId` = '$value'";
                        $result = mysqli_query($sqli,$sql);
                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }

            } else if ($editdoing == "Set Private") {
                $PP = $_POST["pageid"];

                if (count($PP) > 0) {
                    $comp = "private";
                    foreach ($PP as $value) {
                        $sql = "UPDATE Page SET Competence = '$comp' WHERE `Page`.`PageId` = '$value'";
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