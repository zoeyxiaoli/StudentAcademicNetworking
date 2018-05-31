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

   <h1> Moderator Give Permit</h1>
   
   <form action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method = "POST">
   <table>

   <tr>
      <td>Emial</td>
      <td align = "center"><input type="text" name = "email" size="30"/></td>
   </tr>

   <tr>
      <td>Title</td>
      <td align ="center"><input type="text" name="title" size="30"/></td>
   </tr>

   <tr>
      <td>Type</td>
      <td align ="center"><input type="text" name="type" size="30"/></td>
   </tr>

   <tr>
      <td colspan="2" align="center"><input type="submit"/></td>
   </tr>



   </table>
   </form>

   <?php

   $email = $title = $type = "";

   if ($_SERVER["REQUEST_METHOD"] == "POST") {
   $email = $_POST["email"];
   $title = $_POST["title"];
   $type = $_POST["type"];

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


   session_start();
   $user = $_SESSION['user'];
   //echo "$user<br>\n";

   $st3=oci_parse($conn, 'SELECT ADMIN_A,ADMIN_B FROM ORGANIZATION WHERE TITLE=:p1 AND TYPE=:p2');
   oci_bind_by_name($st3, ':p1', $title);
   oci_bind_by_name($st3, ':p2', $type);
   oci_execute($st3);

   $num=oci_fetch_all($st3, $res);
   if ($num==0)
   {
      echo 'No such group/club found.<br>\n';
   }
   else 
   {
      $t=0;
      foreach($res['ADMIN_A'] as $admin)
         if ($admin==$user) $t=1;
      if ($t==0)
         foreach($res['ADMIN_B'] as $admin)
            if ($admin==$user) $t=1;
      if ($t==1)
      {

   //decide if give  registration permit
   $st = oci_parse($conn,'SELECT * FROM Organization where TITLE =:P1 AND TYPE=:P2');
   oci_bind_by_name($st, ':p1', $title);
   oci_bind_by_name($st, ':p2', $type);
   oci_execute($st);


  oci_fetch_All($st, $res);
   FOREACH($res['COURSERELATION'] AS $courseIf){
      if($courseIf==NULL){
         $st = oci_parse($conn,'INSERT INTO Permit VALUES(:p1,:p2,:p3)');
      oci_bind_by_name($st, ':p1', $email);
      oci_bind_by_name($st, ':p2', $title);
      oci_bind_by_name($st, ':p3', $type);
      oci_execute($st);  // executes and commits
      }else{
         $st2=oci_parse($conn,'SELECT * FROM TAKECOURSE WHERE ID=:p4 AND EMAIL=:p5');
         oci_bind_by_name($st2, ':p4', $courseIf);
         oci_bind_by_name($st2, ':p5', $email);
         oci_execute($st2);
         $num=oci_fetch_all($st2, $result);
         if ($num==0)
         {
            echo "Cannot add student into intereset group while the student is not in the course.<br>\n";
         }
         else
         {
            $st = oci_parse($conn,'INSERT INTO Permit VALUES(:p1,:p2,:p3)');
            oci_bind_by_name($st, ':p1', $email);
            oci_bind_by_name($st, ':p2', $title);
            oci_bind_by_name($st, ':p3', $type);
            oci_execute($st);
         }
       
      }
   }
 

   }
   else echo 'You are not the moderator of the group/club. <br>\n';
   }   

   oci_free_statement($st2);
   oci_free_statement($st);
   oci_free_statement($st3);
   //close connection to the database
   oci_close($conn);
      
   }

   ?>

<a href = "home.php">Back</a>
</body>
</html>