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

   <h1> Administrator SQL</h1>

   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "post">
   <fieldset><legend>SQL STATEMENT</legend>
   <table>



   <tr>  
      <textarea  name = "statement" size="30" rows="4" cols="50"/></textarea>
   </tr>

  

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>

   </table>
   </fieldset>
   </form>

<?php
$statement = "";
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $statement = $_POST["statement"];

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
    $st=oci_parse($conn, $statement);
    oci_execute($st);
    $num=oci_fetch_all($st, $res);
    if ($num!=0)
    {
      // echo $num;
      $st=oci_parse($conn, $statement);
      oci_execute($st);
      echo "<h4>Query result</h4>";
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



oci_free_statement($st);
//close connection to the database
oci_close($conn);


}
?>
</body>
</html>