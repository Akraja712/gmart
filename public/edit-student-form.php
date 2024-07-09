<?php
include_once('includes/functions.php');
date_default_timezone_set('Asia/Kolkata');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_GET['id'])) {
    $ID = $db->escapeString($fn->xss_clean($_GET['id']));
} else {
    return false;
    exit(0);
}

if (isset($_POST['btnUpdate'])) {
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

    $sql = "UPDATE student SET form_no='$form_no', fathers_name='$fathers_name', admission_year='$admission_year', dob='$dob', gender='$gender', category_id='$category_id', nationality='$nationality', name='$name', registration_no='$registration_no', course_type='$course_type', faculty='$faculty', course_name='$course_name' WHERE id = '$ID'";
    $db->sql($sql);
    $result = $db->getResult();
    if (!empty($result)) {
        $error['update_slide'] = " <span class='label label-danger'>Failed</span>";
    } else {
        $error['update_slide'] = " <span class='label label-success'>Student Updated Successfully</span>";
    }

    if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0 && !empty($_FILES['image'])) {
        $old_image = $db->escapeString($_POST['old_image']);
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        $result = $fn->validate_image($_FILES["image"]);
        $target_path = 'upload/images/';
        
        $filename = microtime(true) . '.' . strtolower($extension);
        $full_path = $target_path . $filename;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
            echo '<p class="alert alert-danger">Cannot upload image.</p>';
            return false;
            exit();
        }
        if (!empty($old_image) && file_exists($old_image)) {
            unlink($old_image);
        }

        $upload_image = $full_path;
        $sql = "UPDATE student SET image='$upload_image' WHERE id='$ID'";
        $db->sql($sql);

        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        if ($update_result == 1) {
            $error['update_slide'] = " <section class='content-header'><span class='label label-success'>Student updated Successfully</span></section>";
        } else {
            $error['update_slide'] = " <span class='label label-danger'>Failed to update</span>";
        }
    }
}

$sql_query = "SELECT * FROM `student` WHERE id = '$ID'";
$db->sql($sql_query);
$res = $db->getResult();
if (!isset($res[0])) {
    echo '<p class="alert alert-danger">Student not found.</p>';
    exit();
}
?>

