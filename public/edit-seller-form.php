<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php

if (isset($_GET['id'])) {
	$ID = $db->escapeString($_GET['id']);
} else {
	// $ID = "";
	return false;
	exit(0);
}
if (isset($_POST['btnEdit'])) {

	$store_name = $db->escapeString(($_POST['store_name']));
    $name = $db->escapeString(($_POST['name']));
    $mobile_number = $db->escapeString(($_POST['mobile_number']));
    $password = $db->escapeString(($_POST['password']));
    $store_url = $db->escapeString(($_POST['store_url']));
	
    $sql= "UPDATE sellers SET store_name='$store_name',name='$name',mobile_number='$mobile_number',password='$password',store_url='$store_url' WHERE id =  $ID";
    $db->sql($sql);
    $result = $db->getResult();
    if (!empty($result)) {
        $error['update_slide'] = " <span class='label label-danger'>Failed</span>";
    } else {
        $error['update_slide'] = " <span class='label label-success'>Categories Updated Successfully</span>";
    }

    if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0 && !empty($_FILES['image'])) {
     
        $extension = pathinfo($_FILES["image"]["name"])['extension'];

        $result = $fn->validate_image($_FILES["image"]);
        $target_path = 'upload/images/';
        
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . "" . $filename;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
            echo '<p class="alert alert-danger">Can not upload image.</p>';
            return false;
            exit();
        }
        if (!empty($old_image) && file_exists($old_image)) {
            unlink($old_image);
        }

        $upload_image = 'upload/images/' . $filename;
        $sql = "UPDATE sellers SET `image`='$upload_image' WHERE `id`='$ID'";
        $db->sql($sql);

        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        if ($update_result == 1) {
            $error['update_slide'] = " <section class='content-header'><span class='label label-success'>Sellers updated Successfully</span></section>";
        } else {
            $error['update_slide'] = " <span class='label label-danger'>Failed to update</span>";
        }
    }
}

$data = array();

$sql_query = "SELECT * FROM `sellers` WHERE id = '$ID'";
$db->sql($sql_query);
$res = $db->getResult();
?>
<section class="content-header">
	<h1>
		Edit Sellers<small><a href='seller.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Sellers</a></small></h1>
	<small><?php echo isset($error['update_slide']) ? $error['update_slide'] : ''; ?></small>
	<ol class="breadcrumb">
		<li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
	</ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <form id="edit_languages_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                            <div class='col-md-4'>
                                    <label for="store_name">Store Name</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="store_name" value="<?php echo $res[0]['store_name']; ?>" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="name">Name</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']; ?>" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="mobile_number">Mobile Number</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="mobile_number" value="<?php echo $res[0]['mobile_number']; ?>" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="password">Password</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="password" value="<?php echo $res[0]['password']; ?>" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="store_url">Store Url</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="store_url" value="<?php echo $res[0]['store_url']; ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputFile">Image</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                    <input type="file" name="image" onchange="readURL(this);" accept="image/png, image/jpeg" id="image" /><br>
                                    <img id="blah" src="<?php echo $res[0]['image']; ?>" alt="" width="150" height="200" <?php echo empty($res[0]['image']) ? 'style="display: none;"' : ''; ?> />
                                </div>
                            </div>
                        </div>
						<br>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnEdit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="separator"> </div>
<?php $db->disconnect(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#edit_languages_form').validate({
        ignore: [],
        debug: false,
        rules: {
            year_semester: "required",
            exam_month_year: "required",
            total_marks: "required",
            obtained_marks: "required",
            sgpa: "required",
            status: "required"
        }
    });
</script>
<script>
    $(document).ready(function () {
        $('#user_id').select2({
            width: 'element',
            placeholder: 'Type in name to search',
        });
    });

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200)
                    .css('display', 'block');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<!--code for page clear-->
<script>
    function refreshPage() {
        window.location.reload();
    }
</script>