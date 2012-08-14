<?php
# $Id: tab.php,v 1.1.1.1 2005/08/10 12:14:03 yoshis Exp $
// Written by James Flemer
// For eGrad2000.com
// <jflemer@alum.rpi.edu>

/* This function is a hack to handle input-type=image tags.
 * (e.g.  <input type="image" name="tab" src="clickme.gif"> )
 * You cannot set a 'value' attribute in such a tag. When you
 * click on an image, browsers create two form variables based
 * on the 'name' attribute: <name>_x & <name>_y, the coordinates
 * of where the user clicked on the image.
 *
 * To make this work with my 'where=<module>' wrapper, this
 * function checks for certain variables of the form "blah_x"
 * and "blah_y" and includes 'blah'.
 */
/* Use this hack to work around how PHP handles session vars.
 * When Register_Globals is on, the value of the global $foo
 * is saved, and $fmxEditForm->editInfo['foo'] is ignored.
 * When Register_Globals is off, the global $foo is ignored
 * and $fmxEditForm->editInfo['foo'] is saved.
 * By making $fmxEditForm->editInfo['foo'] a reference to $foo
 * when reg_glob is on, we can use $fmxEditForm->editInfo uniformly
 * from here on, regardless of the INI settings.
 */

// submit from preview
if (isset($_POST['save'])){
	if ( in_array($_POST['save'],array(_MB_SaveAsDefault,_MB_Submit_Form)) ){
		include_once('./class/formmakexResponseSaver.class.php');
		$fmxSaver = new responseSaver();
	    if ( empty($_POST['rid']) && !empty($_POST['response_id']) ) $_POST['rid']=$_POST['response_id'];
	    if (strcmp($_POST['tab'],'preview')) $overWrite = true;
	    $_POST['submit'] = $_POST['save'];	// You can not use submit when it using java script.
		$sid = $_POST['sid'] ? intval($_POST['sid']) : 0;
		$_page = $_POST['sec'] ? intval($_POST['sec']) : 0;
		$_rid = $_POST['rid'] ? intval($_POST['rid']) : 0;
        if ( $_rid > 0 ) $fmxSaver->response_delete($sid, $_rid, $_page, "1");
		$_rid = $fmxSaver->response_insert($sid,$_page,$_rid);
		$fmxSaver->response_commit($_rid);
		$_POST['rid'] = $_POST['response_id'] = $_rid;
		$_POST['tab'] = 'general';
	}
}
if (isset($_POST['tab'])) $tab = $_POST['tab'];

if(empty($fmxEditForm->editInfo['new_form']))
	$fmxEditForm->editInfo['new_form'] = empty($fmxEditForm->editInfo['form_id']);

/* check user's ACL to see if they have rights to
 * edit/create _any_ form */
if($fmxEditForm->accessLevel['superuser'] != 'Y' &&
		count($fmxEditForm->accessLevel['pdesign']) < 1) {
	// && !$formTable->auth_no_access(_MB_to_access_this_form
	return;
}
if(empty($FMXCONFIG['tabs'])) {
	echo('<b>'. _MB_No_tabs_defined . '</b>');
	return;
}
if(empty($tab)) {
	foreach($FMXCONFIG['tabs'] as $value) {
		if(isset($_POST[$value])){
			$tab = $value;
			break;
		}
		$tab = $fmxEditForm->editInfo['last_tab'];
	}
}

if(empty($tab)){
	$tab = 'general';
}
$tab = strtolower(ereg_replace(' +','_',$tab));
if(!ereg('^[A-Za-z0-9_]+$',$tab))	// Valid chars are [A-Za-z0-9_]
	$tab = 'general';
if(!file_exists($FMXCONFIG['modpath'].'/include/tab/'.$tab.".php"))
	$tab = 'general';
if(!file_exists($FMXCONFIG['modpath'].'/include/tab/'.$tab.".php")) {
	$msg = _MB_Unable_to_open_include_file . $FMXCONFIG['modpath'].'/include/tab/'.$tab.".php";
	redirect_header($FMXCONFIG['base_url'],3,$msg);
}
$errstr = '';
$copy_question = 0;
$updated = $fmxEditForm->form_update($fmxEditForm->editInfo['form_id'],$tab,$fmxEditForm->editInfo['last_tab'],$copy_question);
$errorMessage = !empty($errstr) ? $errstr : '';

$xoopsTpl->assign('xoops_module_header', '<link href="style.css" media="screen,tv,print" type="text/css" rel="stylesheet" />');

$tpl_vars = array('content' => array(), 'langs' => array(), 'config' => array());
$tpl_vars['content']['pagetitle'] =  _MB_Edit_a_Form;
$tpl_vars['content']['errors'] = array();
if($errorMessage){
	$tpl_vars['content']['errors'][] = $errorMessage;
}
$fmxEditForm->editInfo['last_tab'] = $tab;
$tpl_vars['content']['help'] = $GLOBALS['FMXCONFIG']['manage'].'?where=help';
$tpl_vars['content']['help2'] = _MB_Click_here_to_open_the_Help_window;
$tpl_vars['content']['cancelMessage'] = $fmxEditForm->editInfo['new_form'] ? _MB_Click_cancel_to_cancel : '';
$tpl_vars['content']['guide'] = _MB_The_form_title_and_other;
$tpl_vars['content']['tabNav'] = array(
	array('title'=>_MB_General,'name'=>"general",),
	array('title'=>_MB_Questions,'name'=>"questions",),
	array('title'=>_MB_Order,'name'=>"order",),
	array('title'=>_MB_Preview,'name'=>"preview",),
	array('title'=>_MB_Finish,'name'=>"finish",)
);
$tpl_vars['langs']['help'] = _MB_Help;
$tpl_vars['config']['currentTab'] = $tab;

include($FMXCONFIG['modpath'].'/include/tab/'.$tab.".php");

$tpl_vars['content']['hiddens'] = $fmxEditForm->getHiddens();
$xoopsTpl->assign('formmakex', $tpl_vars);
if($tab=='finish') return;
?>