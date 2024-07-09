<?php
include_once('includes/functions.php');
date_default_timezone_set('Asia/Kolkata');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

function validate_document($file) {
    $max_size = 10 * 1024 * 1024; // 10 MB (adjust as needed)
    $allowed_types = array('pdf', 'doc', 'docx'); // Allowed file types

    $filename = $file['name'];
    $filetype = pathinfo($filename, PATHINFO_EXTENSION);
    $filesize = $file['size'];
    $filetmp = $file['tmp_name'];

    // Check file size
    if ($filesize > $max_size) {
        echo '<p class="alert alert-danger">File size exceeds the maximum limit.</p>';
        exit();
    }

    // Check file type
    if (!in_array(strtolower($filetype), $allowed_types)) {
        echo '<p class="alert alert-danger">Invalid file type. Allowed types are: ' . implode(', ', $allowed_types) . '</p>';
        exit();
    }

    // You can add more checks here as per your requirements

    // If all checks pass, return true
    return true;
}
// Assuming $db is initialized somewhere before this point
if (isset($_GET['id'])) {
    $ID = $db->escapeString($fn->xss_clean($_GET['id']));
} else {
    echo '<p class="alert alert-danger">Invalid Admission ID.</p>';
    exit();
}

    // Fetch existing image and document paths
    $sql_query = "SELECT * FROM admission WHERE id = '$ID'";
    $db->sql($sql_query);
    $res = $db->getResult();

    if (!isset($res[0])) {
        echo '<p class="alert alert-danger">Admission not found.</p>';
        exit();
    }

    $admission = $res[0];


