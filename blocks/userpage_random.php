<?php
/**
 * ****************************************************************************
 * userpage - MODULE FOR XOOPS
 * Copyright (c) Herv� Thouzard of Instant Zero (http://www.instant-zero.com)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Herv� Thouzard of Instant Zero (http://www.instant-zero.com)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         userpage
 * @author 			Herv� Thouzard of Instant Zero (http://www.instant-zero.com)
 *
 * Version : $Id:
 * ****************************************************************************
 */
require_once XOOPS_ROOT_PATH.'/modules/userpage/include/common.php';

function b_userpage_random_show($options)	// 10=Items count, 30=Title's length
{
	$block = array();
	$start = 0;
	$limit = intval($options[0]);

	$userpage_handler =& xoops_getmodulehandler('userpage', 'userpage');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('1', '1','='));
	$pages = array();
	$pages = $userpage_handler->getRandomPages($criteria, false, $limit);
	foreach($pages as $page) {
		$page->setVar('dohtml', userpage_utils::getModuleOption('allowhtml'));
		$data = array();
		$data = $page->toArray();
		$data['up_title'] = xoops_substr(strip_tags($page->getVar('up_title')),0,intval($options[1]));
		$block['pages'][] = $data;
	}
	return $block;
}


/**
 * The edit function
 */
function b_userpage_random_edit($options)	// 10=Items count, 30=Title's length
{
	$form= '';
    $form .= _MB_USERPAGE_ITEMS_COUNT."&nbsp;<input type='text' name='options[]' value='".$options[0]."' />&nbsp;<br />";
    $form .= _MB_USERPAGE_TITLES_LENGTH."&nbsp;<input type='text' name='options[]' value='".$options[1]."' />&nbsp;";
	return $form;
}


/**
* Block, "on the fly".
*/
function b_userpage_random_onthefly($options)
{
	$options = explode('|',$options);
	$block = & b_userpage_random_show($options);

	$tpl = new XoopsTpl();
	$tpl->assign('block', $block);
	$tpl->display('db:userpage_block_random.html');
}
?>