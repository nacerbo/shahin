<?php
/*
	User Functions
*/
/*1
	get all users info form database
	
	$f				: the fields that you want to select; (string) ['title','content','author','status','type','date','sn_link','*']
	$sql_commend	: any extra commend that you need; (string) [sql code] // example WHERE id = 2

	return (array) with all posts
*/
function get_posts($f ='*' ,$sql_commend = ''){

	global $sn_sql;
	if ($f == 'password')
		return null;

	$query = "SELECT $f FROM `posts` ".$sql_commend;
	$result = $sn_sql->query($query);

	if (!$result) {
		return null;
	}

	$count = $sn_sql->query_num();
	
	$posts_array = array();

	for ($i=0; $i < $count; $i++) { 

		$u = $sn_sql->query_object();

		array_push($posts_array, $u);
	}

	return $posts_array;
}
/*
	return array
*/
function get_posts_a($f ='*' ,$sql_commend = ''){

	global $sn_sql;
	if ($f == 'password')
		return null;

	$query = "SELECT $f FROM `posts` ".$sql_commend;
	$result = $sn_sql->query($query);

	if (!$result) {
		return null;
	}

	$count = $sn_sql->query_num();
	
	$posts_array = array();

	for ($i=0; $i < $count; $i++) { 

		$u = $sn_sql->query_array();

		array_push($posts_array, $u);
	}

	return $posts_array;
}
/*2
	get user by it's id 
	$id		: (int) the id of the post you want to get it

	return (array) with user inforamtion
*/ 
function get_post_by_id($id)
{
	$id = (int) $id;

	if ($id === 0 || $id == 0)
		return null;

	$post = get_posts('*', 'WHERE `id`='.$id);

	if ($post == null) {
		return null;
	}

	return $post['0'];
}
/*3
	add new post to your site

	$post_array : (array) have all the thing that you need to add your post
				  | 'title'  	=> * 				this fild is required 
				  | 'content'	=> * 				this fild is required
				  | 'author' 	=> (int) 			the id of the user that add this post - his author - / default - 1
				  | 'type'   	=> (string) 		the type of the post accept / {'post','attachment⁯', .... costume post type } / default - post
				  | 'status' 	=> (int)			the status of the post if it's draft or public or ..... / default - 0
				  | 'password'	=> (string)			the password of this post if there is any password
				  | 'date' 		=> (string / date)	the time that this post has been published / default is date('Y-m-d H:i:s') the current time that the post had published 
				  | 'sn_link' 	=> (string)			this option is for the SEO purposes / default is the title of this post

	return (int) the post id
*/
function sn_add_post($post_array)
{
	global $sn_sql;

	if (!is_array($post_array))
		return null;

	if (!isset($post_array['title']) || !isset($post_array['content'])) {
		return false;
	}

	$exist_post = get_posts('id',"WHERE `title` = '$post_array[title]' AND `content` = '$post_array[content]'" );

	if (!empty($exist_post)) {
		return $exist_post['0']; // return the id
	}
	// default values

	$post_array['author'] 	=  (!isset($post_array['author'])) ? 1 : mysql_real_escape_string ($post_array['author']);

	$post_array['type'] 	=  (!isset($post_array['type'])) ? 'post' : mysql_real_escape_string($post_array['type']);

	$post_array['status'] 	=  (!isset($post_array['status'])) ? 0 : mysql_real_escape_string ($post_array['status']);

	$post_array['password'] =  (!isset($post_array['password'])) ? null : mysql_real_escape_string ($post_array['password']);

	$post_array['date'] 	=  (!isset($post_array['date'])) ? date('Y-m-d H:i:s') : mysql_real_escape_string ($post_array['date']);

	$post_array['sn_link'] 	=  (!isset($post_array['sn_link'])) ?  str_ireplace(array(' '), '-', mysql_real_escape_string($post_array['title'])) : mysql_real_escape_string($post_array['sn_link']);

	$post_array['sn_link'] 	=  post_link_filter($post_array['sn_link']);

	$sql_c 					= "INSERT INTO `posts` VALUES (null, '$post_array[title]', '$post_array[content]', '$post_array[author]','$post_array[status]'
															, '$post_array[type]', '$post_array[date]', '$post_array[password]', '$post_array[sn_link]')";
	
	$result 				= $sn_sql->query($sql_c);

	if(!$result){
		return false;
	}

	return $sn_sql->connect->insert_id;
}
/*4
	delete post by his id
	return boolean true or false
*/
function sn_delete_post($id)
{
	global $sn_sql;

	$id= (int)$id;

	if($id==0)
		return false;
	
	$result = $sn_sql->query("DELETE FROM `posts` WHERE `id`=$id");

	delete_all_post_meta($id); // delete his metas 

	if (!$result) 
		return false;

	return true;
}
/*5
	update post

	$post_array : (array) have all the thing that you need to add your post
				  | 'title'  	=> * 				this fild is required 
				  | 'content'	=> * 				this fild is required
				  | 'author' 	=> (int) 			the id of the user that add this post - his author - / default - 1
				  | 'type'   	=> (string) 		the type of the post accept / {'post','attachment⁯', .... costume post type } / default - post
				  | 'status' 	=> (int)			the status of the post if it's draft or public or ..... / default - 0
				  | 'password'	=> (string)			the password of this post if there is any password
				  | 'date' 		=> (string / date)	the time that this post has been published / default is date('Y-m-d H:i:s') the current time that the post had published 
				  | 'sn_link' 	=> (string)			this option is for the SEO purposes / default is the title of this post

	return boolean true or false or null
*/
function sn_update_post($post_array)
{
	global $sn_sql;

	if (!is_array($post_array) || empty($post_array))
		return null;

	if (!isset($post_array['id']) || !(int)$post_array['id']) {
		return false;
	}

	$id = (int)$post_array['id'];

	$post = get_post_by_id($id);
 	
 	// set default values

		$post_array['title'] 	=  (!isset($post_array['title'])) ? $post->title : mysql_real_escape_string ($post_array['title']);

		$post_array['content'] 	=  (!isset($post_array['content'])) ? $post->content : mysql_real_escape_string($post_array['content']);

		$post_array['author'] 	=  (!isset($post_array['author'])) ? $post->author : mysql_real_escape_string ($post_array['author']);

		$post_array['type'] 	=  (!isset($post_array['type'])) ? $post->type : mysql_real_escape_string($post_array['type']);

		$post_array['status'] 	=  (!isset($post_array['status'])) ? $post->status : mysql_real_escape_string ($post_array['status']);

		$post_array['password'] =  (!isset($post_array['password'])) ? $post->password : mysql_real_escape_string ($post_array['password']);

		$post_array['date'] 	=  (!isset($post_array['date'])) ? $post->date : mysql_real_escape_string ($post_array['date']);

		$post_array['sn_link'] 	=  (!isset($post_array['sn_link'])) ? $post->sn_link  :  str_ireplace(' ', '-', mysql_real_escape_string($post_array['sn_link']));

		$post_array['sn_link'] 	=  str_ireplace(array('§', '!', '?', ',', ';', '^', '$', '£', '(', ')','{', '}' ,'[', ']', '"', "'",), '', $post_array['title']);

	// end of default values

	$sql_c  				= "UPDATE `posts` SET  `title` = '$post_array[title]' , `content` = '$post_array[content]' ,`author` = '$post_array[author]' ,

													`type` = '$post_array[type]' ,`status` = '$post_array[status]' ,`password` = '$post_array[password]' ,

													`date` = '$post_array[date]' ,`sn_link` = '$post_array[sn_link]' WHERE id = $id";

	$result 				= $sn_sql->query($sql_c);

	if(!$result){
		return false;
	}

	return true;
}
/*
	Post meta functions
*/
/*6
	get post meta by the post id

	$post_id 		: (int) the id of the post that you need it meta
	$meta_key 		: (string) the meta key name that you user for this value

			false or null
	return 
			return the value of the meta kye that you enterd
*/
function get_post_meta($post_id,$meta_key)
{
	global $sn_sql;

	$post_id = (int) $post_id;
	if ($post_id === 0) {
		return false;
	}
	$post = get_post_by_id($post_id);
	if (!$post) {
		return false;
	}
	$meta_key = @mysql_real_escape_string($meta_key);

	$sql_c		= "SELECT `meta_value` FROM `post_meta` WHERE `post_id` = $post_id AND `meta_key` = '$meta_key' ";

	$meta_value	= $sn_sql->query($sql_c);
	
	if ($meta_value->num_rows == 0) {
		return null;
	}
	$meta_value	= $sn_sql->query_object();
	
	$meta_value = $meta_value->meta_value;
	
	return $meta_value;
}
/*7
	add new post meta

	$post_id 		: (int) the id of the post that you want to add to it new meta
	$meta_key 		: (string) the meta key name that you user to get this value
	$meta_value 	: (string) the meta value that you want ti store in datebase

			(int) the post meta id
	return 	
			(boolean) only false
*/
function add_post_meta($post_id,$meta_key,$meta_value)
{
	global $sn_sql;

	if(!check_empty($post_id) || !check_empty($meta_key) || !check_empty($meta_value))
		return false;

	$post_id	= (int) $post_id;
	$meta_key	= mysql_real_escape_string($meta_key);
	$meta_value = mysql_real_escape_string($meta_value);

	$post = get_post_by_id($post_id);
	$meta_exist = get_post_meta($post_id,$meta_key);
	if (!empty($meta_exist)) {
		return false; // exist meta;
	}
	if (!$post)
		return false;

	$sql_c = "INSERT INTO `post_meta` VALUES (null, $post_id, '$meta_key', '$meta_value')";

	$meta  = $sn_sql->query($sql_c);
	if (!$meta) {
		return false;
	}
	return $sn_sql->connect->insert_id;	
}
/*8
	update the meta key name
		// you use it when you think that you are using the wrong meta key name for it value
	$id 			: (int) 	the post id to add to it this meta
	$key_name 		: (string) 	the current key name that you want to change
	$new_key_name	: (string) 	the new name for your meta key

	return (boolean) true / false
*/
function update_post_meta_key($id,$key_name,$new_key_name)
{
	global $sn_sql;

	$id = (int) $id;
	
	if(empty($id) || empty($key_name) || empty($new_key_name))
		return false;

	$key_name         = strip_tags(mysql_real_escape_string($key_name));
	$new_key_name     = strip_tags(mysql_real_escape_string($new_key_name));

	if (get_post_meta($id,$key_name) == false)
		return false;

	$sql_c            = "UPDATE `post_meta` SET `meta_key` = '$new_key_name' WHERE `post_id` = $id AND `meta_key` = '$key_name' ";

	$result       = $sn_sql->query($sql_c);

	if (!$result) 
	{
		return false;
	}

	return true;
}
/*9
	set new value for a meta key
*/
function set_post_meta_value($id,$key_name,$new_value)
{
	global $sn_sql;

	$id = (int) $id;
	
	if(empty($id) || empty($key_name) || empty($new_value))
		return false;

	$key_name     = strip_tags(mysql_real_escape_string($key_name));
	$new_key_name = strip_tags(mysql_real_escape_string($new_key_name));

	if (get_post_meta($id,$key_name) == false)
		return false;

	$sql_c        = "UPDATE `post_meta` SET `meta_value` = '$new_value' WHERE `post_id` = $id AND `meta_key` = '$key_name' ";

	$result       = $sn_sql->query($sql_c);

	if (!$result) 
	{
		return false;
	}

	return true;
}
/*10
	delete a post meta by it post id and meta key
*/
function delete_post_meta($post_id,$meta_key)
{
	global $sn_sql;

	if(!check_empty($post_id) || !check_empty($meta_key))
		return false;

	$post_id	= (int) $post_id;
	$meta_key	= mysql_real_escape_string($meta_key);

	$sql_c	= "DELETE FROM `post_meta` WHERE `post_id`= $post_id AND  `meta_key` = '$meta_key'";

	$result = $sn_sql->query($sql_c);

	if (!$result) 
		return false;

	return true;
}
/*11
	delete all post metas so they can't cause any problems in future
*/
function delete_all_post_meta($post_id)
{
	global $sn_sql;

	$post_id	= (int) $post_id;

	if(!check_empty($post_id) || $post_id == 0)
		return false;

	$sql_c	= "DELETE FROM `post_meta` WHERE `post_id`= $post_id ";

	$result = $sn_sql->query($sql_c);

	if (!$result) 
		return false;

	return true;
}

