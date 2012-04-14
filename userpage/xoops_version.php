<?php
/**
 * ****************************************************************************
 * SHORTCUTS - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */

if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}

$modversion['name'] = _MI_SHORTCUTS_NAME;
$modversion['version'] = 1.8;
$modversion['description'] = _MI_SHORTCUTS_DESC;
$modversion['credits'] = "Tony, Christian, Carnuke and Dynamic Drive (www.dynamicdrive.com)";
$modversion['author'] = 'Instant Zero - http://xoops.instant-zero.com';
$modversion['help'] = '';
$modversion['license'] = 'GPL';
$modversion['official'] = 0;
$modversion['image'] = "images/shortcuts.jpg";
$modversion['dirname'] = "shortcuts";

// Sql file
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables
$modversion['tables'][0] = "shortcuts";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Templates
$modversion['templates'][1]['file'] = 'shortcuts_add.html';
$modversion['templates'][1]['description'] = 'Used to add shortcuts';
$modversion['templates'][2]['file'] = 'shortcuts_manage.html';
$modversion['templates'][2]['description'] = 'Used to manage shortcuts';

// Blocks
$modversion['blocks'][1]['file'] = "shortcuts_main.php";
$modversion['blocks'][1]['name'] = _MI_SHORTCUTS_BNAME1;
$modversion['blocks'][1]['description'] = _MI_SHORTCUTS_BNAME1_DESC;
$modversion['blocks'][1]['show_func'] = "b_shortcuts_show";
$modversion['blocks'][1]['template'] = 'shortcuts_main.html';
$modversion['blocks'][1]['options'] = "0";
$modversion['blocks'][1]['edit_func'] = "b_shortcuts_edit";

// Shows most visited pages
$modversion['blocks'][2]['file'] = "shortcuts_most_best.php";
$modversion['blocks'][2]['name'] = _MI_SHORTCUTS_BNAME2;
$modversion['blocks'][2]['description'] = _MI_SHORTCUTS_BNAME2_DESC;
$modversion['blocks'][2]['show_func'] = "b_shortcuts_most_best_show";
$modversion['blocks'][2]['template'] = 'shortcuts_most_best.html';
$modversion['blocks'][2]['options'] = "0|10";
$modversion['blocks'][2]['edit_func'] = "b_shortcuts_most_best_edit";

// Shows best rated pages
$modversion['blocks'][3]['file'] = "shortcuts_most_best.php";
$modversion['blocks'][3]['name'] = _MI_SHORTCUTS_BNAME3;
$modversion['blocks'][3]['description'] = _MI_SHORTCUTS_BNAME3_DESC;
$modversion['blocks'][3]['show_func'] = "b_shortcuts_most_best_show";
$modversion['blocks'][3]['template'] = 'shortcuts_most_best.html';
$modversion['blocks'][3]['options'] = "1|10";
$modversion['blocks'][3]['edit_func'] = "b_shortcuts_most_best_edit";

// Shows most recent pages
$modversion['blocks'][4]['file'] = "shortcuts_most_best.php";
$modversion['blocks'][4]['name'] = _MI_SHORTCUTS_BNAME4;
$modversion['blocks'][4]['description'] = _MI_SHORTCUTS_BNAME4_DESC;
$modversion['blocks'][4]['show_func'] = "b_shortcuts_most_best_show";
$modversion['blocks'][4]['template'] = 'shortcuts_most_best.html';
$modversion['blocks'][4]['options'] = "2|10";
$modversion['blocks'][4]['edit_func'] = "b_shortcuts_most_best_edit";


// Menu
$modversion['hasMain'] = 1;

// Search
$modversion['hasSearch'] = 0;

// Comments
$modversion['hasComments'] = 0;

// Options
/**
 * Maximum count of shortcuts per user
 */
$modversion['config'][1]['name'] = 'maxshortcuts';
$modversion['config'][1]['title'] = '_MI_SHORTCUTS_OPT1';
$modversion['config'][1]['description'] = '_MI_SHORTCUTS_OPT1_DSC';
$modversion['config'][1]['formtype'] = 'textbox';
$modversion['config'][1]['valuetype'] = 'int';
$modversion['config'][1]['default'] = 0;

/**
 * Record hits ?
 */
$modversion['config'][2]['name'] = 'savehits';
$modversion['config'][2]['title'] = '_MI_SHORTCUTS_OPT2';
$modversion['config'][2]['description'] = '_MI_SHORTCUTS_OPT2_DSC';
$modversion['config'][2]['formtype'] = 'yesno';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 1;

/**
 * Limit titles lenght to .. characters
 */
$modversion['config'][3]['name'] = 'titlelimit';
$modversion['config'][3]['title'] = '_MI_SHORTCUTS_OPT3';
$modversion['config'][3]['description'] = '_MI_SHORTCUTS_OPT3_DSC';
$modversion['config'][3]['formtype'] = 'textbox';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 0;

/**
 * Enable shortcuts rating
 */
$modversion['config'][4]['name'] = 'enablerating';
$modversion['config'][4]['title'] = '_MI_SHORTCUTS_OPT4';
$modversion['config'][4]['description'] = '_MI_SHORTCUTS_OPT4_DSC';
$modversion['config'][4]['formtype'] = 'yesno';
$modversion['config'][4]['valuetype'] = 'int';
$modversion['config'][4]['default'] = 1;

/**
 * Number of pages to count in the statistics
 */
$modversion['config'][5]['name'] = 'statsnumber';
$modversion['config'][5]['title'] = '_MI_SHORTCUTS_OPT5';
$modversion['config'][5]['description'] = '_MI_SHORTCUTS_OPT5_DSC';
$modversion['config'][5]['formtype'] = 'textbox';
$modversion['config'][5]['valuetype'] = 'int';
$modversion['config'][5]['default'] = 10;

/**
 * Default's sort order
 */
$modversion['config'][6]['name'] = 'sortorder';
$modversion['config'][6]['title'] = '_MI_SHORTCUTS_OPT6';
$modversion['config'][6]['description'] = '_MI_SHORTCUTS_OPT6_DSC';
$modversion['config'][6]['formtype'] = 'select';
$modversion['config'][6]['valuetype'] = 'int';
$modversion['config'][6]['default'] = 1;
$modversion['config'][6]['options'] = array("_MI_SHORTCUTS_OPT6_1" => 0, "_MI_SHORTCUTS_OPT6_2" => 1, "_MI_SHORTCUTS_OPT6_3" => 2, "_MI_SHORTCUTS_OPT6_4" => 3);

/**
 * Kind of redirection to use (Xoops or http)
 */
$modversion['config'][7]['name'] = 'useredirect';
$modversion['config'][7]['title'] = '_MI_SHORTCUTS_OPT7';
$modversion['config'][7]['description'] = '_MI_SHORTCUTS_OPT7_DSC';
$modversion['config'][7]['formtype'] = 'yesno';
$modversion['config'][7]['valuetype'] = 'int';
$modversion['config'][7]['default'] = 1;

// Notification
$modversion['hasNotification'] = 0;
?>
