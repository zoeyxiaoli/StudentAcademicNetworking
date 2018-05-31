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

   <h1> Display 5 most recently comments from all groups that a student has registered to</h1>
   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <table>

   <tr>
      <td>search by student</td>
      <td align = "center"><input type="text" name = "student" size="30"/></td>
   </tr>

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>

   </table>
   </form>


<?php

   $student="";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //header('Location: login.php');
    //die();
   $student = $_POST["student"];

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

$st = oci_parse($conn,'SELECT TITLE,TOPICS,COMMENTS,TIMESTRING FROM FORUM_COMMENTS Where ROWNUM<=5 AND EMAIL=:p1 ORDER BY TIME DESC');
oci_bind_by_name($st,':p1',$student);
oci_execute($st);

$num=oci_fetch_ALL($st,$res);
if ($num!=0)
  {
    oci_execute($st);
    $st2 = oci_parse($conn,'SELECT NAME FROM STUDENT WHERE EMAIL=:p2');
    oci_bind_by_name($st2, ':p2', $student);
    oci_execute($st2);
    $res2=oci_fetch_row($st2);
    $name=$res2[0];
    echo "</table>\n";
    echo "<h2>5 most recent comments from all groups that student $name has registered to</h2>";
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


oci_free_statement($st);



//close database connection
oci_close($conn);


}
?>

<a href = "admin.php">Back</a>
</body>
</html>