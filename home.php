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

<h1>Display Information</h1>

<?php
//include ('OCI.inc');   
//Connect to the DB

$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = fourier.cs.iit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;

$conn = OCILogon("xli232","Xiaoli232",$db);

if (!$conn) {
  $e = oci_error();
  echo "$e"; 	
} 
else{
    echo "Successfully connected<br>";
}


//find the information of user and display
session_start();
$user = $_SESSION['user'];
//echo "$user<br>\n";

$st = oci_parse($conn,'SELECT * FROM Student WHERE EMAIL = :p1');
oci_bind_by_name($st, ':p1', $user);
oci_execute($st);
$results=array();
$num=oci_fetch_all($st, $res);
/*echo $num;
$decide = oci_parse($conn,'SELECT COUNT(*) AS num FROM Student WHERE EMAIL = :p2');
oci_bind_by_name($decide, ':p2', $user);
oci_define_by_name($decide,'num', $numcount);
oci_execute($decide);
oci_fetch_all($decide,$tmp);
echo "$tmp";*/

if($num ==0){
  $st = oci_parse($conn,'SELECT * FROM Faculty WHERE EMAIL=:p1');
  oci_bind_by_name($st, ':p1', $user);
  oci_execute($st);

  echo  "Here is the basic information for the Professor:<br>";
  oci_fetch_all($st, $res);
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
    echo  "Current position:    $position<br>";
   }
   foreach($res['STATUS'] as $status){
    echo  "Information public/private:    $status<br>";
   }

}else{
  echo  "Here is the basic information for the student:<br>";
  //oci_fetch_all($st, $res);
  //foreach($res['NAME'] as $email)
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
   foreach($res['STATUS'] as $status){
    echo  "Information public/private:    $status<br><br>";

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
    //unset($_SESSION['user']);

//close connection to the database
oci_close($conn);
?>


<a href = "searchStudent.php">Search Student Information<br></a>
<a href = "searchFaculty.php">Search Professor Information<br></a>
<a href = "studentAddCourse.php">Add courses(Students)<br></a>
<a href = "registrateOr.php">Join a group or club<br></a>
<a href = "moderator.php">Moderator Give permit(Moderator)<br></a>
<a href = "assignTA.php">Assign TA(Professor)<br></a>
<a href = "modifyMessage.php">Modify Comments in forum(Professor)<br></a>
<a href = "modifyGPA.php">Modify GPA according to the comments(Professor)<br></a>
<a href = "generateOr.php">Generate a group or club<br></a>
<a href = "changeprivacy.php">Set the profile public or private<br></a>






</body>
</html>