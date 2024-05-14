<?php
    ob_start();
    session_start();
    $pageTitle = 'Create New Ad';
    include("init.php");
    if(isset($_SESSION['user'])){


    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $formErrors = array();

        $name       = html_entity_decode($_POST['name']);
        $desc       = html_entity_decode($_POST['description']);
        $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country    = html_entity_decode($_POST['made']);
        $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $category   = filter_var($_POST['categories'], FILTER_SANITIZE_NUMBER_INT); 


        if(strlen($name) < 4 ){
            $formErrors [] = "Name Of Item Can't Be Less Then 4 Characters ";
        }
        if(empty($price) || empty($price) || empty($price)){
            $formErrors[] = "This Feild Shouldn't Be Left Empty";
        }
        //Check if Errors Are Empty Then Send The Data To DB
        if (empty ($formErrors)){
            $stmt = $conn->prepare("INSERT INTO items (`Name`, `Description`, Price, Add_Date, Made_In, `Status`, Cat_Id, Member_Id) VALUES (?,?,?,now(),?,?,?,?);");
            $stmt->execute(array($name, $desc, $price, $country, $status, $category, $_SESSION['uid']));
            if ($stmt){
                echo "Successfully Added An Item";
            }
        }
     }

?>
    <h1 class="text-center"><?php echo $pageTitle ?></h1>
    <div class="creat-ad block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $pageTitle ?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" class="form-horizontal new-ad" method="POST">
                                <!-- start name of Item  -->
                                <div class="form-group form-group-lg">
                                    <lable class="col-sm-2 control-label">Name</lable>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" name="name"  class="form-control live"
                                            placeholder="Enter Valid Item Name" data-class="live-title" required="required">
                                    </div>
                                </div>
                                <!-- End name of Item  -->
                                <input type="hidden" name="ItemId" value="<?php echo $row['ID'] ?>">
                                <!-- Start Description of Item  -->
                                <div class="form-group form-group-lg">
                                    <lable class="col-sm-2 control-label">Description</lable>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" class="form-control live" name="description" required="required"
                                            placeholder="Enter a Description for Your Product" data-class="live-desc">
                                    </div>
                                </div>
                                <!-- End Description of Item  -->
                                <!-- Start Price of Item  -->
                                <div class="form-group form-group-lg">
                                    <lable class="col-sm-2 control-label">Price</lable>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" class="form-control live" name="price" required="required" placeholder="Price Here" data-class="live-price">
                                    </div>
                                </div>
                                <!-- End Price of Item  -->
                                <!-- Start Made In of Item  -->
                                <div class="form-group form-group-lg">
                                    <lable class="col-sm-2 control-label">Made In?</lable>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="text" class="form-control" name="made" required="required" placeholder="Made In ..?">
                                    </div>
                                </div>
                                <!-- End Made In of Item  -->
                                <!-- Start Status In of Item  -->
                                <div class="form-group form-group-lg">
                                    <lable class="col-sm-2 control-label">Status</lable>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="status" class="" id="">
                                            <option value="0">...</option>
                                            <option value="1">New</option>
                                            <option value="2">Like New</option>
                                            <option value="3">Used</option>
                                            <option value="4">Old</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- End Status of Item  -->
                                
                                <!-- Start Categories  -->
                                <div class="form-group form-group-lg">
                                    <lable class="col-sm-2 control-label">Categories</lable>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="categories" required='required'>
                                            <option value="0">...</option>

                                            <?php
                                            $stmt = $conn->prepare('SELECT * FROM categories');
                                            $stmt->execute();
                                            $users = $stmt->fetchAll();
                                            foreach ($users as $user) {
                                                echo '<option value="' . $user['ID'] . '">' . $user['Name'] . '</option>';
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <!-- End Categories -->
                                <!-- start Submit  -->
                                <div class="form-group form-group-lg">
                                    <div class="col-sm-offset-2 col-md-10">
                                        <input type="submit" name="add" value="Add Item" class="btn btn-primary btn-sm text-center">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail item-box live-preview">
                                <span class="price-tag">
                                    $<span class="live-price"></span>
                                </span>
                                <img src="pair-trainers.jpg" alt="" class="img-responsive">
                                <div class="caption">
                                    <h3 class="live-title">Title</h3>
                                    <p class="live-desc">Description</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        foreach($formErrors as $error){
                            echo "<div class='alert alert-danger text-center'>" . $error . "</div> ";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    header("Location: login.php");
    exit();
}
include($tpl . "footer.inc.php");