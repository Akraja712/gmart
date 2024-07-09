<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;

?>
<?php
if (isset($_POST['btnAdd'])) {

    $name = $db->escapeString($_POST['name']);
    $pdf_file = '';

    if (empty($name)) {
        $error['name'] = " <span class='label label-danger'>Required!</span>";
    }
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $pdf_file = $_FILES['pdf_file']['name'];
        $target_path = 'upload/pdf/' . basename($pdf_file);
        if (!move_uploaded_file($_FILES['pdf_file']['tmp_name'], $target_path)) {
            $error['pdf_file'] = " <span class='label label-danger'>Failed to upload file!</span>";
        }
    } else {
        $error['pdf_file'] = " <span class='label label-danger'>Required!</span>";
    }

    if (!empty($name) && !empty($pdf_file) && empty($error['pdf_file'])) {

        $sql_query = "INSERT INTO pdf (name, pdf_file) VALUES ('$name', '$target_path')";
        $db->sql($sql_query);
        $result = $db->getResult();
        if (!empty($result)) {
            $result = 0;
        } else {
            $result = 1;
        }

        if ($result == 1) {
            $error['add_transaction'] = " <section class='content-header'><span class='label label-success'>Pdf Added Successfully</span></section>";
        } else {
            $error['add_transaction'] = " <span class='label label-danger'>Failed</span>";
        }
    }
}
?>
<section class="content-header">
    <h1>Add Pdf <small><a href='pdf.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Pdf</a></small></h1>

    <?php echo isset($error['add_transaction']) ? $error['add_transaction'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-10">

            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                </div><!-- /.box-header -->
                <!-- form start -->
                <form name="add_transaction_form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group">

                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Name</label><i class="text-danger asterik">*</i><?php echo isset($error['name']) ? $error['name'] : ''; ?>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1">Pdf File</label><i class="text-danger asterik">*</i><?php echo isset($error['pdf_file']) ? $error['pdf_file'] : ''; ?>
                                    <input type="file" class="form-control" name="pdf_file" accept="application/pdf" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Submit</button>
                        <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>

                </form>
            </div><!-- /.box -->
        </div>
    </div>
</section>

<div class="separator"> </div>
<script>
    function refreshPage() {
        window.location.reload();
    }
</script>
<?php $db->disconnect(); ?>
