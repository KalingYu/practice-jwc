<!DOCTYPE HTML>
<html>
<head>
    <meta charset="UTF-8"/>
    <title>
        毕业成绩单
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <link type="text/css" rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
    <style>table,
        table td,
        table th {
            text-align: center;
        }</style>
</head>
<body>
<?php
//		ini_set("display_errors", "On");
//		//开启错误显示
//		error_reporting(E_ALL);
//		//显示所有错误
//		session_start();
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
 * @param $imaPath ,图片的地址
 * @return $verifycode, 识别好的验证码
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

//初始化各种参数
$url = "http://jwpt.neuq.edu.cn/ACTIONLOGON.APPPROCESS?mode=4";
//登录地址
$cookiepath = dirname(__FILE__) . '/cookie_oschina.txt';
//设置cookie保存路径
$imgSavePath = dirname(__FILE__) . '/verifycode.jpeg';
////验证码保存的地址
$imgURLPath = "http://jwpt.neuq.edu.cn/ACTIONVALIDATERANDOMPICTURE.APPPROCESS";
//需要获取的验证码的URL
save_verifycode($imgURLPath, $cookiepath, $imgSavePath);
//初始化用户名、密码、验证码和需要提交的信息
$verifycode = get_verifycode($imgSavePath);
@unlink($imgSavePath);
//删除验证码图片，用完赶紧删
$postfields = "WebUserNO=$user&Password=$password&Agnomen=$verifycode";
$url_content = "http://jwpt.neuq.edu.cn/ACTIONJSCHOSEAPPRAISECONTENT.APPPROCESS?TotalScore=0&HaveAppraise=0&SeriesID=381";
login_post($url, $cookiepath, $postfields);
$content = get_content($url_content, $cookiepath);

$en_content = mb_convert_encoding($content, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5');
echo $en_content;
//对返回的信息进行编码,不然会乱码
@unlink($cookiepath);
?>
</body>
</html>
