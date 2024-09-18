<?php

session_start();
$pageTitle = "Categories";
if (isset($_SESSION["Username"])) {
    include("init.php");

    isset($_GET['do']) ? $do = $_GET['do'] : $do = 'Manage';

    if ($do == 'Manage') { // Manage Page 
        $sort = 'asc';
        $sort_array = array('asc', 'desc');
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        }

        $allCats = getAllFromAnyTable("*", "categories", "where parent = 0", "ordering" , $sort);
        
        $message = "<div class='record-message'>No Records Found</div>";
        if (!empty($allCats)) {
            ?>
            <h1 class="text-center">Manage Categories</h1>
            <div class="container  categories">
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa-solid fa-edit"></i> Mange Categories
                        <div class="option pull-right">
                            <i class="fa-solid fa-sort"></i> Order:[
                            <a class="<?php if ($sort == 'asc') {
                                echo "active";
                            } ?>" href="?sort=asc">Asc</a> |
                            <a class="<?php if ($sort == 'desc') {
                                echo "active";
                            } ?>" href="?sort=desc">Desc</a>
                            ]
                            <i class="fa-solid eye fa-eye"></i>View:[
                            <span class="active" data-view="full">Full View</span> |
                            <span data-view="classic">Classic</span>
                            ]
                        </div>

                    </div>
                    <div class="panel-body">
                        <?php
                        foreach ($allCats as $cats) {
                            echo '<div class="catego">';
                            echo '<div class="hidden-buttons">
                                    <a href="categories.php?do=Edit&CatID=' . $cats['ID'] . '" class="btn btn-xs btn-primary"><i class="fa-solid fa-edit"></i>Edit</a>
                                    <a href="categories.php?do=Delete&CatID=' . $cats['ID'] . '" class="btn btn-xs btn-danger confirm"><i class="fa-solid fa-close"></i>Delete</a>
                                </div>';
                            echo '<h3>' . $cats['Name'] . '</h3>';
                            echo '<div class="full-view">';
                            echo $cats['Description'] == '' ? '<p> No Discription </p>' : '<p>' . $cats['Description'] . '</p>';
                            if ($cats['Visibility'] == 0) {
                                echo ' <span class="visibility"><i class="fa-solid fa-eye-slash"></i> Hidden</span>';
                            }
                            if ($cats['Allow_Comments'] == 0) {
                                echo '<span class="comments">Comments Are Disable</span>';
                            }
                            if ($cats['Allow_Ads'] == 0) {
                                echo '<span class="adverts">Ads Are Disable</span>';
                            }
                            echo '</div>';
                            $childCats = getAllFromAnyTable("*", "categories", "where parent = {$cats['ID']}", "ordering", $sort);
                            if(!empty($childCats)){
                                echo '<ul class="child-cat">';
                                echo '<h4 class="cat-head">Child Categorie</h4>';
                                foreach($childCats as $childcat){?>
                                        <li class="show-btn">
                                            <?php echo $childcat['Name'] ?>
                                            <div class="buttons">
                                                <a href="categories.php?do=Edit&CatID=<?php echo $childcat['ID'] ?>" class="btn btn-primary">Edit</a>
                                                <a href="categories.php?do=Delete&CatID=<?php echo $childcat['ID'] ?>" class="btn btn-danger confirm">Delete</a>
                                            </div>
                                        </li>
                                <?php }
                                echo '</ul>';
                            }
                            echo '</div>';
                            
                            
                            echo '<hr>';
                        }
                        
                        ?>
                    </div>
                </div>
                <a href="?do=Add" class="btn btn-primary add-category"><i class="fa-solid fa-plus"> </i> Add Categories</a>
            </div>
            <?php
        } else {
            echo '<div class="container">';
            echo $message;
            echo ' <a href="?do=Add" class="btn btn-primary add-category"><i class="fa-solid fa-plus"> </i> Add Categories</a> ';
            echo '</div>';
        }

    } elseif ($do == "Add") { ?>
        <h1 class="text-center edit-members">Add New Category</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST">
                <!-- Start name Field  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Name</lable>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="name" class="form-control" placeholder="Enter Category Name"
                            required='required' autocomplete="off">
                    </div>
                </div>
                <!-- End name Field  -->
                <input type="hidden" name="userID" value="<?php echo $userID ?>">
                <!-- Start Description Field  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Discription</lable>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="description" class="pwd form-control" placeholder="Describe Category">
                    </div>
                </div>
                <!-- End Description Field  -->
                <!-- Start Parent Field  -->
                <div class="form-group form-group-lg">
                    <lable for="parent"class="col-sm-2 control-label">Parent?</lable>
                    <div class="col-sm-10 col-md-6">
                    <select name="parent" >
                        <option value="0">None</option>
                        <?php 
                        $allcats = getAllFromAnyTable("*", "categories", "where parent = 0", "ID",);
                        foreach($allcats as $cats){
                            echo '<option value="' . $cats['ID'] . '">' . $cats['Name'] . '</option>';
                        }
                        ?>
                    </select>
                    </div>
                </div>
                <!-- End Parent Field  -->
                <!-- Start Ordering Field  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Ordering</lable>
                    <div class="col-sm-10 col-md-6">
                        <input type="text" name="ordering" class="form-control"
                            placeholder="Enter Number To Arrange The Categories">
                    </div>
                </div>
                <!-- End Ordering Field  -->
                <!-- Start visibility Field  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Visibility</lable>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input type="radio" name="visibility" id="vis-yes" value="0" checked>
                            <label for="vis-yes">Visible</label>
                        </div>
                        <div>
                            <input type="radio" name="visibility" id="vis-no" value="1">
                            <label for="vis-no">Hidden</label>
                        </div>
                    </div>
                </div>
                <!-- End visibility Field  -->
                <!-- Start Comments Field  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Comments</lable>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input type="radio" name="comments" id="commnets-yes" value="1" checked>
                            <label for="comments-yes">Allowed</label>
                        </div>
                        <div>
                            <input type="radio" name="comments" id="commnets-no" value="0">
                            <label for="comments-no">Restricted</label>
                        </div>
                    </div>
                </div>
                <!-- End Comments Field  -->
                <!-- Start Adds Field  -->
                <div class="form-group form-group-lg">
                    <lable class="col-sm-2 control-label">Advertisement</lable>
                    <div class="col-sm-10 col-md-6">
                        <div>
                            <input type="radio" name="ads" id="ads-yes" value="1" checked>
                            <label for="ads-yes">Allowed</label>
                        </div>
                        <div>
                            <input type="radio" name="ads" id="ads-no" value="0">
                            <label for="ads-no">Restricted</label>
                        </div>
                    </div>
                </div>
                <!-- End Adds Field  -->
                <!-- Start Username Field  -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-md-10 ">
                        <input type="submit" name="add" value="Add Category" class="btn btn-primary btn-lg text-center">
                    </div>
                </div>
                <!-- End Username Field  -->

            </form>
            <?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            echo '<h1 class="text-center edit-members">Insert Category</h1>';
            echo '<div class="container">';

            $name = $_POST['name'];
            $description = $_POST['description'];
            $parent = $_POST['parent'];
            $ordering = $_POST['ordering'];
            $visibility = $_POST['visibility'];
            $comments = $_POST['comments'];
            $ads = $_POST['ads'];
            //check If Any Of The Feild Are Empty [Name]
            $check = checkUser('Name', 'categories', $name);
            if ($check == 1) {
                $msg = '<div class="alert alert-danger" >Category <b>' . $name . '</b> Already Exists In Data Base </div>';
                redierctHome($msg, 'back', 4);
            } else {
                // Insert Info Into Categories Table in Db
                $stmt = $conn->prepare("INSERT 
                                        INTO categories(`Name`, `Description`, parent, Ordering, Visibility, Allow_Comments, Allow_Ads) 
                                        VALUES (?,?,?,?,?,?,?) ");
                $stmt->execute(array($name, $description, $parent ,$ordering, $visibility, $comments, $ads));
                $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Category Added Successfully </div>';
                redierctHome($theMsg, 'back', 4);
            }
            echo '</div>';
        } else {
            echo "You Can't Enter This page This Page This Way";
        }

    } elseif ($do == 'Edit') {

        $catId = isset($_GET['CatID']) && is_numeric($_GET['CatID']) ? intval($_GET['CatID']) : 0;
        //check if ID Exist in data base
        $stmt = $conn->prepare('SELECT * FROM categories WHERE ID = ?  LIMIT 1;');
        $stmt->execute(array($catId));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) { ?>
                <h1 class="text-center edit-members">Edit Categories</h1>
                <div class="container">
                    <form class="form-horizontal" action="?do=Update" method="POST">
                        <input type="hidden" name="catID" value="<?php echo $row['ID'] ?>">
                        <!-- Start name Field  -->
                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Name</lable>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Enter Category Name"
                                    value="<?php echo $row['Name']; ?>" required='required' autocomplete="off">
                            </div>
                        </div>
                        <!-- End name Field  -->
                        <input type="hidden" name="userID" value="<?php echo $userID ?>">
                        <!-- Start Description Field  -->
                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Discription</lable>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="description" value="<?php echo $row['Description'] ?>"
                                    class="pwd form-control" placeholder="Describe Category">
                            </div>
                        </div>
                        <!-- End Description Field  -->
                        <!-- Start Parent Field  -->
                       
                        <div class="form-group form-group-lg">
                            <lable for="parent" class="col-sm-2 control-label">Parent?</lable>
                            <div class="col-sm-10 col-md-6">
                            <select name="parent" >
                                <option value="0">None</option>
                                <?php 
                                $allcats = getAllFromAnyTable("*", "categories", "where parent = 0", "ID",);
                                foreach($allcats as $cats){
                                    echo '<option value="' . $cats['ID'] . '"';
                                    if($row['parent'] === $cats['ID']){ echo 'selected';}
                                    echo '>' . $cats['Name'] . '</option>';
                                }
                                ?>
                            </select>
                            </div>
                        </div>
                        
                        <!-- End Parent Field  -->
                        <!-- Start Ordering Field  -->
                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Ordering</lable>
                            <div class="col-sm-10 col-md-6">
                                <input type="text" name="ordering" class="form-control" value="<?php echo $row['Ordering'] ?>"
                                    placeholder="Enter Number To Arrange The Categories">
                            </div>
                        </div>
                        <!-- End Ordering Field  -->
                        <!-- Start visibility Field  -->
                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Visibility</lable>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" name="visibility" id="vis-yes" value="1" <?php if ($row['Visibility'] == 1) {
                                        echo 'checked';
                                    } ?>>
                                    <label for="vis-yes">Visible</label>
                                </div>
                                <div>
                                    <input type="radio" name="visibility" id="vis-no" value="0" <?php if ($row['Visibility'] == 0) {
                                        echo 'checked';
                                    } ?>>
                                    <label for="vis-no">Hidden</label>
                                </div>
                            </div>
                        </div>
                        <!-- End visibility Field  -->
                        <!-- Start Comments Field  -->
                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Comments</lable>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" name="comments" id="commnets-yes" value="1" <?php if ($row['Allow_Comments'] == 0) {
                                        echo 'checked';
                                    } ?>>
                                    <label for="comments-yes">Allowed</label>
                                </div>
                                <div>
                                    <input type="radio" name="comments" id="commnets-no" value="0" <?php if ($row['Allow_Comments'] == 1) {
                                        echo 'checked';
                                    } ?>>
                                    <label for="comments-no">Restricted</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Comments Field  -->
                        <input type="hidden" name="catID" value="<?php echo $catId ?>">
                        <!-- Start Adds Field  -->
                        <div class="form-group form-group-lg">
                            <lable class="col-sm-2 control-label">Advertisement</lable>
                            <div class="col-sm-10 col-md-6">
                                <div>
                                    <input type="radio" name="ads" id="ads-yes" value="1" <?php if ($row['Allow_Ads'] == 0) {
                                        echo 'checked';
                                    } ?>>
                                    <label for="ads-yes">Allowed</label>
                                </div>
                                <div>
                                    <input type="radio" name="ads" id="ads-no" value="0" <?php if ($row['Allow_Ads'] == 1) {
                                        echo 'checked';
                                    } ?>>
                                    <label for="ads-no">Restricted</label>
                                </div>
                            </div>
                        </div>
                        <!-- End Adds Field  -->
                        <!-- Start Username Field  -->
                        <div class="form-group form-group-lg">
                            <div class="col-sm-offset-2 col-md-10 ">
                                <input type="submit" name="add" value="Update Category" class="btn btn-primary btn-lg text-center">
                            </div>
                        </div>
                        <!-- End Username Field  -->

                    </form>
                </div>
                <?php
        } else {
            echo "<div class ='container'>";
            $editError = '<div class="alert alert-danger">No such ID was found</div>';
            redierctHome($editError, 4);
            echo "</div>";
        }
    } elseif ($do == 'Update') {
        echo '<h1 class="text-center edit-members">Update Category</h1>';
        echo '<div class="container">';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['catID'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $parent = $_POST['parent'];
            $ordering = $_POST['ordering'];
            $visibility = $_POST['visibility'];
            $comments = $_POST['comments'];
            $ads = $_POST['ads'];

            // Check If Any Of the Fields Are Empty
            $formErrors = [];
            if (empty($user)) {
                echo "<div class='alert alert-danger'>Category Name Can't Be Left Empty</div>";
            } else {

                $stmt = $conn->prepare('UPDATE categories SET `Name` = ?, `Description` = ?, parent = ? ,Ordering = ?, Visibility = ?, Allow_comments = ?, Allow_Ads = ?  WHERE `ID` = ? LIMIT 1;');
                $stmt->execute(array($name, $description, $parent ,$ordering, $visibility, $comments, $ads, $id));

                $stmt->rowCount() > 0 ? $updateMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Record Updated</div>' :
                    $updateMsg = '<div class="alert alert-danger">' . $stmt->rowCount() . ' Record Updated</div>';

                redierctHome($updateMsg, 'back', 4);

            }


            echo '</div>';


        } else {
            $errorMsg = "<div class='alert alert-danger'>You Can't Enter Update Page This Way </div>";
            redierctHome($errorMsg, 'back', 4);
        }
    } elseif ($do == 'Delete') {
        echo '<h1 class="text-center edit-members">Delete Member</h1>';
        echo '<div class="container">';

        $catId = isset($_GET['CatID']) && is_numeric($_GET['CatID']) ? intval($_GET['CatID']) : 0;

        $check = checkUser('ID', 'categories', $catId);
        if ($check > 0) {
            $stmt = $conn->prepare("DELETE FROM categories WHERE ID = :xID");
            $stmt->bindParam(':xID', $catId);
            $stmt->execute();

            $msg = '<div class="alert alert-danger" > Category ID : <b>' . $catId . '</b> Was Deleted </div>';
            redierctHome($msg, 'back', 4);
        } else {
            $msg = '<div class="alert alert-danger" > ID number: <b>' . $_GET['CatID'] . '</b> Dosn\'t exist </div>';
            redierctHome($msg, 'back', 4);
        }
    }

    include($tpl . 'footer.inc.php');
} else {
    header('Location: index.php');
    exit();
}