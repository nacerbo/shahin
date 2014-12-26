<?php
require_once("../sn-loader.php");

if (!isset($_POST['user_name']) && !isset($_POST['user_pass'])) {
	header('Location: ../sn-login.php');
} else {
	
	$user_name = $_POST['user_name'];
	$user_pass = md5($_POST['user_pass']);

	$_user = get_users('*' ,'WHERE `user_name` = '."'$user_name'".' AND `user_pass` = '."'$user_pass'");

	if ($_POST['user_name'] == $_user['0']->user_name && md5($_POST['user_pass']) == $_user['0']->user_pass ) {

		$_SESSION['user_name'] = $_user['0']->user_name;
		$_SESSION['user_pass'] = $_user['0']->user_pass;
		$time = (isset($_POST['remember'])) ? time()+3600*200 : time()+3600 ;
		setcookie('user_name',$_user['0']->user_name,$time);
		setcookie('user_id',$_user['0']->id, $time);
		header("Location: index.php");
	}

}
