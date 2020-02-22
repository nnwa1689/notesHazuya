<?php
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
    <?php echo "<title>頁面編輯 - " . $mate[0] . "管理中心</title>"; ?>
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

<div>
    <div id="leftnav"> <?php include_once("include/admin-nav.php") ?></div>
    <div id="tittle">頁面編輯</div>
    <div id="right">
        <?PHP
        if ($_SESSION["username"] != null && $userlaw["Law_page"] == 1){
        ?>
        <form action="" method="post">
            <p><?php if ($_GET["id"] != null){
                $pageId = $_GET["id"];
                $sql = "SELECT * FROM Page where PageId ='$pageId'";
                $result = mysqli_query($sqli, $sql);
                $thispage = mysqli_fetch_row($result);
                $sqlN = "SELECT * FROM Navigate ORDER BY `Navigate`.`NavigateId` ASC";//查詢網站導航
                $resultN = mysqli_query($sqli, $sqlN);
                $ListN = mysqli_num_rows($resultN);//建立導航資料筆數
                $serachN = "page?id=" . $thispage[0];
                $searchSQL = "SELECT * FROM Navigate WHERE Navigate.URL='$serachN'";
                $resultN = mysqli_query($sqli, $searchSQL);
                $pageisSear = mysqli_fetch_row($resultN); ?>  </p>
            <p>
                <?php
                $selectdo = $_POST["doing"];
                if ($selectdo == null){
                ?>
            <div id="editRight">
                <a href="../upload.php?id=blog" target="_blank">上傳圖片</a><br/>
                <label for="Pagelist"></label>
                PAGEID：
                <label for="PID"></label>
                <input name="PID" id="bloginput" class="form-control" type="text" id="PID" value="<?php
                print $thispage[0]; ?>">
            </p>
            <p>閱讀權限：
                <label for="competance"></label>
                <select name="competance" id="competance">
                    <?php if ($thispage[1] == "public") { ?>
                        <option selected="selected">public</option>
                        <option>private</option>
                    <?php } else if ($thispage[1] == "private") {
                        ?>
                        <option>public</option>
                        <option selected="selected">private</option>
                    <?php } else { ?>
                        <option selected="selected">public</option>
                        <option>private</option>
                    <?php } ?>

                </select>
            <p>操作：
                <label for="doing"></label>
                <select name="doing" id="doing">
                    <option>儲存本頁</option>
                    <?php
                    if (($pageId != "new") && ($pageId != "home")) {
                        print "<option>刪除本頁</option>";
                    }
                    ?>
                </select>
                <label for="doing"></label>
            <p><a>
                    <input type="submit" name="button" class="btn btn-primary" value="保存編輯"/>
                </a>
            </p>
            </p>
    </div>
    <div id="editLeft">
        <p><input name="title" id="title" class="form-control" type="text" size="50"
                  value="<?php print"$thispage[2]"; ?>"/>
        </p>
        <p>
            <script>
                tinymce.init({
                    language: 'zh_TW',
                    selector: 'textarea',
                    height: '500',
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
            <textarea name="cont" id="cont" cols="139" rows="30" value=""><?php print"$thispage[3]"; ?></textarea>
        </p>
        <div class="alert alert-warning" role="alert">
            請在可視化模式中儲存，否則不會儲存任何變更!
        </div>
        <div class="alert alert-danger" role="alert">
            刪除本文請小心使用，一但按下&quot;保存編輯&quot;將不會再詢問。
        </div>
    </div>
    </form>
</div>
<?php
}
else if ($selectdo == "刪除本頁") {

    $sqlN = "DELETE FROM Navigate WHERE URL='$pageisSear[3]'";
    $resultN = mysqli_query($sqli, $sqlN);
    $sql = "DELETE FROM Page WHERE PageId='$pageId'";
    $result = mysqli_query($sqli, $sql);
    if ($resultN && $result) {
        include("msg/succ_msg.php");
    } else {
        include("msg/error_msg.php");

    }
} else if ($selectdo == "儲存本頁") {

    if ($pageId != "new") {

        $title = addslashes($_POST["title"]);
        $comp = $_POST["competance"];
        $cont = addslashes($_POST["cont"]);
        $sql = "UPDATE Page SET Competence = '$comp',PageName='$title',PageContant='$cont' WHERE `Page`.`PageId` = '$pageId'";
        $result = mysqli_query($sqli, $sql);
        $sql_Name = "UPDATE Navigate SET NavigateName = '$title' WHERE `Navigate`.`URL` = '$pageisSear[3]'";
        $result_Name = mysqli_query($sqli, $sql_Name);
        if ($result && $result_Name) {
            include("msg/succ_msg.php");
        } else {
            include("msg/error_msg.php");
        }
    } else if ($pageId == "new") {
        $newpageId = $_POST["PID"];
        $title = addslashes($_POST["title"]);
        $comp = $_POST["competance"];
        $cont = addslashes($_POST["cont"]);
        $NewNID = $ListN + 1;

        $sql = "INSERT INTO Page (PageId,Competence,PageName,PageContant) VALUES ( '$newpageId' , '$comp','$title','$cont' )";
        $result = mysqli_query($sqli, $sql);
        $NURL = "page?id=" . $newpageId;
        $sqlN = "INSERT INTO Navigate (NavigateId,NavigateName,URL,Competence) VALUES ( '0','$title','$NURL','public' )";
        $resultN = mysqli_query($sqli, $sqlN);
        if ($result && $resultN) {
            include("msg/succ_msg.php");
        } else {
            include("msg/error_msg.php");
        }
    }
}
} ?>
<?php
}
else {
    include("msg/error_msg.php");
    ?>
    <p>&nbsp;</p>
<?php } ?>


</div>
</body>
</html>