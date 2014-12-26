<?php 
require_once('../sn-loader.php');

$author_id = get_user_by_name($_COOKIE['user_name']);


if(isset($_POST['draft_save'])){
	$status = 0;
	$post_type = 'post';
}elseif (isset($_POST['draft_publish'])) {
	$status = 1;
	$post_type = 'note';
}else{
	$status = 0;
}

if (isset($_POST['draft_title']) && isset($_POST['draft_content'])) {
	$draft_array = array(
							'title'		=> $_POST['draft_title'],
							'content'	=> $_POST['draft_content'],
							'status' 	=> $status,
							'type'		=> $post_type,
							'sn_link' 	=> $_POST['draft_title'],
							'author'	=> $author_id->id,
						);

	$new_post_id = sn_add_post($draft_array);
	var_dump($new_post_id);
	if (is_object($new_post_id)) {
		header("Location: edit-posts.php?id=".$new_post_id->id);
	} 
	else 
	{
		header("Location: edit-posts.php?id=$new_post_id");
	}

}
