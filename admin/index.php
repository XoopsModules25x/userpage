<?php
/**
 * ****************************************************************************
 * userpage - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         userpage
 * @author 			Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 *
 * Version : $Id:
 * ****************************************************************************
 */
require_once '../../../include/cp_header.php';
require_once XOOPS_ROOT_PATH.'/modules/userpage/include/common.php';
require_once XOOPS_ROOT_PATH.'/modules/userpage/admin/functions.php';
require_once XOOPS_ROOT_PATH.'/class/pagenav.php';

// ********************************************************************************************************************
// **** Main
// ********************************************************************************************************************
$op = 'default';
if(isset($_POST['op'])) {
 $op=$_POST['op'];
} elseif(isset($_GET['op'])) {
	$op=$_GET['op'];
}
$userpage_handler =& xoops_getmodulehandler('userpage', 'userpage');

switch ($op) {
	/**
 	 * Default action, show statistics and a listing of all the pages
 	 */
	case 'stats':
	default:
        xoops_cp_header();
        userpage_adminmenu(0);

		if (file_exists(XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/admin.php')) {
			require_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/admin.php';
		} else {
			require_once XOOPS_ROOT_PATH.'/modules/userpage/language/english/main.php';
		}

		if (file_exists(XOOPS_ROOT_PATH.'/modules/userpage/language/'.$xoopsConfig['language'].'/main.php')) {
			require_once XOOPS_ROOT_PATH.'/modules/userpage/language/'.$xoopsConfig['language'].'/main.php';
		} else {
			require_once XOOPS_ROOT_PATH.'/modules/userpage/language/english/main.php';
		}
        $totalcount = $userpage_handler->getCount();	// Pages count
		echo '<h4>'.sprintf(_AM_USERPAGE_STATS,$totalcount).'</h4>';
		$limit = userpage_utils::getModuleOption('linesperpage');
		$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
		$critere = new Criteria('1', '1','=');
		$critere->setLimit($limit);
		$critere->setStart($start);
		// tip, replace "up_created" with "up_uid" if you want to sort by user and not by date
		$critere->setSort('up_created');
		$critere->setOrder('DESC');
		$pagescount = $userpage_handler->getCount();
		$pagenav = new XoopsPageNav($pagescount, $limit , $start, 'start', 'op=list');
		$pages = array();
		$pages = $userpage_handler->getObjects($critere);
		echo "<div align='right'>".$pagenav->renderNav().'</div><br />';
		echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
		echo '<tr>';
		echo '<th align="center">'._USERPAGE_USER.'</th>';
		echo '<th align="center">'._USERPAGE_TITLE.'</th>';
		echo '<th align="center">'._USERPAGE_DATE.'</th>';
		echo '<th align="center">'._USERPAGE_HITS.'</th>';
		echo '<th align="center">'._AD_ACTION.'</th>';
		echo '</tr>';
		$class='';
		$allowhtml = userpage_utils::getModuleOption('allowhtml');
		foreach($pages as $page) {
			$class = ($class == 'even') ? 'odd' : 'even';
			$page->setVar('dohtml',$allowhtml);
			echo "<tr class='".$class."'>";
			echo '<td>'.$page->uname().'</td>';
			echo "<td><a href=\"".XOOPS_URL."/modules/userpage/index.php?page_id=".$page->getVar('up_pageid')."\">".$page->getVar('up_title')."</a></td>";
			echo "<td align=\"center\">".formatTimestamp($page->getVar('up_created'), userpage_utils::getModuleOption('dateformat'))."</td>";
			echo "<td align=\"center\">".$page->getVar('up_hits')."</td>";
			$del_action = "<a title='"._DELETE."' href='index.php?op=delete&id=".$page->getVar('up_pageid')."' ".userpage_utils::javascriptLinkConfirm(_USERPAGE_ARE_YOU_SURE)." ><img src='../images/delete.gif' alt='"._DELETE."' border='0' /></a>";
			$view_action = "<a target='_blank' title='"._USERPAGE_VIEW."' href='".$page->getURL()."'><img src='../images/view.gif' alt='"._USERPAGE_VIEW."' border='0' /></a>";
			echo "<td align=\"center\">".$del_action.' '.$view_action.'</td>';
			echo "</tr>";
		}
		echo '</table>';
        echo "<div align='right'>".$pagenav->renderNav().'</div><br />';
		echo "<br /><div align='center'><a href='http://xoops.instant-zero.com' target='_blank'><img src='../images/instantzero.gif'></a></div>";
		break;

	/**
	 * Remove a specific page
	 */
	case 'delete':
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$res = false;
		if($id>0) {
			$page = $userpage_handler->get($id);
			if(is_object($page)) {
				$res = $userpage_handler->delete($page, true);
			}
		}
		if($res) {
		    userpage_utils::updateCache();
			redirect_header('index.php', 2, _USERPAGE_DB_OK);
			exit();
		} else {
			redirect_header('index.php', 4, _ERRORS);
			exit();
		}
		break;
}
xoops_cp_footer();
?>