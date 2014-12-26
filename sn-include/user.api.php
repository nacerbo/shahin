<?php
/*
	User Functions
*/
/* 1
	get all users info form database

	$f				: the fields that you want to select; (string) ['user_name','user_email','*']
	$sql_commend	: any extra commend that you need; (string) [sql code] // example WHERE id = 2

	return (array) with users inforamtion
*/
function get_users($f ='*' ,$sql_commend = ''){

	global $sn_sql;
	if ($f == 'user_pass')
		return null;

	$query = "SELECT $f FROM `users` ".$sql_commend;
	$result = $sn_sql->query($query);

	if (!$result) {
		return null;
	}

	$count = $sn_sql->query_num();
	
	$users_array = array();

	for ($i=0; $i < $count; $i++) { 

		$u = $sn_sql->query_object();

		array_push($users_array, $u);
	}

	return $users_array;
}
/*2
	get user by it's id 
	$id		: (int) the id of the user you want his information

	return (array) with user inforamtion
*/ 
function get_user_by_id($id)
{
	$id = (int) $id;

	if ($id === 0 || $id == 0)
		return null;

	$user = get_users('*', 'WHERE `id`='.$id);

	if ($user == null) {
		return null;
	}

	return $user['0'];
}
/*3
	$user_name	: (string) the name of the user you want his information

	return (array) with user inforamtion
*/
function get_user_by_name($user_name)
{
	$user_name = mysql_real_escape_string($user_name);

	$user = get_users('*', " WHERE `user_name`='$user_name' ");

	if ($user == null) {
		return null;
	}

	return $user['0'];
}
/*4
	$user_name	: (string) the email of the user you want his information

	return (array) with user inforamtion
*/
function get_user_by_email($user_email)
{
	$user_email = mysql_real_escape_string($user_email);

	$user = get_users('*', " WHERE `user_email`='$user_email' ");

	if ($user == null) {
		return null;
	}

	return $user['0'];
}
/*5
	add new user to your site

	$user_name : (string) the user name that he will use it for login
	$user_pass : (string) his password
	return (int) the user id
*/
function sn_add_user($user_name,$user_pass,$user_email)
{
	global $sn_sql;

	if(!check_empty($user_name) || !check_empty($user_pass) || !check_empty($user_email))
		return false;
	

	$user = get_users('id',"WHERE `user_name` = '$user_name' OR `user_email` = '$user_email' ");

	if (!empty($user)) {
		return $user['0'];
	}

	$u_name     = @mysql_real_escape_string(strip_tags($user_name));
	$u_pass     = @md5($user_pass);
	$u_email    = @mysql_real_escape_string(strip_tags($user_email));
	$time 		= date('Y-m-d H:i:s');
	$sql_c      = "INSERT INTO `users` (`id`, `user_name`, `user_pass`, `user_email`, `user_registered`, `display_name`) 
				   VALUES (null, '$u_name', '$u_pass', '$u_email','$time', '$u_name')";
	// echo $time;
	$result = $sn_sql->query($sql_c);
	
	if (!$result) 
		return false;

	return $sn_sql->connect->insert_id; // إرجاع أي دي الخاص بالمستخدم الجديد
}
/*6
	delete user from database
	$id : (int) the user id that you want to delete

	return boolean (true | false)
*/
function sn_delete_user($id)
{
	global $sn_sql;

	$id= (int)$id;

	if($id==0)
		return false;
	
	$result = $sn_sql->query("DELETE FROM `users` WHERE `id`=$id");

	delete_all_user_meta($id); // delete his metas 

	if (!$result) 
		return false;

	return true;
}
/*7
	update user informations

	$id 		: the id of the user that you want to update his information
	$user_name  : the new user name (string) default : null
	$user_pass  : the new password  (string) default : null
	$user_email : the new email;    (string) default : null

			- array with the errors 
	return 
			- boolean true or false 
*/
function sn_update_user($id,$user_name= null,$user_pass = null,$user_email = null)
{
	global $sn_sql;

	$id= (int)$id;

	if($id==0)
		return false;

	$error = array();

	$user_pass = md5($user_pass);
	$user = get_user_by_id($id);
	
	// تحقق إذا لم يكن هناك مستخدم بذلك االمعرف id

	if (!$user){ 
		array_push($error, 'there is no user with that id'); // send error massege at the end of the function
		return $error;
	}
	if(!check_empty($user_name) && !check_empty($user_pass) && !check_empty($user_email))
	{
		return false;
	}

	$exist_user_name = get_user_by_name($user_name);
	$exist_user_email = get_user_by_email($user_email);

	if (!empty($exist_user_name) && $user->user_name != $user_name) {
		array_push($error,'the name that you entred is used by another user');
	}

	if (!empty($exist_user_email) && $user->user_email != $user_email) {
		array_push($error,'the email that you entred is used by another user');
	}

	if (!empty($error)) {
		return $error;
	}

	$fildes = array();
	$sql_c  = 'UPDATE `users` SET ';

	if (!empty($user_name)) {
		$user_name = strip_tags($user_name);
		array_push($fildes, "`user_name` = '$user_name' ");
	}

	if (!empty($user_pass)) {
		$user_name = strip_tags($user_pass);
		array_push($fildes, "`user_pass` = '$user_pass' ");
	}

	if (!empty($user_email)) {
		$user_name = strip_tags($user_email);
		array_push($fildes, "`user_email` = '$user_email' ");
	}

	$fcount= @count($fildes);
	if ($fcount == 1) 
	{
		$sql_c .= $fildes['0'] . ' WHERE `id`='.$id;
		$r		= $sn_sql->query($sql_c);
		if(!$r)
			return false;
		else
			return true;
	}

	for ($i=0; $i < $fcount ; $i++) 
	{ 
		$sql_c .= $fildes[$i];
		if ($i != ($fcount - 1)) 
			$sql_c .= ' , ';
	}
	$sql_c .= 'WHERE `id` ='.$id;
	$r		= $sn_sql->query($sql_c);
		if(!$r)
			return false;
		else
			return true; 
}
/*
	User meta Functions
*/
/*8
	get the user meta by the user id and the meta key

	$uid : the user id (int)
	$meta_key : the meta key (string)

			- boolean false only

	return 	- Null

			- (string) the meta value
*/
function get_user_meta($uid,$meta_key)
{
	global $sn_sql;

	$uid = (int) $uid;
	if ($uid === 0) {
		return false;
	}
	$user = get_user_by_id($uid);
	if (!$user) {
		return false;
	}
	$meta_key = @mysql_real_escape_string($meta_key);

	$sql_c		= "SELECT `meta_value` FROM `user_meta` WHERE `user_id` = $uid AND `meta_key` = '$meta_key' ";

	$meta_value	= $sn_sql->query($sql_c);
	
	if ($meta_value->num_rows == 0) {
		return null;
	}
	$meta_value	= $sn_sql->query_object();
	
	$meta_value = $meta_value->meta_value;
	
	return $meta_value;
}
/*9
	Add new meta key

	$user_id 	: (int) the user id
	$meta_key 	: (string) the name of the meta key
	$meta_value : (string) his value
	return (int) the meta id
*/
function add_user_meta($user_id,$meta_key,$meta_value)
{
	global $sn_sql;

	if(!check_empty($user_id) || !check_empty($meta_key) || !check_empty($meta_value))
		return false;

	$user_id	= (int) $user_id;
	$meta_key	= mysql_real_escape_string($meta_key);
	$meta_value = mysql_real_escape_string($meta_value);

	$user = get_user_by_id($user_id);
	$meta_exist = get_user_meta($user_id,$meta_key);
	if (!empty($meta_exist)) {
		return false; // exist meta;
	}
	if (!$user)
		return false;

	$sql_c = "INSERT INTO `user_meta` VALUES (null, $user_id, '$meta_key', '$meta_value')";

	$meta  = $sn_sql->query($sql_c);
	if (!$meta) {
		return false;
	}
	return $sn_sql->connect->insert_id;	
}
/*10
	delete a user meta 

	$user_id    : (int) the user id that you want to delete his meta
	$meta_key   : (string) the meta_key that you want to delete

	return boolean (true | false)
*/
function delete_user_meta($user_id,$meta_key)
{
	global $sn_sql;

	if(!check_empty($user_id) || !check_empty($meta_key))
		return false;

	$user_id	= (int) $user_id;
	$meta_key	= mysql_real_escape_string($meta_key);

	$sql_c	= "DELETE FROM `user_meta` WHERE `user_id`= $user_id AND  `meta_key` = '$meta_key'";

	$result = $sn_sql->query($sql_c);

	if (!$result) 
		return false;

	return true;
}
/*11
	Delete all meta of one user by his id
	use it carefully

	$user_id    : (int) the user id that you want to delete his meta

	return boolean (true | false)
*/
function delete_all_user_meta($user_id)
{
	global $sn_sql;

	$user_id	= (int) $user_id;

	if(!check_empty($user_id) || $user_id == 0)
		return false;


	$sql_c	= "DELETE FROM `user_meta` WHERE `user_id`= $user_id ";

	$result = $sn_sql->query($sql_c);

	if (!$result) 
		return false;

	return true;
}
// Corrent user
function corrent_user(){
	return $_SESSION['user_name'];
}