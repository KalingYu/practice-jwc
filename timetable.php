<?php
/**
 * Created by PhpStorm.
 * User: kaling
 * Date: 10/18/15
 * Time: 9:44 PM
 */
/**
 * 返回结果
 * 0------姓名或者学号为空
 * 1------查询成功
 * -1-----查询失败
 */
require('hackfunctions.php');
if (!empty($_POST) && !empty($_POST)) {
    $stutentnumber = $_POST('studentnumber');
    $password = $_POST('password');
    echo $stutentnumber."<br>";
    echo $password;
}else{
    echo 0;
}