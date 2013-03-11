<?php
/**
 * ****************************************************************************
 * USERPAGE - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

require '../../mainfile.php';
$userpage_handler =& xoops_getmodulehandler('userpage', 'userpage');
global $myts;
$myts =& MyTextSanitizer::getInstance();
$com_itemid = isset($_GET['com_itemid']) ? intval($_GET['com_itemid']) : 0;
if ($com_itemid > 0) {
	$criteria = new Criteria('up_pageid', $com_itemid, '=');
	$cnt = $userpage_handler->getCount($criteria);
	if($cnt>0) {
		$pagetbl = $userpage_handler->getObjects($criteria);
		$page = $pagetbl[0];
		$title = $page->getVar('up_title');
	} else {
		$title = '';
	}
	$com_replytitle = $title;
    include XOOPS_ROOT_PATH.'/include/comment_new.php';
}
?>