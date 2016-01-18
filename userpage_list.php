<?php
/**
 * ****************************************************************************
 * userpage - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard (http://www.herve-thouzard.com/)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Hervé Thouzard (http://www.herve-thouzard.com/)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         userpage
 * @author 			Hervé Thouzard (http://www.herve-thouzard.com/)
 *
 * Version : $Id:
 * ****************************************************************************
 */
require 'header.php';
$xoopsOption['template_main'] = 'userpage_list.html';
require_once XOOPS_ROOT_PATH.'/header.php';
require_once XOOPS_ROOT_PATH.'/class/pagenav.php';

$userpage_handler =& xoops_getmodulehandler('userpage', 'userpage');
$allowhtml = userpage_utils::getModuleOption('allowhtml');
$myts =& MyTextSanitizer::getInstance();

$limit = userpage_utils::getModuleOption('linesperpage');
$xoopsTpl->assign('allowrss', userpage_utils::getModuleOption('allowrss'));
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
	$xoopsTpl->append('pages', $page->toArray());
}
// Page's title
$xoopsTpl->assign('xoops_pagetitle', strip_tags(_USERPAGE_BOOK).' - '.$myts->htmlSpecialChars($xoopsModule->name()));
require_once(XOOPS_ROOT_PATH."/footer.php");
?>