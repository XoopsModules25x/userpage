<?php
/**
 * ****************************************************************************
 * SHORTCUTS - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

include_once XOOPS_ROOT_PATH.'/modules/shortcuts/include/functions.php';

function b_shortcuts_most_best_show($options)
{
	$block = array();
	$shortcuts_handler =& xoops_getmodulehandler('shortcuts', 'shortcuts');
	$critere = new Criteria('shortcutid', '0', '<>');
	switch($options[0]) {
		case 0:	// Show most visited pages
			$critere->setSort('hits');
			break;
		case 1:	// Show best rated pages
			$critere->setSort('rating');
			break;
		case 2:	// Show most recent pages
			$critere->setSort('date');
			break;
	}
	$critere->setOrder('DESC');
	$critere->setLimit(intval($options[1]));

	$tbl_shortcuts = array();
	$tbl_shortcuts = $shortcuts_handler->getObjects($critere);
	foreach($tbl_shortcuts as $one_shortcut) {
       	$block['shortcuts'][]=Array(
  				'date' => formatTimestamp($one_shortcut->getVar('date')),
   				'title' => $one_shortcut->getVar('title'),
   				'url' =>  $one_shortcut->getVar('url'),
   				'hits' => $one_shortcut->getVar('hits'),
   				'rating' => $one_shortcut->getVar('rating'),
   				'id' => $one_shortcut->getVar('shortcutid'));
	}
	return $block;
}



function b_shortcuts_most_best_edit($options)
{
	global $xoopsConfig;
	if (file_exists(XOOPS_ROOT_PATH.'/modules/shortcuts/language/'.$xoopsConfig['language'].'/modinfo.php')) {
		include_once XOOPS_ROOT_PATH.'/modules/shortcuts/language/'.$xoopsConfig['language'].'/modinfo.php';
	} else {
		include_once XOOPS_ROOT_PATH.'/modules/shortcuts/language/english/modinfo.php';
	}

    $form = _SHORTCUT_INFORMATION_TO_DISPLAY."&nbsp;<select name='options[]'>";
    $form .= "<option value='0'";
    if ( $options[0] == 0 ) {
        $form .= " selected='selected'";
    }
    $form .= ">"._MI_SHORTCUTS_BNAME2."</option>\n";

    $form .= "<option value='1'";
    if($options[0] == 1){
        $form .= " selected='selected'";
    }
    $form .= ">"._MI_SHORTCUTS_BNAME3."</option>";

    $form .= "<option value='2'";
    if ( $options[0] == 2 ) {
        $form .= " selected='selected'";
    }
    $form .= ">" . _MI_SHORTCUTS_BNAME4 . "</option>";

    $form .= "<option value='3'";
    if ( $options[0] == 3 ) {
        $form .= " selected='selected'";
    }
    $form .= "</select>\n";

    $form .= '<br /><br />'._SHORTCUT_ELEMENTS_TO_DISPLAY." <input type='text' name='options[]' size='5' value='".$options[1]."' />";
    return $form;
}

?>