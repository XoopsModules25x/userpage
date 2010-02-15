<?php
/**
 * ****************************************************************************
 * SHORTCUTS - MODULE FOR XOOPS
 * Copyright (c) Hervé Thouzard of Instant Zero (http://www.instant-zero.com)
 * ****************************************************************************
 */
define('_MI_SHORTCUTS_NAME', 'Shortcuts');
define('_MI_SHORTCUTS_DESC','Enable users to manage internals shortcuts');
define('_MI_SHORTCUTS_BNAME1','Shortcuts');
define('_MI_SHORTCUTS_BNAME1_DESC','Show, add and delete shortcuts');

// Module's options
define('_MI_SHORTCUTS_OPT1','Maximum number of shortcuts per user');
define('_MI_SHORTCUTS_OPT1_DSC','You can limit the number of shortcuts per user. Let it to 0 for no limits');
define('_MI_SHORTCUTS_OPT2','Record hits');
define('_MI_SHORTCUTS_OPT2_DSC','If you want, you can record link\'s hits');
define('_MI_SHORTCUTS_OPT3','Limit titles length ?');
define('_MI_SHORTCUTS_OPT3_DSC',"You can limit the links title's length so that it does not break your theme (0= no limit)");
define('_MI_SHORTCUTS_OPT4','Enable users to rank links ?');
define('_MI_SHORTCUTS_OPT4_DSC',"");
define('_MI_SHORTCUTS_OPT5','Number of pages to count in the statistics');
define('_MI_SHORTCUTS_OPT5_DSC',"");

// Admin's menu
define('_MI_SHORTCUTS_ADMIN_MENU1',"Statistics");

// Added in 1.3 Beta 2
define('_MI_SHORTCUTS_OPT6',"List sort order");
define('_MI_SHORTCUTS_OPT6_DSC',"Select the items order in the url's list");
define('_MI_SHORTCUTS_OPT6_1',"Date");
define('_MI_SHORTCUTS_OPT6_2',"Title");
define('_MI_SHORTCUTS_OPT6_3',"Url");
define('_MI_SHORTCUTS_OPT6_4',"Rating");
define('_MI_SHORTCUTS_OPT7',"Use Xoops redirections ?");
define('_MI_SHORTCUTS_OPT7_DSC',"By default, when a user select an url from his list, the script displays a message while redirecting him to the url");

// Added in version 1.4
define('_MI_SHORTCUTS_BNAME2','Shows most visited pages');
define('_MI_SHORTCUTS_BNAME2_DESC','');

define('_MI_SHORTCUTS_BNAME3','Shows best rated pages');
define('_MI_SHORTCUTS_BNAME3_DESC','');

define('_MI_SHORTCUTS_BNAME4','Shows most recent pages');
define('_MI_SHORTCUTS_BNAME4_DESC','');
?>