if (isset($_POST['btnUpdate'])) {
    // Sanitize and escape all inputs
    $candidate_name = $db->escapeString($_POST['candidate_name']);
    $fathers_name = $db->escapeString($_POST['fathers_name']);
    $mothers_name = $db->escapeString($_POST['mothers_name']);
    $dob = $db->escapeString($_POST['dob']);
    $gender = $db->escapeString($_POST['gender']);
    $category_id = $db->escapeString($_POST['category_id']);
    $id_proof_type = $db->escapeString($_POST['id_proof_type']);
    $id_proof_no = $db->escapeString($_POST['id_proof_no']);
    $employeed = $db->escapeString($_POST['employeed']);
    $center_id = $db->escapeString($_POST['center_id']);
    $contact_number = $db->escapeString($_POST['contact_number']);
    $email = $db->escapeString($_POST['email']);
    $fathers_contact_number = $db->escapeString($_POST['fathers_contact_number']);
    $mothers_contact_number = $db->escapeString($_POST['mothers_contact_number']);
    $country = $db->escapeString($_POST['country']);
    $nationality = $db->escapeString($_POST['nationality']);
    $state = $db->escapeString($_POST['state']);
    $city = $db->escapeString($_POST['city']);
    $address = $db->escapeString($_POST['address']);
    $pincode = $db->escapeString($_POST['pincode']);
    $secondary_year_passing = $db->escapeString($_POST['secondary_year_passing']);
    $secondary_board_university = $db->escapeString($_POST['secondary_board_university']);
    $secondary_percentage_cgpa = $db->escapeString($_POST['secondary_percentage_cgpa']);
    $senior_secondary_year_passing = $db->escapeString($_POST['senior_secondary_year_passing']);
    $senior_secondary_board_university = $db->escapeString($_POST['senior_secondary_board_university']);
    $senior_secondary_percentage_cgpa = $db->escapeString($_POST['senior_secondary_percentage_cgpa']);
    $graduation_year_passing = $db->escapeString($_POST['graduation_year_passing']);
    $graduation_board_university = $db->escapeString($_POST['graduation_board_university']);
    $graduation_percentage_cgpa = $db->escapeString($_POST['graduation_percentage_cgpa']);
    $post_graduation_year_passing = $db->escapeString($_POST['post_graduation_year_passing']);
    $post_graduation_board_university = $db->escapeString($_POST['post_graduation_board_university']);
    $post_graduation_percentage_cgpa = $db->escapeString($_POST['post_graduation_percentage_cgpa']);
    $other_year_passing = $db->escapeString($_POST['other_year_passing']);
    $other_board_university = $db->escapeString($_POST['other_board_university']);
    $other_percentage_cgpa = $db->escapeString($_POST['other_percentage_cgpa']);
    $course_type = $db->escapeString($_POST['course_type']);
    $faculty = $db->escapeString($_POST['faculty']);
    $course = $db->escapeString($_POST['course']);
    $stream = $db->escapeString($_POST['stream']);
    $year = $db->escapeString($_POST['year']);
    $month = $db->escapeString($_POST['month']);
    $mode_of_study = $db->escapeString($_POST['mode_of_study']);
    $hostel_facility = $db->escapeString($_POST['hostel_facility']);
    $application_fees = $db->escapeString($_POST['application_fees']);
    $duration = $db->escapeString($_POST['duration']);
    $total_fees = $db->escapeString($_POST['total_fees']);
    $paying_fees = $db->escapeString($_POST['paying_fees']);

      $old_image = $admission['image'];
      $old_secondary_document = $admission['secondary_document'];
      $old_senior_secondary_document = $admission['senior_secondary_document'];
      $old_graduation_document = $admission['graduation_document'];
      $old_post_graduation_document = $admission['post_graduation_document'];
      $old_other_document = $admission['other_document'];
  
      // Handle image upload
      $imagePath = $old_image;
      if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0) {
          $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
  
          $result = $fn->validate_image($_FILES["image"]);
          $target_path = 'upload/images/';
  
          $filename = microtime(true) . '.' . strtolower($extension);
          $full_path = $target_path . $filename;
          if (move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
              if (!empty($old_image) && file_exists($old_image)) {
                  unlink($old_image);
              }
              $imagePath = $full_path;
          } else {
              echo '<p class="alert alert-danger">Cannot upload image.</p>';
              exit();
          }
      }
  
      // Handle document uploads
      $secondaryDocumentPath = $old_secondary_document;
      $seniorSecondaryDocumentPath = $old_senior_secondary_document;
      $graduationDocumentPath = $old_graduation_document;
      $postGraduationDocumentPath = $old_post_graduation_document;
      $otherDocumentPath = $old_other_document;
  
      // Secondary Document
      if ($_FILES['secondary_document']['size'] != 0 && $_FILES['secondary_document']['error'] == 0) {
          if (validate_document($_FILES['secondary_document'])) {
              $target_path = 'upload/documents/';
              $filename = microtime(true) . '.' . strtolower(pathinfo($_FILES["secondary_document"]["name"], PATHINFO_EXTENSION));
              $full_path = $target_path . $filename;
              if (move_uploaded_file($_FILES["secondary_document"]["tmp_name"], $full_path)) {
                  if (!empty($old_secondary_document) && file_exists($old_secondary_document)) {
                      unlink($old_secondary_document);
                  }
                  $secondaryDocumentPath = $full_path;
              } else {
                  echo '<p class="alert alert-danger">Cannot upload secondary document.</p>';
                  exit();
              }
          }
      }
  
      // Senior Secondary Document
      if ($_FILES['senior_secondary_document']['size'] != 0 && $_FILES['senior_secondary_document']['error'] == 0) {
          if (validate_document($_FILES['senior_secondary_document'])) {
              $target_path = 'upload/documents/';
              $filename = microtime(true) . '.' . strtolower(pathinfo($_FILES["senior_secondary_document"]["name"], PATHINFO_EXTENSION));
              $full_path = $target_path . $filename;
              if (move_uploaded_file($_FILES["senior_secondary_document"]["tmp_name"], $full_path)) {
                  if (!empty($old_senior_secondary_document) && file_exists($old_senior_secondary_document)) {
                      unlink($old_senior_secondary_document);
                  }
                  $seniorSecondaryDocumentPath = $full_path;
              } else {
                  echo '<p class="alert alert-danger">Cannot upload senior secondary document.</p>';
                  exit();
              }
          }
      }
  
      // Graduation Document
      if ($_FILES['graduation_document']['size'] != 0 && $_FILES['graduation_document']['error'] == 0) {
          if (validate_document($_FILES['graduation_document'])) {
              $target_path = 'upload/documents/';
              $filename = microtime(true) . '.' . strtolower(pathinfo($_FILES["graduation_document"]["name"], PATHINFO_EXTENSION));
              $full_path = $target_path . $filename;
              if (move_uploaded_file($_FILES["graduation_document"]["tmp_name"], $full_path)) {
                  if (!empty($old_graduation_document) && file_exists($old_graduation_document)) {
                      unlink($old_graduation_document);
                  }
                  $graduationDocumentPath = $full_path;
              } else {
                  echo '<p class="alert alert-danger">Cannot upload graduation document.</p>';
                  exit();
              }
          }
      }
  
      // Post Graduation Document
      if ($_FILES['post_graduation_document']['size'] != 0 && $_FILES['post_graduation_document']['error'] == 0) {
          if (validate_document($_FILES['post_graduation_document'])) {
              $target_path = 'upload/documents/';
              $filename = microtime(true) . '.' . strtolower(pathinfo($_FILES["post_graduation_document"]["name"], PATHINFO_EXTENSION));
              $full_path = $target_path . $filename;
              if (move_uploaded_file($_FILES["post_graduation_document"]["tmp_name"], $full_path)) {
                  if (!empty($old_post_graduation_document) && file_exists($old_post_graduation_document)) {
                      unlink($old_post_graduation_document);
                  }
                  $postGraduationDocumentPath = $full_path;
              } else {
                  echo '<p class="alert alert-danger">Cannot upload post graduation document.</p>';
                  exit();
              }
          }
      }
  
      // Other Document
      if ($_FILES['other_document']['size'] != 0 && $_FILES['other_document']['error'] == 0) {
          if (validate_document($_FILES['other_document'])) {
              $target_path = 'upload/documents/';
              $filename = microtime(true) . '.' . strtolower(pathinfo($_FILES["other_document"]["name"], PATHINFO_EXTENSION));
              $full_path = $target_path . $filename;
              if (move_uploaded_file($_FILES["other_document"]["tmp_name"], $full_path)) {
                  if (!empty($old_other_document) && file_exists($old_other_document)) {
                      unlink($old_other_document);
                  }
                  $otherDocumentPath = $full_path;
              } else {
                  echo '<p class="alert alert-danger">Cannot upload other document.</p>';
                  exit();
              }
          }
      }
  
      // Update the admission record
      $sql = "UPDATE admission SET 
              candidate_name = '$candidate_name',
              fathers_name = '$fathers_name',
              mothers_name = '$mothers_name',
              dob = '$dob',
              gender = '$gender',
              category_id = '$category_id',
              id_proof_type = '$id_proof_type',
              id_proof_no = '$id_proof_no',
              employeed = '$employeed',
              center_id = '$center_id',
              contact_number = '$contact_number',
              email = '$email',
              fathers_contact_number = '$fathers_contact_number',
              mothers_contact_number = '$mothers_contact_number',
              country = '$country',
              nationality = '$nationality',
              state = '$state',
              city = '$city',
              address = '$address',
              pincode = '$pincode',
              secondary_year_passing = '$secondary_year_passing',
              secondary_board_university = '$secondary_board_university',
              secondary_percentage_cgpa = '$secondary_percentage_cgpa',
              secondary_document = '$secondaryDocumentPath',
              senior_secondary_year_passing = '$senior_secondary_year_passing',
              senior_secondary_board_university = '$senior_secondary_board_university',
              senior_secondary_percentage_cgpa = '$senior_secondary_percentage_cgpa',
              senior_secondary_document = '$seniorSecondaryDocumentPath',
              graduation_year_passing = '$graduation_year_passing',
              graduation_board_university = '$graduation_board_university',
              graduation_percentage_cgpa = '$graduation_percentage_cgpa',
              graduation_document = '$graduationDocumentPath',
              post_graduation_year_passing = '$post_graduation_year_passing',
              post_graduation_board_university = '$post_graduation_board_university',
              post_graduation_percentage_cgpa = '$post_graduation_percentage_cgpa',
              post_graduation_document = '$postGraduationDocumentPath',
              other_year_passing = '$other_year_passing',
              other_board_university = '$other_board_university',
              other_percentage_cgpa = '$other_percentage_cgpa',
              other_document = '$otherDocumentPath',
              course_type = '$course_type',
              faculty = '$faculty',
              course = '$course',
              stream = '$stream',
              year = '$year',
              month = '$month',
              mode_of_study = '$mode_of_study',
              hostel_facility = '$hostel_facility',
              application_fees = '$application_fees',
              duration = '$duration',
              total_fees = '$total_fees',
              paying_fees = '$paying_fees',
              image = '$imagePath'
              WHERE id = '$ID'";

    // Execute SQL query
    $db->sql($sql);

    $update_result = $db->getResult();
    if (!empty($update_result)) {
        $update_result = 0;
    } else {
        $update_result = 1;
    }

    if ($update_result == 1) {
        $error['update_slide'] = " <section class='content-header'><span class='label label-success'>Admission updated Successfully</span></section>";
    } else {
        $error['update_slide'] = " <span class='label label-danger'>Failed to update</span>";
    }
      // Fetch updated data
      $sql_query = "SELECT * FROM `admission` WHERE id = '$ID'";
      $db->sql($sql_query);
      $res = $db->getResult();
      $admission = $res[0];
}


