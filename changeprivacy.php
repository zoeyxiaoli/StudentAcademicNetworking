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

   <h1> Change Privacy Information</h1>
   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <fieldset><legend>Change your privacy information</legend>
   <table>

   <tr>
      <input type="radio" name="status" value="public">Public
      <input type="radio" name="status" value="private">Private
   </tr>

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>

   </table>
   </fieldset>
   </form>


<?php

   $status="";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //header('Location: login.php');
    //die();
  
   $status=$_POST["status"];



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

session_start();
$user = $_SESSION['user'];

$st = oci_parse($conn, 'SELECT EMAIL FROM STUDENT WHERE EMAIL = :p1');
oci_bind_by_name($st, ':p1', $user);
oci_execute($st);
$num=oci_fetch_all($st, $res);

if($num ==0){
  $st2 = oci_parse($conn,'UPDATE FACULTY SET STATUS =:p2 WHERE EMAIL =:p1');
  oci_bind_by_name($st2, ':p1', $user);
  oci_bind_by_name($st2, ':p2', $status);
  oci_execute($st2);

}else{
  $st3 = oci_parse($conn,'UPDATE STUDENT SET STATUS =:p3 WHERE EMAIL =:p1');
  oci_bind_by_name($st3, ':p1', $user);
  oci_bind_by_name($st3, ':p3', $status);
  oci_execute($st3);
}




oci_free_statement($st);

//close database connection
oci_close($conn);


}
?>

   <a href = "home.php">Back</a>

</body>
</html>