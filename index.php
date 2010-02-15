<?php
/**
 * ****************************************************************************
 * SHORTCUTS - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

require_once 'header.php';
$xoopsOption['template_main'] = 'shortcuts_manage.html';
require_once XOOPS_ROOT_PATH.'/modules/shortcuts/include/functions.php';
require_once XOOPS_ROOT_PATH.'/header.php';
$myts =& MyTextSanitizer::getInstance();

if(!is_object($xoopsUser)) {	// Only for registred users
	redirect_header(XOOPS_URL.'/index.php', 2, _SHORTCUTS_REGISTRED);
}

$maxshortcuts = st_getmoduleoption('maxshortcuts');
$titlelimit = st_getmoduleoption('titlelimit');
$enablerating = st_getmoduleoption('enablerating');
$savehits = st_getmoduleoption('savehits');

$xoopsTpl->assign('savehits',$savehits);
$xoopsTpl->assign('enable_rating',$enablerating);

$shortcuts_handler =& xoops_getmodulehandler('shortcuts', 'shortcuts');

if($titlelimit>0) {
	$xoopsTpl->assign('maxlength','maxlength='.$titlelimit);
} else {
	$xoopsTpl->assign('maxlength','');
}
$xoopsTpl->assign('xoops_pagetitle',_SHORTCUTS_MANAGEMENTS);
$xoopsTpl->assign('shortcuts_select_options',array('0'=>'--', '10'=>10, '9'=>9, '8'=>8, '7'=>7, '6'=>6, '5'=>5, '4'=>4, '3'=>3, '2'=>2, '1'=>1));

$op = '';
$op = isset($_GET['Op']) ? $_GET['Op'] : '';
if($op == '') {
	$op = isset($_POST['Op']) ? $_POST['Op'] : '';
}

if($op =='') {
	$op = 'list';
}

if(isset($_POST['modify']) || isset($_POST['modify_x']) || isset($_POST['modify_y']) ) {
	$op = 'Modify';
} elseif(isset($_POST['delete']) || isset($_PST['delete_x']) || isset($_POST['delete_y']) ) {
	$op = 'Delete';
}

$uid = $xoopsUser->getVar('uid');

function ListShortcuts()
{
	global $shortcuts_handler, $uid, $xoopsTpl, $enablerating;
	$shortcutarr = array();
	$critere = new Criteria('uid', $uid,'=');
	$shortcutarr = $shortcuts_handler->getObjects($critere);
   	foreach ($shortcutarr as $one_shortcut) {
   		$xoopsTpl->append('shortcuts_list', array(	'date' => formatTimestamp($one_shortcut->getVar('date')),
   													'title' => $one_shortcut->getVar('title'),
   													'url' => $one_shortcut->getVar('url'),
   													'hits' => $one_shortcut->getVar('hits'),
   													'rating' => $one_shortcut->getVar('rating'),
   													'id' => $one_shortcut->getVar('shortcutid')));
	}
}


switch($op) {
	case 'Modify':
		$shortcut = $shortcuts_handler->get(intval($_POST['shortcutid']));
		$shortcut->unsetNew();
		$shortcut->setVar('title',$_POST['title']);
		if ($enablerating) {
			$shortcut->setVar('rating',intval($_POST['Rating']));
		}
		$shortcuts_handler->insert($shortcut);
		ListShortcuts();
		break;

	case 'Delete':
		$shortcut = $shortcuts_handler->get(intval($_POST['shortcutid']));
		$shortcuts_handler->delete($shortcut);
		ListShortcuts();
		break;

	case 'list':
		ListShortcuts();
		break;
}
require_once(XOOPS_ROOT_PATH.'/footer.php');
?>
