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

<h1>Forum Information</h1>

<form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <fieldset><legend>Topics and Comments</legend>
   <table>

   <tr>
      <td>Topic</td>
      <td align = "center"><input type="text" name = "topic" size="30"/></td>
   </tr>

   <tr>
      <td>Comment</td>
      <td align = "center"><input type="text" name = "comment" size="30"/></td>
   </tr>
  

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>

   </table>
   </fieldset>
   </form>


<?php
//include ('OCI.inc');   
//Connect to the DB

$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = fourier.cs.iit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;

$conn = OCILogon("xli232","Xiaoli232",$db);

if (!$conn) {
  $e = oci_error();
  echo "$e"; 	
} 
else{
    echo "Successfully connected<br>";
}


//find the information of user and display
session_start();
$title = $_SESSION['titleOr'];
$type = $_SESSION['typeOr'];
$email = $_SESSION['userOr'];
//echo "$title<br>\n";
//echo "$type<br>\n";
//echo "$email<br>\n";

$st = oci_parse($conn,'SELECT TOPICS, EMAIL, COMMENTS, TIMESTRING FROM Forum_comments WHERE TITLE= :p1 AND TYPE =:p2 ORDER BY TOPICS,TIME');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_execute($st);
echo "<h2>$title $type comments</h2>";
echo "<table border='1'>\n";
while ($row = oci_fetch_array($st, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table>\n";


$topic = $comment = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $topic = $_POST["topic"];
   $comment = $_POST["comment"];

$time = time();
// $timeString = to_char($time);
// echo $time."<br>";
$timeString=(string)date("Y-n-d H:i:s",$time);
$st = oci_parse($conn,"INSERT INTO Forum_comments VALUES(:p1,:p2,:p3,:p4,:p5,:p6,:p7)");
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $topic);
oci_bind_by_name($st, ':p4', $email);
oci_bind_by_name($st, ':p5', $comment);
oci_bind_by_name($st, ':p6', $time);
oci_bind_by_name($st, ':p7', $timeString);

$r = oci_execute($st);  // executes and commits
if ($r) {
    print "One row inserted<br>";
}



 }


    oci_free_statement($st);
    //unset($_SESSION['user']);

//close connection to the database
oci_close($conn);
?>


<a href = "home.php">Back</a>

</body>
</html>