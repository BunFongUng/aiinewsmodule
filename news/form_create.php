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

if(isset($_POST['create'])) {
    $required_error_message = array();
    $errors_message = array();

    //get the information from the user that fill the form
    $title = (isset($_POST['title']) && !empty($_POST['title'])) ? $_POST['title'] : '';
    $post_date = (isset($_POST['post_date']) && !empty($_POST['post_date'])) ? $_POST['post_date'] : '';
    $image_name = (isset($_FILES['news_image']['name']) && !empty($_FILES['news_image']['name'])) ? basename($_FILES['news_image']['name']) : '';
    $desc = (isset($_POST['desc']) && !empty($_POST['desc'])) ? $_POST['desc'] : '';
    $content = (isset($_POST['contents']) && !empty($_POST['contents'])) ? $_POST['contents'] : '';

    //get the detail about file upload
    $temp_folder = $_FILES['news_image']['tmp_name'];
    $image_type = $_FILES['news_image']['type'];
    $upload_directory = "../uploads";

    if($title == "") {
        $required_error_message["title_required"] = "Title Filed Required!";
    }

    if($image_name == "") {
        $required_error_message["image_required"] = "News Image Field Required!";
    }

    if($post_date == "") {
        $required_error_message["post_date_required"] = "Post Date Field Required!";
    }

    if($desc == "") {
        $required_error_message["desc_required"] = "Short Description Field Required!";
    }

    if($content == "") {
        $required_error_message["content_required"] = "Content Field Required!";
    }

    if(empty($required_error_message)) {
        if(($image_type != "image/jpeg") && ($image_type != "image/png") && ($image_type != "image/jpg") && ($image_type !="image/gif")) {
            $errors_message["image_type"] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            if(file_exists($upload_directory."/".$image_name)) {
//                $errors_message["file_exits"] = "This {$image_name} already exits!";
                unlink($upload_directory."/".$image_name);
            } else {
                if(move_uploaded_file($temp_folder, $upload_directory."/".$image_name)) {
                    $sql_insert = "INSERT INTO news(title, image, post_date, des, content) VALUES(:title, :image, :post_date , :des, :content)";
                    $stmt = $conn->prepare($sql_insert);
                    $stmt->bindParam(":title", $title);
                    $stmt->bindParam(":image", $image_name);
                    $stmt->bindParam(":post_date", $post_date);
                    $stmt->bindParam(":des", $desc);
                    $stmt->bindParam(":content", $content);
                    $stmt->execute();
                    if($stmt->rowCount() > 0) {
                        $user->redirect("index.php");
                    } else {
                        $errors_message["error"] = "Look Like something wrong!";
                    }
                }
            }
        }
    }
}

?>

<?php include_once('../include_header.php');?>

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
                                            <p>Form Create News</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                <form id="form-personal" role="form" autocomplete="off" method="post" action="<?= $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group <?php echo $title_error = (isset($required_error_message['title_required'])) ? $title_error = 'has-error' : ''?>">
                                                <label>News Title</label>
                                                <input type="text" name="title" id="title" class="form-control">
                                                <?php if(isset($required_error_message['title_required'])):?>
                                                    <span class="help-block"><?= $required_error_message['title_required']?></span>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group <?php echo $image_error = (isset($required_error_message['image_required'])) ? $image_error = 'has-error' : ''?>">
                                                <label>News Image</label>
                                                <input type="file" name="news_image" id="news_image">
                                                <?php if(isset($required_error_message['image_required'])):?>
                                                    <span class="help-block"><?= $required_error_message['image_required']?></span>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group <?php echo $post_date = (isset($required_error_message['post_date_required'])) ? $post_date = 'has-error' : ''?>">
                                                <label>Post Date</label>
                                                <div id="datepicker-component" class="input-group date">
                                                    <input type="text" class="form-control" name="post_date" placeholder="Post Date"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                </div>
                                                <?php if(isset($required_error_message['post_date_required'])):?>
                                                    <span class="help-block"><?= $required_error_message['post_date_required']?></span>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group <?php echo $desc_error = (isset($required_error_message["desc_required"])) ? $desc_error = 'has-error' : ''?>">
                                                <label>Short Description</label>
                                                <textarea name="desc" id="summernote1"></textarea>
                                                <?php if(isset($required_error_message["desc_required"])):?>
                                                    <span class="help-block"><?= $required_error_message["desc_required"]?></span>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group <?php echo $content_error = (isset($required_error_message["content_required"])) ? $content_error = 'has-error' : ''?>">
                                                <label>Content</label>
                                                <textarea name="contents" id="summernote"></textarea>
                                                <?php if(isset($required_error_message["content_required"])):?>
                                                    <span class="help-block"><?= $required_error_message["content_required"]?></span>
                                                <?php endif;?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="clearfix"></div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-success" name="create" type="submit">Create New News</button>
                                        </div>
                                    </div>
                                </form>
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

    <?php include_once('../include_footer.php');?>


