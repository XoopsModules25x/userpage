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
$userpage_handler =& xoops_getmodulehandler('userpage', 'userpage');
global $myts;
$myts = MyTextSanitizer::getInstance();
$com_itemid = isset($_GET['com_itemid']) ? intval($_GET['com_itemid']) : 0;
if ($com_itemid > 0) {
	$criteria = new Criteria('up_pageid', $com_itemid, '=');
	$cnt = $userpage_handler->getCount($criteria);
	if($cnt > 0) {
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