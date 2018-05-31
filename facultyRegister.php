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

   <h1> Interactive Students/ Faculties Netwotking Registration</h1>

   <h1> Faculty Information Registration</h1>
   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <fieldset><legend>Personal information registration</legend>
   <table>

   <tr>
      <input type="radio" name="status" value="public">Public
      <input type="radio" name="status" value="private">Private
   </tr>

   <tr>
      <td>Name</td>
      <td align = "center"><input type="text" name = "name" size="30"/></td>
   </tr>

    <tr>
      <td>Email</td>
      <td align ="center"><input type="text" name="email" size="30"/></td>
   </tr>

    <tr>
      <td>YearBeginDegree</td>
      <td align ="center"><input type="text" name="year" size="30"/></td>
   </tr>

    <tr>
      <td>Position</td>
      <td align ="center"><input type="text" name="position" size="30"/></td>
   </tr>
  

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>

   </table>
   </fieldset>
   </form>


<?php

   $email = $name = $year = $position =$status="";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //header('Location: login.php');
    //die();
   $name = $_POST["name"];
   $email = $_POST["email"];
   $year = $_POST["year"];
   $position=$_POST["position"];
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

//produce username and password and insert into login table
$password = strval( rand(100000, 999999));
$admin = "No";
$st = oci_parse($conn,'INSERT INTO Login VALUES(:p1,:p2,:p3,:p4)');
oci_bind_by_name($st, ':p1', $email);
oci_bind_by_name($st, ':p2', $email);
oci_bind_by_name($st, ':p3', $password);
oci_bind_by_name($st, ':p4', $admin);

$r = oci_execute($st);  // executes and commits
if ($r) {
    print "One row inserted<br>";
}
echo "Your username is $email<br>";
echo "Your password is $password<br>";
oci_free_statement($st);



//insert into faculty table 
$st = oci_parse($conn,'INSERT INTO Faculty VALUES(:p1,:p2,:p3,:p4,:p5)');
oci_bind_by_name($st, ':p1', $email);
oci_bind_by_name($st, ':p2', $year);
oci_bind_by_name($st, ':p3', $name);
oci_bind_by_name($st, ':p4', $position);
oci_bind_by_name($st, ':p5', $status);

$r = oci_execute($st);  // executes and commits
if ($r) {
    print "One row inserted<br>";
}

oci_free_statement($st);

//close database connection
oci_close($conn);


}
?>

   <a href = "login.php">Log in</a>

</body>
</html>