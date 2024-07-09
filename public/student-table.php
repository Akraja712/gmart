
<section class="content-header">
    <h1>Student /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="add-student.php"><i class="fa fa-plus-square"></i> Add Student</a>
    </ol>
</section>

    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <div class="col-xs-12">
                <div class="box">
                    
                    <div  class="box-body table-responsive">
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=student" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "students-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                <th  data-field="operate" data-events="actionEvents">Action</th>
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th data-field="form_no" data-sortable="true">Form No</th>
                                    <th data-field="registration_no" data-sortable="true">Registration No</th>
                                    <th data-field="image">Photo</th>
                                    <th data-field="name" data-sortable="true">Name</th>
                                    <th data-field="fathers_name" data-sortable="true">Father's Name</th>
                                    <th data-field="admission_year" data-sortable="true">Admission Year</th>
                                    <th data-field="dob" data-sortable="true">Date of Birth</th>
                                    <th data-field="gender" data-sortable="true">Gender</th>
                                    <th data-field="category_name" data-sortable="true">Category Name</th>
                                    <th data-field="nationality" data-sortable="true">Nationality</th>
                                    <th data-field="course_type" data-sortable="true">Course Type</th>
                                    <th data-field="faculty" data-sortable="true">Faculty</th>
                                    <th data-field="course_name" data-sortable="true">Course Name</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="separator"> </div>
        </div>
    </section>

<script>
    $('#seller_id').on('change', function() {
        $('#products_table').bootstrapTable('refresh');
    });
    $('#community').on('change', function() {
        $('#users_table').bootstrapTable('refresh');
    });

    function queryParams(p) {
        return {
            "category_id": $('#category_id').val(),
            "seller_id": $('#seller_id').val(),
            "community": $('#community').val(),
            limit: p.limit,
            sort: p.sort,
            order: p.order,
            offset: p.offset,
            search: p.search
        };
    }
</script>