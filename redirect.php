<?php
/**
 * ****************************************************************************
 * SHORTCUTS - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */
require_once('header.php');
require_once(XOOPS_ROOT_PATH.'/header.php');
require_once XOOPS_ROOT_PATH.'/modules/shortcuts/include/functions.php';

if(!is_object($xoopsUser)) {
	redirect_header(XOOPS_URL.'/index.php', 2, _SHORTCUTS_REGISTRED);
}

$uid = $xoopsUser->getVar('uid');
$urlid = 0;
$urlid = isset($_POST['selshort']) ? intval($_POST['selshort']) : 0;
if($urlid == 0) {
	$urlid = isset($_GET['selshort']) ? intval($_GET['selshort']) : 0;
}

$shortcuts_handler =& xoops_getmodulehandler('shortcuts', 'shortcuts');

if($urlid) {
	$myshortcuts = null;
	$myshortcuts = $shortcuts_handler->get($urlid);
	if(!is_object($myshortcuts)) {
		redirect_header(XOOPS_URL, 2, _ERRORS);
		exit();
	}
	if($myshortcuts->getVar('uid') != $xoopsUser->getVar('uid')) {
		redirect_header(XOOPS_URL.'/index.php', 2, _ERRORS);
		exit();
	}
	if(st_getmoduleoption('savehits')) {
		$myshortcuts->UpdateUrlHits();
	}
	if(st_getmoduleoption('useredirect')) {
		redirect_header($myshortcuts->getVar('url'), 1, $myshortcuts->getVar('title'));
		exit();
	} else {
		header('Location: '.$myshortcuts->getVar('url'));
	}
} else {
	redirect_header(XOOPS_URL.'/index.php', 2, _SHORTCUTS_ADD_AN_ENTRY);
	exit();
}
require_once(XOOPS_ROOT_PATH.'/footer.php');
?>
