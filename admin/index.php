<?php
/**
 * ****************************************************************************
 * SHORTCUTS - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

require_once '../../../include/cp_header.php';
require_once XOOPS_ROOT_PATH.'/modules/shortcuts/include/functions.php';


xoops_cp_header();
// Get the module's options
$userscount = st_getmoduleoption('statsnumber');;
$enablerating = st_getmoduleoption('enablerating');
$savehits = st_getmoduleoption('savehits');

$shortcuts_handler =& xoops_getmodulehandler('shortcuts', 'shortcuts');

echo '<h1>'._AM_SHORTCUT_STATS."</h1>\n";

$start = 0;

// Total number of shortcuts
printf('<br /><h4>'._AM_SHORTCUT_STATS0.'</h4>',$shortcuts_handler->getCount());
if($savehits) {
	printf("<div style='text-align: center;'><b>"._AM_SHORTCUT_STATS1.'</b><br />',$userscount);
	echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr>";
	echo "<th align='center'>"._AM_SHORTCUT_PAGE."</th>";
	echo "<th align='center'>"._AM_SHORTCUT_VISITS."</th>";
	echo "</tr>\n";
	$start = 0;
	$critere=new Criteria('1', '1','=');
	$critere->setLimit($userscount);
	$critere->setStart($start);
	$critere->setSort('hits');
	$critere->setOrder('DESC');
	$tblshortcuts = $shortcuts_handler->getObjects($critere);
	foreach($tblshortcuts as $one_shortcut) {
		$url = XOOPS_URL.$one_shortcut->getVar('url');
		printf("<tr><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td></tr>\n",$url,$url,$one_shortcut->getVar('hits'));
	}
	echo "</table></div><br />\n";
}



// Most bookmarked urls
printf("<div style='text-align: center;'><b>"._AM_SHORTCUT_STATS3.'</b><br />',$userscount);
echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr><th align='center'>"._AM_SHORTCUT_PAGE."</th><th align='center'>"._AM_SHORTCUT_COUNT."</th></tr>";
$critere=new Criteria('1', '1','=');
$critere->setLimit($userscount);
$critere->setStart($start);
$critere->setSort('cpt');
$critere->setOrder('DESC');
$critere->setGroupby('url');
$tblshortcuts = $shortcuts_handler->getObjects2('count(shortcutid) as cpt, url as lib',$critere);
foreach($tblshortcuts as $one_shortcut) {
	$url = XOOPS_URL.$one_shortcut['lib'];
	printf("<tr><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td></tr>\n",$url,$url,$one_shortcut['cpt']);
}
echo "</table></div><br />\n";



// Best rated pages
printf("<div style='text-align: center;'><b>"._AM_SHORTCUT_STATS2.'</b><br />',$userscount);
echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr><th align='center'>"._AM_SHORTCUT_PAGE."</th><th align='center'>"._AM_SHORTCUT_VOTE."</th></tr>";
$critere=new Criteria('1', '1','=');
$critere->setLimit($userscount);
$critere->setStart($start);
$critere->setSort('rating');
$critere->setOrder('DESC');
$tblshortcuts = $shortcuts_handler->getObjects($critere);
foreach($tblshortcuts as $one_shortcut) {
	$url = XOOPS_URL.$one_shortcut->getVar('url');
	printf("<tr><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td></tr>\n",$url,$url,$one_shortcut->getVar('rating'));
}
echo "</table></div><br />\n";


// Users Top
printf("<div style='text-align: center;'><b>"._AM_SHORTCUT_STATS4.'</b><br />',$userscount);
echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr><th align='center'>"._AM_SHORTCUT_USER."</th><th align='center'>"._AM_SHORTCUT_USER_COUNT."</th></tr>";
$critere=new Criteria('1', '1','=');
$critere->setLimit($userscount);
$critere->setStart($start);
$critere->setSort('cpt');
$critere->setOrder('DESC');
$critere->setGroupby('uid');
$tmpshortcut = $shortcuts_handler->create();
$tblshortcuts = $shortcuts_handler->getObjects2('count(uid) as cpt, uid as lib',$critere);
foreach($tblshortcuts as $one_shortcut) {
	$url = XOOPS_URL.$one_shortcut['lib'];
	printf("<tr><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='right'>%u</td></tr>\n",$url,$tmpshortcut->uname($one_shortcut['lib']),$one_shortcut['cpt']);
}
echo "</table></div><br />\n";


// Latest shortcuts
printf("<div style='text-align: center;'><b>"._AM_SHORTCUT_LATEST_SHORTCUTS.'</b><br />',$userscount);
echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'><tr><th align='center'>"._AM_SHORTCUT_USER."</th><th align='center'>"._AM_SHORTCUT_PAGE."</th><th>"._AM_SHORTCUT_DATE."</th></tr>";
$critere=new Criteria('1', '1','=');
$critere->setLimit($userscount);
$critere->setStart($start);
$critere->setSort('date');
$critere->setOrder('DESC');
$tblshortcuts = $shortcuts_handler->getObjects($critere);
foreach($tblshortcuts as $one_shortcut) {
	$url2 = XOOPS_URL.$one_shortcut->getVar('url');
	$url=XOOPS_URL."/userinfo.php?uid=".$one_shortcut->getVar('uid');
	$date=formatTimestamp($one_shortcut->getVar('date'));
	printf("<tr><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='left'><a href='%s' target ='_blank'>%s</a></td><td align='center'>%s</td></tr>\n",$url,$one_shortcut->uname(),$url2,$url2,$date);
}
echo "</table></div><br />\n";


echo "<br /><br /><table width='100%' cellspacing='1' cellpadding='3' border='0'><tr><td align='center'><a href='http://xoops.instant-zero.com' target='_blank'><img src='../images/instantzero.gif'></a></td></tr></table>";
xoops_cp_footer();
?>
