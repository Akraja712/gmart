<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_POST['btnAdd'])) {
    $center_name = $db->escapeString($_POST['center_name']);
    $center_code = $db->escapeString($_POST['center_code']);
    $director_name = $db->escapeString($_POST['director_name']);
    $mobile_number = $db->escapeString($_POST['mobile_number']);
    $whatsapp_number = $db->escapeString($_POST['whatsapp_number']);
    $email_id = $db->escapeString($_POST['email_id']);
    $institute_address = $db->escapeString($_POST['institute_address']);
    $city = $db->escapeString($_POST['city']);
    $state = $db->escapeString($_POST['state']);
    $country = $db->escapeString($_POST['country']);
    $password = $db->escapeString($_POST['password']);

    if (empty($center_name)) {
        $error['center_name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($center_code)) {
        $error['center_code'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($director_name)) {
        $error['director_name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($mobile_number)) {
        $error['mobile_number'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($whatsapp_number)) {
        $error['whatsapp_number'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($email_id)) {
        $error['email_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($institute_address)) {
        $error['institute_address'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($city)) {
        $error['city'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($state)) {
        $error['state'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($country)) {
        $error['country'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($password)) {
        $error['password'] = " <span class='label label-danger'>Required!</span>";
    }

    // Function to handle image upload
    function handle_image_upload($file_input_name, $target_dir) {
        if ($_FILES[$file_input_name]['size'] != 0 && $_FILES[$file_input_name]['error'] == 0 && !empty($_FILES[$file_input_name])) {
            $temp_name = $_FILES[$file_input_name]["tmp_name"];
            $extension = pathinfo($_FILES[$file_input_name]["name"], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . strtolower($extension);
            $target_path = $target_dir . $filename;
            if (move_uploaded_file($temp_name, $target_path)) {
                return $target_dir . $filename;
            }
        }
        return '';
    }
    
    // Function to handle document upload
    function handle_document_upload($file_input_name, $target_dir) {
        if ($_FILES[$file_input_name]['size'] != 0 && $_FILES[$file_input_name]['error'] == 0 && !empty($_FILES[$file_input_name])) {
            $temp_name = $_FILES[$file_input_name]["tmp_name"];
            $extension = pathinfo($_FILES[$file_input_name]["name"], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . strtolower($extension);
            $target_path = $target_dir . $filename;
            if (move_uploaded_file($temp_name, $target_path)) {
                return $target_dir . $filename;
            }
        }
        return '';
    }
    
    // Upload image
    $image_target_dir = 'upload/images/';
    $upload_image = handle_image_upload('image', $image_target_dir);
    
    // Upload documents
    $document_target_dir = 'upload/pdf/';
    $pdf_1 = handle_document_upload('pdf_1', $document_target_dir);
    $pdf_2 = handle_document_upload('pdf_2', $document_target_dir);
    $pdf_3 = handle_document_upload('pdf_3', $document_target_dir);

        // Database insert query
        $sql = "INSERT INTO center (center_name, image, center_code, director_name, mobile_number, whatsapp_number, email_id, institute_address, city, state, country, password, pdf_1, pdf_2, pdf_3) VALUES ('$center_name', '$upload_image', '$center_code', '$director_name', '$mobile_number', '$whatsapp_number', '$email_id', '$institute_address', '$city', '$state', '$country', '$password', '$pdf_1', '$pdf_2', '$pdf_3')";

        // Execute query
        $db->sql($sql);
        $result = $db->getResult();
    
        // Check result and set the error message
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }
    
        if ($result == 1) {
            $error['add_slide'] = "<section class='content-header'><span class='label label-success'>Center Added Successfully</span></section>";
        } else {
            $error['add_slide'] = "<span class='label label-danger'>Failed</span>";
        }
    }
    ?>

<section class="content-header">
    <h1>Add Center <small><a href='center.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Center</a></small></h1>

    <?php echo isset($error['add_slide']) ? $error['add_slide'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form name="add_slide_form" method="post" enctype="multipart/form-data" onsubmit="return validateIdProofNumber();">
                    <div class="box-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-3'>
                                        <label for="center_name">Center Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['center_name']) ? $error['center_name'] : ''; ?>
                                        <input type="text" class="form-control" name="center_name" id="center_name" required>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="center_code">Center Code</label> <i class="text-danger asterik">*</i><?php echo isset($error['center_code']) ? $error['center_code'] : ''; ?>
                                        <input type="text" class="form-control" name="center_code" id="center_code" required>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="director_name">Director Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['director_name']) ? $error['director_name'] : ''; ?>
                                        <input type="text" class="form-control" name="director_name" id="director_name" required>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="mobile_number">Mobile Number</label> <i class="text-danger asterik">*</i><?php echo isset($error['mobile_number']) ? $error['mobile_number'] : ''; ?>
                                        <input type="number" class="form-control" name="mobile_number" id="mobile_number" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-3'>
                                        <label for="whatsapp_number">Whatsapp Number</label> <i class="text-danger asterik">*</i><?php echo isset($error['whatsapp_number']) ? $error['whatsapp_number'] : ''; ?>
                                        <input type="number" class="form-control" name="whatsapp_number" id="whatsapp_number" required>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="email_id">Email ID</label> <i class="text-danger asterik">*</i><?php echo isset($error['email_id']) ? $error['email_id'] : ''; ?>
                                        <input type="email" class="form-control" name="email_id" id="email_id" required>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="institute_address">Institude Address</label> <i class="text-danger asterik">*</i><?php echo isset($error['institute_address']) ? $error['institute_address'] : ''; ?>
                                        <input type="text" class="form-control" name="institute_address" id="institute_address" required>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="city">City</label> <i class="text-danger asterik">*</i><?php echo isset($error['city']) ? $error['city'] : ''; ?>
                                        <input type="text" class="form-control" name="city" id="city" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                <div class='col-md-3'>
                                        <label for="state">State</label> <i class="text-danger asterik">*</i><?php echo isset($error['state']) ? $error['state'] : ''; ?>
                                        <input type="text" class="form-control" name="state" id="state" required>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="country">Country</label> <i class="text-danger asterik">*</i><?php echo isset($error['country']) ? $error['country'] : ''; ?>
                                        <input type="text" class="form-control" name="country" id="country" required>
                                    </div>
                                    <div class='col-md-3'>
                                        <label for="password">Password</label> <i class="text-danger asterik">*</i><?php echo isset($error['password']) ? $error['password'] : ''; ?>
                                        <input type="text" class="form-control" name="password" id="password" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                <div class="col-md-3">
                                        <label for="image">Photo</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                        <input type="file" name="image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="image" required/><br>
                                        <img id="blah" src="#" alt="" style="display:none"/>
                                    </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Pdf File 1</label><i class="text-danger asterik">*</i><?php echo isset($error['pdf_1']) ? $error['pdf_1'] : ''; ?>
                                    <input type="file" class="form-control" name="pdf_1" accept="application/pdf" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Pdf File 2</label><i class="text-danger asterik">*</i><?php echo isset($error['pdf_file']) ? $error['pdf_2'] : ''; ?>
                                    <input type="file" class="form-control" name="pdf_2" accept="application/pdf" required>
                                </div>
                                <div class="col-md-3">
                                    <label for="exampleInputEmail1">Pdf File 3</label><i class="text-danger asterik">*</i><?php echo isset($error['pdf_file']) ? $error['pdf_3'] : ''; ?>
                                    <input type="file" class="form-control" name="pdf_3" accept="application/pdf" required>
                                </div>
                            </div>
                            </div>
                        </div>
        
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Add</button>
                        <input type="reset" onClick="refreshPage()" class="btn btn-warning" value="Clear" />
                    </div>

                </form>

            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_mahabharatham_form').validate({
        ignore: [],
        debug: false,
        rules: {
            name: "required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });

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
    function refreshPage(){
        window.location.reload();
    } 
</script>
<?php $db->disconnect(); ?>
