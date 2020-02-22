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
    <?php echo "<title>首頁公告編輯 - " . $mate[0] . "管理中心</title>"; ?>
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
<div id="tittle">首頁公告編輯</div>
<div id="right">
    <?PHP
    if ($_SESSION["username"] != null && $userlaw["Law_web"]==1) {
    ?>
    <form action="" method="post">
        <?php if ($_GET["id"] != null) {
        $postId = $_GET["id"];
        $sql = "SELECT * FROM HomePost where PostId ='$postId'";
        $result = mysqli_query($sqli,$sql);
        $thispost = mysqli_fetch_row($result);
        ?>
        <p>
            <?php
            $selectdo = $_POST["doing"];
            if ($selectdo == null) {
            ?>
            <div id="editLeft">
        <p>
            <input name="title" id="title" class="form-control" type="text" size="50"
                   value="<?php print"$thispost[2]"; ?>"/>
        </p>
        <p>
            <script>
                tinymce.init({
                    language: 'zh_TW',
                    selector: 'textarea',
                    height: '600',
                    plugins: [
                        "advlist autolink lists link image charmap print preview hr anchor codesample",
                        "searchreplace wordcount visualblocks visualchars code fullscreen",
                        "insertdatetime media nonbreaking save table contextmenu directionality",
                        "emoticons template paste textcolor colorpicker textpattern imagetools",
                        "autoresize"
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
            <textarea name="cont" id="cont" cols="139" rows="30"
                      value=""><?php print"$thispost[4]"; ?></textarea>
        </p>

        <div class="alert alert-warning" role="alert">
            請在可視化模式中儲存，否則不會儲存任何變更!
        </div>
        <div class="alert alert-danger" role="alert">
            刪除本文請小心使用，一但按下&quot;保存編輯&quot;將不會再詢問。
        </div>
</div>

<div id="editRight">
    <a href="../upload.php?id=blog" target="_blank">上傳圖片</a><br/>
    <label for="Postlist"></label>
    POSTID：
    <label for="PID"></label>
    <input name="PID" id="bloginput" class="form-control" type="text" id="PID" value="<?php
    if ($postId != 0) {
        print $thispost[0];
    } else {
        print "發表後取得";
    } ?>" readonly="readonly"/>
    </p>
    <p>日期：
        <label for="date"></label>
        <input name="date" id="bloginput" class="form-control" type="text" id="date" value="<?php
        if ($postId != 0) {
            print"$thispost[3]";
        } else if ($postId == 0) {
            date_default_timezone_set('Asia/Taipei');
            print date("Y-m-d");
        }
        ?>" size="50"/></p>
    <p>閱讀權限：
        <label for="competance"></label>
        <select name="competance" id="competance">
            <?php if ($thispost[1] == "public") { ?>
                <option selected="selected">public</option>
                <option>private</option>
            <?php } else if ($thispost[1] == "private") {
                ?>
                <option>public</option>
                <option selected="selected">private</option>
            <?php } else { ?>
                <option selected="selected">public</option>
                <option>private</option>
            <?php } ?>
        </select>
    </p>
    <p>操作：
        <label for="doing"></label>
        <select name="doing" id="doing">
            <option>儲存本文</option>
            <?php
            if ($postId != 0) {
                print "<option>刪除本文</option>";
            }
            ?>
        </select>
    <p><a>
            <input type="submit" name="button" class="btn btn-primary" value="保存"/>
        </a>
    </p>
</div>
</form>

<?php
} else if ($selectdo == "刪除本文") {
    $sql = "DELETE FROM HomePost WHERE PostId='$postId'";
    $result = mysqli_query($sqli,$sql);
    if ($result) {
        include("msg/succ_msg.php");
    } else {
        include("msg/error_msg.php");
    }
} else if ($selectdo == "儲存本文") {
    if ($postId > 0) {
        $title = addslashes($_POST["title"]);
        $date = $_POST["date"];
        $comp = $_POST["competance"];
        $cont = addslashes($_POST["cont"]);
        $sql = "UPDATE HomePost SET Competence = '$comp',PostTittle='$title',PostDate='$date',PostContant='$cont' WHERE `HomePost`.`PostId` = $postId";
        $result = mysqli_query($sqli,$sql);
        if ($result) {
            include("msg/succ_msg.php");
        } else {
            include("msg/error_msg.php");
        }
    } else if ($postId == 0) {

        $title = addslashes($_POST["title"]);
        $date = $_POST["date"];
        $comp = $_POST["competance"];
        $cont = addslashes($_POST["cont"]);
        $sql = "INSERT INTO HomePost (Competence,PostTittle,PostDate,PostContant) VALUES ('$comp','$title','$date','$cont' )";
        $result = mysqli_query($sqli,$sql);
        if ($result) {
            include("msg/succ_msg.php");
        } else {
            include("msg/error_msg.php");
        }

    }
}


} ?>
<?php
} else {
    include("msg/error_msg.php");
    ?>
    <p>&nbsp;</p>
<?php } ?>
</div>
</body>
</html>