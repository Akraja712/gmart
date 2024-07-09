<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_POST['btnAdd'])) {
    $form_no = $db->escapeString($_POST['form_no']);
    $fathers_name = $db->escapeString($_POST['fathers_name']);
    $admission_year = $db->escapeString($_POST['admission_year']);
    $dob = $db->escapeString($_POST['dob']);
    $gender = $db->escapeString($_POST['gender']);
    $category_id = $db->escapeString($_POST['category_id']);
    $nationality = $db->escapeString($_POST['nationality']);
    $name = $db->escapeString($_POST['name']);
    $registration_no = $db->escapeString($_POST['registration_no']);
    $course_type = $db->escapeString($_POST['course_type']);
    $faculty = $db->escapeString($_POST['faculty']);
    $course_name = $db->escapeString($_POST['course_name']);

    if (empty($form_no)) {
        $error['form_no'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($fathers_name)) {
        $error['fathers_name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($admission_year)) {
        $error['admission_year'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($dob)) {
        $error['dob'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($gender)) {
        $error['gender'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($category_id)) {
        $error['category_id'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($nationality)) {
        $error['nationality'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($name)) {
        $error['name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($registration_no)) {
        $error['registration_no'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($course_type)) {
        $error['course_type'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($faculty)) {
        $error['faculty'] = " <span class='label label-danger'>Required!</span>";
    }
    if (empty($course_name)) {
        $error['course_name'] = " <span class='label label-danger'>Required!</span>";
    }

    // Validate and process the image upload
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

        $upload_image = 'upload/images/' . $filename;
        $sql = "INSERT INTO student (form_no, image, fathers_name, admission_year, dob, gender, category_id, nationality, name, registration_no, course_type,faculty,course_name) VALUES ('$form_no', '$upload_image','$fathers_name','$admission_year','$dob','$gender','$category_id','$nationality','$name','$registration_no','$course_type','$faculty','$course_name')";
        $db->sql($sql);
    } else {
        // Image is not uploaded or empty, insert only the name
        $sql = "INSERT INTO student (form_no,fathers_name, admission_year, dob, gender, category_id, nationality, name, registration_no, course_type,faculty,course_name) VALUES ('$form_no','$fathers_name','$admission_year','$dob','$gender','$category_id','$nationality','$name','$registration_no','$course_type','$faculty','$course_name')";
        $db->sql($sql);
    }

    $result = $db->getResult();
    if (!empty($result)) {
        $result = 0;
    } else {
        $result = 1;
    }

    if ($result == 1) {
        $error['add_slide'] = "<section class='content-header'>
                                            <span class='label label-success'>Student Added Successfully</span> </section>";
    } else {
        $error['add_slide'] = " <span class='label label-danger'>Failed</span>";
    }
}
?>

<section class="content-header">
    <h1>Add Student <small><a href='student.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Student</a></small></h1>

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
                <form name="add_slide_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="farm_no">Farm No</label> <i class="text-danger asterik">*</i><?php echo isset($error['form_no']) ? $error['form_no'] : ''; ?>
                                        <input type="number" class="form-control" name="form_no" id="form_no" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="admission_year"></label>Admission Year<i class="text-danger asterik">*</i><?php echo isset($error['admission_year']) ? $error['admission_year'] : ''; ?>
                                        <input type="number" class="form-control" name="admission_year" id="admission_year" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="registration_no">Registration No</label> <i class="text-danger asterik">*</i><?php echo isset($error['registration_no']) ? $error['registration_no'] : ''; ?>
                                        <input type="number" class="form-control" name="registration_no" id="registration_no" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="faculty">Faculty</label> <i class="text-danger asterik">*</i><?php echo isset($error['faculty']) ? $error['faculty'] : ''; ?>
                                        <input type="text" class="form-control" name="faculty" id="faculty" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="course_type">Course Type</label> <i class="text-danger asterik">*</i><?php echo isset($error['course_type']) ? $error['course_type'] : ''; ?>
                                        <input type="text" class="form-control" name="course_type" id="course_type" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="course_name">Course Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['course_name']) ? $error['course_name'] : ''; ?>
                                        <input type="text" class="form-control" name="course_name" id="course_name" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                <div class='col-md-4'>
                                        <label for="name">Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                        <input type="text" class="form-control" name="name" id="name" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="fathers_name">Father's Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['fathers_name']) ? $error['fathers_name'] : ''; ?>
                                        <input type="text" class="form-control" name="fathers_name" id="fathers_name" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="dob">Date of Birth</label> <i class="text-danger asterik">*</i><?php echo isset($error['dob']) ? $error['dob'] : ''; ?>
                                        <input type="date" class="form-control" name="dob" id="dob" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                       <label for="gender">Gender</label> <i class="text-danger asterik">*</i>
                                       <select id='gender' name="gender" class='form-control' required>
                                          <option value='male'>male</option>
                                          <option value='female'>female</option>
                                          <option value='others'>others</option>
                                       </select>
                                    </div>
                                    <div class='col-md-4'>
                                       <label for="category_id">Category</label> <i class="text-danger asterik">*</i>
                                       <select id='category_id' name="category_id" class='form-control' required>
                                             <option value="">--Select--</option>
                                             <?php
                                              $sql = "SELECT id, name FROM `category`";
                                              $db->sql($sql);
                                               $result = $db->getResult();
                                              foreach ($result as $value) {
                                                  ?>
                                             <option value='<?= $value['id'] ?>'><?= $value['name'] ?></option>
                                            <?php } ?>
                                       </select>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="nationality">Nationality</label> <i class="text-danger asterik">*</i><?php echo isset($error['nationality']) ? $error['nationality'] : ''; ?>
                                        <input type="text" class="form-control" name="nationality" id="nationality" required>
                                    </div>
                                </div>
                            </div> 
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-4">
                                        <label for="image">Photo</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                        <input type="file" name="image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="image" required/><br>
                                        <img id="blah" src="#" alt="" style="display:none"/>
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
