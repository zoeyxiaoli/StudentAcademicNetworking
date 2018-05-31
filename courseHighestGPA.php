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

   <h1> Display the courses with the highest and lowest average GPA by a faculty and by all faculties</h1>
   
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

/*$st = oci_parse($conn,'SELECT UNIQUE ID FROM TAKECOURSE Where TAKECOURSE.POINTS IN (SELECT MAX(AVG(TAKECOURSE.POINTS)) FROM TAKECOURSE,TEACHCOURSE WHERE TEACHCOURSE.ID IN(SELECT TEACHCOURSE.ID FROM TAKECOURSE,TEACHCOURSE WHERE TEACHCOURSE.EMAIL=:p1 AND TEACHCOURSE.ID=TAKECOURSE.ID) GROUP BY TEACHCOURSE.ID)');*/
$st=oci_parse($conn, " SELECT ID, AVG(POINTS) AS AVGP FROM TAKECOURSE WHERE ID IN(SELECT ID FROM TEACHCOURSE WHERE EMAIL=:p1)
   GROUP BY ID ");
oci_bind_by_name($st,':p1',$email);
oci_execute($st);

$num=oci_fetch_ALL($st,$res);
if ($num==0){
  echo "Not found.<br>\n";
}
else      //record found
{
  $i=0;
  $j=1;
  $k=1;
  foreach($res['AVGP'] as $avg)
  {
    $i=$i+1;
    if ($i==1)
    {
      $maxp=$avg;
      $minp=$avg;
    }
    else
    {
      if ($maxp<$avg) {$maxp=$avg;$j=$i;}
      if ($minp>$avg) {$minp=$avg;$k=$i;}
    }
  }
  $i=0;
  foreach($res['ID'] as $id)
  {
    $i=$i+1;
    if ($i==$j) $maxID=$id;
    if ($i==$k) $minID=$id;
  }
  echo "Professor teaching statistics:<br>\n";
  echo "The course with the highest average points is ".$maxID .".<br>\n";
  echo "The course with the lowest average points is ".$minID .".<br>\n";


}

echo "<br>\n";

$st=oci_parse($conn, " SELECT ID, AVG(POINTS) AS AVGP FROM TAKECOURSE GROUP BY ID ");
oci_execute($st);

$num=oci_fetch_ALL($st,$res);
if ($num==0){
  echo "No course found.<br>\n";
}
else      //record found
{
  $i=0;
  $j=1;
  $k=1;
  foreach($res['AVGP'] as $avg)
  {
    $i=$i+1;
    if ($i==1)
    {
      $maxp=$avg;
      $minp=$avg;
    }
    else
    {
      if ($maxp<$avg) {$maxp=$avg;$j=$i;}
      if ($minp>$avg) {$minp=$avg;$k=$i;}
    }
  }
  $i=0;
  foreach($res['ID'] as $id)
  {
    $i=$i+1;
    if ($i==$j) $maxID=$id;
    if ($i==$k) $minID=$id;
  }

  echo "Overall teaching statistics:<br>\n";
  echo "The course with the overall highest average points is ".$maxID .".<br>\n";
  echo "The course with the overall lowest average points is ".$minID .".<br>\n";
}










oci_free_statement($st);



//close database connection
oci_close($conn);
}


?>

<a href = "admin.php">Back</a>
</body>
</html>