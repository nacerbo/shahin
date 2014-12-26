<?php
session_start();
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
	session_destroy();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>تسجيل الدخول-> إسم الموقع</title>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<link rel="stylesheet" type="text/css" href="sn-admin/assets/css/style.css">
		<script src="sn-admin/assets/javascript/jquery.js"></script>
		<script src="sn-admin/assets/javascript/shahin.js"></script>
		<script src="sn-admin/assets/javascript/autogrow.min.js"></script>
	</head>
	<body class="" id="">

		<div class="login-container" id="login-container">
			<div class="login-board">
				<div class="sn-logo"></div>
				<div class="">
					<h2 class="title">تسجيل الدخول</h2>
					<?php
						if (isset($_GET['msg']) && $_GET['msg'] == 2 ) {
							echo '<div class="msg2">ليس لديك الصلاحيات لدخول ذاك الرابط عليك بتسجيل الدخول</div>';
						}
					?>
					
					<div class="section-content" id="login-box-content">
						<div class="login-form">
							<form method="post" action="sn-admin/check-login.php">
								<p class="input-collection">
									<label for="login-uname">إسم المستخدم</label>
									<input id="login-uname" name="user_name" placeholder="إسم المستخدم" type="text" />
								</p>
								<p class="input-collection">
									<label for="login-pass">كلمة السر</label>
									<input id="login-pass" name="user_pass" placeholder="كلمة السر" type="password" />
								</p>
								<p class="input-collection login-rtext">
									<label for="login-rem" >
										<input id="login-rem" name="remember" type="checkbox" /><span class="label-text" >تذكرني</span>
									</label>
									
								</p>
								<input name="login" type="submit" value="تسجيل الدخول" class="flat-btn flat-dgreen block bigbtn" />
							</form>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</body>
</html>