<section class="content-header">
    <h1>Edit Student <small><a href='student.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Student</a></small></h1>
    <?php echo isset($error['update_slide']) ? $error['update_slide'] : ''; ?>
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
                <div class="box-header">
                    <?php echo isset($error['cancelable']) ? '<span class="label label-danger">Till status is required.</span>' : ''; ?>
                </div>

                <form id='edit_slide_form' method="post" enctype="multipart/form-data">
                    <div class="box-body">
                    <input type="hidden" name="old_image" value="<?php echo isset($res[0]['image']) ? $res[0]['image'] : ''; ?>">
                    <div class="row">
                        <div class="form-group">
                            <div class='col-md-4'>
                                <label for="form_no">Form No</label> <i class="text-danger asterik">*</i><?php echo isset($error['form_no']) ? $error['form_no'] : ''; ?>
                                <input type="text" class="form-control" name="form_no" id="form_no" value="<?php echo $res[0]['form_no']; ?>" required>
                            </div>
                            <div class='col-md-4'>
                                <label for="admission_year">Admission Year</label> <i class="text-danger asterik">*</i><?php echo isset($error['admission_year']) ? $error['admission_year'] : ''; ?>
                                <input type="text" class="form-control" name="admission_year" id="admission_year" value="<?php echo $res[0]['admission_year']; ?>" required>
                            </div>
                            <div class='col-md-4'>
                                <label for="registration_no">Registration No</label> <i class="text-danger asterik">*</i><?php echo isset($error['registration_no']) ? $error['registration_no'] : ''; ?>
                                <input type="text" class="form-control" name="registration_no" id="registration_no" value="<?php echo $res[0]['registration_no']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <div class='col-md-4'>
                                <label for="faculty">Faculty</label> <i class="text-danger asterik">*</i><?php echo isset($error['faculty']) ? $error['faculty'] : ''; ?>
                                <input type="text" class="form-control" name="faculty" id="faculty" value="<?php echo $res[0]['faculty']; ?>" required>
                            </div>
                            <div class='col-md-4'>
                                <label for="course_type">Course Type</label> <i class="text-danger asterik">*</i><?php echo isset($error['course_type']) ? $error['course_type'] : ''; ?>
                                <input type="text" class="form-control" name="course_type" id="course_type" value="<?php echo $res[0]['course_type']; ?>" required>
                            </div>
                            <div class='col-md-4'>
                                <label for="course_name">Course Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['course_name']) ? $error['course_name'] : ''; ?>
                                <input type="text" class="form-control" name="course_name" id="course_name" value="<?php echo $res[0]['course_name']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                        <div class='col-md-4'>
                                <label for="name">Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                <input type="text" class="form-control" name="name" id="name" value="<?php echo $res[0]['name']; ?>" required>
                            </div>
                            <div class='col-md-4'>
                                <label for="fathers_name">Father's Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['fathers_name']) ? $error['fathers_name'] : ''; ?>
                                <input type="text" class="form-control" name="fathers_name" id="fathers_name" value="<?php echo $res[0]['fathers_name']; ?>" required>
                            </div>
                            <div class='col-md-4'>
                                <label for="dob">Date of Birth</label> <i class="text-danger asterik">*</i><?php echo isset($error['dob']) ? $error['dob'] : ''; ?>
                                <input type="date" class="form-control" name="dob" id="dob" value="<?php echo $res[0]['dob']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                        <div class='col-md-4'>
                               <label for="gender">Gender</label> <i class="text-danger asterik">*</i>
                               <select id='gender' name="gender" class='form-control' required>
                                  <option value='male' <?php echo ($res[0]['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                                  <option value='female' <?php echo ($res[0]['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                                  <option value='others' <?php echo ($res[0]['gender'] == 'others') ? 'selected' : ''; ?>>Others</option>
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
                                     <option value='<?php echo $value['id'] ?>' <?php echo ($res[0]['category_id'] == $value['id']) ? 'selected' : ''; ?>><?php echo $value['name'] ?></option>
                                    <?php } ?>
                               </select>
                            </div>
                            <div class='col-md-4'>
                                <label for="nationality">Nationality</label> <i class="text-danger asterik">*</i><?php echo isset($error['nationality']) ? $error['nationality'] : ''; ?>
                                <input type="text" class="form-control" name="nationality" id="nationality" value="<?php echo $res[0]['nationality']; ?>" required>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                        <div class="col-md-4">
                                <label for="image">Photo</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                <input type="file" name="image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="image" /><br>
                                <img id="blah" src="<?php echo $res[0]['image']; ?>" alt="" style="display:block; width:150px; height:200px;"/>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <input type="submit" class="btn-primary btn" value="Update" name="btnUpdate" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<div class="separator"> </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#edit_slide_form').validate({
        ignore: [],
        debug: false,
        rules: {
            name: "required",
        }
    });
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

    function updateIdProofNumberLength() {
        var idProofType = document.getElementById('id_proof_type').value;
        var idProofNo = document.getElementById('id_proof_no');

        if (idProofType == 'aadhaarcard') {
            idProofNo.setAttribute('maxlength', '12');
            idProofNo.setAttribute('minlength', '12');
        } else if (idProofType == 'hsc') {
            idProofNo.setAttribute('maxlength', '6');
            idProofNo.setAttribute('minlength', '6');
        } else if (idProofType == 'sslc') {
            idProofNo.setAttribute('maxlength', '7');
            idProofNo.setAttribute('minlength', '7');
        } else {
            idProofNo.removeAttribute('maxlength');
            idProofNo.removeAttribute('minlength');
        }
    }

    function validateIdProofNumber() {
        var idProofType = document.getElementById('id_proof_type').value;
        var idProofNo = document.getElementById('id_proof_no').value;

        if (idProofType == 'aadhaarcard' && idProofNo.length != 12) {
            alert('Aadhaar card number must be 12 digits long.');
            return false;
        } else if ((idProofType == 'hsc' || idProofType == 'sslc') && idProofNo.length != 10) {
            alert('HSC/SSLC number must be 10 digits long.');
            return false;
        } else if (idProofType == 'hsc' && idProofNo.length != 6) {
            alert('HSC number must be 6 digits long.');
            return false;
        } else if (idProofType == 'sslc' && idProofNo.length != 7) {
            alert('SSLC number must be 7 digits long.');
            return false;
        }

        return true;
    }

    // Initialize the length attributes based on the current value
    updateIdProofNumberLength();
</script>