/*	get the excerpt of the content default 44 word	*/


function content_excerpt($content=""){
	
	$date_array=explode(" ", $content);

	$content_array= array();

	for ($ii=0; $ii < 44; $ii++) { 
		if(empty($date_array[$ii]))
		{
			$data=implode(" " ,$content_array);

			return $data;
			break;			
		}
		$content_array[]= $date_array[$ii];
		

	}
	$data=implode(" " ,$content_array);
	return $data." ... " ;
}

/*	posts file show all the posts in the site in the panel posts page 	*/

function show_all_posts($extra= ''){

	$posts = get_posts('*',' '.$extra.' ORDER BY `id` desc');

	if ($posts == null) {
		echo "<tr><div class='no-content'><a href='edit-posts.php?action=add'>لا يوجد مقالات في موقعك يمكنك أن تنشئ واحد من هنا</a></div></tr>";
	}

	if (!empty($posts) && is_object($posts['0'])) : 
		foreach ($posts as $post) :
			$author = @get_user_by_id($post->author);

			$thumbnail = get_post_meta($post->id,'thumbnail');
			echo '<tr>
			<td>'.$post->id.'</td>
			<td>
				<a href="edit-posts.php?id='.$post->id.'">
					<img class="post-thumb" src="'.$thumbnail.'" width="100" height="100" title="'.@$post->title.'" alt="'.@$post->title.'" />
				</a>

			</td>
			<td>
				<a href="edit-posts.php?id='.$post->id.'">'.@$post->title.'</a> 
				<p>'.draft_excerpt_d(@$post->content).'</p>
			</td>
			<td>'.@$author->display_name.'</td>
			<td>'.@mysql2date( 'Y-m-d', $post->date ).'</td>
			<td>empty</td>
			<td>غير مصنف</td>
		 </tr>';
		endforeach;
	endif;

	/*
	foreach ($posts as $post) {
		

	}*/
	
}// function end