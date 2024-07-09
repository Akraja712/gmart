<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_POST['btnAdd'])) {
    $store_name = $db->escapeString($_POST['store_name']);
    $name = $db->escapeString($_POST['name']);
    $mobile_number = $db->escapeString($_POST['mobile_number']);
    $password = $db->escapeString($_POST['password']);
    $store_url = $db->escapeString($_POST['store_url']);
    $error = array();

    if (empty($store_name)) {
        $error['store_name'] = "<span class='label label-danger'>Required!</span>";
    }
    if (empty($name)) {
        $error['name'] = "<span class='label label-danger'>Required!</span>";
    }
    if (empty($mobile_number)) {
        $error['mobile_number'] = "<span class='label label-danger'>Required!</span>";
    }
    if (empty($password)) {
        $error['password'] = "<span class='label label-danger'>Required!</span>";
    }
    if (empty($store_url)) {
        $error['store_url'] = "<span class='label label-danger'>Required!</span>";
    }

    // Validate and process the image upload
    if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0 && !empty($_FILES['image'])) {
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $result = $fn->validate_image($_FILES["image"]);
        $target_path = 'upload/images/';
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . $filename;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
            $error['image'] = '<p class="alert alert-danger">Cannot upload image.</p>';
        } else {
            $upload_image = 'upload/images/' . $filename;
            $sql_query = "INSERT INTO sellers (store_name, name, mobile_number, password, store_url, image) VALUES ('$store_name', '$name', '$mobile_number', '$password', '$store_url', '$upload_image')";
            $db->sql($sql_query);
        }
    } else {
        // Image is not uploaded or empty, insert only the details
        $sql_query = "INSERT INTO sellers (store_name, name, mobile_number, password, store_url) VALUES ('$store_name', '$name', '$mobile_number', '$password', '$store_url')";
        $db->sql($sql_query);
    }

    $result = $db->getResult();
    if (empty($result)) {
        $result = 1;
    } else {
        $result = 0;
    }

    if ($result == 1) {
        $error['add_seller'] = "<section class='content-header'>
                                    <span class='label label-success'>Seller Added Successfully</span> 
                                </section>";
    } else {
        $error['add_seller'] = "<span class='label label-danger'>Failed</span>";
    }
}
?>

<section class="content-header">
    <h1>Add New Seller <small><a href='seller.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Seller</a></small></h1>
    <?php echo isset($error['add_seller']) ? $error['add_seller'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>

<section class="content">
    <div class="row">
        <div class="col-md-10">
            <div class="box box-primary">
                <div class="box-header with-border"></div>
                <form method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="store_name">Store Name</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="store_name" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="name">Name</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="mobile_number">Mobile Number</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="mobile_number" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-4'>
                                    <label for="password">Password</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="password" required>
                                </div>
                                <div class='col-md-4'>
                                    <label for="store_url">Store URL</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="store_url" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="exampleInputFile">Image</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                    <input type="file" name="image" onchange="readURL(this);" accept="image/png, image/jpeg" id="image" required/><br>
                                    <img id="blah" src="#" alt="" style="display:none;" />
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Submit</button>
                        <input type="reset" class="btn btn-warning" value="Clear" onclick="refreshPage()">
                    </div>
                </form>
                <div id="result"></div>
            </div>
        </div>
    </div>
</section>

<div class="separator"></div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $(document).ready(function () {
        $('form').validate({
            rules: {
                store_name: "required",
                name: "required",
                mobile_number: "required",
                password: "required",
                store_url: "required",
                image: "required"
            }
        });

        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });

    function refreshPage() {
        window.location.reload();
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('blah').src = e.target.result;
                document.getElementById('blah').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php $db->disconnect(); ?>
