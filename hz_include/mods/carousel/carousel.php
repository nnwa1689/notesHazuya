<script src="hz_include/common/js/jquery.js"></script>
<script src="hz_include/common/js/bootstrap.js"></script>
<?php
/*判斷是否被啟用*/
$sqlPosition = "SELECT * FROM Mods Where ModsName = 'carousel'";
$resultPosition = mysqli_query($sqli,$sqlPosition);
$position = mysqli_fetch_row($resultPosition);
if ($position[3] == "Enabled"){ ?>

<div id="container" class="container">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php
            /*判斷是否被啟用*/
            $sqlPosition = "SELECT * FROM Mods Where ModsName = 'carousel'";
            $resultPosition = mysqli_query($sqli,$sqlPosition);
            $position = mysqli_fetch_row($resultPosition);
            $sql = "SELECT * FROM Carousel ORDER BY `OrderNumber` ASC";
            $result = mysqli_query($sqli,$sql);
            $carousel = mysqli_fetch_array($result);
            $carCounts = mysqli_num_rows($result);
            ?>
            <!--第一個圖片的小圓點，設為預設驅動-->
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <?php
            //產生輪播第二個開始的導航小圓點
            for ($i = 1; $i < $carCounts; $i++) { ?>
                <li data-target="#myCarousel" data-slide-to="<?php echo $i ?>"></li>
            <?php } ?>
        </ol>
        <div class="carousel-inner">
            <!--第一個圖片，設為預設驅動-->
            <?php
            $sqlC1 = "SELECT * FROM Carousel Where OrderNumber='1'";
            $resultC1 = mysqli_query($sqli,$sqlC1);
            $car1 = mysqli_fetch_row($resultC1);
            ?>
            <!-- Wrapper for slides -->
            <div class="item active">
                <a href="<?php echo $car1[2] ?>"><img style="border-radius: 5px;" src="<?php echo $car1[1] ?>" alt="<?php echo $car1[4] ?>"
                                                       style="width:1040px;height:300px;"></a>
            </div>
            <?php
            //第二個圖片開使
            while ($carousel = mysqli_fetch_array($result)) {
                ?>
                <div class="item">
                    <a href="<?php echo $carousel["URL"] ?>"><img style="border-radius: 5px;" src="<?php echo $carousel["ImageURL"] ?>"
                                                                   alt="<?php echo $carousel["Name"] ?>"
                                                                   style="width:1040px;height:300px;"></a>
                </div>
            <?php }
            } ?>
        </div>
    </div>
</div>
