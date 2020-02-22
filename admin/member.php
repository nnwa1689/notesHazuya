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
//暫時放著
function identifyPw($username, $pw)
{
    global $sqli;
    $result = mysqli_query($sqli, "SELECT * FROM admin where username = '$username'");
    $userpw = mysqli_fetch_array($result);
    if ($userpw["Position"] == "on") {
        if (password_verify($pw, $userpw["pw"]))
            return true;
        else
            return false;
    } else {
        return 2;
    }

}
//****************************************
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <?php echo "<title>會員管理 - " . $mate[0] . "管理中心</title>"; ?>
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
<div id="tittle">會員管理</div>
<div id="right">
    <?PHP
    if ($_SESSION["username"] != null && $userlaw["Law_member"] == 1){
    ?>
    <br/>
    <?php
    if ($_POST["pwo"] == null){ ?>
    <form action="" method="post">
        <div id="rightcontant">
            <div class="card bg-light mb-3" style="width: 100%;">
                <div class="card-header"><i class="fas fa-users"></i>會員列表</div>
                <div class="card-body">
                    <p align="right">批次操作：<select name="editse">
                            <option selected="selected">Delete</option>
                            <option>Set on</option>
                            <option>Set off</option>
                            <option value="updatelaw">更新權限組</option>
                            <option value="crp">取消記住登入</option>
                        </select>
                    <table width="100%" border="0" cellpadding="2" cellspacing="5">
                        <tr>
                            <th width="5%" scope="row"><input name="all" type="checkbox"
                                                              onClick="check_all(this,'postid[]')"
                                                              value=""/></th>
                            <td width="20%">使用者帳號</td>
                            <td width="20%">暱稱</td>
                            <td width="10%">權限組</td>
                            <td width="5%">帳號狀態</td>
                            <td width="20%">最後登入IP</td>
                            <td width="20%">最後登入日期</td>
                        </tr>
                        <?php
                        $sqlmem = "SELECT * FROM admin";
                        $resultmem = mysqli_query($sqli,$sqlmem);
                        while ($user = mysqli_fetch_array($resultmem)) {
                            ?>
                            <tr>
                                <th scope="row" width="5%"><input name="postid[]" type="checkbox"
                                                                  value="<?php echo $user["username"]; ?>"/></th>
                                <td width="20%"><a href="../person.php?id=<?php echo ($user["username"]) ?>"
                                                   target="_blank"><?php echo $user["username"] ?> </a></td>
                                <td width="20%"><?php echo $user["Yourname"] ?></td>
                                <td width="10%">
                                    <select name="userlaw<?echo $user["username"]?>" id="userlaw<?echo $user["username"]?>">
                                        <?php $lawsql = mysqli_query($sqli,"SELECT * FROM userlaw");
                                        while ($resultlaw = mysqli_fetch_array($lawsql)): ?>
                                            <option value="<? echo $resultlaw["Law_ID"] ?>"><? echo $resultlaw["Lawname"] ?></option>

                                        <? endwhile; ?>
                                    </select>
                                    <script>
                                        $('#userlaw<?echo $user["username"]?> option[value=<?php echo $user["Permissions"] ?>]').attr('selected', 'selected');//自動取得目前設定值
                                    </script>
                                </td>

                                <td width="5%"><?php echo $user["Position"] ?></td>
                                <td width="20%"><?php echo $user["LastIPdata"] ?></td>
                                <td width="20%"><?php echo $user["LastDate"] ?></td>

                            </tr>
                        <?php } ?></table>
                    </br>
                    <div class="alert alert-danger" role="alert">
                        刪除用戶將會一併刪除用戶的回覆與文章，請謹慎使用。
                    </div>
                    <div class="alert alert-warning" role="alert">
                        基於安全性，本系統不得更改用戶密碼，如需更改用戶密碼，請至伺服器端修改，您也無法修改自己帳戶之資料。
                    </div>
                    <p>&nbsp;</p>
                    <br/>
                    <h3>請輸入密碼驗證您的身份:
                        <label for="pwo"></label>
                        <input type="password" name="pwo" id="pwo"/></h3>
                    <p>
                        <input name="editsub" type="submit" class="btn btn-primary" value="確認"/>
                    </p>
                </div>
            </div>
        </div>
    </form>
    </p>
    <p>
        <?php
        }
        else if (identifyPw($b,$_POST["pwo"])) {
            $editdoing = $_POST["editse"];
            if ($editdoing == "Delete") {
                $checkusername = $_POST["postid"];
                if (count($checkusername) > 0) {
                    foreach ($checkusername as $value) {
                        if ($value != $b) {
                            $sqlDELPOST = "DELETE FROM Blog WHERE UserID='$value'";
                            $sqlDELREPLY = "DELETE FROM reply WHERE ReplyUserID='$value'";
                            $sql = "DELETE FROM admin WHERE username='$value'";
                            $result = mysqli_query($sqli,$sql);
                            $resultDELPOST = mysqli_query($sqli,$sqlDELPOST);
                            $resultDELREPLY = mysqli_query($sqli,$sqlDELREPLY);
                            $resultDELFOLLOW=mysqli_query($sqli,"DELETE FROM followRelationship WHERE follow_UserName='$value' OR followed_UserName='$value'");
                            $resultDELmes=mysqli_query($sqli,"DELETE FROM Message WHERE CatcherId='$value'");

                        }
                    }
                    if ($result && $resultDELPOST && $resultDELREPLY && $resultDELFOLLOW && $resultDELmes) {
                        include("msg/succ_msg.php");

                    }
                } else {
                    include("msg/error_msg.php");
                }
            } else if ($editdoing == "Set on") {
                $checkusername = $_POST["postid"];

                if (count($checkusername) > 0) {
                    $position = "on";
                    foreach ($checkusername as $value) {
                        if ($value != $b) {
                            $sql = "UPDATE admin SET Position = '$position' WHERE `admin`.`username` = '$value'";
                            $result = mysqli_query($sqli,$sql);
                        }
                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }

            } else if ($editdoing == "Set off") {
                $checkusername = $_POST["postid"];

                if (count($checkusername) > 0) {
                    $position = "off";
                    foreach ($checkusername as $value) {
                        if ($value != $b) {
                            $sql = "UPDATE admin SET Position = '$position' WHERE `admin`.`username` = '$value'";
                            $result = mysqli_query($sqli,$sql);
                        }
                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }

            } else if ($editdoing == "Set administrator") {
                $checkusername = $_POST["postid"];

                if (count($checkusername) > 0) {
                    $permissions = "1";
                    foreach ($checkusername as $value) {
                        if ($value != $b) {
                            $sql = "UPDATE admin SET Permissions = '$permissions' WHERE `admin`.`username` = '$value'";
                            $result = mysqli_query($sqli,$sql);
                        }
                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }
            } else if ($editdoing == "crp") {
                $checkusername = $_POST["postid"];

                if (count($checkusername) > 0) {
                    foreach ($checkusername as $value) {
                        if ($value != $b) {
                            $sql = "UPDATE admin SET LoginToken = 'unrem' WHERE `admin`.`username` = '$value'";
                            $result = mysqli_query($sqli,$sql);
                        }
                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }


            }elseif($editdoing=="updatelaw"){
                $checkusername = $_POST["postid"];
                if (count($checkusername) > 0) {
                    foreach ($checkusername as $value) {
                        $laworg="userlaw".$value;
                        $law = $_POST[$laworg];
                        if ($value != $b) {
                            $sql = "UPDATE admin SET Permissions = '$law' WHERE `admin`.`username` = '$value'";
                            $result = mysqli_query($sqli,$sql);
                        }
                    }
                    if ($result) {
                        include("msg/succ_msg.php");
                    } else {
                        include("msg/error_msg.php");
                    }
                }
            }

        }
        } ?>
    </p>
    <p>&nbsp;</p>
</div>
</body>
</html>