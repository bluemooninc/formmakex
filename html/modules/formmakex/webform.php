<?php
# $Id: webform.php,v 1.1.1.1 2005/08/10 12:14:03 yoshis Exp $
// Matthew Gregg
// <greggmc at musc.edu>

require('../../mainfile.php');
require('./conf.php');
require_once('./admin/phpESP.ini.php');	
include_once('./class/formmakexTable.class.php');
include_once('./class/formmakexHtmlRender.class.php');
include_once('./class/submitcount.class.php');		// 2010.05.25 yoshis
include_once('./class/formmakexEditForm.class.php');
include_once('./class/formmakexResponseSaver.class.php');
include_once('./class/formmakexMailSender.class.php');
include_once('./class/formmakexStatus.class.php');

$fmxStatus = new formmakexStatus();
$fmxEditForm = new formmakexEditForm();
$fmxSaver = new responseSaver();
//$formmakexTable->assign_message($xoopsTpl);

include('./public/handler.php');

$xoops_module_header = '<link href="style.css" media="screen,tv,print" type="text/css" rel="stylesheet" />';
$xoops_module_header .= '
<script language="javascript">
function other_check(name){
	other = name.split("_");
	var f = document.phpesp_response;
	for (var i=0; i<=f.elements.length; i++) {
		if (f.elements[i].value == "other_"+other[1]) {
			f.elements[i].checked=true;
			break;
		}
	}
}
function uncheckRadio(rbname){
	for(x=0; x<document.phpesp_response.elements[rbname].length; x++){
		document.phpesp_response.elements[rbname][x].checked = false;
	}
}
</script>
';
$xoopsTpl->assign('xoops_module_header', $xoopsTpl->get_template_vars('xoops_module_header').$xoops_module_header);
$xoopsTpl->assign('formmakex', $tpl_vars);
$xoopsTpl->assign('xoops_pagetitle', htmlspecialchars($tpl_vars['content']['form']['title']));
require(XOOPS_ROOT_PATH.'/header.php');
$xoopsOption['template_main'] = 'formmakex_form.html';
include(XOOPS_ROOT_PATH.'/footer.php');
?>
