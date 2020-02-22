<div id="manu">
    <div id="navContant">
        <ul class="drop-down-menu">
            <?php
            $sql = "SELECT * FROM Navigate WHERE type='0' ORDER BY Navigate.NavigateId";
            $result = mysqli_query($sqli, $sql);
            $List = mysqli_num_rows($result);
            while ($OPage = mysqli_fetch_assoc($result)) :
                if (($OPage["Competence"] == "public") || ($OPage["Competence"] == "protect" && $userlaw["Law_Read"] >= $OPage["ReadAut"] && $_SESSION["username"] != null)) :
                    //判斷是否當前頁面
                    if ($OPage["URL"] == $nowURL) : ?>
                        <li>
                        <a style="border-bottom-color: #0096ff; color: #444444;"><?php echo $OPage["NavigateName"]; ?></a>
                    <?php
                    else : ?>
                        <li>
                        <a href="<?php echo $OPage["URL"]; ?>"><?php echo $OPage["NavigateName"]; ?></a>
                    <?php endif; ?>
                    <ul>
                        <?php
                        $stid = $OPage["IndexId"];
                        $sqlNd = "SELECT * FROM Nd_Navigate WHERE Nd_Navigate.St_id='$stid' ORDER BY Nd_Navigate.NavigateId";
                        $resultNd = mysqli_query($sqli, $sqlNd);
                        while ($navNd = mysqli_fetch_assoc($resultNd)) :
                            if (($navNd["Competence"] == "public") || $navNd["Competence"] == "protect" && $userlaw["Law_Read"] >= $navNd["ReadAut"] && $_SESSION["username"] != null) : ?>
                                <a href="<?php echo $navNd["URL"] ?>"><?php echo $navNd["NavigateName"] ?></a>

                            <?php endif; ?>
                        <?php endwhile; ?>
                    </ul>
                    </li>
                <?php endif; ?>
            <?php endwhile; ?>
        </ul>
    </div>
</div>