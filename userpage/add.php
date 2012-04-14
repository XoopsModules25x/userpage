<?php
/**
 * ****************************************************************************
 * SHORTCUTS - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */
require_once 'header.php';
require_once XOOPS_ROOT_PATH.'/modules/shortcuts/include/functions.php';
$xoopsOption['template_main'] = 'shortcuts_add.html';
require_once XOOPS_ROOT_PATH.'/header.php';

if(!is_object($xoopsUser)) {
	redirect_header(XOOPS_URL.'/index.php', 2, _SHORTCUTS_REGISTRED);
}

$option = 0;
$myts =& MyTextSanitizer::getInstance();

if(isset($_GET['Url'])) {
	$url = $myts->htmlSpecialChars(urldecode($_GET['Url']));
} elseif(isset($_POST['Url'])) {
	$url = $myts->htmlSpecialChars(urldecode($_POST['Url']));
} elseif(isset($_SESSION['shortcuts_url'])) {
	$url = $myts->htmlSpecialChars($_SESSION['shortcuts_url']);
} else {
	redirect_header('index.php', 2, _ERRORS);
}

$UrlTitle = '';
if(isset($_GET['Title'])) {
	$UrlTitle=$myts->htmlSpecialChars(urldecode($_GET['Title']));
} elseif(isset($_POST['Title'])) {
	$UrlTitle=$myts->htmlSpecialChars(urldecode($_POST['Title']));
} elseif(isset($_SESSION['shortcuts_pagetitle'])) {
	$UrlTitle=$myts->htmlSpecialChars($_SESSION['shortcuts_pagetitle']);
}

$op = '';
$op = isset($_GET['Op']) ? $_GET['Op'] : $_POST['Op'];
$maxshortcuts = st_getmoduleoption('maxshortcuts');

$titlelimit=st_getmoduleoption('titlelimit');
$xoopsTpl->assign('url_title',$UrlTitle);
$xoopsTpl->assign('xoops_pagetitle',_SHORTCUTS_PGTITLE1);
$xoopsTpl->assign('lang_reach_max_limit',sprintf(_SHORTCUTS_REACHLIMIT,$maxshortcuts));
$xoopsTpl->assign('url',urlencode($url));
$xoopsTpl->assign('visible_url',$url);
$xoopsTpl->assign('module_url',XOOPS_URL.'/modules/shortcuts/add.php');
$xoopsTpl->assign('module_index',XOOPS_URL.'/modules/shortcuts/index.php');

$enablerating = 0;
$enablerating = st_getmoduleoption('enablerating');
if ($enablerating) {
	$xoopsTpl->assign('lang_rating',_SHORTCUTS_RATING);
	$xoopsTpl->assign('enable_rating',true);
} else {
	$xoopsTpl->assign('enable_rating',false);
}

if($titlelimit>0) {
	$xoopsTpl->assign('maxlength','maxlength='.$titlelimit);
} else {
	$xoopsTpl->assign('maxlength','');
}

$shortcuts_handler =& xoops_getmodulehandler('shortcuts', 'shortcuts');
$uid = $xoopsUser->getVar('uid');

switch($op) {
	case 'Add':
		if($maxshortcuts>0) {	// Is the number of shortcuts limited ?
			$critere = new Criteria('uid', $uid,'=');
			$usershortcuts = $shortcuts_handler->getCount($critere)+1;
			if($usershortcuts > $maxshortcuts) {	// The limit has been reached
				$option = 2;
			} else {	// The number of shortcuts is limited but not reached
				$criteria = new CriteriaCompo();
				$criteria->add(new Criteria('uid', $uid,'='));
				$criteria->add(new Criteria('url', $url,'='));
				if($shortcuts_handler->getCount($criteria)) {	// Is the url already in the list ?
					$option = 3;
				} else {	// The url does not exists, we can add it
					$option = 4;
				}
			}
		} else {	// No limits
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('uid', $uid,'='));
			$criteria->add(new Criteria('url', $url,'='));
			if($shortcuts_handler->getCount($criteria)) {	// Is the url already in the list ?
				$option = 3;
			} else {	// The url does not exists, we can add it
				$option = 4;
			}
		}
		break;


	case 'addshortcut':
		$title = $_POST['title'];
		if(xoops_trim($title) == '') {
			redirect_header('add.php?Op=Add&Url='.$url, 2, _SHORTCUTS_ADD_ERROR);
			exit();
		} else {
			$shortcut = $shortcuts_handler->create(true);
			$shortcut->setNew();
			if ($enablerating) {
				$rating=intval($_POST['Rating']);
				$shortcut->setVar('rating',$rating);
			}
			$shortcut->setVar('uid',$uid);
			$shortcut->setVar('title',$title);
			$shortcut->setVar('date',mktime());
			$shortcut->setVar('url',$url);
			if($shortcuts_handler->insert($shortcut)) {
				redirect_header($url, 2, _SHORTCUTS_ADD_OK);
			} else {
				redirect_header($url, 2, _SHORTCUTS_ADD_PROBLEM);
			}
		}
		break;


	case 'Delete':
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('uid', $uid,'='));
		$criteria->add(new Criteria('url', $url,'='));
		if($shortcuts_handler->deleteAll($criteria)) {
			redirect_header($url, 2, _SHORTCUTS_DELETE_OK);
		} else {
			redirect_header($url, 2, _SHORTCUTS_DELETE_PROBLEM);
		}
		break;

}
$xoopsTpl->assign('result',$option);
require_once(XOOPS_ROOT_PATH.'/footer.php');
?>