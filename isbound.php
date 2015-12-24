<!--判断是否已经绑定学号和密码-->
<!--如果存在学号则输出1，否则输出0-->
<?php
 require('connect.php');
 $stuNum = mysql_real_escape_string($_POST['stuNum']);
 $query = "SELECT `stuNum` from `basicinfo` WHERE `stuNum` = '{$stuNum}'";
 if($query){
 	echo 1;
 } else{
 	echo 0;
 }
?>