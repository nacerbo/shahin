<?php
$GLOBALS['template'] = '';

function is_404(){
	if ($GLOBALS['template'] == '404')
		return true;
	else
		return false;
}

function is_search(){
	if ($GLOBALS['template'] == 'search')
		return true;
	else
		return false;
}

function is_single(){
	if ($GLOBALS['template'] == 'single')
		return true;
	else
		return false;
}

function is_category(){
	if ($GLOBALS['template'] == 'category')
		return true;
	else
		return false;
}

function is_tag(){
	if ($GLOBALS['template'] == 'tag')
		return true;
	else
		return false;
}