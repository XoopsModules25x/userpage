<?php
/**
 * ****************************************************************************
 * USERPAGE - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */
include_once XOOPS_ROOT_PATH.'/modules/userpage/include/functions.php';

/**
 * Show most viewed pages
 */
function b_userpage_top_show($options)	// 10=Items count, 30=Title's length
{
	$block = array();
	$start = 0;
	$limit = intval($options[0]);

	$userpage_handler =& xoops_getmodulehandler('userpage', 'userpage');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('1', '1','='));
	$criteria->setLimit($limit);
	$criteria->setStart($start);
	$criteria->setSort('up_hits');
	$criteria->setOrder('DESC');
	$pages = array();
	$pages = $userpage_handler->getObjects($criteria);

	foreach($pages as $page) {
		$page->setVar('dohtml', userpage_getmoduleoption('allowhtml'));
		$block['pages'][]=array(
			'up_pageid' => $page->getVar('up_pageid'),
			'up_uid' => $page->getVar('up_uid'),
			'user_name' => $page->uname(),
			'up_title' => xoops_substr(strip_tags($page->getVar('up_title')),0,intval($options[1])),
			'up_text' => $page->getVar('up_text'),
			'up_created' => formatTimestamp($page->getVar('up_created'),userpage_getmoduleoption('dateformat')),
			'up_hits' => $page->getVar('up_hits')
		);
	}
	return $block;
}


/**
 * The edit function
 */
function b_userpage_top_edit($options)		// 10=Items count, 30=Title's length
{
	$form= '';
    $form .= _MB_USERPAGE_ITEMS_COUNT."&nbsp;<input type='text' name='options[]' value='".$options[0]."' />&nbsp;<br />";
    $form .= _MB_USERPAGE_TITLES_LENGTH."&nbsp;<input type='text' name='options[]' value='".$options[1]."' />&nbsp;";
	return $form;
}


/**
* Block, "on the fly".
*/
function b_userpage_top_onthefly($options)
{
	$options = explode('|',$options);
	$block = & b_userpage_top_show($options);

	$tpl = new XoopsTpl();
	$tpl->assign('block', $block);
	$tpl->display('db:userpage_block_top.html');
}
?>