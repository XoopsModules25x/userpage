<?php
/**
 * ****************************************************************************
 * SHORTCUTS - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

include_once '../../../include/cp_header.php';

xoops_cp_header();

if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
	$errors=0;
	// 1) Add the new fields to the topic table
	$sql=sprintf("ALTER TABLE " . $xoopsDB->prefix('shortcuts') . " ADD rating TINYINT( 4 ) DEFAULT '0' NOT NULL ;");
	$result=$xoopsDB->queryF($sql);
	if (!$xoopsDB->queryF($sql)) {
    	echo _SHORTCUTS_UPGRADEFAILED.' '._AM_SHORTCUTS_UPGRADEFAILED21;
    	$errors++;
	}

    // At the end, if there was errors, show them or redirect user to the module's upgrade page
	if($errors) {
		echo "<H1>" . _AM_SHORTCUTS_UPGRADEFAILED . "</H1>";
		echo "<br />" . _AM_SHORTCUTS_UPGRADEFAILED0;
	} else {
		echo _AM_SHORTCUTS_UPGRADECOMPLETE." - <a href='".XOOPS_URL."/modules/system/admin.php?fct=modulesadmin&op=update&module=shortcuts'>"._AM_UPDATEMODULE."</a>";
	}
} else {
	printf("<H2>%s</H2>\n",_AM_SHORTCUTS_UPGR_ACCESS_ERROR);
}
xoops_cp_footer();
?>
