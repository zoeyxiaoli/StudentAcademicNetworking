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

   <h1> Search Student Information</h1>
   
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

$st = oci_parse($conn,'SELECT * FROM STUDENT Where EMAIL=:p1');
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
   foreach($res['BEGIN_YEAR'] as $year){
    echo  "Year began pursuing degree:    $year<br>";
   }
   foreach($res['BEGIN_SEMESTER'] as $semester){
    echo  "Semester began pursuing degree:    $semester<br>";
   }
   foreach($res['DEG_STATUS'] as $degree){
    echo  "Degree type:    $degree<br>";
   }
   foreach($res['GPA'] as $gpa){
    echo  "GPA:    $gpa<br>";
   }




   $st=oci_parse($conn, 'SELECT ID,DESCRIPTION FROM COURSE WHERE TAEMAIL=:p1');
    oci_bind_by_name($st, ':p1', $email);
    oci_execute($st);
    $num2=oci_fetch_all($st, $res2);
    if ($num2!=0)
    {
      oci_execute($st);
      echo "<h4>TA of the following course(s)</h4>";
      echo "<table border='1'>\n";
      while ($row = oci_fetch_array($st, OCI_ASSOC+OCI_RETURN_NULLS)) {
      echo "<tr>\n";
      foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
      }
      echo "</tr>\n";
    }
    echo "</table>\n";
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