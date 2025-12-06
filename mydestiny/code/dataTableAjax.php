<?php
include('../../controller/application.php');
$app = new Application();

if($_GET['type'] == 'getjobpost')
{
// DB table to use
    $table = <<<EOT
    (select job_posts.*,GROUP_CONCAT(cities.name separator ", ") as cities_name from job_posts LEFT JOIN cities on find_in_set(cities.id, job_posts.job_location) GROUP BY id) temp
EOT;
     
    // Table's primary key
    $primaryKey = 'id';
     
    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database, while the `dt`
    // parameter represents the DataTables column identifier. In this case simple
    // indexes
    $columns = array(
        array( 'db' => 'id', 'dt' => 0 ),
        array( 'db' => 'job_title',  'dt' => 1 ),
        array( 'db' => 'cities_name',  'dt' => 2),
        array( 'db' => 'min_exp',  'dt' => 3 ),
        array( 'db' => 'max_exp',  'dt' => 4 ),
        array( 'db' => 'min_salary',  'dt' => 5),
        array( 'db' => 'max_salary',  'dt' => 6),
        array( 'db' => 'company_name',  'dt' => 7),
        array( 'db' => 'date_added',  'dt' => 8,'formatter' => function( $d, $row ) {
                return date( 'd M y h:i:s', strtotime($d));
            }),
        array( 'db' => 'status',  'dt' => 9)
    );
} 
// SQL server connection information
$sql_details = array(
    'user' => DBUSER,
    'pass' => DBPASSWORD,
    'db'   => DBNAME,
    'host' => HOST
);
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);
?>