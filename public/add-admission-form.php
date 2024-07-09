<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_POST['btnAdd'])) {
    // Escape and retrieve form data
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
    $document_target_dir = 'upload/documents/';
    $secondary_document = handle_document_upload('secondary_document', $document_target_dir);
    $senior_secondary_document = handle_document_upload('senior_secondary_document', $document_target_dir);
    $graduation_document = handle_document_upload('graduation_document', $document_target_dir);
    $post_graduation_document = handle_document_upload('post_graduation_document', $document_target_dir);
    $other_document = handle_document_upload('other_document', $document_target_dir);

    // Prepare SQL query (sanitize inputs properly to prevent SQL injection)
    $sql = "INSERT INTO admission (candidate_name, image, fathers_name, mothers_name, dob, gender, category_id, id_proof_type, id_proof_no, employeed, center_id, contact_number, email, fathers_contact_number, mothers_contact_number, country, nationality, state, city, address, pincode, secondary_year_passing, secondary_board_university, secondary_percentage_cgpa, secondary_document, senior_secondary_year_passing, senior_secondary_board_university, senior_secondary_percentage_cgpa, senior_secondary_document, graduation_year_passing, graduation_board_university, graduation_percentage_cgpa, graduation_document, post_graduation_year_passing, post_graduation_board_university, post_graduation_percentage_cgpa, post_graduation_document, other_year_passing, other_board_university, other_percentage_cgpa, other_document, course_type, faculty, course, stream, year, month, mode_of_study, hostel_facility, application_fees, duration, total_fees, paying_fees) 
            VALUES ('$candidate_name', '$upload_image', '$fathers_name', '$mothers_name', '$dob', '$gender', '$category_id', '$id_proof_type', '$id_proof_no', '$employeed', '$center_id', '$contact_number', '$email', '$fathers_contact_number', '$mothers_contact_number', '$country', '$nationality', '$state', '$city', '$address', '$pincode', '$secondary_year_passing', '$secondary_board_university', '$secondary_percentage_cgpa', '$secondary_document', '$senior_secondary_year_passing', '$senior_secondary_board_university', '$senior_secondary_percentage_cgpa', '$senior_secondary_document', '$graduation_year_passing', '$graduation_board_university', '$graduation_percentage_cgpa', '$graduation_document', '$post_graduation_year_passing', '$post_graduation_board_university', '$post_graduation_percentage_cgpa', '$post_graduation_document', '$other_year_passing', '$other_board_university', '$other_percentage_cgpa', '$other_document', '$course_type', '$faculty', '$course', '$stream', '$year', '$month', '$mode_of_study', '$hostel_facility', '$application_fees', '$duration', '$total_fees', '$paying_fees')";

    // Execute SQL query
    $db->sql($sql);

    $result = $db->getResult();
    if (!empty($result)) {
        $result = 0;
    } else {
        $result = 1;
    }

    if ($result == 1) {
        $error['add_slide'] = "<section class='content-header'>
                                            <span class='label label-success'>Admission Added Successfully</span> </section>";
    } else {
        $error['add_slide'] = " <span class='label label-danger'>Failed</span>";
    }
}
?>

