<?php
/**
 * ****************************************************************************
 * USERPAGE - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

function userpage_search($queryarray, $andor, $limit, $offset, $userid) {
	include_once XOOPS_ROOT_PATH.'/modules/userpage/include/functions.php';
	$userpage_handler =& xoops_getmodulehandler('userpage', 'userpage');
	$ret = array();

	$criteria = new CriteriaCompo();
	if ($userid != 0) {
		$criteria->add(new Criteria('up_uid', $userid,'='));
	}

	if (is_array($queryarray) && $count = count($queryarray)) {
		$tmpcrit = new CriteriaCompo(new Criteria('up_title', "%".$queryarray[0]."%",'like'));
		$tmpcrit->add(new Criteria('up_text', "%".$queryarray[0]."%",'like'),'OR');
		$criteria->add($tmpcrit);
		unset($tmpcrit);
		for($i=1;$i < $count;$i++) {
			$tmpcrit = new CriteriaCompo(new Criteria('up_title', "%".$queryarray[$i]."%",'like'));
			$tmpcrit->add(new Criteria('up_text', "%".$queryarray[$i]."%",'like'),'OR');
			$criteria->add($tmpcrit,$andor);
			unset($tmpcrit);
		}
	}

	$criteria->setOrder('DESC');
	$criteria->setSort('up_created');
	$tblpages = array();
	$tblpages = $userpage_handler->getObjects($criteria);
	$i = 0;
	foreach($tblpages as $page) {
		$ret[$i]['image'] = "images/icon.gif";
		$ret[$i]['link'] = "index.php?page_id=".$page->getVar('up_pageid');
		$ret[$i]['title'] = $page->getVar('up_title');
		$ret[$i]['time'] = $page->getVar('up_created');
		$ret[$i]['uid'] = $page->getVar('up_uid');
		$i++;
	}
	return $ret;
}
?>