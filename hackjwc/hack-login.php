
<?php
/**
 * 从教务处获取学生信息
 * 将这个文件上传到服务器时要修改参数$urlDataTo的值
 */
require ('valite.php');//引入验证码识别的类
$user = "";
$password = "";
if(!empty($_POST["user"]) && !empty($_POST["password"])){
	$user = $_POST["user"];
	$password = $_POST["password"];
	$phoneNum = mysql_real_escape_string($_POST["phoneNum"]);
}
if (empty($user)) {
	echo 2;
	exit();
}
if (empty($password)) {
	echo 3;
	exit();
}
if(empty($phoneNum)){
	echo -1;
	exit();
}

/**
 * 获取验证码和cookie
 * @param $url，验证码的网页地址
 * @param $cookiepath, 保存cookie文件的服务器本地地址
 * @param $imgPath, 保存从网络上获取并存到服务器本地的图片地址
 */
function save_verifycode($url, $cookiepath, $imgPath) {	
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
 * @param $imaPath,图片的地址
 * @return $verifycode, 识别好的验证码
 */
function get_verifycode($imgPath){
	$valite = new valite();
	$valite -> setImage($imgPath);
	$valite -> getHec();
	$verifycode = $valite -> run();
	return $verifycode;
}

/**
 * 模拟登录
 * @param $url 登录的域名
 * @param $cookiepath 保存cookie的文件地址
 * @param postfields 需要post提交的所以数据 
 */
function login_post($url, $cookiepath, $postfields) {
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
 * @param $url, 需要采集的域名
 * @param $cookiepath, 保存在服务器本地的cookie文件地址
 */
function get_content($url, $cookiepath) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiepath);//读取cookie	
	$content = curl_exec($ch);//执行cURL抓取页面内容
	curl_close($ch);
	return $content;
}

/**
 * 将一组值post给目标url
 * @param url, 目标url
 * @param postfield, 需要post的url字符串
 */
function transmit_data($url, $postfield){
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
$url = "http://202.206.20.36/ACTIONLOGON.APPPROCESS?mode=4";							//登录地址
$cookiepath		= dirname(__FILE__) . '/cookie_oschina.txt';								//设置cookie保存路径
$imgSavePath		= dirname(__FILE__).'/verifycode.jpeg';									//验证码保存的地址
$imgURLPath 		= "http://202.206.20.36/ACTIONVALIDATERANDOMPICTURE.APPPROCESS";			//需要获取的验证码的URL
save_verifycode($imgURLPath, $cookiepath, $imgSavePath);

//初始化用户名、密码、验证码和需要提交的信息
$verifycode = get_verifycode($imgSavePath);
@unlink($imgSavePath);																//删除验证码图片，用完赶紧删
$postfields = "WebUserNO=$user&Password=$password&Agnomen=$verifycode";		

$url_content 	= "http://202.206.20.36/ACTIONFINDSTUDENTINFO.APPPROCESS?mode=1&showMsg=";
login_post($url, $cookiepath, $postfields);
$content 		= get_content($url_content, $cookiepath);									//获取登录页的信息
$en_content		= mb_convert_encoding($content, 'UTF-8', 'UTF-8,GBK,GB2312,BIG5'); 		//对返回的信息进行编码,不然会乱码
@unlink($cookiepath); 																//删除cookie文件																

//匹配页面信息
$patternNum = "/<td colspan=\"2\" align=\"left\">&nbsp;(.*)<\/td>/i";
$patternMaj = "/<td width=\"35%\" align=\"left\">&nbsp;(.*)<\/td>/i";
$patternCol = "/<td width=\"35%\" align=\"left\" nowrap>&nbsp;(.*)<\/td>/i";
preg_match_all($patternNum, $en_content, $arrNum);
preg_match_all($patternMaj, $en_content,$arrMaj);
preg_match_all($patternCol, $en_content,$arrCol);

$data = array(
	"password"	=> $password,
	"stuNum" 	=> $arrNum[1][0],
	"name" 		=> $arrNum[1][1],
	"sex"		=> $arrNum[1][4],
	"phoneNum"	=> $phoneNum,
	"major"		=> $arrMaj[1][15],
	"college" 	=> $arrCol[1][0]
	);
if ($data["stuNum"] == "" | $data["name"] == "" | $data["sex"] == "" | $data["major"] == "" | $data["college"] == "" ){
	echo 3;				//密码错误，去教务处查询不成功，返回3
	exit();
}
$urlDataTo		= "http://127.0.0.1/twohands-php/hackjwc/get_insert.php";						//本地调试时用
//$urlDataTo		= "http://zzcypc.sinaapp.com/hackjwc/get_insert.php";			//实际新浪云平台用
$postfiedData   = http_build_query($data);										//将$data数组转化为url形式的字符串
$resultT = transmit_data($urlDataTo, $postfiedData);
echo $resultT;
//echo $postfiedData;


?>