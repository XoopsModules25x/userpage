<?php
/**
 * ****************************************************************************
 * USERPAGE - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

require_once '../../mainfile.php';
require_once XOOPS_ROOT_PATH.'/class/template.php';
require_once XOOPS_ROOT_PATH."/modules/userpage/include/functions.php";

if(!userpage_getmoduleoption('allowrss')) {
	exit();
}

if (function_exists('mb_http_output')) {
	mb_http_output('pass');
}
$charset = 'utf-8';
header ('Content-Type:text/xml; charset='.$charset);
$tpl = new XoopsTpl();
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(3600);
if (!$tpl->is_cached('db:userpage_rss.html')) {
	$sitename = htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES);
	$email = $xoopsConfig['adminmail'];
	$slogan = htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES);
	$category = 'UserPage';
	$module = 'UserPage';
	$tpl->assign('charset',$charset);
	$tpl->assign('channel_title', xoops_utf8_encode($sitename));
	$tpl->assign('channel_link', XOOPS_URL.'/');
	$tpl->assign('channel_desc', xoops_utf8_encode($slogan));
	$tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
	$tpl->assign('channel_webmaster', xoops_utf8_encode($email));
	$tpl->assign('channel_editor', xoops_utf8_encode($email));
	$tpl->assign('channel_category', xoops_utf8_encode($category));
	$tpl->assign('channel_generator', xoops_utf8_encode($module));
	$tpl->assign('channel_language', _LANGCODE);
	$tpl->assign('image_url', XOOPS_URL.'/images/logo.gif');
	$dimention = getimagesize(XOOPS_ROOT_PATH.'/images/logo.gif');
	if (empty($dimention[0])) {
		$width = 88;
	} else {
		$width = ($dimention[0] > 144) ? 144 : $dimention[0];
	}
	if (empty($dimention[1])) {
		$height = 31;
	} else {
		$height = ($dimention[1] > 400) ? 400 : $dimention[1];
	}
	$tpl->assign('image_width', $width);
	$tpl->assign('image_height', $height);

	$userpage_handler =& xoops_getmodulehandler('userpage', 'userpage');
	$critere = new Criteria('1', '1','=');
	$critere->setLimit(10);
	$critere->setStart(0);
	$critere->setOrder('DESC');
	$critere->setSort('up_created');
	$pages = $userpage_handler->getObjects($critere);
	foreach($pages as $page) {
		$titre  = htmlspecialchars($page->getVar('up_title'), ENT_QUOTES);
		$description = xoops_substr(htmlspecialchars(strip_tags($page->getVar('up_text')), ENT_QUOTES),0,userpage_getmoduleoption('rsslength'));
		$tpl->append('items', array('title' => xoops_utf8_encode($titre),
			'link' => XOOPS_URL.'/modules/userpage/index.php?page_id='.$page->getVar('up_pageid'),
			'guid' => XOOPS_URL.'/modules/userpage/index.php?page_id='.$page->getVar('up_pageid'),
			'pubdate' => formatTimestamp($page->getVar('up_created'), 'rss'),
			'description' => xoops_utf8_encode($description)));
	}
}
$tpl->display('db:userpage_rss.html');
?>