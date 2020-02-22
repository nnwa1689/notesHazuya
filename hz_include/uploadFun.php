<?php
if ($_SESSION["username"] != null && $userlaw["Law_Upload"] == 1) {
    include_once("themes/def/msg.php");
    $fileis = $_POST['myFile'];
    $fileinfo = $_FILES['myFile'];
    if ($fileinfo['name'] != null) {
        //print_r ($fileinfo);
        $flag = true;//是否檢查圖檔
        $filetype = array('jpeg', 'jpg', 'gif', 'png', 'PNG');
        $maxsize = 3097152;
        $address = htmlentities($_GET["id"],3,"UTF-8");
        $ext = pathinfo($fileinfo['name'], PATHINFO_EXTENSION);
        $uniName = md5(uniqid(microtime(true), true)) . "." . $ext;
        if ($address == "logo") {//判斷上傳功能
            $uploadPath = 'image';
            $des = "uploadfile/logo/" . $uniName;
        } elseif ($address == "blog") {
            $des = "uploadfile/blog/" . $uniName;
            $sqlrec = true;
        } elseif ($address == "avatar") {
            $des = "uploadfile/avatar/" . $uniName;
        } elseif ($address == "perbg") {
            $des = "uploadfile/perbg/" . $uniName;
        }elseif ($address == "categoryImg"){
            $des = "uploadfile/classImg/" . $uniName;
        } else {
            exit("引數錯誤!");
        }

        if ($fileinfo['error'] > 0) {
            printmsg("error", "錯誤代碼" . $fileinfo['error']);
            exit();
        }
        if (!in_array($ext, $filetype)) {
            printmsg("error", "非法副檔名");
            exit();
        }

        /*if($flag && !getimagesize($fileinfo['tmp_name']));{
            include_once("admin/msg/error_msg.php");
            exit("檔案不是圖片檔案");
            }*/

        if ($fileinfo['size'] > $maxsize) {
            printmsg("error", "檔案超過大小限制!");
            exit();
        }

        /*if(!file_exists($uploatPath)){
            mkdir($uploadPath, 0777, true);
            }*/

        if (!move_uploaded_file($fileinfo['tmp_name'], $des)) {
            printmsg("error", "檔案從暫存區移動至資料夾失敗");
            exit();
        }
        if ($sqlrec) {
            //檔案上傳成功，寫入資料庫
            $filename = $uniName;
            $fileURL = "/" . $des;
            date_default_timezone_set('Asia/Taipei');
            $fileUploadDate = date("Y-m-d");
            $filecap = $fileinfo['size'];
            if (mysqli_query($sqli, "INSERT INTO media (Name,URL,UploadDate,Type,Cap) VALUES ('$filename','$fileURL','$fileUploadDate','$ext','$filecap')")) {
                printmsg("suc", "<p>檔案上傳成功，檔案位址：</p><p><a href=\"$des\" target=\"new\">$des</a></p>");
            }
        } else {
            printmsg("suc", "<p>檔案上傳成功，檔案位址：</p><p><a href=\"$des\" target=\"new\">$des</a></p>");
        }
    } else { ?>
        <div id="upload" style="margin-left: auto;margin-right: auto;width: 50%;text-align: center">
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
            <p><input class="btn btn-primary" type="file" name="myFile" accept="image/jpeg,image/jpg,image/gif,image/png,image/JPEG,image/JPG,image/GIF,image/PNG"></p>
            <p><input class="btn btn-primary" name="" type="submit" value="上傳"></p>
        </div>
        <p>
        <div class="alert alert-warning" role="alert">
            目前支援之圖檔：jpg、gif、png
        </div>
        </p>
        <?php
    }
} else {
    echo "權限不足";
} ?>