<?php
//include ('OCI.inc');   
//Connect to the DB

$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = fourier.cs.iit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;

$conn = OCILogon("xli232","Xiaoli232",$db);

if($conn){
	echo $conn;
}

if (!$conn) {
  $e = oci_error();
  echo "$e"; 	
} 
else{
    echo "Successfully connected<br>";
}



////////////////////////////add course + teach course + organizaiton + forum///////////////////////////////////////////////
$id = 101;
$description = "math";
$taemail= "null";
$professor = "huhud@iit.edu";
//$title= "$description"."group";
$title= "$description group";
$type ="Group";
$admin2 = "null";
$password = strval( rand(100000, 999999));



$st = oci_parse($conn,'INSERT INTO Course VALUES(:p1,:p2,:p3)');
oci_bind_by_name($st, ':p1', $id);
oci_bind_by_name($st, ':p2', $description);
oci_bind_by_name($st, ':p3', $taemail);
oci_execute($st);  // executes and commits

$st = oci_parse($conn,'INSERT INTO TeachCourse VALUES(:p1,:p2)');
oci_bind_by_name($st, ':p1', $professor);
oci_bind_by_name($st, ':p2', $id);
oci_execute($st);  // executes and commits

$st = oci_parse($conn,'INSERT INTO Organization VALUES(:p1,:p2,:p3,:p4,:p5,:p6)');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $professor);
oci_bind_by_name($st, ':p4', $admin2);
oci_bind_by_name($st, ':p5', $admin2);
oci_bind_by_name($st, ':p6', $id);
oci_execute($st);  // executes and commits

$st = oci_parse($conn,'INSERT INTO Forum VALUES(:p1,:p2,:p3)');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $admin2);
oci_execute($st);  // executes and commits

$st = oci_parse($conn,'INSERT INTO Organization_Members VALUES(:p1,:p2,:p3,:p4,:p5)');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $professor);
oci_bind_by_name($st, ':p4', $professor);
oci_bind_by_name($st, ':p5', $password);
oci_execute($st);  // executes and commits

$st = oci_parse($conn,'INSERT INTO Forum_Member VALUES (:p1,:p2,:p3,:p4,:p5)');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $professor);
oci_bind_by_name($st, ':p4', $professor);
oci_bind_by_name($st, ':p5', $password);
oci_execute($st);  // executes and commits




oci_free_statement($st);
//close connection to the database
oci_close($conn);



?>
