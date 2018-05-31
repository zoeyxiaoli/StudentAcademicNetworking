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

   <h1> Comments filtering</h1>
   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <table>

   <tr>
      <td>Forum Title</td>
      <td align = "center"><input type="text" name = "title" size="30"/></td>
   </tr>

    <tr>
      <td>Forum Type</td>
      <td align = "center"><input type="text" name = "type" size="30"/></td>
   </tr>

   <tr>
      <td>Filtering keyword</td>
      <td align = "center"><input type="text" name = "key" size="30"/></td>
   </tr>

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>

   </table>
   </form>


<?php
    session_start();
    $user = $_SESSION['user'];
   $title = $type = $key = "";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $title = $_POST["title"];
   $type = $_POST["type"];
   $key = $_POST["key"];


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


$st=oci_parse($conn, 'SELECT * FROM ORGANIZATION WHERE (ADMIN_A=:p3 OR ADMIN_B=:p3)AND TITLE=:p1 AND TYPE=:p2');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $user);
oci_execute($st);
$num=oci_fetch_all($st, $res);

if ($num!=0)
{
$st=oci_parse($conn, 'DELETE FROM FORUM_COMMENTS WHERE TITLE=:p1 AND TYPE=:p2 AND INSTR(COMMENTS,:p3)>0');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $key);
oci_execute($st);
echo "Filtering executed.<br>\n";
} else echo "You are not the moderator.<br>\n";



oci_free_statement($st);
//close database connection
oci_close($conn);


}
?>

<a href = "home.php">Back</a>
</body>
</html>