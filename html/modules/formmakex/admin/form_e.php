<?php
# $Id: webform.php,v 1.1.1.1 2005/08/10 12:14:03 yoshis Exp $
// Matthew Gregg
// <greggmc at musc.edu>
require('../../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');
require('../conf.php');
require_once('phpESP.ini.php');
include_once('../class/formmakexTable.class.php');
include_once('../class/formmakexHtmlRender.class.php');

if($xoopsTpl){
	formmakexTable::assign_message($xoopsTpl);
}
$_name = '';
$_title = '';
$_css = '';
if (isset($_GET['name'])) {
	$_name = addslashes($_GET['name']);
	unset($_GET['name']);
	$_SERVER['QUERY_STRING'] =
		ereg_replace('(^|&)name=[^&]*&?', '', $_SERVER['QUERY_STRING']);
}
$rid = isset($_GET['rid']) ? intval($_GET['rid']) : NULL;

if (!empty($_name)) {
    $_sql = "SELECT id,title,theme FROM ".TABLE_FORM." WHERE name = '$_name'";
    if ($_result = $xoopsDB->query($_sql)) {
       	if ($xoopsDB->getRowsNum($_result) > 0) list($sid, $_title, $_css) = $xoopsDB->fetchRow($_result);
       	
    }
   	unset($_sql);
   	unset($_result);
}
if (empty($_name) && isset($sid) && $sid) {
    $_sql = "SELECT title,theme FROM ".TABLE_FORM." WHERE id = '$sid'";
    if ($_result = $xoopsDB->query($_sql)) {
        if ($xoopsDB->getRowsNum($_result) > 0){
            list($_title, $_css) = $xoopsDB->fetchRow($_result);
        }
        
    }
    unset($_sql);
    unset($_result);
}
unset($_name);
unset($_title);

include($FMXCONFIG['handler_e']);
$xoopsOption['template_main'] = 'formmakex_form.html';

include(XOOPS_ROOT_PATH.'/footer.php');
?>
