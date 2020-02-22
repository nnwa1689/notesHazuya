<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "include/PHPMailer/src/Exception.php";
require "include/PHPMailer/src/PHPMailer.php";
require "include/PHPMailer/src/SMTP.php";

session_start();
$pagefilename = basename(__FILE__, ".php");//取得本業面的黨名
include_once("../SQLC.inc.php");
$sql = "SELECT * FROM web ORDER BY `ID` ASC";
$result = mysqli_query($sqli, $sql);
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
    <?php echo "<title>Email廣播 - " . $mate[0] . "管理中心</title>"; ?>
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
<div id="tittle">Email廣播</div>
<div id="right">
    <?PHP
    if ($userlaw["Law_web"] == 1) {

        if ($_POST["title"] == null || $_POST["cont"] == null) {
            ?>
            <form action="" method="post">
            <div id="rightcontant">
            <div class="card bg-light mb-3" style="width: 100%;">
            <div class="card-header"><i class="fas fa-bullhorn"></i>Email廣播</div>
            <div class="card-body">

            <p>本信主旨： <input name="title" type="text" value="" size="50" maxlength="40"/></p>
            <p>條件收件： <select name="sendcondition">
                    <option value="0">全體會員</option>
                    <?php $lawsql = mysqli_query($sqli, "SELECT * FROM userlaw");
                    while ($resultlaw = mysqli_fetch_array($lawsql)): ?>
                        <option value="<? echo $resultlaw["Law_ID"] ?>"><? echo $resultlaw["Lawname"] ?></option>
                    <? endwhile; ?>
                    <option value="on">帳號狀態ON</option>
                    <option value="off">帳號狀態OFF</option>
                </select></p>
            <p>指定收件：<input name="catcher" type="text" value="" size="50" maxlength="40"/></p>
            <p>※條件與指定收件只能擇一，若指定收件不為空則以指定收件為主</p>
            <p>內容：
                <script>
                    tinymce.init({
                        language: 'zh_TW',
                        selector: 'textarea',
                        height: '500',
                        plugins: [
                            "advlist autolink lists link image charmap print preview hr anchor codesample",
                            "searchreplace wordcount visualblocks visualchars code fullscreen",
                            "insertdatetime media nonbreaking save table contextmenu directionality",
                            "emoticons template paste textcolor colorpicker textpattern imagetools"
                        ],
                        toolbar1: "insertfile undo redo | formatselect fontselect fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table hr pagebreak blockquote codesample",
                        toolbar2: "bold italic underline strikethrough subscript superscript | forecolor backcolor charmap emoticons | link unlink image media | cut copy paste | insertdatetime fullscreen code",
                        menubar: false,
                        image_advtab: true,
                        relative_urls: false,
                        convert_urls: false,
                    });
                </script>
                <label for="cont"></label>
                <textarea name="cont" id="cont" cols="100" rows="20" value=""></textarea>
            </p>
            <p><a>
                    <input type="submit" name="button" class="btn btn-primary" value="送信"/>
                </a>
            </p>
            <div class="alert alert-warning" role="alert">
                廣播不會保存寄件備份，重要資料請自行存留。
            </div>
            <div class="alert alert-warning" role="alert">
                請在可視化模式中儲存，否則不會儲存任何變更!
            </div>
            <div class="alert alert-warning" role="alert">
                若按下送信鈕沒有反應，請確認主旨與內容均有內容。
            </div>
        <?php } elseif ($_POST["title"] != null && $_POST["cont"] != null) {
            date_default_timezone_set('Asia/Taipei');
            $cont = $_POST["cont"];
            $title = addslashes($_POST["title"]);

            if ($_POST["catcher"]!=null) {
                $catcher = $_POST["catcher"];
                $sqlmem = "SELECT * FROM admin WHERE username='$catcher'";
                $resultmem = mysqli_query($sqli, $sqlmem);
                if (!(mysqli_num_rows($resultmem) > 0)) {
                    die("用戶不存在，請重新操作");
                }
            } else {
                $catcher = $_POST["sendcondition"];
                if ($catcher == 0) {
                    $sqlmem = "SELECT * FROM admin";
                } elseif ($catcher == "on") {
                    $sqlmem = "SELECT * FROM admin WHERE Position='$catcher'";
                }elseif ($catcher=="off"){
                    $sqlmem = "SELECT * FROM admin WHERE Position='$catcher'";
                }elseif($catcher>0){
                    $sqlmem = "SELECT * FROM admin WHERE Permissions='$catcher'";
                }
            }
            $resultmem = mysqli_query($sqli, $sqlmem);
            $sendflag = true;
            $sended=array();
            $mail=new PHPMailer();
            try{
                $mail->SMTPDebug=0;
                $mail->CharSet = 'UTF-8';
                $mail->isSMTP();
                $mail->Host="notes-hz.com";
                $mail->SMTPAuth=true;
                $mail->Username=$mate[19];
                $mail->Password=$db_passwd;//保持與DB同密碼以便管理
                $mail->SMTPSecure="ssl";
                $mail->Port=465;
                $mail->setFrom($mate[19],$mate[0]);
                while ($user = mysqli_fetch_array($resultmem)) {
                    $value = $user["username"];
                    $to=$user["Email"];
                    if($sended[$value]){

                    }else{
                        $mail->addBCC($to);
                        $sended[$value]=true;
                    }
                }
                $mail->Subject=$title;
                $mail->Body=$cont;
                $mail->isHTML(true);
                $mail->send();
                $sendflag=true;
            }catch (Exception $e){
                echo "Send Error<BR>";
                echo "Mailer Error:".$mail->ErrorInfo;
                $sendflag=false;
            }


            if ($sendflag) { ?>
                <div id="msg">
                    <p><img src="../image/succ.png"></p>
                    <strong>發送成功!等候轉跳</strong>
                </div>
                <meta http-equiv=REFRESH CONTENT=3;url=broadcast-mail>
                <?php

            } else {?>
                <div id="msg">
                    <p><img src="../image/error.png"></p>
                    <strong>信件發送失敗!</strong>
                </div>
                <meta http-equiv=REFRESH CONTENT=4;url=broadcast-mail>
                <?php
            }
        }
        ?>
        </div>
        </div>
        </div>
        </form>

        <p>&nbsp;</p>
    <?php } ?>
</div>
</body>
</html>