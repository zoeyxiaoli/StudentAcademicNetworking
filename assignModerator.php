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

   <h1> Assign moderator</h1>
   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <table>

   <tr>
      <td>Organization  Title</td>
      <td align = "center"><input type="text" name = "title" size="30"/></td>
   </tr>

    <tr>
      <td>Organization Type</td>
      <td align = "center"><input type="text" name = "type" size="30"/></td>
   </tr>

   <tr>
      <td>Email</td>
      <td align = "center"><input type="text" name = "email" size="30"/></td>
   </tr>

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>

   </table>
   </form>


<?php
    //session_start();
    //$user = $_SESSION['user'];
   $title = $type = $email = "";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $title = $_POST["title"];
   $type = $_POST["type"];
   $email = $_POST["email"];


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



$st=oci_parse($conn, 'SELECT * FROM ORGANIZATION WHERE TITLE=:p1 AND TYPE=:p2');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_execute($st);
$num=oci_fetch_all($st, $res);

if ($num!=0)
{
$st=oci_parse($conn, 'SELECT * FROM ORGANIZATION_MEMBERS WHERE EMAIL=:p3 AND TITLE=:p1 AND TYPE=:p2 ');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $email);
oci_execute($st);
$num=oci_fetch_all($st, $res);

if ($num==0){echo "The student is not a member.<br>\n";}
else
{
$st=oci_parse($conn, 'UPDATE ORGANIZATION SET ADMIN_B=:p3 WHERE TITLE=:p1 AND TYPE=:p2 ');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $email);
oci_execute($st);
echo "Moderator assigned.<br>\n";

}


} else echo "No forum found.<br>\n";



oci_free_statement($st);
//close database connection
oci_close($conn);


}
?>

<a href = "admin.php">Back</a>
</body>
</html>