<section class="content-header">
    <h1>Add Admission <small><a href='admission.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Admission</a></small></h1>

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
                    <span style="font-size:25px;">Admission Details:</span><br><br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="candidate_name">Candidate Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['candidate_name']) ? $error['candidate_name'] : ''; ?>
                                        <input type="text" class="form-control" name="candidate_name" id="candidate_name" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="fathers_name">Father's Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['fathers_name']) ? $error['fathers_name'] : ''; ?>
                                        <input type="text" class="form-control" name="fathers_name" id="fathers_name" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="mothers_name">Mother's Name</label> <i class="text-danger asterik">*</i><?php echo isset($error['mothers_name']) ? $error['mothers_name'] : ''; ?>
                                        <input type="text" class="form-control" name="mothers_name" id="mothers_name" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="dob">Date of Birth</label> <i class="text-danger asterik">*</i><?php echo isset($error['dob']) ? $error['dob'] : ''; ?>
                                        <input type="date" class="form-control" name="dob" id="dob" required>
                                    </div>
                                    <div class='col-md-4'>
                                       <label for="gender">Gender</label> <i class="text-danger asterik">*</i>
                                       <select id='gender' name="gender" class='form-control' required>
                                          <option value='male'>male</option>
                                          <option value='female'>female</option>
                                          <option value='others'>others</option>
                                       </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="image">Photo</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                        <input type="file" name="image" onchange="readURL(this);" accept="image/png,  image/jpeg" id="image" required/><br>
                                        <img id="blah" src="#" alt="" style="display:none"/>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
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
                                       <label for="id_proof_type">Id Proof Type</label> <i class="text-danger asterik">*</i>
                                       <select id='id_proof_type' name="id_proof_type" class='form-control' onchange="updateIdProofNumberLength()" required>
                                          <option value=''>Select Id Type</option>
                                          <option value='aadhaarcard'>Aadhaar Card</option>
                                          <option value='hsc'>HSC</option>
                                          <option value='sslc'>SSLC</option>
                                       </select>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="id_proof_no">Id Proof No</label> <i class="text-danger asterik">*</i><?php echo isset($error['id_proof_no']) ? $error['id_proof_no'] : ''; ?>
                                        <input type="text" class="form-control" name="id_proof_no" id="id_proof_no" required>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <div class='col-md-4'>
                                       <label for="center_id">Center</label> <i class="text-danger asterik">*</i>
                                       <select id='center_id' name="center_id" class='form-control' required>
                                             <option value="">--Select--</option>
                                             <?php
                                              $sql = "SELECT id, center_name FROM `center`";
                                              $db->sql($sql);
                                               $result = $db->getResult();
                                              foreach ($result as $value) {
                                                  ?>
                                             <option value='<?= $value['id'] ?>'><?= $value['center_name'] ?></option>
                                            <?php } ?>
                                       </select>
                                    </div>
                                    <div class='col-md-4'>
                                       <label for="employeed">Are You Employeed</label> <i class="text-danger asterik">*</i>
                                       <select id='employeed' name="employeed" class='form-control' required>
                                          <option value='1'>Yes</option>
                                          <option value='0'>No</option>
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
                                        <label for="contact_number">Contact Number</label> <i class="text-danger asterik">*</i><?php echo isset($error['contact_number']) ? $error['contact_number'] : ''; ?>
                                        <input type="text" name="contact_number" class="form-control" placeholder="Contact Number" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="email">Email</label> <i class="text-danger asterik">*</i><?php echo isset($error['email']) ? $error['email'] : ''; ?>
                                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="fathers_contact_number">Father's Contact Number</label> <i class="text-danger asterik">*</i><?php echo isset($error['fathers_contact_number']) ? $error['fathers_contact_number'] : ''; ?>
                                        <input type="text" name="fathers_contact_number" class="form-control" placeholder="Father's Contact Number" required>
                                    </div>
                                </div>
                            </div>  
                            <br> 
                                <div class="row">
                                 <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="mothers_contact_number">Mother's Contact Number</label> <i class="text-danger asterik">*</i><?php echo isset($error['mothers_contact_number']) ? $error['mothers_contact_number'] : ''; ?>
                                        <input type="text" name="mothers_contact_number" class="form-control" placeholder="Mother's Contact Number" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="country">Country</label> <i class="text-danger asterik">*</i><?php echo isset($error['country']) ? $error['country'] : ''; ?>
                                        <input type="text" name="country" class="form-control" placeholder="Country" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="nationality">Nationality</label> <i class="text-danger asterik">*</i><?php echo isset($error['nationality']) ? $error['nationality'] : ''; ?>
                                        <input type="text" name="nationality" class="form-control" placeholder="Nationality" required>
                                    </div>
                                </div>
                            </div>
                            <br> 
                                <div class="row">
                                 <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="state">State</label> <i class="text-danger asterik">*</i><?php echo isset($error['state']) ? $error['state'] : ''; ?>
                                        <input type="text" name="state" class="form-control" placeholder="State" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="city">City</label> <i class="text-danger asterik">*</i><?php echo isset($error['city']) ? $error['city'] : ''; ?>
                                        <input type="text" name="city" class="form-control" placeholder="City" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="address">Address</label> <i class="text-danger asterik">*</i><?php echo isset($error['address']) ? $error['address'] : ''; ?>
                                        <input type="text" name="address" class="form-control" placeholder="Address" required>
                                    </div>
                                </div>
                            </div>
                            <br> 
                                <div class="row">
                                 <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="pincode">Pincode</label> <i class="text-danger asterik">*</i><?php echo isset($error['pincode']) ? $error['pincode'] : ''; ?>
                                        <input type="text" name="pincode" class="form-control" placeholder="pincode" required>
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
                                        <td><input type="text" name="secondary_year_passing" class="form-control" required></td>
                                        <td><input type="text" name="secondary_board_university" class="form-control" required></td>
                                        <td><input type="text" name="secondary_percentage_cgpa" class="form-control" required></td>
                                        <td><input type="file" name="secondary_document" class="form-control-file" accept=".pdf,.doc,.docx" required></td>
                                    </tr>
                                    <tr>
                                        <td>Senior Secondary</td>
                                        <td><input type="text" name="senior_secondary_year_passing" class="form-control" required></td>
                                        <td><input type="text" name="senior_secondary_board_university" class="form-control" required></td>
                                        <td><input type="text" name="senior_secondary_percentage_cgpa" class="form-control" required></td>
                                        <td><input type="file" name="senior_secondary_document" class="form-control-file" accept=".pdf,.doc,.docx" required></td>
                                    </tr>
                                    <tr>
                                        <td>Graduation</td>
                                        <td><input type="text" name="graduation_year_passing" class="form-control"></td>
                                        <td><input type="text" name="graduation_board_university" class="form-control"></td>
                                        <td><input type="text" name="graduation_percentage_cgpa" class="form-control"></td>
                                        <td><input type="file" name="graduation_document" class="form-control-file" accept=".pdf,.doc,.docx"></td>
                                    </tr>
                                    <tr>
                                        <td>Post Graduation</td>
                                        <td><input type="text" name="post_graduation_year_passing" class="form-control"></td>
                                        <td><input type="text" name="post_graduation_board_university" class="form-control"></td>
                                        <td><input type="text" name="post_graduation_percentage_cgpa" class="form-control"></td>
                                        <td><input type="file" name="post_graduation_document" class="form-control-file" accept=".pdf,.doc,.docx"></td>
                                    </tr>
                                    <tr>
                                        <td>Other</td>
                                        <td><input type="text" name="other_year_passing" class="form-control"></td>
                                        <td><input type="text" name="other_board_university" class="form-control"></td>
                                        <td><input type="text" name="other_percentage_cgpa" class="form-control"></td>
                                        <td><input type="file" name="other_document" class="form-control-file" accept=".pdf,.doc,.docx"></td>
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
                                        <label for="course_type">Course Type</label> <i class="text-danger asterik">*</i><?php echo isset($error['course_type']) ? $error['course_type'] : ''; ?>
                                        <input type="text" name="course_type" class="form-control" placeholder="Course Type" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="faculty">Faculty</label> <i class="text-danger asterik">*</i><?php echo isset($error['faculty']) ? $error['faculty'] : ''; ?>
                                        <input type="text" name="faculty" class="form-control" placeholder="faculty" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="course">Course</label> <i class="text-danger asterik">*</i><?php echo isset($error['course']) ? $error['course'] : ''; ?>
                                        <input type="text" name="course" class="form-control" placeholder="course" required>
                                    </div>
                                </div>
                            </div>  
                            <br> 
                                <div class="row">
                                 <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="stream">Stream</label> <i class="text-danger asterik">*</i><?php echo isset($error['stream']) ? $error['stream'] : ''; ?>
                                        <input type="text" name="stream" class="form-control" placeholder="stream" required>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="year">Year</label> <i class="text-danger asterik">*</i>
                                            <select name="year" class="form-control" required>
                                                <option value="">Select Year</option>
                                                <?php
                                                $currentYear = date("Y");
                                                for ($year = $currentYear; $year >= 1900; $year--) {
                                                    echo "<option value='$year'>$year</option>";
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
                                                foreach ($months as $num => $name) {
                                                    echo "<option value='$num'>$name</option>";
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
                                        <label for="mode_of_study">Mode of Study</label> <i class="text-danger asterik">*</i><?php echo isset($error['mode_of_study']) ? $error['mode_of_study'] : ''; ?>
                                        <input type="text" name="mode_of_study" class="form-control" placeholder="Mode of Study" required>
                                    </div>
                                    <div class='col-md-4'>
                                       <label for="hostel_facility">Hostel Facility</label> <i class="text-danger asterik">*</i>
                                       <select id='hostel_facility' name="hostel_facility" class='form-control' required>
                                          <option value='1'>Yes</option>
                                          <option value='0'>No</option>
                                       </select>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="application_fees">Application_fees</label> <i class="text-danger asterik">*</i><?php echo isset($error['application_fees']) ? $error['application_fees'] : ''; ?>
                                        <input type="text" name="application_fees" class="form-control" placeholder="Application Fees" required>
                                    </div>
                                </div>
                            </div>
                            <br> 
                                <div class="row">
                                 <div class="form-group">
                                    <div class='col-md-4'>
                                        <label for="duration">Duration</label> <i class="text-danger asterik">*</i><?php echo isset($error['duration']) ? $error['duration'] : ''; ?>
                                        <input type="text" name="duration" class="form-control" placeholder="Duration" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="total_fees">Total Fees</label> <i class="text-danger asterik">*</i><?php echo isset($error['total_fees']) ? $error['total_fees'] : ''; ?>
                                        <input type="text" name="total_fees" class="form-control" placeholder="Total Fees" required>
                                    </div>
                                    <div class='col-md-4'>
                                        <label for="paying_fees">Paying Fees</label> <i class="text-danger asterik">*</i><?php echo isset($error['paying_fees']) ? $error['paying_fees'] : ''; ?>
                                        <input type="text" name="paying_fees" class="form-control" placeholder="paying_fees" required>
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
        }

        return true;
    }
</script>

<!--code for page clear-->
<script>
    function refreshPage(){
        window.location.reload();
    } 
</script>
<?php $db->disconnect(); ?>
