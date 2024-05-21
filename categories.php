<?php include("init.php"); ?>

<div class="container">
    <h1 class="text-center">Show Categories</h1>
    <div class="row">
        <?php
        $items = getItems('Cat_ID',$_GET['pageid']);
        foreach ($items as $item) {
            print <<<HTML
                    <div class="col-sm-6 col-md-3">
                        <div class="thumbnail item-box">
                            <span class="price-tag">$item[Price]</span>
                            <img src="pair-trainers.jpg" alt="" class="img-responsive">
                            <div class="caption">
                                <h3><a href='items.php?itemid=$item[Item_ID]'>$item[Name]</a></h3>
                                <p>$item[Description]</p>
                            </div>
                        </div>
                    </div>
                HTML;
        }
        ?>
    </div>
</div>

<?php include($tpl . "footer.inc.php");
