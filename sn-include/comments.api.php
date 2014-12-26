<?php
/*
	comments Functions
*/
/*1
	get all comments form database
	
	$f				: the fields that you want to select; (string) ['content','user','status','*']
	$sql_commend	: any extra commend that you need; (string) [sql code] // example WHERE id = 2

	return (array) with all comments
*/
function get_comments($f ='*' ,$sql_commend = ''){

	global $sn_sql;

	$query = "SELECT $f FROM `comments` ".$sql_commend;
	$result = $sn_sql->query($query);

	if (!$result) {
		return null;
	}

	$count = $sn_sql->query_num();
	
	$comments_array = array();

	for ($i=0; $i < $count; $i++) { 

		$u = $sn_sql->query_object();

		array_push($comments_array, $u);
	}

	return $comments_array;
}
/*2
	get comment by it's id 
	$id		: (int) the id of the comment you want to get it

	return (array) with comment
*/ 
function get_comment_by_id($id)
{
	$id = (int) $id;

	if ($id === 0 || $id == 0)
		return null;

	$comment = get_comments('*', 'WHERE `comment_id`='.$id);

	if ($comment == null) {
		return null;
	}

	return $comment['0'];
}
/*3
	get comment by user id
	$id		: (int) the id of the user to get his comments

	return (array) with comments
*/ 
function get_comment_by_user_id($id)
{
	$id = (int) $id;

	if ($id === 0 || $id == 0)
		return null;

	$comment = get_comments('*', 'WHERE `user_id`='.$id);

	if ($comment == null) {
		return null;
	}

	return $comment;
}
/*4
	get comment by post id
	$id		: (int) the id of the post to get it comments

	return (array) with comments
*/ 
function get_comment_by_post_id($id)
{
	$id = (int) $id;

	if ($id === 0 || $id == 0)
		return null;

	$comment = get_comments('*', 'WHERE `post_id`='.$id);

	if ($comment == null) {
		return null;
	}

	return $comment;
}
/*5
	add new comment to your site

	$comment_array :  (array) have all the thing that you need to add your comment
	
	return (int) the post id
*/
function sn_add_comment($comment_array)
{
	global $sn_sql;

	if (!is_array($comment_array))
		return null;

	/*-------------------------------------*/
	if(!isset($comment_array['author']) || !isset($comment_array['author_email']) || !isset($comment_array['post_id']) || !isset($comment_array['content']) ){
		return false;
	}

	$agent = unserialize($GLOBALS['_COOKIE']['ci_session'])["user_agent"];

	$comment_array['author']		= mysql_real_escape_string ($comment_array['author']);

	$comment_array['author_email']	= mysql_real_escape_string ($comment_array['author_email']);

	$comment_array['content']		= mysql_real_escape_string ($comment_array['content']);

	$comment_array['post_id']		= (empty($comment_array['post_id'])) ? 0 : mysql_real_escape_string ($comment_array['post_id']);

	$comment_array['author_url']	= (!isset($comment_array['author_url'])) ? get_option('site_url') : mysql_real_escape_string ($comment_array['author_url']);

	$comment_array['date']			= (!isset($comment_array['date'])) ? date('Y-m-d H:i:s') : mysql_real_escape_string ($comment_array['date']);

	$comment_array['approved']		= (!isset($comment_array['approved'])) ? 0 : mysql_real_escape_string ($comment_array['approved']);

	$comment_array['parent']		= (!isset($comment_array['parent'])) ? 0 : mysql_real_escape_string ($comment_array['parent']);

	$comment_array['user_id']		= (!isset($comment_array['user_id'])) ? 0 : mysql_real_escape_string ($comment_array['user_id']);

	$comment_array['agent']			= $agent;

	/*-------------------------------------*/
	// var_dump($comment_array);

	$sql_c 					= "INSERT INTO `comments` VALUES (null, '$comment_array[post_id]', '$comment_array[author]', '$comment_array[author_url]',
															'$comment_array[author_email]', '$comment_array[date]', '$comment_array[content]',
															'$comment_array[approved]', '$comment_array[agent]' , '$comment_array[parent]' , '$comment_array[user_id]')";
	
	$result 				= $sn_sql->query($sql_c);

	if(!$result){
		return false;
	}

	return $sn_sql->connect->insert_id;
}
/*4
	delete comment by his id
	return boolean true or false
*/
function sn_delete_comment($id)
{
	global $sn_sql;

	$id= (int)$id;

	if($id==0)
		return false;
	
	$result = $sn_sql->query("DELETE FROM `comments` WHERE `comment_id`=$id");

	if (!$result) 
		return false;

	return true;
}
/*5
	update comment

	$comment_array : (array) have all the thing that you need to add your comment

	return boolean true or false or null
*/
function sn_update_comment($comment_array)
{
	global $sn_sql;

	if (!is_array($comment_array) || empty($comment_array))
		return null;

	if (!isset($comment_array['id']) || !(int)$comment_array['id']) {
		return 'hahahah';
	}

	$id = (int) $comment_array['id'];

	$comment = get_comment_by_id($id);

	$comment_array['author']		= (!isset($comment_array['author'])) ? $comment->comment_author : mysql_real_escape_string ($comment_array['author']);

	$comment_array['author_email']	= (!isset($comment_array['author_email'])) ? $comment->comment_author_email : mysql_real_escape_string ($comment_array['author_email']);

	$comment_array['author_url']	= (!isset($comment_array['author_url'])) ? $comment->comment_author_url : mysql_real_escape_string ($comment_array['author_url']);

	$comment_array['content']		= (!isset($comment_array['content'])) ? $comment->comment_content : mysql_real_escape_string ($comment_array['content']);

	$comment_array['approved']		= (!isset($comment_array['approved'])) ? $comment->comment_approved : mysql_real_escape_string ($comment_array['approved']);


	$sql_c  				= "UPDATE `comments` SET `comment_author` = '$comment_array[author]', `comment_author_email` = '$comment_array[author_email]', 
													 `comment_author_url` = '$comment_array[author_url]', `comment_content` = '$comment_array[content]',
													 `comment_approved` = '$comment_array[approved]'

												 WHERE `comment_id` = $id";
	$result 				= $sn_sql->query($sql_c);

	if(!$result){
		return false;
	}

	return true;
}
