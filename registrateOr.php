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

   <h1> Group or Club Registration</h1>

   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <fieldset><legend>Personal information registration</legend>
   <table>

   <tr>
      <input type="radio" name="type" value="Group">Group
      <input type="radio" name="type" value="Club">Club
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
      <td>Organization Title</td>
      <td align ="center"><input type="text" name="title" size="30"/></td>
   </tr>



   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>

   </table>
   </fieldset>
   </form>

 


<?php


   $email = $name = $title= $type ="";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //header('Location: login.php');
    //die();
   $name = $_POST["name"];
   $email = $_POST["email"];
   $title = $_POST["title"];
   $type=$_POST["type"];
  


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


//decide if he has the permit to registrate into the organization
$st = oci_parse($conn,'SELECT * FROM Permit WHERE EMAIL = :p1 AND TITLE = :P2 AND TYPE =:P3');
oci_bind_by_name($st, ':p1', $email);
oci_bind_by_name($st, ':p2', $title);
oci_bind_by_name($st, ':p3', $type);
oci_execute($st);

$results=array();
$num=oci_fetch_all($st, $res);

if($num == 0){
  echo "Sorry, you don't have the permit to registrate in this $title";
}else{
  //produce username and password and insert into login table
$password = strval( rand(100000, 999999));
$st = oci_parse($conn,'INSERT INTO Organization_Members VALUES(:p1,:p2,:p3,:p4,:p5)');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $email);
oci_bind_by_name($st, ':p4', $email);
oci_bind_by_name($st, ':p5', $password);
oci_execute($st);  // executes and commits

$st = oci_parse($conn,'INSERT INTO Forum_Member VALUES(:p1,:p2,:p3,:p4,:p5)');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $email);
oci_bind_by_name($st, ':p4', $email);
oci_bind_by_name($st, ':p5', $password);

oci_execute($st);  // executes and commits

echo "Your username for the $title $type and forum is $email<br>";
echo "Your password or the $title $type and forum is $password<br>";



//insert into student table 
/*$st = oci_parse($conn,'INSERT INTO Student VALUES(:p1,:p2,:p3,:p4,:p5,:p6,:p7)');
oci_bind_by_name($st, ':p1', $email);
oci_bind_by_name($st, ':p2', $name);
oci_bind_by_name($st, ':p3', $year);
oci_bind_by_name($st, ':p4', $semester);
oci_bind_by_name($st, ':p5', $degreestatus);
oci_bind_by_name($st, ':p6', $GPA);
oci_bind_by_name($st, ':p7', $status);
oci_execute($st);  // executes and commits*/


}



oci_free_statement($st);
//close connection to the database
oci_close($conn);


}
?>

  <a href = "loginOr.php">Login  a group or club<br></a> 

</body>
</html>