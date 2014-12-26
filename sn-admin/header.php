<?php

// header of the most of pages

require_once("../sn-loader.php");

if (!isset($_SESSION['user_name']) && !isset($_SESSION['user_pass']) ) {
	header("Location: ../sn-login.php?msg=2");
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Title</title>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">
		<script src="assets/javascript/jquery.js"></script>
		<script src="assets/javascript/shahin.js"></script>
		<script src="assets/javascript/autogrow.min.js"></script>
	</head>
	<body class="" id="">
		<div id="user-menu" class="user-menu">
			<div class="clear"></div>
			<ul class="menu right">
				<div class="clear"></div>
				<li class="first item-id item-1">
					<a href="#item">
						<i class="fa fa-info-circle"></i>
						<ul class="child-menu">
							<a href="#item">
								<li>
									عن شاهين
								</li>
							</a>
							<a href="#item">
								<li>
									الموقع الرسمي
								</li>
							</a>
							<a href="#item">
								<li>
									وثائق المساعدة
								</li>
							</a>
						</ul>
					</a>
				</li>
				
				<li class="item-id item-1">
					<a href="#item">
						<i class="fa fa-home"></i>
						إسم الموقع
						<ul class="child-menu">
							<li>
								<a href="#item">
									زيارة الموقع
								</a>
							</li>
							<li>
								<a href="#item">
									صفحة الإعدادات
								</a>
							</li>
							<li>
								<a href="#item">
									صفحة الأعضاء
								</a>
							</li>
						</ul>
					</a>
				</li>
				<li class="item-id item-1">
					<a href="#item">
						<i class="fa fa-plus"></i>
						جديد
						<ul class="child-menu">
							<li>
								<a href="#item">
									مقالة
								</a>
							</li>
							
							<li>
								<a href="#item">
									صفحة
								</a>
							</li>
							
							<li>
								<a href="#item">
									عضو
								</a>
							</li>
						</ul>
					</a>
				</li>
				
				<div class="clear"></div>
			</ul>
			<ul class="menu left">
				<div class="clear"></div>
				<li class="item-id item-1">
					<a href="#item">
						<i class="fa fa-user"></i> عبد الرحمن
						<ul class="child-menu">
							
							<li>
								<a href="#item">
									ملفك الشخصي
								</a>
							</li>
							
							
							<li>
								<a href="../sn-login.php?action=logout">
									تسجيل الخروج
								</a>
							</li>
							
						</ul>
					</li>
				</a>
				<div class="clear"></div>
			</ul>
			<div class="clear"></div>
		</div>
		<div class="header" id="header">
				<div class="logo">
					<a href="#site"><img src="assets/imgs/logo.png" id="header_logo" /></a>
				</div>
				<div class="menu top" id="header-menu">
					<div class="clear"></div>
					<ul class="" id="top-menu">
						
						<li class="item-id item-1">
							<a href="index.php"><i class="fa fa-dashboard"></i> لوحة التحكم</a>
						</li>
						
						
						<li class="item-id item-1">
							<a href="posts.php"><i class="fa fa-thumb-tack"></i> المقالات</a>
						</li>
						
						
						<li class="item-id item-1">
							<a href="pages.php"><i class="fa fa-file"></i> الصفحات</a>
						</li>
						
						
						<li class="item-id item-1">
							<a href="comments.php"><i class="fa fa-comments"></i> التعليقات</a>
						</li>
						
						
						<li class="item-id item-1">
							<a href="users.php"><i class="fa fa-users"></i> الأعضاء</a>
						</li>
						
						
						<li class="item-id item-1">
							<a href="setting.php"><i class="fa fa-gears"></i> الإعدادات</a>
						</li>
						
						
						<li class="item-id item-1">
							<a href="../sn-login.php?action=logout"><i class="fa fa-power-off"></i> تسجيل خروج</a>
						</li>
						
					</ul>
					<div class="clear"></div>
				</div>
		</div><!-- header end; -->