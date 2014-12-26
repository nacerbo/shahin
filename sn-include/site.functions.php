<?php 
/*
	Site title
*/
function check_empty($string){
	$new_name=str_replace(" ", "", $string);

	if (!empty($new_name)) 
		return true;
	else
		return false;	
}

/* links filtering  */
function post_link_filter($ptitle){
	if (empty($ptitle)) {
		return null;
	}

	$ptitle 	=  str_ireplace(array(' '), '-', mysql_real_escape_string($ptitle));

	$ptitle 	=  str_ireplace(array('§', '!', '?', ',', ';', '^', '$', '£', '(', ')','{', '}' ,'[', ']', '"', "'",":",'\\'), '', $ptitle);

	return $ptitle;
}

/**
 * Convert given date string into a different format.
 *
 * $format should be either a PHP date format string, e.g. 'U' for a Unix
 * timestamp, or 'G' for a Unix timestamp assuming that $date is GMT.
 *
 *
 *
 * @param string $format    Format of the date to return.
 * @param string $date      Date string to convert.
 * @param bool   $translate Whether the return date should be translated. Default true.
 * @return string|int|bool Formatted date string or Unix timestamp. False if $date is empty.
 */
function mysql2date( $format, $date ) {
	if ( empty( $date ) )
		return false;

	if ( 'G' == $format )
		return strtotime( $date . ' +0000' );

	$i = strtotime( $date );

	if ( 'U' == $format )
		return $i;

	else
		return date( $format, $i );
}


function set_current_page($page_name)
{
	global $current_page;
	$current_page = strip_tags(mysql_real_escape_string($page_name));
	return $current_page;
}

function sn_get_title($title='',$extra='')
{
	global $current_page;

	$title = (empty($title)) ? get_option('site_name') : $title ;

	$title = strip_tags(mysql_real_escape_string($title));

	if (!empty($extra)) 
		$extra = ' - '.$extra;
	
	else
		$extra=' - '.$current_page;
	
	return $title.$extra;
}

function sn_title()
{
	echo sn_get_title();
}


/*	Dashboard functions   */

/*	dashboard excerpt for drafts max 20 word	*/
function draft_excerpt_d($content=""){
	
	$date_array=explode(" ", $content);

	$content_array= array();

	for ($ii=0; $ii < 20; $ii++) { 
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
/**
 * show the draft posts in the box
 */
function tiny_drafts($number){
	$number = (int) $number;
	$posts = get_posts('*', 'WHERE `status`= 0 ORDER BY `id` desc');

	if ($posts == null) {
		echo "<div class='no-content'>لا يوجد مسودات أدخل أول مسودة فوق</div>";
	}
	$count =-1;

	if (!empty($posts)) : 
		foreach ($posts as $post) : $count++;
			if ($count === $number) {
				break;
			}
			?>
			<div class="tyni-draft">
				<a href="edit-posts.php?id=<?php echo $post->id; ?>"><?php echo $post->title; ?></a> 
				<span class="small"><?php echo mysql2date( 'Y-m-d', $post->date ); ?></span>
				<p>
				 <?php echo draft_excerpt_d($post->content); ?>
				</p>
			</div>
			<?php 
			
		endforeach;
	endif;

}// end of the function

/**
 * show the published posts in the box of activitys
 */
function tiny_lposts($number){
	$number = (int) $number;
	$posts = get_posts('*', 'WHERE `status`= 1 ORDER BY `id` desc');

	if ($posts == null) {
		echo "<div class='no-content'><a href='edit-posts.php?action=add'>لا يوجد مقالات في موقعك يمكنك أن تنشئ واحد من هنا</a></div>";
	}

	$count =-1;

	if (!empty($posts)) : 
		foreach ($posts as $post) : $count++; 
		if ($count === $number) {
			break;
		}
			?>
			<div class="tyni-draft">
				<a href="edit-posts.php?id=<?php echo $post->id; ?>"><?php echo $post->title; ?></a> 
				<span class="small"><?php echo mysql2date( 'Y-m-d', $post->date ); ?></span>
				<p>
				 <?php echo draft_excerpt_d($post->content); ?>
				</p>
			</div>
			<?php 
			
		endforeach;
	endif;
}// end of the function


/*
	the numbers of the posts accourding to their type
*/

function draft_num(){
	$posts_draft = get_posts_a('count(*)' ,' WHERE `status` = 0 ');
	$count = $posts_draft["0"]["count(*)"];
	
	return $count;
}


function public_num(){
	$posts_draft = get_posts_a('count(*)' ,' WHERE `status` = 1 ');
	$count = $posts_draft["0"]["count(*)"];
	
	return $count;

}

function all_posts_num(){
	$posts_draft = get_posts_a('count(id)' ,'');
	$count = $posts_draft["0"]["count(id)"];
	
	return $count;
}

