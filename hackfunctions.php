<?php
/**
 * 各种用于采集信息的函数
 *
 * Created by PhpStorm.
 * User: kaling
 * Date: 10/18/15
 * Time: 9:40 PM
 */

require('hackjwc/valite.php');
/**
 * 从教务处获取学生信息
 * 将这个文件上传到服务器时要修改参数$urlDataTo的值
 */
//引入验证码识别的类
$user = "";
$password = "";
$user = $_POST["studentnumber"];
$password = $_POST["password"];
/**
 * 获取验证码和cookie
 * @param $url ，验证码的网页地址
 * @param $cookiepath , 保存cookie文件的服务器本地地址
 * @param $imgPath , 保存从网络上获取并存到服务器本地的图片地址
 */
function save_verifycode($url, $cookiepath, $imgPath)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiepath);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $img = curl_exec($ch);
    curl_close($ch);
    $fp = fopen($imgPath, "w");
    fwrite($fp, $img);
    fclose($fp);
}

/**
 * 根据图片的本地服务器地址识别验证码
 * @param $imgPath
 * @return string $verifycode, 识别好的验证码
 * @internal param $imaPath ,图片的地址
 */
function get_verifycode($imgPath)
{
    $valite = new valite();
    $valite->setImage($imgPath);
    $valite->getHec();
    $verifycode = $valite->run();
    return $verifycode;
}

/**
 * 模拟登录
 * @param $url 登录的域名
 * @param $cookiepath 保存cookie的文件地址
 * @param postfields 需要post提交的所以数据
 */
function login_post($url, $cookiepath, $postfields)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_COOKIEFILE, $cookiepath);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
    curl_exec($curl);
    curl_close($curl);
}

/**
 * 采集信息
 * @param $url , 需要采集的域名
 * @param $cookiepath , 保存在服务器本地的cookie文件地址
 * @return mixed
 */
function get_content($url, $cookiepath)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiepath);
    //读取cookie
    $content = curl_exec($ch);
    //执行cURL抓取页面内容
    curl_close($ch);
    return $content;
}

/**
 * 将一组值post给目标url
 * @param url , 目标url
 * @param postfield , 需要post的url字符串
 * @return mixed
 */
function transmit_data($url, $postfield)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfield);
    $result = curl_exec($ch);
    return $result;
}
