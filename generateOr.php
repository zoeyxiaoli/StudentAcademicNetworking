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

   <h1> Generate a group or club for discussion and study</h1>

   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <fieldset><legend>Personal information registration</legend>
   <table>

   <tr>
      <input type="radio" name="type" value="Group">Group
      <input type="radio" name="type" value="Club">Club
   </tr>

   <tr>
      <td>Organization Name</td>
      <td align = "center"><input type="text" name = "title" size="30"/></td>
   </tr>

   <tr>
      <td>Creater Email</td>
      <td align = "center"><input type="text" name = "name" size="30"/></td>
   </tr>
  

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>

   </table>
   </fieldset>
   </form>

 


<?php

   $type = $title = $name ="";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //header('Location: login.php');
    //die();
   $name = $_POST["name"];
   $title = $_POST["title"];
   $type = $_POST["type"];



//include ('OCI.inc');   
//Connect to the DB
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = fourier.cs.iit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;
$conn = OCILogon("xli232","Xiaoli232",$db);
if($conn){
  echo $conn;
}
if (!$conn) {
  $e = oci_error();
  echo "$e";  
} 
else{
    echo "Successfully connected<br>";
}


//produce username and password and insert into organization table
$def = "null";
$defn = Null;

$st = oci_parse($conn,'INSERT INTO Organization VALUES(:p1,:p2,:p3,:p4,:p5,:p6)');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $name);
oci_bind_by_name($st, ':p4', $defn);
oci_bind_by_name($st, ':p5', $def);
oci_bind_by_name($st, ':p6', $defn);

$r = oci_execute($st);  // executes and commits
if ($r) {
    print "One row inserted<br>";
}


//insert into forum table 
$st = oci_parse($conn,'INSERT INTO Forum VALUES(:p1,:p2,:p3)');
oci_bind_by_name($st, ':p1', $title);
oci_bind_by_name($st, ':p2', $type);
oci_bind_by_name($st, ':p3', $name);



$r = oci_execute($st);  // executes and commits
if ($r) {
    print "One row inserted<br>";
}

oci_free_statement($st);
//close connection to the database
oci_close($conn);


}
?>

  <a href = "login.php">Log in</a>

</body>
</html>