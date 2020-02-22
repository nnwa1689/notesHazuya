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
    <?php echo "<title>導航管理 - " . $mate[0] . "管理中心</title>"; ?>
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
<div id="tittle">導航管理</div>
<div id="right">
    <?PHP
    if ($_SESSION["username"] != null && $userlaw["Law_navigate"]==1) {
        $sql = "SELECT * FROM Navigate ORDER BY `Navigate`.`NavigateId` ASC";
        //This Sql have KEY:IndexId,but only change on PhpMyAdmin.
        //Navigate isn't KEY!
        $result = mysqli_query($sqli,$sql);
        ?>
        <br/>
        <?php
        $editdoing = $_POST["editse"];
        if ($editdoing == null) { ?>
            <form action="" method="post">
            <div id="rightcontant">
            <div class="card bg-light mb-3" style="width: 100%;">
            <div class="card-header"><i class="fas fa-bars"></i>導航管理</div>
            <div class="card-body">
            <p class="card-text">
            <div class="alert alert-dark" role="alert">
                批次操作均需要勾選項目，包含更新各導航之資料，且更改權限僅能透過批次更改
            </div>
            <br>
            <div class="alert alert-dark" role="alert">
                閱讀權限僅protect有效
            </div>
            <p align="right">批次操作：<select name="editse">
                    <option>Create New</option>
                    <option>Update</option>
                    <option>Delete</option>
                    <option>Set Public</option>
                    <option>Set Protect</option>
                    <option>Set Private</option>
                </select>
                ｜<input class="btn btn-primary" name="editsub" type="submit" value="批次編輯"/></p>
            <?php
            $List = mysqli_num_rows($result);
            for ($i = 0; $i < $List; $i++) {
                $ONav = mysqli_fetch_assoc($result);
                ?>
                <input name="indexid[]" type="checkbox" value="<?php print $ONav["IndexId"]; ?>"/>順序
                <input name="inputNavigateId[<?php print $ONav["IndexId"]; ?>]" type="text" size="5" maxlength="2"
                       value="<?php print $ONav["NavigateId"]; ?>"/>
                名稱
                <input name="inputNavigateName[<?php print $ONav["IndexId"]; ?>]" type="text" maxlength="10"
                       value="<?php print $ONav["NavigateName"]; ?>"/>
                URL
                <input name="inputURL[<?php print $ONav["IndexId"]; ?>]" type="text"
                       value="<?php print $ONav["URL"]; ?>"/>
                可用:<?php print $ONav["Competence"]; ?>
                | 閱讀權限:
                <input maxlength="3" size="5" name="inputAut[<?php print $ONav["IndexId"]; ?>]" type="text"
                       value="<?php print $ONav["ReadAut"]; ?>"/>
                <select name="type[<?php print $ONav["IndexId"]; ?>]" id="type">
                    <?php
                    if ($ONav["type"] == 0) {
                        ?>
                        <option value="0" selected="selected">頂部導航</option>
                        <option value="1">底部導航</option>
                    <?php } elseif ($ONav["type"] == 1) {
                        ?>
                        <option value="0">頂部導航</option>
                        <option value="1" selected="selected">底部導航</option>
                    <?php } ?>
                </select>
                <?php if ($ONav["type"] == 0) { ?>
                    <a href="nd_navigate.php?<?php print "name=" . $ONav["NavigateName"] . "&id=" . $ONav["IndexId"] ?>">&nbsp;修改子導航</a>
                <?php }
                print "<hr>";
            }
            ?>

            <input name="all" type="checkbox" onClick="check_all(this,'indexid[]')" value=""/>全選/全不選
            <br/>
            Create New(*每次只能新增一個，完成後請點上方的批次編輯送出)<br/>
            順序
            <input name="inputNavigateId[0]" type="text" size="5" maxlength="2" value=""/>
            名稱
            <input name="inputNavigateName[0]" type="text" value="" maxlength="10"/>
            URL
            <input name="inputURL[0]" type="text" value=""/>
            可用:
            <select name="competance" id="competance">
                <option selected="selected">public</option>
                <option>protect</option>
                <option>private</option>
            </select>
            | 閱讀權限:
            <input maxlength="3" size="5" name="inputAut[0]" type="text"
                   value=""/>
            <select name="type[0]" id="type">
                <option value="0" selected="selected">頂部導航</option>
                <option value="1">底部導航</option>
            </select>

            <?php
        } elseif ($editdoing != null) {
            ?>
            <?php
            if ($editdoing == "Delete") {
                $PP = $_POST["indexid"];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $sql = "DELETE FROM Navigate WHERE IndexId='$value'";
                        $result = mysqli_query($sqli,$sql);
                        $sql_nd = "DELETE FROM Nd_Navigate WHERE Nd_Navigate.St_id='$value'";
                        $result_nd = mysqli_query($sqli,$sql_nd);
                    }
                    if ($result && $result_nd) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }
            } else if ($editdoing == "Create New") {
                $List = mysqli_num_rows($result);
                $NindexId = $List + 1;
                $NIDP = $_POST["inputNavigateId"];
                $NNameP = $_POST["inputNavigateName"];
                $NNameP[0] = addslashes($NNameP[0]);
                $NURLP = $_POST["inputURL"];
                $comp = $_POST["competance"];
                $type = $_POST["type"];
                $aut=$_POST["inputAut"];
                $sql = "INSERT INTO Navigate (NavigateId,NavigateName,URL,Competence,type,ReadAut) VALUES ( '$NIDP[0]','$NNameP[0]','$NURLP[0]','$comp','$type[0]',$aut[0] )";
                $result = mysqli_query($sqli,$sql);
                if ($result) {
                    include("msg/succ_msg.php");
                } else {
                    include("msg/error_msg.php");
                }
            } else if ($editdoing == "Update") {
                $PP = $_POST["indexid"];
                $NIDP = $_POST["inputNavigateId"];
                $NNameP = $_POST["inputNavigateName"];
                $NURLP = $_POST["inputURL"];
                $type = $_POST["type"];
                $aut=$_POST["inputAut"];
                print $NIDP[0];
                if (count($PP) > 0) {
                    foreach ($PP as $value) {
                        $NNameP[$value] = addslashes($NNameP[$value]);
                        $sql_ID = "UPDATE Navigate SET NavigateId = '$NIDP[$value]' WHERE `Navigate`.`IndexId` = '$value'";
                        $result_ID = mysqli_query($sqli,$sql_ID);
                        $sql_NdnavSet = "UPDATE Nd_Navigate SET St_id = '$NNameP[$value]' WHERE `Nd_Navigate`.`NavigateName` = '$value'";
                        $result_NdnavSet = mysqli_query($sqli,$sql_NdnavSet);
                        $sql_Name = "UPDATE Navigate SET NavigateName = '$NNameP[$value]' WHERE `Navigate`.`IndexId` = '$value'";
                        $result_Name = mysqli_query($sqli,$sql_Name);
                        $sql_URL = "UPDATE Navigate SET URL = '$NURLP[$value]' WHERE `Navigate`.`IndexId` = '$value'";
                        $result_URL = mysqli_query($sqli,$sql_URL);
                        $sql_type = "UPDATE Navigate SET type = '$type[$value]' WHERE `Navigate`.`IndexId` = '$value'";
                        $result_type = mysqli_query($sqli,$sql_type);
                        $sqlaut=mysqli_query($sqli,"UPDATE Navigate SET ReadAut = '$aut[$value]' WHERE `Navigate`.`IndexId` = '$value'");

                    }
                    if ($result_ID && $result_Name && $result_URL && $result_type && $sqlaut) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }

            } else if ($editdoing == "Set Public") {
                $PP = $_POST["indexid"];
                if (count($PP) > 0) {
                    $comp = "public";
                    foreach ($PP as $value) {
                        $sql = "UPDATE Navigate SET Competence = '$comp' WHERE `Navigate`.`IndexId` = '$value'";
                        $result = mysqli_query($sqli,$sql);
                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }

            } else if ($editdoing == "Set Private") {
                $PP = $_POST["indexid"];

                if (count($PP) > 0) {
                    $comp = "private";
                    foreach ($PP as $value) {
                        $sql = "UPDATE Navigate SET Competence = '$comp' WHERE `Navigate`.`IndexId` = '$value'";
                        $result = mysqli_query($sqli,$sql);
                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }

            } else if ($editdoing == "Set Protect") {
                $PP = $_POST["indexid"];
                if (count($PP) > 0) {
                    $comp = "protect";
                    foreach ($PP as $value) {
                        $sql = "UPDATE Navigate SET Competence = '$comp' WHERE `Navigate`.`IndexId` = '$value'";
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

    <?php } ?>
    <p>&nbsp;</p>
</div>
</body>
</html>