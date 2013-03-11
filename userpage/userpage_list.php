<?php
/**
 * ****************************************************************************
 * USERPAGE - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

require_once "../../mainfile.php";
$xoopsOption['template_main'] = 'userpage_list.html';
require_once XOOPS_ROOT_PATH.'/header.php';
require_once XOOPS_ROOT_PATH.'/modules/userpage/include/functions.php';

$userpage_handler =& xoops_getmodulehandler('userpage', 'userpage');
$allowhtml = userpage_getmoduleoption('allowhtml');
$myts =& MyTextSanitizer::getInstance();

require_once XOOPS_ROOT_PATH.'/class/pagenav.php';
$limit = userpage_getmoduleoption('linesperpage');
$xoopsTpl->assign('allowrss', userpage_getmoduleoption('allowrss'));
//$xoopsTpl->assign('op', $op);
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$critere = new Criteria('1', '1','=');
$critere->setLimit($limit);
$critere->setStart($start);
// tip, replace "up_created" with "up_uid" if you want to sort by user and not by date
$critere->setSort('up_created');
$critere->setOrder('DESC');
$pagescount = $userpage_handler->getCount();
if ($pagescount > $limit) {
	$pagenav = new XoopsPageNav($pagescount, $limit , $start, 'start', 'op=list');
	$xoopsTpl->assign('pagenav', $pagenav->renderNav());
}

$pages = array();
$pages = $userpage_handler->getObjects($critere);
foreach($pages as $page) {
	$page->setVar('dohtml',$allowhtml);
	$xoopsTpl->append('pages',array(
		'up_pageid' => $page->getVar('up_pageid'),
		'up_uid' => $page->getVar('up_uid'),
		'user_name' => $page->uname(),
		'up_title' => $page->getVar('up_title'),
		'up_text' => $page->getVar('up_text'),
		'up_created' => formatTimestamp($page->getVar('up_created'),userpage_getmoduleoption('dateformat')),
		'up_hits' => $page->getVar('up_hits')
	));
}
// Page's title
$xoopsTpl->assign('xoops_pagetitle', strip_tags(_USERPAGE_BOOK).' - '.$myts->htmlSpecialChars($xoopsModule->name()));
require_once(XOOPS_ROOT_PATH."/footer.php");
?>