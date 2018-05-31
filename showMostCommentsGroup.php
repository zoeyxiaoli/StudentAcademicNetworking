<!DOCTYPE html>
<html>

<head>
      <style>
         body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #ADABAB;
         }
      </style>     
</head>


<body>

<?php
//include ('OCI.inc');   
//Connect to the DB

$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = fourier.cs.iit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;

$conn = OCILogon("xli232","Xiaoli232",$db);
/*
if($conn){
	echo $conn;
}*/

if (!$conn) {
  $e = oci_error();
  echo "$e"; 	
} 
else{
    echo "Successfully connected<br>";
}


/////////////////////////////////////////////////////////////////show information
$st = oci_parse($conn,'SELECT TITLE FROM (SELECT TITLE FROM FORUM_COMMENTS GROUP BY TITLE ORDER BY COUNT(COMMENTS)DESC) WHERE ROWNUM<=1');
oci_execute($st);


$num=oci_fetch_ALL($st,$res);
if ($num==0){
  echo "Not found.<br>\n";
}
else      //record found
{
	foreach($res['TITLE'] as $title){
		echo "The most commented group is $title<br>";
	}
}

?>

<a href = "admin.php">Back</a>
</body>
</html>