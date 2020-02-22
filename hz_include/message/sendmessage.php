<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/5/17
 * Time: 下午 04:40
 */
function sendMessageUI($b)
{

    $sender = $b;
    if (preg_match("/^([0-9A-Za-z\x7f-\xff]+)$/", $_GET["catcherid"]))
        $catcher = $_GET["catcherid"];
    if ($_POST["title"] == null || $_POST["cont"] == null) { ?>
        <p style="font-size: 30px;"><i class="fas fa-share-square"></i>&nbsp;發送信件</p>
        <form action="" method="post">
        <p>主旨： <input name="title" style="width: 80%;" class="form-control" placeholder="主旨" type="text"
                      value="<?php echo $_GET["re"] ?>" maxlength="40"></p>
        <p>收件： <input name="catcher" type="text" style="width: 80%;" class="form-control"
                      placeholder="收件人，多人請用半形逗號隔開" value="<?php echo $catcher ?>" size="50"
                      maxlength="100"></p>
        <p>內容：
            <script>
                tinymce.init({
                    language: 'zh_TW',
                    selector: 'textarea',
                    height: '600',
                    statusbar: false,
                    plugins: [
                        "advlist autolink lists link image charmap print preview hr anchor codesample",
                        "searchreplace wordcount visualblocks visualchars code fullscreen",
                        "insertdatetime media nonbreaking save table contextmenu directionality",
                        "emoticons template paste textcolor colorpicker textpattern imagetools"
                    ],
                    toolbar1: "insertfile undo redo | bold italic underline strikethrough subscript superscript | forecolor backcolor | link unlink image media | table pagebreak blockquote codesample",
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
            請在可視化模式中儲存，否則不會儲存任何變更!
        </div>
        <div class="alert alert-warning" role="alert">
            若按下送信鈕沒有反應，請確認主旨與內容均有內容。
        </div>
    <?php } elseif ($_POST["title"] != null && $_POST["cont"] != null && $_POST["catcher"] != null) {
        date_default_timezone_set('Asia/Taipei');
        $date = date("Y-m-d H:i:s");
        $cont = $_POST["cont"];
        $title = $_POST["title"];
        $catchers = $_POST["catcher"];
        $sendflag = true;
        foreach (explode(",", $catchers) as $value) {
            if ($sendedflag[$value] != true) {//是否重複發送
                $resultsend = sendMessage($title, $date, $cont, $value, $sender);
                $sendedflag[$value] = true;//是否已經發送過
                if ($resultsend != true) {
                    $sendflag = false;
                }
            } else {
                $sendflag = false;
            }
        }
        if ($sendflag) {
            printmsg(suc, '發送成功，等候轉跳'); ?>
            <meta http-equiv=REFRESH CONTENT=3;url=message>
            <?php
        } else {
            printmsg(error, '<strong>部分信件發送失敗!可能原因:</strong><br><p>1.收件者不存在</p><p>2.逗號分隔錯誤</p><p>3.收件者有自己</p>'); ?>
            <meta http-equiv=REFRESH CONTENT=4;url=message>
            <?php
        }
    }
    ?>
    </form>
    <?
}

?>