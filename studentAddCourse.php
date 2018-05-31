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

   <h1> Add the courses you have taken</h1>

   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <fieldset><legend>Personal information registration</legend>
   <table>


   <tr>
      <td>CourseID</td>
      <td align = "center"><input type="text" name = "id" size="30"/></td>
   </tr>

    <tr>
      <td>Points</td>
      <td align ="center"><input type="text" name="points" size="30"/></td>
   </tr>
  

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>

   </table>
   </fieldset>
   </form>

 


<?php
 

   $points = 0;
   $id =0;

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //header('Location: login.php');
    //die();
   $id = $_POST["id"];
   $points = $_POST["points"];
  



//include ('OCI.inc');   
//Connect to the DB
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = fourier.cs.iit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;
$conn = OCILogon("xli232","Xiaoli232",$db);
/*if($conn){
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
  echo $_SESSION['user'];


$st = oci_parse($conn,'INSERT INTO TakeCourse VALUES(:p1,:p2,:p3)');
oci_bind_by_name($st, ':p1', $user);
oci_bind_by_name($st, ':p2', $id);
oci_bind_by_name($st, ':p3', $points);
oci_execute($st);  // executes and commits





oci_free_statement($st);
//close connection to the database
oci_close($conn);

}

?>
<a href = "home.php">Back</a>

</body>
</html>