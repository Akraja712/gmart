<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

if (isset($_GET['id'])) {
    $ID = $db->escapeString($_GET['id']);
} else {
    // ID is not set, return false and exit script
    return false;
    exit(0);
}

if (isset($_POST['btnEdit'])) {

    $name = $db->escapeString($_POST['name']);
    $pdf_file = '';
    $error = array();

    // Handle file upload
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $pdf_file = $_FILES['pdf_file']['name'];
        $target_path = 'upload/pdf/' . basename($pdf_file);
        if (!move_uploaded_file($_FILES['pdf_file']['tmp_name'], $target_path)) {
            $error['pdf_file'] = " <span class='label label-danger'>Failed to upload file!</span>";
        }
    } else {
        $target_path = $_POST['existing_pdf_file'];
    }

    if (!empty($name) && !empty($target_path)) {
        $sql_query = "UPDATE pdf SET name='$name', pdf_file='$target_path' WHERE id = $ID";
        $db->sql($sql_query);
        $update_result = $db->getResult();
        if (!empty($update_result)) {
            $update_result = 0;
        } else {
            $update_result = 1;
        }

        // Check update result
        if ($update_result == 1) {
            $error['update_transaction'] = " <section class='content-header'><span class='label label-success'>Pdf updated Successfully</span></section>";
        } else {
            $error['update_transaction'] = " <span class='label label-danger'>Failed to Update</span>";
        }
    }
}

// Create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM pdf WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
    <script>
        window.location.href = "pdf.php";
    </script>
<?php } ?>
<section class="content-header">
    <h1>
        Edit Pdf<small><a href='pdf.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Pdf</a></small>
    </h1>
    <small><?php echo isset($error['update_transaction']) ? $error['update_transaction'] : ''; ?></small>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <!-- Main row -->
    <div class="row">
        <div class="col-md-10">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                </div><!-- /.box-header -->
                <!-- form start -->
                <form id="edit_user_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Name</label><i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="name" value="<?php echo $res[0]['name']; ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Pdf File</label><i class="text-danger asterik">*</i>
                                    <input type="file" class="form-control" name="pdf_file" accept="application/pdf">
                                    <input type="hidden" name="existing_pdf_file" value="<?php echo $res[0]['pdf_file']; ?>">
                                    <br>
                                    <label>Existing File:</label> <a href="<?php echo $res[0]['pdf_file']; ?>" target="_blank"><?php echo basename($res[0]['pdf_file']); ?></a>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnEdit">Update</button>
                    </div>
                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<?php $db->disconnect(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
