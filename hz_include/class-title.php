<?php
/****************************
 * -->title標題判別模組
 * -->設計:hazuya
 * -->2018/2/10
 ****************************/

/**************************
 * ->標題判別依照檔案名稱判定
 * 請在葉面加入:
 * $pagefilename=basename(__FILE__,".php");
 *
 * 變數:$pagefilename
 ***************************/
if ($pagefilename == "blog") { //文章頁面
    if ($_GET["id"] != null && $thispost->competence != "private") {    //確認文章非空或隱藏?>
        <title><?php echo $thispost->posttitle . " - " . $mate[0]; ?></title>
        <?php
    } else {
        ?>
        <title><?php echo "文章 - " . $mate[0]; ?></title>
        <?php
    }
} elseif ($pagefilename == "index") { //首頁?>
    <title><?php echo $mate[0]; ?></title>
    <?php
} else if ($pagefilename == "hp") { //公告
    if ($_GET["id"] != null) {
        ?>
        <title><?php echo $thishp->posttitle . " - 公告 - " . $mate[0]; ?></title>
        <?php
    } else {
        ?>
        <title><?php echo "公告 - " . $mate[0]; ?></title>
        <?php
    }
} else if ($pagefilename == "upload") { //上傳?>
    <title><?php echo "上傳 - " . $mate[0]; ?></title>
    <?php
} elseif ($pagefilename == "category") { //文章依照分類?>
    <title><?php echo $ClassName . " - " . $mate[0]; ?></title>
    <?php
} elseif ($pagefilename == "person") { //個人資料?>
    <title><?php echo $perid . "  - " . $mate[0]; ?></title>
    <?php
} elseif ($pagefilename == "userlaw") { //使用者條款?>
    <title>網站使用者條款<?php echo " - " . $mate[0]; ?></title>
    <?php
} elseif ($pagefilename == "message") { //站內信?>
    <title>站內信<?php echo " - " . $mate[0]; ?></title>
    <?php
} else if ($pagefilename == "search") { //搜尋
    if ($_GET["q"] != null) {?>
        <title><?php echo $_GET["q"] . " - 文章搜尋結果 - " . $mate[0]; ?></title>
    <?php } else {
        ?>
        <title><?php echo "文章搜尋 - " . $mate[0]; ?></title>
        <?php
    }
} else if ($pagefilename == "setting") { //個人中心?>
    <title><?php echo "個人中心 - " . $mate[0]; ?></title>
    <?php
} else { //自訂頁面
    ?>
    <title><?php echo $List_P[2] . " - " . $mate[0]; ?></title>
    <?php
}
?>