<?php
session_start();
require_once("../../config/User.php");
require_once("../../config/Database.php");

$user = new User();
$database = new Database();
$conn = $database->getConnection();

if(!$user->is_logged_in()) {
    $user->redirect("../index.php");
}

$sql_select = "SELECT * FROM news";
$stmt = $conn->prepare($sql_select);
$stmt->execute();

?>

<?php include_once('../include_header.php');?>

<!--display form edit for news-->
<?php if(isset($_GET["id"]) && isset($_GET["edit"])):?>
<?php
    $update_id = $_GET["id"];
    $sql_select_update = "SELECT * FROM news WHERE id = :update_id";
    $stmt2 = $conn->prepare($sql_select_update);
    $stmt2->bindParam(":update_id", $update_id);
    $stmt2->execute();
?>
<!-- START PAGE CONTENT WRAPPER -->
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid padding-25 sm-padding-10">
            <!-- start row-->
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <!-- START CONTAINER FLUID -->
                    <div class="container-fluid container-fixed-lg bg-white">
                        <!-- START PANEL -->
                        <div class="panel panel-transparent">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>Form Editing News</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                <?php if($stmt2->rowCount() > 0):?>
                                    <?php $update_result_found = $stmt2->fetch(PDO::FETCH_ASSOC); extract($update_result_found);?>
                                    <form id="form-personal" role="form" autocomplete="off" method="post" action="update.php" enctype="multipart/form-data">
                                        <input type="hidden" name="update_id" value="<?= $update_id?>">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>News Title</label>
                                                    <input type="text" name="title" id="title" class="form-control" value="<?php echo $title_display = (isset($title)) ? $title_display = $title : ""?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>News Image</label>
                                                    <input type="hidden" name="old_image" value="<?= $image?>">
                                                    <img src="../uploads/<?= $image ?>" width="250px;" height="250px;" alt="">
                                                    <input type="file" name="news_image" id="news_image">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Post Date</label>
                                                    <div id="datepicker-component" class="input-group date">
                                                        <input type="text" class="form-control" name="post_date" placeholder="Post Date" value="<?php echo $display_date = (isset($post_date)) ? $display_date = $post_date : ""?>"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="label-control">Short Description</label>
                                                    <textarea name="desc" id="edit"><?php echo $display_desc = (isset($des)) ? $display_desc = $des : ""?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Content</label>
                                                    <textarea name="contents" id="edit2"><?php echo $display_content = (isset($content)) ? $display_content = $content : ""?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-success" name="update" type="submit">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                <?php else:?>
                                    <div class="alert alert-info">
                                        <h3>Result Not Found!</h3>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                        <!-- END PANEL -->
                        <?php if(!empty($errors_message)):?>
                            <div class="alert alert-info">
                                <?php foreach($errors_message as $error):?>
                                    <p><?= $error?></p>
                                <?php endforeach;?>
                            </div>
                        <?php endif;?>
                    </div>
                    <!-- END CONTAINER FLUID -->
                </div>
            </div>
            <!--end row-->
        </div>
        <!-- END CONTAINER FLUID -->
    </div>
    <!-- END PAGE CONTENT -->
<?php endif;?>
<!--end of display form edit for news-->


<!--display confirm delete form for news-->
<?php if(isset($_GET["id"]) && isset($_GET["delete"])):?>
<?php
    $delete_id = $_GET["id"];
    $sql_select_delete = "SELECT * FROM news WHERE id = :delete_id";
    $stmt3 = $conn->prepare($sql_select_delete);
    $stmt3->bindParam(":delete_id", $delete_id);
    $stmt3->execute();
?>

