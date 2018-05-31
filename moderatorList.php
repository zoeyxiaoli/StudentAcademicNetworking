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

   <h1> Display the list of all moderators, the group/club/course that they moderate and are members of</h1>
   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <table>

  

   <tr>
      <td colspan="2" align="center"><input type="submit" name="button1" value="Click Me"/></td>
   </tr>

   </table>
   </form>


<?php


   if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
$st=oci_parse($conn, " SELECT DISTINCT ADMIN_A FROM ORGANIZATION ");

oci_execute($st);

$num=oci_fetch_ALL($st,$res);
echo $num."<br>\n";
if ($num==0){
  echo "Not found.<br>\n";
}
else      //record found
{
  $i=0;
  $j=1;
  $k=1;
  foreach($res['ADMIN_A'] as $ad_A)
  {
    echo "<br>\n";
    $st2=oci_parse($conn,"SELECT DISTINCT TITLE,TYPE FROM ORGANIZATION WHERE Admin_A=:p1 OR Admin_B=:p1");
    oci_bind_by_name($st2, ":p1", $ad_A);
    oci_execute($st2);
    // $num=oci_fetch_ALL($st2,$res2);
    // if ($num!=0)
    {
      echo "$ad_A<br>\n";
      echo "<table border='1'>\n";
      while ($row = oci_fetch_array($st2, OCI_ASSOC+OCI_RETURN_NULLS)) 
      {
        echo "<tr>\n";
        foreach ($row as $item) 
        {
          echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
        }
        echo "</tr>\n";
      }
    echo "</table>\n";
    }
  }
  
  $st=oci_parse($conn, " SELECT DISTINCT ADMIN_B FROM ORGANIZATION WHERE ADMIN_B NOT IN (SELECT DISTINCT ADMIN_A FROM ORGANIZATION)");
  oci_execute($st);
  $num=oci_fetch_ALL($st,$res);
  if ($num!=0){
  
  foreach($res['ADMIN_B'] as $ad_A)
  {
    echo "<br>\n";
    $st2=oci_parse($conn,"SELECT DISTINCT TITLE,TYPE FROM ORGANIZATION WHERE Admin_A=:p1 OR Admin_B=:p1");
    oci_bind_by_name($st2, ":p1", $ad_A);
    oci_execute($st2);
    // $num=oci_fetch_ALL($st2,$res2);
    // if ($num!=0)
    {
      echo "$ad_A<br>\n";
      echo "<table border='1'>\n";
      while ($row = oci_fetch_array($st2, OCI_ASSOC+OCI_RETURN_NULLS)) 
      {
        echo "<tr>\n";
        foreach ($row as $item) 
        {
          echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
        }
        echo "</tr>\n";
      }
    echo "</table>\n";
    }
  }
}
}

echo "<br>\n";




oci_free_statement($st);



//close database connection
oci_close($conn);
}


?>
<a href = "admin.php">Back</a>

</body>
</html>