<?php
$status = (isset($_GET['status'])) ? ' WHERE `status` = '.$_GET['status'].'   ' : ' ' ;
?>
<?php require_once("header.php") ?>
		<div class="container" id="container">
			<div class="table-header">
				<p>
					المقالات
					<a href="#newpost" class="flat-btn flat-blue">أضف مقال جديد</a>
				</p>
				<p>
					<a href="posts.php">الكل</a> <span class="post_num" >(<?php echo all_posts_num(); ?>)</span> |
					<a href="posts.php?status=1">المنشورة</a> <span class="post_num">(<?php echo public_num(); ?>)</span> |
					<a href="posts.php?status=0">المسودات</a> <span class="post_num" >(<?php echo draft_num(); ?>)</span> 
				</p>
				<div class="table-content">
					<table>
						<tr>
							<th>id</th>
							<th>الصورة البارزة</th>
							<th>العنوان</th>
							<th>الكاتب</th>
							<th>تاريخ</th>
							<th>عدد التعليقات</th>
							<th>تصنيف</th>
						</tr>
						<?php show_all_posts($status); ?>

					</table>
				</div>
			</div>
		</div>
<?php require_once("footer.php"); ?>