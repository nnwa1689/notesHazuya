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
    <?php echo "<title>站內信管理 - " . $mate[0] . "管理中心</title>"; ?>
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
<div id="tittle">站內信</div>
<div id="right">
    <?PHP
    if ($_SESSION["username"] != null && $userlaw["Law_msg"]==1){
    $sql = "SELECT * FROM Message ORDER BY Message.MessageTime DESC";
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

    $editdoing = $_POST["editse"];
    if ($editdoing == null){ ?>
    <form action="" method="post">
        <p align="right">批次操作：<select name="editse">
                <option value="del" selected="selected">Del</option>
            </select>
            <input class="btn btn-primary" name="editsub" type="submit" value="批次編輯"/>
        <table width="100%" border="0" cellpadding="2" cellspacing="5">
            <tr>
                <th width="5%" scope="row"><input name="all" type="checkbox" onClick="check_all(this,'msgid[]')"
                                                  value=""/></th>
                <td width="35%">訊息主旨</td>
                <td width="20%">送件者</td>
                <td width="20%">接收者</td>
                <td width="20%">發送日期</td>
            </tr>
            <?php while ($msg = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <th scope="row" width="5%"><input name="msgid[]" type="checkbox"
                                                      value="<?php print $msg["MessageId"]; ?>"/></th>
                    <td width="35%"><?php print $msg["MessageTitle"] ?></td>
                    <td width="20%"><?php print $msg["SenderId"] ?></td>
                    <td width="20%"><?php print $msg["CatcherId"] ?></td>
                    <td width="20%"><?php print $msg["MessageTime"] ?></td>
                </tr>
            <?php } ?>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $pages; $i++) { ?>
                    <li class="page-item"><a class="page-link" href="<?php print "?page=" . $i ?>"><?php print $i ?></a>
                    </li>
                <?php } ?>
            </ul>
        </nav>
        <?php }
        else if ($editdoing == "del") {
            $PP = $_POST["msgid"];
            if (count($PP) > 0) {
                foreach ($PP as $value) {
                    $sql = "DELETE FROM Message WHERE MessageId='$value'";
                    $result = mysqli_query($sqli,$sql);
                }
                if ($result) { ?>

                    <p><img src="../image/succ.png"></p>
                    <strong>刪除成功!等候轉跳</strong>

                    <meta http-equiv=REFRESH CONTENT=1;url=msg.php>

                <?php }
            } else { ?>

                <p><img src="../image/error.png"></p>
                <strong>刪除失敗，等候轉跳</strong>

                <meta http-equiv=REFRESH CONTENT=3;url=msg.php>
            <?php }
        }
        }
        ?>
    </form>
    <p>&nbsp;</p>

</div>
</body>
</html>