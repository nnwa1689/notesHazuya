<!DOCTYPE html>
<html lang="zh-tw">
    <?php
    include_once('hz_include/ReCaptcha/src/autoload.php');
    $siteKey = '6LfDakIUAAAAAB2htHJvZLPkjlTFr5reRyZJpdLJ';
    $secret = '6LfDakIUAAAAADshIbvvpDgb2k8bXGzGXkekLgbN';
    // 語言 https://developers.google.com/recaptcha/docs/language
    $lang = 'zh-TW';
    // 初始化變數為空值
    $resp = '';
    $pagefilename = basename(__FILE__, ".php");//取得本業面的黨
    include_once("SQLC.inc.php");
    include_once("hz_include/class-userLoginToken.php");
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
        <?php echo "<title>" . $mate[0] . " - 登入</title>"; ?>
        <link href="hz_include/themes/def/Style.css" rel="stylesheet" type="text/css"/>
        <link href="hz_include/common/css/bootstrap.css" rel="stylesheet">
        <link href="hz_include/themes/def/fontawesome-all.css" rel="stylesheet">
        <script src='https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>'></script>

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
        <p><a href="/"><img src="<?echo $mate[5]?>"></a></p>
        <br>
        <?PHP
        if ($_SESSION["username"] != null) {
            header('Location: /');
            exit;
        }
        $error = 0;//錯誤代碼:1帳密錯誤、2帳戶關閉、3其他錯誤
        if ($_POST["username"] != null && $_POST["pw"] != null) {
            if (isset($_POST['g-recaptcha-response'])) {
                // 建立一個命名空間
                $recaptcha = new \ReCaptcha\ReCaptcha($secret,new \ReCaptcha\RequestMethod\CurlPost());
                // 將 recaptcha->verify 的值給 resp 變數
                $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if (($resp->isSuccess() && $mode===1)|| $mode===0) {
                    $id = addslashes($_POST["username"]);
                    if (!preg_match("/^([0-9A-Za-z]+)$/", $id)) {
                        echo "非法字符";
                        die();
                    }
                    $pw =htmlentities(addslashes($_POST["pw"]),3,"UTF-8");
                    $idenpw=identifyPw($id,$pw);
                    if ((bool)$idenpw===true) {
                        $error = 0; ?>
                        <p style="font-size: 32px;"><i class="fas fa-check-circle"></i></p>
                        <?php echo "登入成功，等候轉跳...";
                        $_SESSION["username"] = $id;

                        //先將所有可能的IP位址儲存，以供未來需要使用
                        $ipdata = $_SERVER['HTTP_CLIENT_IP'] . "/" . $_SERVER['HTTP_X_FORWARDED_FOR'] . "/" . $_SERVER['HTTP_X_FORWARDED'] . "/" . $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] . "/" . $_SERVER['HTTP_FORWARDED_FOR'] . "/" . $_SERVER['HTTP_FORWARDED'] . "/" . $_SERVER['REMOTE_ADDR'] . "/" . $_SERVER['HTTP_VIA'];
                        //更新IP資料至資料庫
                        $sqlIP = "UPDATE admin SET LastIPdata ='$ipdata' WHERE admin.username='$id'";
                        $resultIP = mysqli_query($sqli,$sqlIP);

                        //取得最後登入日期
                        date_default_timezone_set('Asia/Taipei');
                        $lastDate = date("Y-m-d H:i:s");
                        $sqlDate = "UPDATE admin SET LastDate ='$lastDate' WHERE admin.username='$id'";
                        $resultDate = mysqli_query($sqli,$sqlDate);

                        //檢查是否有勾選記住登入狀態
                        $rem = $_POST["remlog"];
                        if ($rem == "remlog") {
                            $newtoken = hash("sha256", madetoken());
                            $sql_rem = "UPDATE `admin` SET `LoginToken` = '$newtoken' WHERE `admin`.`username` = '$id'";
                            $result_rem = mysqli_query($sqli,$sql_rem);
                            if ($result_rem) {
                                setcookie("t", $newtoken, time() + 604800, "/");
                                setcookie("u", $id, time() + 604800, "/");
                            }

                        } else {

                            $sql_rem = "UPDATE `admin` SET `LoginToken` = 'unrem' WHERE `admin`.`username` = '$id'";
                            $result_rem = mysqli_query($sqli,$sql_rem);

                        }

                        echo "<meta http-equiv=REFRESH CONTENT=2;url=/>";
                    } else if ($idenpw==2) {
                        $error = 2;
                    } else {//帳號密碼錯誤
                        $error = 1;
                    }
                } else {
                    $error=3;
                }
            } else {
                echo "引數錯誤";
            }

        }

        if (($_POST["username"] == null || $_POST["pw"] == null) || ($_POST["username"] != null && $_POST["pw"] != null && $error != 0)) {
            if ($error == 1):?>
                <div class="alert alert-danger" role="alert">帳號或密碼錯誤！</div>
            <? elseif ($error == 2):?>
                <div class="alert alert-danger" role="alert">您的帳號已被關閉，請聯絡網站管理員！</div>
            <?elseif($error==3):?>
                <div class="alert alert-danger" role="alert">請勾選"我不是機器人"</div>
            <? endif; ?>
            <form action="" method="post" name="login">
                <p>
                    <label for="username"><i class="fas fa-user"></i></label>
                    <input name="username" type="text" maxlength="15" class="form-control" placeholder="使用者帳號"
                           aria-label="username"/>
                </p>
                <p>
                    <label for="pw"><i class="fas fa-lock"></i></label>
                    <input name="pw" type="password" maxlength="50" class="form-control" placeholder="使用者密碼"
                           aria-label="pw"/>
                </p>
                <p>
                <div class="custom-control custom-checkbox" style="width: 40%; margin-left: auto;margin-right: auto;">
                    <input id="relog" name="remlog" class="custom-control-input" type="checkbox" value="remlog"/>
                    <label class="custom-control-label" for="relog">記住我的登入狀態</label>
                </div>
                </p>
                <div id="recaptcha">
                    <div class="g-recaptcha" data-sitekey="6LfDakIUAAAAAB2htHJvZLPkjlTFr5reRyZJpdLJ"></div>
                </div>
                <p>
                    <input name="sub" type="submit" class="btn btn-primary" value="登入"/>
                </p>
            </form>
            <br/>
            <?php
            $sqlrestf = "SELECT * FROM web where ID='12'";
            $resultrestf = mysqli_query($sqli,$sqlrestf);
            $restf = mysqli_fetch_row($resultrestf);
            if ($restf[1] == "on") {
                echo "<a href=\"register\"><i class=\"fas fa-rss\"></i>註冊帳號</a>";
            }
        } ?>
        </p>
    </div>
</div>
</body>
</html>