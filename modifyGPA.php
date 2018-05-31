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

   <h1> Professor can modify students' GPA</h1>
   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "POST">
   <table>

   <tr>
      <td>Students Email</td>
      <td align = "center"><input type="text" name = "email" size="30"/></td>
   </tr>

   <tr>
      <td>Course ID</td>
      <td align ="center"><input type="text" name="id" size="30"/></td>
   </tr>

    <tr>
      <td>Credits</td>
      <td align ="center"><input type="text" name="credit" size="30"/></td>
   </tr>

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>



   </table>
   </form>

   <?php

   $email = $id = $credit = "";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $email = $_POST["email"];
   $id = $_POST["id"];
   $credit = $_POST["credit"];

   //Connect to the DB
   $db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = fourier.cs.iit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;

   $conn = OCILogon("xli232","Xiaoli232",$db);

   session_start();
   $username=$_SESSION['user'];
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
   $st = oci_parse($conn,'SELECT * FROM TEACHCOURSE WHERE EMAIL=:p1 AND ID=:p2');
   oci_bind_by_name($st, ':p1', $username);
   oci_bind_by_name($st, ':p2', $id);
   oci_execute($st);
   $num=oci_fetch_all($st, $res);
   if ($num!=0)
   {
   $st = oci_parse($conn,'SELECT POINTS FROM TAKECOURSE WHERE EMAIL=:p1 AND ID=:p2');
   oci_bind_by_name($st, ':p1', $email);
   oci_bind_by_name($st, ':p2', $id);
   oci_execute($st);
   $num=oci_fetch_all($st, $res);

   if ($num==0) {echo "The student is not in the course.<br>\n";}
   else
   {
      oci_execute($st);
   $num=oci_fetch_all($st, $res);
   foreach ($res["POINTS"] as $p)
   {

   }

   $p=$p+$credit;
   $st = oci_parse($conn,'UPDATE TAKECOURSE SET POINTS =:p3 WHERE EMAIL=:p1 AND ID=:p2');
   oci_bind_by_name($st, ':p1', $email);
   oci_bind_by_name($st, ':p2', $id);
   oci_bind_by_name($st, ':p3', $p);
   oci_execute($st);

   $st=oci_parse($conn, 'SELECT AVG(POINTS) FROM TAKECOURSE WHERE EMAIL=:p1');
   oci_bind_by_name($st, ':p1', $email);
   oci_execute($st);
   oci_fetch_all($st, $res);
   foreach($res['AVG(POINTS)'] as $newgpa)
   {
      $st =oci_parse($conn,'UPDATE STUDENT SET GPA =:p1 WHERE EMAIL=:p2');
      oci_bind_by_name($st, ':p1', $newgpa);
      oci_bind_by_name($st, ':p2', $email);
      oci_execute($st);
   }

   }
   }
   ELSE {echo "You are not teaching this course!<br>\n";}
  
   


   oci_free_statement($st);
   //close connection to the database
   oci_close($conn);
      
   }

   ?>

<a href = "home.php">Back</a>

</body>
</html>