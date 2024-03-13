<?php include("init.php"); ?>

<div class="container">
    <h1 class="text-center">
        <?php echo str_replace('-', ' ', $_GET['name']); ?>
    </h1>
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
                                <h3>$item[Name]</h3>
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
