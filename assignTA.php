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

   <h1> Assign TA to the course</h1>
   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <table>

   <tr>
      <td>TAemail</td>
      <td align = "center"><input type="text" name = "email" size="30"/></td>
   </tr>

    <tr>
      <td>CourseID</td>
      <td align = "center"><input type="text" name = "id" size="30"/></td>
   </tr>

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>

   </table>
   </form>


<?php

   $email = $id = "";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $email = $_POST["email"];
   $id = $_POST["id"];


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
   //echo "$user<br>\n";

$st = oci_parse($conn,'SELECT * FROM TeachCourse WHERE EMAIL =:p1 AND ID=:p2');
oci_bind_by_name($st,':p1',$user);
oci_bind_by_name($st,':p2',$id);
oci_execute($st);
$num = oci_fetch_ALL($st,$res);

if ($num==0){
  echo "You don't teach the course";
}else{
  $st1 = oci_parse($conn,'SELECT * FROM TakeCourse WHERE EMAIL =:p1 AND ID=:p2');
  oci_bind_by_name($st1,':p1',$email);
  oci_bind_by_name($st1,':p2',$id);
  oci_execute($st1);
  $num1 = oci_fetch_ALL($st1,$res1);
  if($num1==0){
    
    $st = oci_parse($conn,'UPDATE Course SET TAEMAIL =:p1 WHERE ID=:p2');
oci_bind_by_name($st,':p1',$email);
oci_bind_by_name($st,':p2',$id);
oci_execute($st);
  }else{
       echo "The student take the course can not be a TA";
  }
}



oci_free_statement($st);
//close database connection
oci_close($conn);


}
?>

<a href = "home.php">Back</a>
</body>
</html>