<!-- START PAGE CONTENT WRAPPER -->
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid padding-25 sm-padding-10">
            <!-- start row-->
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <!-- START CONTAINER FLUID -->
                    <div class="container-fluid container-fixed-lg bg-white">
                        <!-- START PANEL -->
                        <div class="panel panel-transparent">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>Confirm Delete News</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                <?php if($stmt3->rowCount() > 0):?>
                                    <?php $update_result_found = $stmt3->fetch(PDO::FETCH_ASSOC); extract($update_result_found);?>
                                    <form id="form-personal" role="form" autocomplete="off" method="post" action="update.php" enctype="multipart/form-data">
                                        <input type="hidden" name="delete_id" value="<?= $delete_id?>">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>News Title</label>
                                                    <input readonly="readonly" type="text" name="title" id="title" class="form-control" value="<?php echo $title_display = (isset($title)) ? $title_display = $title : ""?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="lable-control">News Image</label>
                                                    <img src="../uploads/<?= $image ?>" width="250px;" height="250px;" alt="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="label-control">Post Date</label>
                                                    <div id="datepicker-component" class="input-group date">
                                                        <input readonly="readonly" type="text" class="form-control" name="post_date" placeholder="Post Date" value="<?php echo $display_date = (isset($post_date)) ? $display_date = $post_date : ""?>"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="label-control">Short Description</label>
                                                    <textarea readonly="readonly" name="desc" id="edit"><?php echo $display_desc = (isset($des)) ? $display_desc = $des : ""?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Content</label>
                                                    <textarea readonly="readonly" name="contents" id="edit"><?php echo $display_content = (isset($content)) ? $display_content = $content : ""?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-success" name="delete" type="submit">Delete</button>
                                            </div>
                                        </div>
                                    </form>
                                <?php else:?>
                                    <div class="alert alert-info">
                                        <h3>Result Not Found!</h3>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                        <!-- END PANEL -->
                        <?php if(!empty($errors_message)):?>
                            <div class="alert alert-info">
                                <?php foreach($errors_message as $error):?>
                                    <p><?= $error?></p>
                                <?php endforeach;?>
                            </div>
                        <?php endif;?>
                    </div>
                    <!-- END CONTAINER FLUID -->
                </div>
            </div>
            <!--end row-->
        </div>
        <!-- END CONTAINER FLUID -->
    </div>
    <!-- END PAGE CONTENT -->

<?php endif;?>
<!--end of display confirm delete form for news-->


<!--listing all the news-->
<?php if((!isset($_GET["id"]) && !isset($_GET["edit"])) || (!isset($_GET["id"]) && !isset($_GET["delete"]))):?>

<!-- START PAGE CONTENT WRAPPER -->
<div class="page-content-wrapper ">
    <!-- START PAGE CONTENT -->
    <div class="content sm-gutter">
        <!-- START CONTAINER FLUID -->
        <div class="container-fluid padding-25 sm-padding-10">
            <!-- start row-->
            <div class="row">
                <!-- START CONTAINER FLUID -->
                <div class="container-fluid container-fixed-lg bg-white">
                    <!-- START PANEL -->
                    <div class="panel panel-transparent">
                        <div class="panel-heading">
                            <div class="panel-title">Listing News</div>

                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <?php if($stmt->rowCount() > 0):?>
                            <div class="table-responsive">
                                <table class="table table-hover" id="basicTable">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Image</th>
                                        <th>Short Description</th>
                                        <th>Posted Data</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)):?>
                                            <?php extract($row)?>
                                            <tr>
                                                <td class="v-align-middle">
                                                    <p><?= $id ?></p>
                                                </td>

                                                <td class="v-align-middle">
                                                    <p><?= $title ?></p>
                                                </td>

                                                <td class="v-align-middle">
                                                    <img src="../uploads/<?= $image?>" width="100%" alt="">
                                                </td>

                                                <td class="v-align-middle">
                                                    <p><?= $des ?></p>
                                                </td>

                                                <td class="v-align-middle">
                                                    <p><?= $post_date ?></p>
                                                </td>

                                                <td class="v-align-middle">
                                                    <a style="margin-bottom: 15px;" class="form-control btn btn-success" href="index.php?id=<?= $id?>&edit=true">Edit</a>
                                                    <a class="form-control btn btn-danger" href="index.php?id=<?= $id?>&delete=true">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endwhile;?>
                                    </tbody>
                                </table>
                                <a href="form_create.php" class="btn btn-success">Add New News</a>
                                <?php else:?>
                                    <div class="alert alert-info">
                                        <h3>Result Not Found!</h3>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                    <!-- END PANEL -->
                </div>
                <!-- END CONTAINER FLUID -->

            </div>
            <!--end row-->

        </div>
        <!-- END CONTAINER FLUID -->
    </div>
    <!-- END PAGE CONTENT -->
<?php endif;?>

<?php include_once('../include_footer.php');?>

