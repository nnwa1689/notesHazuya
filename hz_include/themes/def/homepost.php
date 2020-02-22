<?php
//公告系統資料庫區--首頁用(完整公告列表hp)
//判斷是否開啟首頁公告
if ($mate[9] == "on") : ?>
    <div id="WhatNews"> 最新資訊</div>
    <?php
    $sql = "SELECT * FROM `HomePost` WHERE Competence='public' ORDER BY `HomePost`.`PostDate` DESC";
    $result = mysqli_query($sqli,$sql);
    $AllList = mysqli_num_rows($result);
    $List = $mate[8];//首頁公告顯數
    //mysqli_num_rows($result);//取得公告比數
    for ($i = 0; $i < $List; $i++) :
        $OPost = mysqli_fetch_assoc($result);
        if ($OPost["Competence"] == "public") ://輸出公告內容
            ?>
            <a href="hp?id=<?php print $OPost["PostId"] ?>">
                <div id="HP">
                    <div id="HPDate"><i class="fas fa-calendar-alt"></i><?php
                        $postdate = strtotime($OPost["PostDate"]);
                        print date("Y-m-d  ", $postdate) ?></div>
                    <div id="HPTittle"><?php print $OPost["PostTittle"] ?></div>
                </div>
            </a>
        <?php endif; ?>
    <?php endfor; ?>
    <?php
    if ($AllList > $List) : ?></a>
        <p align="right"><a href="hp">More</a></p>
    <?php endif; ?>
<?php endif; ?>
