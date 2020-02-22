<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <?php
    session_start();
    $pagefilename = basename(__FILE__, ".php");//取得本業面的黨
    include_once("SQLC.inc.php");
    $sql_web = "SELECT * FROM web ORDER BY `ID` ASC";
    $result_web = mysqli_query($sqli,$sql_web);
    $List_web = mysqli_num_rows($result_web);
    $mate = array("", "", "");
    for ($i = 0; $i < $List_web; $i++) {
        $search = mysqli_fetch_assoc($result_web);
        $mate[$i] = $search["tittle"];
    }
    session_start(); ?>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="icon" type="image/png" href="favicon.png"/>
        <?php echo "<title>" . $mate[0] . " - 登出</title>"; ?>
        <link href="hz_include/themes/def/Style.css" rel="stylesheet" type="text/css"/>
        <style type="text/css">
            body {
                border: none;
                font-family: NotoSansTC-Regular;
            }

        </style>

    </head>

<body>
<div id="logformout">
    <div id="logform">
        <p><img src="<?echo $mate[5]?>"></p>
        <?PHP
        if ($_SESSION["username"] != null) { ?>
            <br/>
            <?php
            $user = $_SESSION["username"];
            $sql = "UPDATE `admin` SET `LoginToken` = 'unrem' WHERE `admin`.`username` = '$user'";
            $result = mysqli_query($sqli,$sql);
            setcookie("t", "", 0);
            setcookie("u", "", 0);
            unset($_SESSION["username"]);?>
            <p style="font-size: 32px;"><i class="fas fa-check-circle"></i></p>
            <p>您已經登出，等候轉跳……</p>
            <meta http-equiv=REFRESH CONTENT=2;url=/>
        <?} else {
            header('Location:/ ');
            exit;
         } ?>

    </div>
</div>
</body>
</html>