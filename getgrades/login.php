<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		<title>成绩查询</title>
		<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="css/style-login.css" />
		<style type="text/css">
		</style>
	</head>

	<body>
		<div class="login-box">
			<div class="login-title text-center">
				<h1><small>教务处登录</small></h1>
			</div>
			<div class="login-content ">
				<div class="form">
					<form action="getgrades.php" method="post">
						<div class="form-group">
							<div class="col-xs-12  ">
								<div class="input-group">
									<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
									<input type="text" id="username" name="studentnumber" class="form-control" placeholder="学号">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-xs-12  ">
								<div class="input-group">
									<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
									<input type="text" id="password" name="password" class="form-control" placeholder="密码">
								</div>
							</div>
						</div>
						<div class="form-group form-actions">
							<div class="col-xs-12 ">
								<input type="submit" class="col-xs-12 btn btn-sm btn-info"></input>
							</div>
						</div>
					</form>
					<div class="col-xs-12">
						<p>密码错误，请重新输入</p>
					</div>
				</div>
			</div>

		</div>
	</body>

</html>