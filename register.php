<!DOCTYPE html>
<html lang="zh-tw">
<head>
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
    if ($mate[17] != "on") {
        header('Location: fix.php');
        exit;
    }
    session_start(); ?>
    <link href="hz_include/common/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css"/>
    <link href="hz_include/common/SpryValidationConfirm.css" rel="stylesheet" type="text/css"/>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="icon" type="image/png" href="favicon.png"/>
        <?php echo "<title>" . $mate[0] . " - 註冊</title>"; ?>
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
<div id="resformout">
    <div id="resform">
        <p><a href="/"><img src="<?echo $mate[5]?>"></a></p>
        <br>
        <?PHP
        $sql = "SELECT * FROM web where ID='12'";
        $result = mysqli_query($sqli,$sql);
        $restf = mysqli_fetch_row($result);
        if ($restf[1] == "on") {
            if ($_SESSION["username"] != null) {
                header('Location: /');
                exit;
            }
            $error = 0;

            if ($_POST["username"] != null && $_POST["password"] != null) {
                if (isset($_POST['g-recaptcha-response'])) {
                    // 建立一個命名空間
                    $recaptcha = new \ReCaptcha\ReCaptcha($secret,new \ReCaptcha\RequestMethod\CurlPost());
                    // 將 recaptcha->verify 的值給 resp 變數
                    $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                    if (($resp->isSuccess() == true && $mode===1)||$mode===0) {
                        $id =htmlentities(addslashes($_POST["username"]),3,"UTF-8");
                        $pw = hashpw(htmlentities(addslashes($_POST["password"]),3,"UTF-8"));
                        $mail =htmlentities(addslashes($_POST["email"]),3,"UTF-8");
                        $yourname = htmlentities(addslashes($_POST["yourname"]),3,"UTF-8");
                        $introductionSelf = "這個人太懶了，還沒有更新自己的簡介！";
                        $competence = "public";

                        $sqluserright = "SELECT * FROM web where ID='16'";
                        $resultuserright = mysqli_query($sqli,$sqluserright);
                        $userrightROW = mysqli_fetch_row($resultuserright);
                        $userright = $userrightROW[1];

                        $sqlsear = "SELECT * FROM admin WHERE username='$id'";
                        $resultsear = mysqli_query($sqli,$sqlsear);
                        $idCount = mysqli_num_rows($resultsear);
                        if ($_POST["repassword"] == $_POST["password"]) {
                            if ($idCount == 0) {
                                if (preg_match("/^([0-9A-Za-z]+)$/", $id) && preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $mail)) {
                                    $sql = "INSERT INTO admin (username,pw,Email,Permissions,Position,Yourname,IntroductionSelf,Competence,EmailShow,URLshow,SignatureShow) VALUES ('$id','$pw','$mail','$userright','on','$yourname','$introductionSelf','$competence','hide','hide',0)";
                                    $result = mysqli_query($sqli,$sql);
                                } else {
                                    $result = false;
                                }
                                if ($result) {
                                    include_once("hz_include/themes/def/msg.php");
                                    printmsg("suc","註冊成功，請等候轉跳");?>
                                    <meta http-equiv=REFRESH CONTENT=3;url=login>
                                    <?
                                } else {
                                    $error=4;
                                }
                            } else {
                                $error=1;
                            }
                        } else {
                            $error=2;
                        }

                    } else {
                        $error=3;
                    }
                } else {
                    echo "錯誤，請聯絡管理員";
                }

            }

            if ($_POST["username"] == null || $_POST["password"] == null || ($_POST["username"] != null && $_POST["password"] != null && $error != 0)) {
                if ($error == 1):?>
                    <div class="alert alert-danger" role="alert">該帳號已經存在！</div>
                <? elseif ($error == 2): ?>
                    <div class="alert alert-danger" role="alert">您的兩次密碼有誤，請重新輸入</div>
                <? elseif ($error == 3): ?>
                    <div class="alert alert-danger" role="alert">請勾選"我不是機器人"</div>
                <? elseif ($error == 4): ?>
                    <div class="alert alert-danger" role="alert">請聯絡管理員</div>
                <? endif; ?>
                <form action="" method="post" name="pw">
                    <p>
                        <label for="username"><i class="fas fa-user"></i></label>
                        <span id="sprytextfield2">
    <input name="username" type="text" maxlength="15" class="form-control" placeholder="使用者帳號" aria-label="username"
           onblur="value=value.replace(/[^\w\.\/]/ig,'')" onkeyup="value=value.replace(/[^\w\.\/]/ig,'')"/>
    <span class="textfieldRequiredMsg">＊</span><span class="textfieldMinCharsMsg">未達到字元數目的最小值。</span><span
                                    class="textfieldMaxCharsMsg">已超出字元數目的最大值。</span></span></p>
                    <p><span id="sprytextfield4">
      <label for="yourname"><i class="fas fa-user-circle"></i></label>
      <input type="text" name="yourname" class="form-control" maxlength="10" placeholder="顯示名稱" aria-label="name"/>
      <span class="textfieldRequiredMsg">＊</span></span></p>
                    <p><span id="sprytextfield1">
  <label for="email"><i class="fas fa-at"></i></label>
  <input type="text" name="email" class="form-control" placeholder="信箱" aria-label="email"/>
  <span class="textfieldRequiredMsg">＊</span><br><span class="textfieldInvalidFormatMsg">不正確的電子信箱</span></span>
                    <p>
                        <label for="password"><i class="fas fa-lock"></i> </label>
                        <span id="sprytextfield3">
	<input name="password" type="password" class="form-control" placeholder="使用者密碼" aria-label="pw"/>
	<span class="textfieldRequiredMsg">＊</span><br><span class="textfieldMinCharsMsg">密碼長度有問題</span><span
                                    class="textfieldMaxCharsMsg">密碼長度有問題</span></span>
                    </p>
                    <p>
	<label for="repassword"><i class="fas fa-lock"></i></label>
	<input type="password" name="repassword" class="form-control" placeholder="確認密碼" aria-label="rpw"/>
                    </p>
                    <p>
                        <?php
                        $sqluserlaw = "SELECT * FROM web where ID='14'";
                        $resultuserlaw = mysqli_query($sqli,$sqluserlaw);
                        $userlawYN = mysqli_fetch_row($resultuserlaw);
                        if ($userlawYN[1] == "Yes"){
                        ?>
                    <p>按下註冊代表您同意本網站的<a href="userlaw" target="_blank">使用者條款</a></p>
                    <?php } ?>
                    <div id="recaptcha">
                        <div class="g-recaptcha" data-sitekey="6LfDakIUAAAAAB2htHJvZLPkjlTFr5reRyZJpdLJ"></div>
                    </div>
                    <p>
                        <input class="btn btn-primary" name="sub" type="submit" value="註冊"/>
                    </p>
                </form>
                <?php
            }
        } else {
            echo "<p>未開放註冊</p>";
        }
        ?>

    </div>
</div>
<script src="hz_include/common/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="hz_include/common/SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script type="text/javascript">
    var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1", "email");
    var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2", "none");
    var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3", "none", {minChars: 6, maxChars: 255});
    var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "none");
</script>


</body>
</html>