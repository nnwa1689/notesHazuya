<?php
$pagefilename = basename(__FILE__, ".php");//取得本業面的
include_once("SQLC.inc.php");
if (preg_match("/^([0-9]+)$/", $_GET["id"])) {
    include_once("hz_include/class-postList.php");
    $Nowid = $_GET["id"];
    $thishp = new post($Nowid, "homepost");
}
include_once("hz_include/themes/def/header.php") ?>
<body>
<div id="top">
    <div class="top_contant"><?php include("hz_include/themes/def/header_top.php") ?></div>
</div>
<?include_once ("hz_include/themes/def/breadtraiil.php") ?>
<div id="contant">
    <?PHP
    $sql = "SELECT * FROM `HomePost` WHERE Competence='public' ORDER BY `HomePost`.`PostDate` DESC";
    $per = $mate[6];//每頁數量
    include("hz_include/themes/def/pagenum.php"); ?>
    <?php
    if ($Nowid == null): ?>
    <div id="PageTitle">
        公告<br>
    </div>
    <form action="" method="post">
        <?php
        while ($OPost = mysqli_fetch_array($result)) : ?>
            <a href="hp?id=<?php echo $OPost["PostId"] ?>">
                <div id="HP">
                    <div id="HPDate"><i class="fas fa-calendar-alt"></i><?php
                        $postdate = strtotime($OPost["PostDate"]);
                        echo date("Y-m-d  ", $postdate) ?></div>
                    <div id="HPTittle"><?php echo $OPost["PostTittle"] ?></div>
                </div>
            </a>
        <?php endwhile; ?>
        <div id="pageBotton">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <?php
                for ($i = 1; $i <= $pages; $i++) :
                    if ($_GET["page"] == null) {
                        $nowpagenum = 1;
                    } else {
                        $nowpagenum = $_GET["page"];
                    }
                    if ($nowpagenum == $i) :?>
                        <li class="page-item active"><a class="page-link"><?php echo $i ?></a></li>
                    <?php else : ?>
                        <li class="page-item"><a class="page-link"
                                                 href="<?php echo "?page=" . $i ?>"><?php echo $i ?></a></li>
                    <?php endif;
                endfor; ?>
            </ul>
        </nav>
        </div>
        <?php
        elseif ($Nowid != null && $thishp->competence=="public") : ?>
            <i class="fas fa-calendar-alt"></i><?php echo "發布日期：" . $thishp->postdate ?>
            <div id="postTitle">
                <?php echo $thishp->posttitle ?>
            </div>
            <?php echo $thishp->postcontant;
        endif; ?>
    </form>
</div>
<div id="copyr">
    <?php include_once("hz_include/themes/def/footer.php") ?>
</div>
</body>
</html>
