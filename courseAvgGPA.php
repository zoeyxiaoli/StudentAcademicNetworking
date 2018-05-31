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

   <h1> Display the past average GPA of all the courses taught by a faculty</h1>
   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <table>

   <tr>
      <td>search by email</td>
      <td align = "center"><input type="text" name = "email" size="30"/></td>
   </tr>

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>

   </table>
   </form>


<?php

   $email="";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //header('Location: login.php');
    //die();
   $email = $_POST["email"];

//echo $email;

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

$st = oci_parse($conn,'SELECT AVG(TAKECOURSE.POINTS) FROM TEACHCOURSE, TAKECOURSE Where TEACHCOURSE.ID=TAKECOURSE.ID AND TEACHCOURSE.EMAIL=:p1');
oci_bind_by_name($st,':p1',$email);
oci_execute($st);


$num=oci_fetch_ALL($st,$res);
if ($num==0){
  echo "Not found.<br>\n";
}
else      //record found
{
  foreach($res['AVG(TAKECOURSE.POINTS)'] as $points){
    echo "This professor's past average GPA is $points<br>";

  }
}


oci_free_statement($st);



//close database connection
oci_close($conn);
}


?>
<a href = "admin.php">Back</a>

</body>
</html>