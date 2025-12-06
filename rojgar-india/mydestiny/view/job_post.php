<?php
setcontroller('jobposts');
$jobobj = new Jobposts();
//$job_lists = $jobobj->getjoblist();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card_with_button">
                <div class="header pull-left">
                    <h4 class="title">Posted Jobs</h4>
                    
                </div>
                <div class="header pull-right">
                    <a href="?view=addjob" class="btn btn-info btn-fill">Add New Job</a>
                </div>
                <div class="clearfix"></div>
                <div style="padding: 15px">
                    <?php
                    if(isset($_SESSION['message']) && $_SESSION['message'] != "")
                    {
                        echo $_SESSION['message'];unset($_SESSION['message']);
                    }
                    ?>
                </div>
                <div class="content table-responsive">
                    <table id="example" class="table table-hover table-striped table-responsive">
                        <thead>
                            <th>ID</th>
                            <th>Job Title</th>
                            <th>Job location</th>
                            <th>Experience</th>
                            <th>Max Experience</th>
                            <th>Salary range</th>
                            <th>Max Salary range</th>
                            <th>Organization</th>
                            <th>Date Added</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#example').DataTable( {
            "processing": true,
            "serverSide": true,
            "autoWidth": false,
            "ajax": "code/dataTableAjax.php?type=getjobpost",
            "columnDefs": [
            {"targets": 3,"render": function ( data, type, row ) {return data +' to '+ row[4];}},
            {"targets": 5,"render": function ( data, type, row ) {return data +' to '+ row[6] + ' annually';}},
            {"targets": 10,"render": function ( data, type, row ) {return '<div class="dropdown"><a href="#" class="dropdown-toggle" id="dropdownMenuOffset" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action <b class="caret"></b></a><div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuOffset"><li><a class="dropdown-item" href="#">View</a></li><li><a class="dropdown-item" href="?view=addjob&action=edit&id='+ row[0] +'">Edit</a></li><li><a onclick="return confirm(\'Are you sure? You want to delete this record?\');" class="dropdown-item" href="?view=job_post&action=delete&id='+ row[0] +'">Delete</a></li></div></div>';},"orderable": false},
            { "visible": false,  "targets": [ 4,6 ] }
            ]
        } );
    } );
</script>