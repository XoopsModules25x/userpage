<?php
/**
 * ****************************************************************************
 * USERPAGE - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

require_once "../../mainfile.php";
require_once XOOPS_ROOT_PATH.'/header.php';
require_once XOOPS_ROOT_PATH.'/modules/userpage/include/functions.php';

$userpage_handler =& xoops_getmodulehandler('userpage', 'userpage');
$uid = $id = 0;
$res = false;

if(is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
	$uid = $xoopsUser->getVar('uid');
} else {
	header('Location: userpage_list.php');
	exit;
}

if(isset($_GET['id'])) {
	$id = intval($_GET['id']);
	$page = $userpage_handler->get($id);
	if(is_object($page)) {
		$res = $userpage_handler->delete($page, true);
	}
}
if($res) {
	redirect_header('index.php', 2, _USERPAGE_DB_OK);
	exit();
} else {
	redirect_header('index.php', 4, _ERRORS);
	exit();
}
require_once(XOOPS_ROOT_PATH."/footer.php");
?>
