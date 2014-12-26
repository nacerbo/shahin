<?php require_once("header.php") ?>
		<div class="container" id="container">
			<div class="script-news">
				<div class="half-row entry">
					<h2 class="section-title" id="drafts-box">مسودة سريعة</h2>
					<div class="section-content" id="drafts-box-content">
						<div class="draft-form">
							<form method="post" action="fast-draft.php">
								<input	   name="draft_title"   placeholder="عنوان المسودة" type="text" />
								<textarea  name="draft_content" placeholder="ماذا يدور في ذهنك؟" ></textarea>
								<input name="draft_save"    type="submit" value="حفظ كمسودة"   class="flat-btn flat-blue" />
								<input name="draft_publish" type="submit" value="نشر كملاحظة" class="flat-btn flat-violet" />
							</form>
						</div>
						<h2 class="section-title">أخر المسودات</h2>
						<div class="section-content">
							<?php tiny_drafts(4)?>
							<a href="posts.php?status=0" class="flat-btn block flat-orange">عرض جميع المسودات</a>
						</div>
					</div>
				</div>

				<div class="half-row lest">
					<h2 class="section-title" id="last-activ">نشاط الموقع</h2>
					<div class="section-content" id="last-ac-content">
						<?php tiny_lposts(4); ?>
						<a href="posts.php?status=1" class="flat-btn block flat-dgreen">عرض جميع المقالات</a>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div><!-- container end -->
<?php require_once("footer.php"); ?>