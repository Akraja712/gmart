
<section class="content-header">
    <h1>Admission /<small><a href="home.php"><i class="fa fa-home"></i> Home</a></small></h1>
    <ol class="breadcrumb">
        <a class="btn btn-block btn-default" href="add-admission.php"><i class="fa fa-plus-square"></i> Add Admission</a>
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
                    <table id='users_table' class="table table-hover" data-toggle="table" data-url="api-firebase/get-bootstrap-table-data.php?table=admission" data-page-list="[5, 10, 20, 50, 100, 200]" data-show-refresh="true" data-show-columns="true" data-side-pagination="server" data-pagination="true" data-search="true" data-trim-on-search="false" data-filter-control="true" data-query-params="queryParams" data-sort-name="id" data-sort-order="desc" data-show-export="false" data-export-types='["txt","excel"]' data-export-options='{
                            "fileName": "students-list-<?= date('d-m-Y') ?>",
                            "ignoreColumn": ["operate"] 
                        }'>
                            <thead>
                                <tr>
                                <th  data-field="operate" data-events="actionEvents">Action</th>
                                    <th  data-field="id" data-sortable="true">ID</th>
                                    <th data-field="image">Photo</th>
                                    <th data-field="candidate_name" data-sortable="true">Candidate Name</th>
                                    <th data-field="fathers_name" data-sortable="true">Father's Name</th>
                                    <th data-field="mothers_name" data-sortable="true">Mother's Name</th>
                                    <th data-field="dob" data-sortable="true">Date of Birth</th>
                                    <th data-field="gender" data-sortable="true">Gender</th>
                                    <th data-field="category_name" data-sortable="true">Category Name</th>
                                    <th data-field="id_proof_type" data-sortable="true">Id Proof Type</th>
                                    <th data-field="id_proof_no" data-sortable="true">Id Proof No</th>
                                    <th data-field="center_name" data-sortable="true">Center Name</th>
                                    <th data-field="employeed" data-sortable="true">Employeed</th>
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