// Example HTML form for file uploads (adjust according to your form structure)
?>

<section class="content-header">
    <h1>Edit Admission <small><a href='admission.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Admission</a></small></h1>
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
                    <span style="font-size:25px;">Admission Details:</span><br><br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="candidate_name">Candidate Name</label> <i class="text-danger asterik">*</i>
                                        <input type="text" class="form-control" name="candidate_name" id="candidate_name"  value="<?php echo isset($res[0]['candidate_name']) ? $res[0]['candidate_name'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="fathers_name">Father's Name</label> <i class="text-danger asterik">*</i>
                                        <input type="text" class="form-control" name="fathers_name" id="fathers_name" value="<?php echo isset($res[0]['fathers_name']) ? $res[0]['fathers_name'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="mothers_name">Mother's Name</label> <i class="text-danger asterik">*</i>
                                        <input type="text" class="form-control" name="mothers_name" id="mothers_name" value="<?php echo isset($res[0]['mothers_name']) ? $res[0]['mothers_name'] : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="dob">Date of Birth</label> <i class="text-danger asterik">*</i>
                                        <input type="date" class="form-control" name="dob" id="dob" value="<?php echo isset($res[0]['dob']) ? $res[0]['dob'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="gender">Gender</label> <i class="text-danger asterik">*</i>
                                        <select id='gender' name="gender" class='form-control' required>
                                            <option value=''>Select Gender</option>
                                            <option value='male' <?= isset($res[0]['gender']) && $res[0]['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                                            <option value='female' <?= isset($res[0]['gender']) && $res[0]['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                                            <option value='others' <?= isset($res[0]['gender']) && $res[0]['gender'] == 'others' ? 'selected' : ''; ?>>Others</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                <label for="image">Photo</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                <input type="file" name="image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="image" /><br>
                                <img id="blah" src="<?php echo $res[0]['image']; ?>" alt="" style="display:block; width:150px; height:200px;"/>
                            </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                       <label for="category_id">Category</label> <i class="text-danger asterik">*</i>
                                       <select id='category_id' name="category_id" class='form-control'>
                                           <option value="">--Select--</option>
                                                <?php
                                                  $sql = "SELECT id, name FROM `category`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                ?>
                                                    <option value='<?= $value['id'] ?>' <?= $value['id']==$res[0]['category_id'] ? 'selected="selected"' : '';?>><?= $value['name'] ?></option>
                                                    
                                                <?php } ?>
                                    </select>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="id_proof_type">Id Proof Type</label> <i class="text-danger asterik">*</i>
                                        <select id='id_proof_type' name="id_proof_type" class='form-control' onchange="updateIdProofNumberLength()" required>
                                            <option value=''>Select Id Type</option>
                                            <option value='aadhaarcard' <?= isset($res[0]['id_proof_type']) && $res[0]['id_proof_type'] == 'aadhaarcard' ? 'selected' : ''; ?>>Aadhaar Card</option>
                                            <option value='hsc' <?= isset($res[0]['id_proof_type']) && $res[0]['id_proof_type'] == 'hsc' ? 'selected' : ''; ?>>HSC</option>
                                            <option value='sslc' <?= isset($res[0]['id_proof_type']) && $res[0]['id_proof_type'] == 'sslc' ? 'selected' : ''; ?>>SSLC</option>
                                        </select>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="id_proof_no">Id Proof No</label> <i class="text-danger asterik">*</i>
                                        <input type="text" class="form-control" name="id_proof_no" id="id_proof_no"  value="<?php echo isset($res[0]['id_proof_no']) ? $res[0]['id_proof_no'] : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                       <label for="center_id">Center</label> <i class="text-danger asterik">*</i>
                                       <select id='center_id' name="center_id" class='form-control'>
                                           <option value="">--Select--</option>
                                                <?php
                                                  $sql = "SELECT id, center_name FROM `center`";
                                                $db->sql($sql);

                                                $result = $db->getResult();
                                                foreach ($result as $value) {
                                                ?>
                                                    <option value='<?= $value['id'] ?>' <?= $value['id']==$res[0]['center_id'] ? 'selected="selected"' : '';?>><?= $value['center_name'] ?></option>
                                                    
                                                <?php } ?>
                                    </select>
                                    </div>
                                    <div class='col-md-4'>
                                       <label for="employeed">Are You Employeed</label> <i class="text-danger asterik">*</i>
                                       <select id='employeed' name="employeed" class='form-control'  value="<?php echo isset($res[0]['employeed']) ? $res[0]['employeed'] : ''; ?>" required>
                                       <option value='1' <?php echo isset($res[0]['employeed']) && $res[0]['employeed'] == '1' ? 'selected' : ''; ?>>Yes</option>
                                       <option value='0' <?php echo isset($res[0]['employeed']) && $res[0]['employeed'] == '0' ? 'selected' : ''; ?>>No</option>
                                       </select>
                                    </div>
                                </div>
                            </div> 

                            <br>
                            <hr>
                            <span style="font-size:25px;">Contact Details:</span><br>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="contact_number">Contact Number</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="contact_number" class="form-control" placeholder="Contact Number"  value="<?php echo isset($res[0]['contact_number']) ? $res[0]['contact_number'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="email">Email</label> <i class="text-danger asterik">*</i><?php echo isset($error['email']) ? $error['email'] : ''; ?>
                                        <input type="email" name="email" class="form-control" placeholder="Email"  value="<?php echo isset($res[0]['email']) ? $res[0]['email'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="fathers_contact_number">Father's Contact Number</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="fathers_contact_number" class="form-control" placeholder="Father's Contact Number"  value="<?php echo isset($res[0]['fathers_contact_number']) ? $res[0]['fathers_contact_number'] : ''; ?>" required>
                                    </div>
                                </div>
                            </div>  
                            <br> 
                                <div class="row">
                                 <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="mothers_contact_number">Mother's Contact Number</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="mothers_contact_number" class="form-control" placeholder="Mother's Contact Number"  value="<?php echo isset($res[0]['mothers_contact_number']) ? $res[0]['mothers_contact_number'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="country">Country</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="country" class="form-control" placeholder="Country" value="<?php echo isset($res[0]['country']) ? $res[0]['country'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="nationality">Nationality</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="nationality" class="form-control" placeholder="Nationality" value="<?php echo isset($res[0]['nationality']) ? $res[0]['nationality'] : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <br> 
                                <div class="row">
                                 <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="state">State</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="state" class="form-control" placeholder="State" value="<?php echo isset($res[0]['state']) ? $res[0]['state'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="city">City</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="city" class="form-control" placeholder="City" value="<?php echo isset($res[0]['city']) ? $res[0]['city'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="address">Address</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="address" class="form-control" placeholder="Address" value="<?php echo isset($res[0]['address']) ? $res[0]['address'] : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <br> 
                                <div class="row">
                                 <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="pincode">Pincode</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="pincode" class="form-control" placeholder="pincode" value="<?php echo isset($res[0]['pincode']) ? $res[0]['pincode'] : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <span style="font-size:25px;">Qualification Details:</span><br>
                            <br>
                        
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Examination</th>
                                        <th>Year & Passing</th>
                                        <th>Board/University</th>
                                        <th>Percentage/CGPA</th>
                                        <th>Upload Document</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    <tr>
                                        <td>Secondary</td>
                                        <td><input type="text" name="secondary_year_passing" class="form-control" value="<?php echo htmlspecialchars($admission['secondary_year_passing']); ?>" required></td>
                                        <td><input type="text" name="secondary_board_university" class="form-control" value="<?php echo htmlspecialchars($admission['secondary_board_university']); ?>" required></td>
                                        <td><input type="text" name="secondary_percentage_cgpa" class="form-control" value="<?php echo htmlspecialchars($admission['secondary_percentage_cgpa']); ?>" required></td>
                                        <td>
                                        <div class="form-group">
                                        <input type="file" name="secondary_document" class="form-control-file" accept=".pdf,.doc,.docx" <?php if (!empty($admission['secondary_document'])) echo ''; ?>>
                                        <?php if (!empty($admission['secondary_document'])): ?>
                                        <br>
                                        <a href="<?php echo $res[0]['secondary_document']; ?>" target="_blank"><?php echo basename($res[0]['secondary_document']); ?></a>
                                        <?php endif; ?>
                                                                </tr>
                                                                <tr>
                                                                    <td>Senior Secondary</td>
                                                                    <td><input type="text" name="senior_secondary_year_passing" class="form-control" value="<?php echo $admission['senior_secondary_year_passing']; ?>" required></td>
                                        <td><input type="text" name="senior_secondary_board_university" class="form-control" value="<?php echo $admission['senior_secondary_board_university']; ?>" required></td>
                                        <td><input type="text" name="senior_secondary_percentage_cgpa" class="form-control" value="<?php echo $admission['senior_secondary_percentage_cgpa']; ?>" required></td>
                                        <td>
                                        <div class="form-group">
                                        <input type="file" name="senior_secondary_document" class="form-control-file" accept=".pdf,.doc,.docx" <?php if (!empty($admission['senior_secondary_document'])) echo ''; ?>>
                                        <?php if (!empty($admission['senior_secondary_document'])): ?>
                                        <br>
                                        
                                        <a href="<?php echo $res[0]['senior_secondary_document']; ?>" target="_blank"><?php echo basename($res[0]['senior_secondary_document']); ?></a>
                                        <?php endif; ?>
                                                                </tr>
                                                                <tr>
                                                                    <td>Graduation</td>
                                                                    <td><input type="text" name="graduation_year_passing" class="form-control" value="<?php echo $admission['graduation_year_passing']; ?>" ></td>
                                        <td><input type="text" name="graduation_board_university" class="form-control" value="<?php echo $admission['graduation_board_university']; ?>" ></td>
                                        <td><input type="text" name="graduation_percentage_cgpa" class="form-control" value="<?php echo $admission['graduation_percentage_cgpa']; ?>" ></td>
                                        <td>
                                        <div class="form-group">
                                        <input type="file" name="graduation_document" class="form-control-file" accept=".pdf,.doc,.docx" <?php if (!empty($admission['graduation_document'])) echo ''; ?>>
                                        <?php if (!empty($admission['graduation_document'])): ?>
                                        <br>
                                        
                                        <a href="<?php echo $res[0]['graduation_document']; ?>" target="_blank"><?php echo basename($res[0]['graduation_document']); ?></a>
                                        <?php endif; ?>
                                                                </tr>
                                                                <tr>
                                                                    <td>Post Graduation</td>
                                                                    <td><input type="text" name="post_graduation_year_passing" class="form-control" value="<?php echo $admission['post_graduation_year_passing']; ?>" ></td>
                                        <td><input type="text" name="post_graduation_board_university" class="form-control" value="<?php echo $admission['post_graduation_board_university']; ?>" ></td>
                                        <td><input type="text" name="post_graduation_percentage_cgpa" class="form-control" value="<?php echo $admission['post_graduation_percentage_cgpa']; ?>" ></td>
                                        <td>
                                        <div class="form-group">
                                        <input type="file" name="post_graduation_document" class="form-control-file" accept=".pdf,.doc,.docx" <?php if (!empty($admission['post_graduation_document'])) echo ''; ?>>
                                        <?php if (!empty($admission['post_graduation_document'])): ?>
                                        <br>
                                        
                                        <a href="<?php echo $res[0]['post_graduation_document']; ?>" target="_blank"><?php echo basename($res[0]['post_graduation_document']); ?></a>
                                        <?php endif; ?>
                                                                </tr>
                                                                <tr>
                                                                    <td>Other</td>
                                                                    <td><input type="text" name="other_year_passing" class="form-control" value="<?php echo $admission['other_year_passing']; ?>" ></td>
                                                <td><input type="text" name="other_board_university" class="form-control" value="<?php echo $admission['other_board_university']; ?>" ></td>
                                                <td><input type="text" name="other_percentage_cgpa" class="form-control" value="<?php echo $admission['other_percentage_cgpa']; ?>" ></td>
                                                <td>
                                                <div class="form-group">
                                        <input type="file" name="other_document" class="form-control-file" accept=".pdf,.doc,.docx" <?php if (!empty($admission['other_document'])) echo ''; ?>>
                                        <?php if (!empty($admission['other_document'])): ?>
                                        <br>
                                        <a href="<?php echo $res[0]['other_document']; ?>" target="_blank"><?php echo basename($res[0]['other_document']); ?></a>
                                        <?php endif; ?>
                                    </div>
                                    </tr>
                                </tbody>

                            </table>
    
                            <br>
                            <hr>
                            <span style="font-size:25px;">Programme Details:</span><br>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="course_type">Course Type</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="course_type" class="form-control" placeholder="Course Type" value="<?php echo isset($res[0]['course_type']) ? $res[0]['course_type'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="faculty">Faculty</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="faculty" class="form-control" placeholder="faculty" value="<?php echo isset($res[0]['faculty']) ? $res[0]['faculty'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="course">Course</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="course" class="form-control" placeholder="course" value="<?php echo isset($res[0]['course']) ? $res[0]['course'] : ''; ?>" required>
                                    </div>
                                </div>
                            </div>  
                            <br> 
                                <div class="row">
                                 <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="stream">Stream</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="stream" class="form-control" placeholder="stream" value="<?php echo isset($res[0]['stream']) ? $res[0]['stream'] : ''; ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="year">Year</label> <i class="text-danger asterik">*</i>
                                            <select name="year" class="form-control" required>
                                                <option value="">Select Year</option>
                                                <?php
                                                $currentYear = date("Y");
                                                $selectedYear = isset($res[0]['year']) ? $res[0]['year'] : '';
                                                for ($year = $currentYear; $year >= 1900; $year--) {
                                                    $selected = ($year == $selectedYear) ? 'selected' : '';
                                                    echo "<option value='$year' $selected>$year</option>";
                                                }
                                                ?>
                                            </select>
                                            <?php echo isset($error['year']) ? $error['year'] : ''; ?>
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="month">Month</label> <i class="text-danger asterik">*</i>
                                            <select name="month" class="form-control" required>
                                                <option value="">Select Month</option>
                                                <?php
                                                $months = [
                                                    "01" => "January",
                                                    "02" => "February",
                                                    "03" => "March",
                                                    "04" => "April",
                                                    "05" => "May",
                                                    "06" => "June",
                                                    "07" => "July",
                                                    "08" => "August",
                                                    "09" => "September",
                                                    "10" => "October",
                                                    "11" => "November",
                                                    "12" => "December"
                                                ];
                                                $selectedMonth = isset($res[0]['month']) ? $res[0]['month'] : '';
                                                foreach ($months as $num => $name) {
                                                    $selected = ($num == $selectedMonth) ? 'selected' : '';
                                                    echo "<option value='$num' $selected>$name</option>";
                                                }
                                                ?>
                                            </select>
                                            <?php echo isset($error['month']) ? $error['month'] : ''; ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <br> 
                                <div class="row">
                                 <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="mode_of_study">Mode of Study</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="mode_of_study" class="form-control" placeholder="Mode of Study" value="<?php echo isset($res[0]['mode_of_study']) ? $res[0]['mode_of_study'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="hostel_facility">Hostel Facility</label> <i class="text-danger asterik">*</i>
                                        <select id='hostel_facility' name="hostel_facility" class='form-control' required>
                                            <option value='1' <?php echo isset($res[0]['hostel_facility']) && $res[0]['hostel_facility'] == '1' ? 'selected' : ''; ?>>Yes</option>
                                            <option value='0' <?php echo isset($res[0]['hostel_facility']) && $res[0]['hostel_facility'] == '0' ? 'selected' : ''; ?>>No</option>
                                        </select>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="application_fees">Application_fees</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="application_fees" class="form-control" placeholder="Application Fees" value="<?php echo isset($res[0]['application_fees']) ? $res[0]['application_fees'] : ''; ?>" required>
                                    </div>
                                </div>
                            </div>
                            <br> 
                                <div class="row">
                                 <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="duration">Duration</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="duration" class="form-control" placeholder="Duration" value="<?php echo isset($res[0]['duration']) ? $res[0]['duration'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="total_fees">Total Fees</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="total_fees" class="form-control" placeholder="Total Fees" value="<?php echo isset($res[0]['total_fees']) ? $res[0]['total_fees'] : ''; ?>" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="paying_fees">Paying Fees</label> <i class="text-danger asterik">*</i>
                                        <input type="text" name="paying_fees" class="form-control" placeholder="Paying Fees" value="<?php echo isset($res[0]['paying_fees']) ? $res[0]['paying_fees'] : ''; ?>" required>
                                    </div>
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