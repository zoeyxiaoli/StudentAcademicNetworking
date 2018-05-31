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

   <h1> Search Faculty Information</h1>
   
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

$st = oci_parse($conn,'SELECT * FROM FACULTY Where EMAIL=:p1');
oci_bind_by_name($st,':p1',$email);
oci_execute($st);

$num=oci_fetch_ALL($st,$res);
if ($num==0){
  echo "Not found.<br>\n";
}
else      //record found
{
  FOREACH($res['STATUS'] AS $status){
  if ($status=="private") 
    {
      $visible=0;ECHO "Information is private.<br>\n";
    }
  else $visible=1;
}

if ($visible==1)
{
  foreach($res['NAME'] as $name){
    echo  "Name:    $name<br>";
   }
   foreach($res['EMAIL'] as $email){
    echo  "Email:    $email<br>";
   }
   foreach($res['YEAR'] as $year){
    echo  "Which year join IIT:    $year<br>";
   }
   foreach($res['POSITION'] as $position){
    echo  "Current posotion:    $position<br>";
   }
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