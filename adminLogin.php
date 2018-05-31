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

   <h1> Administrator Login Web</h1>
   
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

   $st = oci_parse($conn,"SELECT * FROM Login WHERE USERNAME = :P1 AND PASSWORD = :P2 AND ADMIN ='Yes' ");
   oci_bind_by_name($st,':p1',$username);
   oci_bind_by_name($st,':p2',$password);
   oci_execute($st);

    /*session_start();
    $_SESSION['user'] = $username;

   while (oci_fetch($st)) {
      if(oci_result($st, 'USERNAME')==$username && oci_result($st, 'PASSWORD')==$password){
         echo "ok,you are the administrator!";
         header("Location: admin.php");
         break;
      }
   }*/

   $num=oci_fetch_ALL($st,$res);
    if ($num!=0){
         echo "ok,you are the administrator!";
         header("Location: admin.php");
      }

  
   


   oci_free_statement($st);
   //close connection to the database
   oci_close($conn);
      
   }

   ?>


</body>
</html>