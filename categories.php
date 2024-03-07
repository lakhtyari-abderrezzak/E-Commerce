<?php include("init.php"); ?>

    <div class="container">
        <h1 class="text-center">
            <?php echo str_replace('-', ' ',$_GET['name']); ?>
        </h1>
        <?php 
            $items = getItems($_GET['pageid']);
            foreach($items as $item) {
                echo '<p>'.$item['Name'].'</p>';
            }
        ?>
        
    </div>

<?php include($tpl . "footer.inc.php");
