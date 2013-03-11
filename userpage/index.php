<?php
/**
 * ****************************************************************************
 * USERPAGE - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

require_once '../../mainfile.php';
$xoopsOption['template_main'] = 'userpage_index.html';
require_once XOOPS_ROOT_PATH.'/header.php';
require_once XOOPS_ROOT_PATH.'/modules/userpage/include/functions.php';

$userpage_handler = & xoops_getmodulehandler('userpage', 'userpage');
$allowhtml = userpage_getmoduleoption('allowhtml');
$myts = & MyTextSanitizer::getInstance();

$is_admin = false;
$uid = 0;
if(is_object($xoopsUser)) {
	$uid = $xoopsUser->getVar('uid');
	if($xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
		$is_admin = true;
	}
	$xoopsTpl->assign('confirm_delete', userpage_JavascriptLinkConfirm(_USERPAGE_ARE_YOU_SURE));
} else {
	if(!isset($_GET['page_id'])) {
		header('Location: userpage_list.php');
	}
}

$xoopsTpl->assign('is_admin', $is_admin);
$page_id = 0;
if(isset($_GET['page_id'])) {
	$page_id = intval($_GET['page_id']);
}

$xoopsTpl->assign('allowrss', userpage_getmoduleoption('allowrss'));
if(empty($page_id)) {	// Show current user's page
	$xoopsTpl->assign('currentuser', true);
	$criteria = new Criteria('up_uid', $uid, '=');
	$cnt = $userpage_handler->getCount($criteria);
	if($cnt>0) {
		$pagetbl = $userpage_handler->getObjects($criteria);
		$page = $pagetbl[0];
	} else {
		$page = $userpage_handler->create(true);
	}
} else {	// Shows a user's page
	// Is this the current user's page ?
	$xoopsTpl->assign('currentuser', false);
	$criteria = new Criteria('up_pageid', $page_id, '=');
	$cnt = $userpage_handler->getCount($criteria);
	if($cnt > 0) {
		$pagetbl = $userpage_handler->getObjects($criteria);
		$page = $pagetbl[0];
		if($page->getVar('up_uid') == $uid) {
			$xoopsTpl->assign('currentuser', true);
		}
	} else {	// Page not found
	    redirect_header(XOOPS_URL.'/index.php',2,_USERPAGE_PAGE_NOT_FOUND);
		exit();
	}
}
$page->setVar('dohtml',$allowhtml);		// Set html

if($page->getVar('up_pageid') !=0 ) {
	$xoopsTpl->assign('mail_cmd', 'mailto:?subject='.sprintf(_USERPAGE_INTARTICLE,$xoopsConfig['sitename']).'&amp;body='.sprintf(_USERPAGE_INTARTFOUND, $xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/userpage/index.php?page_id='.$page->getVar('up_pageid'));
	// Update counter (only if the user is not the owner and if the page exists)
	if($uid != $page->getVar('up_uid')) {
		$userpage_handler->UpdateCounter($page->getVar('up_pageid'));
	}
} else {
	$xoopsTpl->assign('mail_cmd', '');
}
$xoopsTpl->assign('up_pageid',$page->getVar('up_pageid'));
$xoopsTpl->assign('up_title',$page->getVar('up_title'));
$xoopsTpl->assign('up_text',$page->getVar('up_text'));
$xoopsTpl->assign('up_created',$page->getVar('up_created'));
$xoopsTpl->assign('up_uid', $page->getVar('up_uid'));

$page_user = null;
$page_user = new XoopsUser($page->getVar('up_uid'));
if(is_object($page_user)) {
	$xoopsTpl->assign('user_avatar', XOOPS_UPLOAD_URL.'/'.$page_user->getVar('user_avatar'));
	$xoopsTpl->assign('user_name', $page_user->getVar('name'));
	$xoopsTpl->assign('user_uname', $page_user->getVar('uname'));
	$xoopsTpl->assign('user_email', $page_user->getVar('email'));
	$xoopsTpl->assign('user_url', $page_user->getVar('url'));
	$xoopsTpl->assign('user_from', $page_user->getVar('user_from'));
	$xoopsTpl->assign('user_sig', $page_user->getVar('user_sig'));
}
$xoopsTpl->assign('up_uid', $page->getVar('up_uid'));


if($page->getVar('up_created')!=0) {
	$xoopsTpl->assign('up_dateformated',formatTimestamp($page->getVar('up_created'),userpage_getmoduleoption('dateformat')));
} else {
	$xoopsTpl->assign('up_dateformated','');
}
$xoopsTpl->assign('up_hits',$page->getVar('up_hits'));
// Page's title
$xoopsTpl->assign('xoops_pagetitle', strip_tags($page->getVar('up_title')).' - '.$myts->htmlSpecialChars($xoopsModule->name()));
// META Keywords and description
$meta_keywords = userpage_createmeta_keywords(strip_tags($page->getVar('up_title')).' '.strip_tags($page->getVar('up_text')));
if(isset($xoTheme) && is_object($xoTheme)) {
	$xoTheme->addMeta( 'meta', 'keywords', $meta_keywords);
} else {	// Compatibility for old Xoops versions
	$xoopsTpl->assign('xoops_meta_keywords', $meta_keywords);
}

$meta_description = strip_tags($page->getVar('up_title'));
if(isset($xoTheme) && is_object($xoTheme)) {
	$xoTheme->addMeta( 'meta', 'description', $meta_description);
} else {	// Compatibility for old Xoops versions
	$xoopsTpl->assign('xoops_meta_description', $meta_description);
}

if(!empty($page_id)) {
	require_once XOOPS_ROOT_PATH.'/include/comment_view.php';
}
require_once(XOOPS_ROOT_PATH."/footer.php");
?>