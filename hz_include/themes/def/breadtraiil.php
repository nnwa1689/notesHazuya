<!--麵包屑導航-->
<div id="breadTraiil">
    <a id="breadlink" href="<?php echo $mate[13] ?>"><i class="fas fa-home"></i>首頁</a>
    <?php //文章
    if ($pagefilename == "blog"):
        if ($Nowid == null) :?>
            <i class="fas fa-angle-right"></i> 所有文章
        <?php
        elseif ($thispost->competence != "private") : ?>
            <i class="fas fa-angle-right"></i> <a id="breadlink" href="blog">所有文章</a> <i
                class="fas fa-angle-right"></i> <a id="breadlink"
                                                   href="category?classid=<?php echo $thispost->classid ?>&page=1"><?php echo $thispost->classname ?></a>
            <i class="fas fa-angle-right"></i> <?php echo $thispost->posttitle ?>
        <?php endif; ?>
    <?php
    //頁面
    elseif ($pagefilename == "page" && $pageId != null) : ?>
        <i class="fas fa-angle-right"></i> <?php echo $List_P[2] ?>
    <?php
    elseif ($pagefilename == "category") : ?>
        <i class="fas fa-angle-right"></i> <a id="breadlink" href="blog">所有文章</a> <i
            class="fas fa-angle-right"></i> <?php echo $ClassName ?>
    <?php
    elseif ($pagefilename == "person") : ?>
        <i class="fas fa-angle-right"></i> <?php echo $periddata[4] ?>的個人檔案
    <?php
    elseif ($pagefilename == "hp") :
        if ($Nowid != null) :?>
            <i class="fas fa-angle-right"></i> <a id="breadlink" href="hp">公告</a> <i
                class="fas fa-angle-right"></i> <?php echo $thishp->posttitle ?>
        <?php
        else : ?>
            <i class="fas fa-angle-right"></i><?php echo "公告"; ?>
        <?php endif; ?>
    <?php
    elseif ($pagefilename == "message"): ?>
        <i class="fas fa-angle-right"></i><?php echo "站內信";
    elseif ($pagefilename == "upload"):?>
        <i class="fas fa-angle-right"></i><?php echo "檔案上傳中心";
    elseif ($pagefilename == "userlaw"): ?>
        <i class="fas fa-angle-right"></i><?php echo "使用者條款";
    elseif ($pagefilename == "search"): ?>
        <i class="fas fa-angle-right"></i><?php echo "文章搜尋";
    elseif ($pagefilename == "setting"):?>
        <i class="fas fa-angle-right"></i><?php echo "個人中心" ?>
    <?php endif; ?>

</div>