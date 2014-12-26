<?php
/*
	Site Options API 

		get the site Options from database

		- get_option($key_name);
		- add_option($key_name,$value);
		- delete_option($key_name);
		
		- update_option_key($key_name,$new_kname);
		- set_option_value($key_name,$new_value);

*/
/*
	get_option($key_name)
	$key_name : String, the name of the option in database;
	return $option_value;
*/
function get_option($key_name)
{
	global $sn_sql;
	$key_name     = strip_tags(mysql_real_escape_string($key_name));
	$sql_c        = 'SELECT `option_value` FROM `site_options` WHERE `option_key` = '."'$key_name'  ";	
	$result       = $sn_sql->query($sql_c);

	if (!$result) 
	{
		return false;
	}

	$key_value    = $sn_sql->query_object();
	$key_value    = @$key_value->option_value;

	return $key_value;
}
/*
	add_option($key_name,$value);
	$key_name : String, the name of the option in database;
	$value    : the value of the option;
	return Bool true || false;
*/
function add_option($key_name,$value)
{
	global $sn_sql;
	
	if(empty($key_name) || empty($value))
		return false;

	$key_name     = strip_tags(mysql_real_escape_string($key_name));
	$value     = strip_tags(mysql_real_escape_string($value));


	if (get_option("$key_name")) {
		return false;
	}

	$sql_c        = "INSERT INTO `site_options` VALUES (NULL,'$key_name','$value')";

	$result       = $sn_sql->query($sql_c);
	if ($result) 
	{
		return true;
	}
	else
	{
		return false;
	}
}
/*
	delete_option($key_name);
	$key_name : String, the name of the option in database;
	return Bool true || false;
*/
function delete_option($key_name)
{
	global $sn_sql;
	if(empty($key_name))
		return false;

	$key_name     = strip_tags(mysql_real_escape_string($key_name));
	
	$sql_c        = "DELETE FROM `site_options` WHERE `option_key` = '$key_name' ";

	$result       = $sn_sql->query($sql_c);
	if ($result) 
	{
		return true;
	}
	else
	{
		return false;
	}
}
/*
	update_option_key($key_name,new_key_name);
*/
function update_option_key($key_name,$new_key_name)
{
	global $sn_sql;
	
	if(empty($key_name) || empty($new_key_name))
		return false;

	$key_name         = strip_tags(mysql_real_escape_string($key_name));
	$new_key_name     = strip_tags(mysql_real_escape_string($new_key_name));


	if (get_option($key_name) == false)
		return false;

	$sql_c            = "UPDATE `site_options` SET `option_key` = '$new_key_name' WHERE `option_key` = '$key_name' ";
	echo $sql_c;
	$result       = $sn_sql->query($sql_c);

	if ($result) 
	{
		return true;
	}
	else
	{
		return false;
	}
}
/*
	set_option_value($key_name,new_key_name);
*/

function set_option_value($key_name,$new_value)
{
	global $sn_sql;
	
	if(empty($key_name) || empty($new_value))
		return false;

	$key_name         = strip_tags(mysql_real_escape_string($key_name));
	$new_value        = strip_tags(mysql_real_escape_string($new_value));


	if (get_option($key_name) == false)
		return false;

	$sql_c            = "UPDATE `site_options` SET `option_value` = '$new_value' WHERE `option_key` = '$key_name' ";
	
	$result       = $sn_sql->query($sql_c);

	if ($result) 
	{
		return true;
	}
	else
	{
		return false;
	}
}
