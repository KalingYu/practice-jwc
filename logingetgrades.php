
<!DOCTYPE html>
<html>
<head>
    <title>教务处登录</title>
    <meta charset="utf-8"/>
    <!--Import Google Icon Font-->
    <link href="css/mdfontsicon.css" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="css/style.css">
    <link type="text/css" rel="stylesheet" href="css/login.css">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
</head>

<body>

<div class="container-login">
    <div class="banner dark-primary-color">

    </div>
    <img id="avatar" class="offset-l4 col circle mdi-av-play-circle-outline z-depth-2" align="center"
         src="img/iconfont-touxiang.png"/>

    <form id="loginbox" class="" action="getgrades.php" method="post">
        <div class="row">
            <div class="row">
                <div class="offset-l4 offset-s1 s12  input-field l4 col">
                    <input id="email" type="text" name="studentnumber" class="validate">
                    <label for="email">学号</label>
                </div>

            </div>
            <div class="row">
                <div class="offset-l4 input-field col s12 l4">
                    <input id="password" type="password" name="password" class="validate">
                    <label for="password">密码</label>
                </div>
            </div>

            <div class="row">
                <button type="submit" class="offset-l4 offset-s1 center-block waves-effect col s12logingetgrades.php l4 waves-light btn btn-login" id="btn-login" >提交</button>
            </div>
        </div>
    </form>

    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>

</body>
</html>