<?php
include_once("SQLC.inc.php");
include_once("hz_include/class-meta.php");
session_start();
if($mate[17]=="on"){
    header('Location: '.$mate[13]);
    exit;
}else {

    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>網站維護中</title>
    <link rel="icon" type="image/png" href="favicon.png"/>
    <link href="hz_include/themes/def/fontawesome-all.css" rel="stylesheet">
    <meta name="description" content="<?php print $mate[2]; ?>">
    <meta name="keywords" content="<?php print $mate[1]; ?>">
    <link href="hz_include/common/css/bootstrap.css" rel="stylesheet">
    <link href="hz_include/themes/def/Style.css" rel="stylesheet" type="text/css"/>
</head>
    <body>

    <div id="contant" name="contant">
        <div class="card bg-light mb-3" style="width: 100%;">
            <div class="card-header"><i class="fas fa-info-circle"></i>維護</div>
            <div class="card-body">
                <p style="text-align: center">
                    <a style="font-size: 50px; text-align: center"><i class="fas fa-info-circle"></i></a></p>
                <br>
                <p style="text-align: center">
                <a style="font-size: 30px; text-align: center">網站關閉中</a></p>
                <br>
                <p style="text-align: center">
                <a><?php print $mate[18] ?></a></p>
            </div>
        </div>

    </div>
    <?php
}
?>
    <p style="text-align: center"><?php print $mate[3]; ?><br/>Ver<?php print $ver ?></p>
    </body>
