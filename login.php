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

   <h1> Interactive Students/ Faculties Netwotking Login</h1>
   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "POST">
   <table>

   <tr>
      <td>Username</td>
      <td align = "center"><input type="text" name = "username" size="30"/></td>
   </tr>

   <tr>
      <td>Password</td>
      <td align ="center"><input type="password" name="password" size="30"/></td>
   </tr>

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>



   </table>
   </form>

   <?php

   $username = $password = "";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $username = $_POST["username"];
   $password = $_POST["password"];

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

   $st = oci_parse($conn,'SELECT * FROM Login');
   oci_execute($st);

    session_start();
    $_SESSION['user'] = $username;

   while (oci_fetch($st)) {
      if(oci_result($st, 'USERNAME')==$username&&oci_result($st, 'PASSWORD')==$password){
         echo "ok,find it";
         header("Location: home.php");
         break;
      }
   }

  
   


   oci_free_statement($st);
   //close connection to the database
   oci_close($conn);
      
   }

   ?>
   
<a href = "studentRegister.php">Student registration<br></a>
<a href = "facultyRegister.php">Faculty registration<br></a>
<a href = "adminLogin.php">administrator login<br></a>

</body>
</html>