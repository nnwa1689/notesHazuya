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
    <?php echo "<title>站內信廣播 - " . $mate[0] . "管理中心</title>"; ?>
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
<div id="tittle">站內信廣播</div>
<div id="right">
    <?PHP
    if ($userlaw["Law_web"] == 1) {
        $sender = "系統管理員";
        if ($_POST["title"] == null || $_POST["cont"] == null) {
            ?>
            <form action="" method="post">
            <div id="rightcontant">
            <div class="card bg-light mb-3" style="width: 100%;">
            <div class="card-header"><i class="fas fa-bullhorn"></i>站內信廣播</div>
            <div class="card-body">

            <p>本信主旨： <input name="title" type="text" value="<?php print $_GET["re"] ?>" size="50" maxlength="40"/></p>
            <p>收件用戶： 全體會員</p>
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
            $date = date("Y-m-d H:i:s");
            $cont = addslashes($_POST["cont"]);
            $title = addslashes($_POST["title"]);
            $sqlmem = "SELECT * FROM admin";
            $resultmem = mysqli_query($sqli,$sqlmem);
            $sendflag = true;
            while ($user = mysqli_fetch_array($resultmem)) {
                $value = $user["username"];
                $sendsql = "INSERT INTO `Message`(`MessageTitle`, `SenderId`, `CatcherId`, `MessageText`, `MessageTime`) VALUES 	
					('$title','$sender','$value','$cont','$date')";
                $resultsend = mysqli_query($sqli,$sendsql);
                if ($resultsend != true) {
                    $sendflag = false;
                }

            }
            if ($sendflag) { ?>
                <div id="msg">
                    <p><img src="../image/succ.png"></p>
                    <strong>發送成功!等候轉跳</strong>
                </div>
                <meta http-equiv=REFRESH CONTENT=3;url=msg.php>
                <?php

            } else { ?>
                <div id="msg">
                    <p><img src="../image/error.png"></p>
                    <strong>信件發送失敗!</strong>
                </div>
                <meta http-equiv=REFRESH CONTENT=4;url=msg.php>
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