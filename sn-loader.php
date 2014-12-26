<?php
session_start();

$inc_path = 'sn-include';

require_once('sn-config.php');

require_once($inc_path.'/class.db.php');

require_once($inc_path.'/site.options.php');

require_once($inc_path.'/site.functions.php');

require_once($inc_path.'/template.functions.php');

require_once($inc_path.'/user.api.php');

require_once($inc_path.'/posts.api.php');

require_once($inc_path.'/comments.api.php');
