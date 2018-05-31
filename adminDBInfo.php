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
    echo "Successfully connected";
}



/////////////////////////////////////////////////////////////////show information
$st = oci_parse($conn,'SELECT * FROM Login');
oci_execute($st);
echo "<h2>Login Table</h2>";
echo "<table border='1'>\n";
while ($row = oci_fetch_array($st, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";


$st = oci_parse($conn,'SELECT * FROM Student');
oci_execute($st);
echo "<h2>Student Table</h2>";
echo "<table border='1'>\n";
while ($row = oci_fetch_array($st, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

$st = oci_parse($conn,'SELECT * FROM TakeCourse');
oci_execute($st);
echo "<h2>Student take course Table</h2>";
echo "<table border='1'>\n";
while ($row = oci_fetch_array($st, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

$st = oci_parse($conn,'SELECT * FROM Faculty');
oci_execute($st);
echo "<h2>Faculty Table</h2>";
echo "<table border='1'>\n";
while ($row = oci_fetch_array($st, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

$st = oci_parse($conn,'SELECT * FROM TeachCourse');
oci_execute($st);
echo "<h2>Faculty teach course Table</h2>";
echo "<table border='1'>\n";
while ($row = oci_fetch_array($st, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";


$st = oci_parse($conn,'SELECT * FROM Course');
oci_execute($st);
echo "</table>\n";
echo "<h2>Course Table</h2>";
echo "<table border='1'>\n";
while ($row = oci_fetch_array($st, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";



$st = oci_parse($conn,'SELECT * FROM Organization');
oci_execute($st);
echo "</table>\n";
echo "<h2>Organization Table</h2>";
echo "<table border='1'>\n";
while ($row = oci_fetch_array($st, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

$st = oci_parse($conn,'SELECT * FROM Organization_Members');
oci_execute($st);
echo "<h2>Organization member Table</h2>";
echo "<table border='1'>\n";
while ($row = oci_fetch_array($st, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

$st = oci_parse($conn,'SELECT * FROM Forum');
oci_execute($st);
echo "<h2>Forum Table</h2>";
echo "<table border='1'>\n";
while ($row = oci_fetch_array($st, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

$st = oci_parse($conn,'SELECT * FROM Forum_Comments');
oci_execute($st);
echo "<h2>Forum Comments Table</h2>";
echo "<table border='1'>\n";
while ($row = oci_fetch_array($st, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";

oci_free_statement($st);
//close connection to the database
oci_close($conn);

?>
