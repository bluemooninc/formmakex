<?php
# $Id: general.php,v 1.1.1.1 2005/08/10 12:14:03 yoshis Exp $
// Written by James Flemer
// For eGrad2000.com
// <jflemer@alum.rpi.edu>
include_once('./class/formmakexHtmlRender.class.php');

$fmxRender = new formmakexHtmlRender();
	
$sid = $fmxEditForm->editInfo['form_id'];
// load current values from DB if $sid exists (in session)
$form = array();
if(!empty($sid) && $updated) {
	$sql = "SELECT * FROM ".TABLE_FORM." WHERE id='${sid}'";
	$result = $xoopsDB->query($sql);
	$form = $xoopsDB->fetchArray($result);
} else {
    $form = $fmxEditForm->generalInfo;
}
$realms = array();
if($fmxEditForm->accessLevel['superuser'] == 'Y') {
	$realms = $member_handler->getGroupList(); 
} else {
	$groups = $xoopsUser->getGroups();
	foreach($fmxEditForm->accessLevel['pdesign'] as $k => $r){
		if (in_array($k, $groups)) $realms[$k] = $r;
	}
}
$send_array = array(_MD_FROM_OPTION_0,_MD_FROM_OPTION_1,_MD_FROM_OPTION_2);
$themes_array = array();
$dir = dir($FMXCONFIG['css_path']);
$dir->rewind();
while ($file=$dir->read()) {
	if (stristr($file,".css")) {
		$pos = strrpos($file, ".");
		$form_name = substr($file, 0,$pos);
		$themes_array[$file] = $form_name;
	}
}
$dir->close();

$tpl_vars['content']['tab'] = array(
	'title' => _MB_General,
	'form_name' => 'general',
	'description' => _MB_The_information_on_this_tab_applies_to_the_whole_form
);
$usetgroups = explode("|",$xoopsModuleConfig['UNSET_GROUP']);
foreach($realms as $k=>$v){
	// set group w/o unset group
	// echo $v. in_array($v,$usetgroups). "<br />";
	if (!in_array($v,$usetgroups)){
		$_realms[$k] = $realms[$k];
	}
}
if (count($_realms)>1){
	$group = array(
		'label' => _MB_Owner,
		'type' => 'select',
		'required' => 1,
		'input' => $fmxRender->mkselect('realm',$_realms, $form),
		'htmlTag' => $fmxRender->getHtmlTag()
	);
	$group_description = _MB_User_and_Group_that_owns_this_form;
}else{
	foreach($_realms as $k=>$v);
	$data['realm']=$k;
	$group = array(
		'label' => _MB_Owner,
		'type' => 'hidden',
		'required' => 0,
		'input' => $fmxRender->mkhidden('realm',$k),	//$groups
		'htmlTag' => $fmxRender->getHtmlTag()
	);
	$group_description = "";
}
$uinfo['uid'] = $xoopsUser->uid();
$tpl_vars['content']['generalInfos'] = array(
	'form_name' => array(
		'label' => _MB_Name,
//		'type' => 'text',
		'type' => 'hidden',
//		'input' => $fmxRender->mktext('form_name', 20, 64, $form['name']),
//	2011/10/18 S.Uchi 内部の名前なので、自動で付与、ユーザーに入力させない。
//  hiddenで隠す。
		'input' => $fmxRender->mktext('form_name', 20, 64, empty($form['name']) ? md5(uniqid(rand(), true)) : $form['name']) ,
		'htmlTag' => $fmxRender->getHtmlTag(),
		'required' => 1,
		'restrictions' => array(_MB_no_spaces, _MB_alpha_numeric_only),
		'description' => _MB_This_is_used_for_all_further_access_to_this_form
	),
	'uid' => array(
		'label' => "",
		'type' => 'hidden',
		'required' => 0,
		'input' => $fmxRender->mkhidden('uid',$uinfo),
		'htmlTag' => $fmxRender->getHtmlTag(),
		'description' => NULL
	),
	'group' => $group,
/*		
	'owner'=>array(
		'label' => _MB_Owner,
		'type' => 'composition',
		'subInfos' => array(
			'user' => array(
				'label' => '',
				'type' => 'determinate',
				'required' => 0,
				'input' => $xoopsUser->name()
			),
			'group' => $group
		),
		'description' => ""			//$group_description
	),
*/
/* 2011-11-16 yoshis		
	'respondents'=>array(
		'label' => _MB_Respondents,
		'type' => 'composition',
		'subInfos' => array(
			'group' => array(
				'label' => '',
				'type' => 'select',
				'required' => 1,
				'input' => $fmxRender->mkselect('respondents',$realms, $form),
				'htmlTag' => $fmxRender->getHtmlTag()
			)
		),
		'description' => _MB_User_and_Group_that_input_this_form
	),
*/
	'respondents' => array(
		'label' => _MB_Respondents,
		'type' => 'hidden',
		'required' => 0,
		'input' => $fmxRender->mktext('respondents',1,0, ""),
		'htmlTag' => $fmxRender->getHtmlTag(),
		'description' => NULL
	),
	'title' => array(
		'label' => _MB_Title,
		'type' => 'text',
		'input' => $fmxRender->mktext('title', 60, 60, $form['title']),
		'htmlTag' => $fmxRender->getHtmlTag(),
		'required' => 1,
		'restrictions' => array(_MB_free_form_including_spaces),
		'description' => _MB_This_appears_at
	),
	'subtitle' => array(
		'label' => _MB_Subtitle,
		'type' => 'text',
		'input' => $fmxRender->mktext('subtitle', 60, 128, $form['subtitle']),
		'htmlTag' => $fmxRender->getHtmlTag(),
		'required' => 0,
		'restrictions' => array(_MB_free_form_including_spaces),
		'description' => _MB_Appears_below_the_title
	),
	'info' => array(
		'label' => _MB_Additional_Info,
		'type' => 'textarea',
		'input' => $fmxRender->mktextarea('info', 5, 60, 'virtual', $form['info']),
		'htmlTag' => $fmxRender->getHtmlTag(),
		'required' => 0,
		'restrictions' => array(_MB_free_form_including_spaces),
		'description' => _MB_Text_to_be_displayed_on_this_form_before_any_fields
	),
		'thanks_url' => array(
				'label' => 'URL',
				'type' => 'hidden',
				'input' => $fmxRender->mkhidden('thanks_page',''),
				'htmlTag' => $fmxRender->getHtmlTag(),
				'required' => 0,
				'restrictions' => array(),
				'description' => _MB_The_URL_to_which_a_user_is_redirected_after_completing_this_form
		),
		'thank_head' => array(
				'label' => _MB_heading_text,
				'type' => 'hidden',
				'input' => $fmxRender->mkhidden('thanks_head',''),
				'htmlTag' => $fmxRender->getHtmlTag(),
				'required' => 0,
				'restrictions' => array(),
				'description' => _MB_Heading_in_bold
		),
		'thank_body' => array(
				'label' => _MB_body_text,
				'type' => 'hidden',
				'input' => $fmxRender->mkhidden('thanks_body',''),
				'htmlTag' => $fmxRender->getHtmlTag(),
				'required' => 0,
				'restrictions' => array(),
				'description' => _MB_Heading_in_bold
		),
/*
** for Wakdoki Hack by bluemooninc.
	'thanks_page' => array(
		'label' => _MB_Confirmation_Page,
		'type' => 'composition',
		'subInfos' => array(
			'thanks_url' => array(
				'label' => 'URL',
				'type' => 'text',
				'input' => $fmxRender->mktext('thanks_page', 60, 255, $form['thanks_page']),
				'htmlTag' => $fmxRender->getHtmlTag(),
				'required' => 0,
				'restrictions' => array(),
				'description' => _MB_The_URL_to_which_a_user_is_redirected_after_completing_this_form
			),
			'thank_head' => array(
				'label' => _MB_heading_text,
				'type' => 'text',
				'input' => $fmxRender->mktext('thank_head', 30, 0, $form['thank_head']),
				'htmlTag' => $fmxRender->getHtmlTag(),
				'required' => 0,
				'restrictions' => array(),
				'description' => _MB_Heading_in_bold
			),
			'thank_body' => array(
				'label' => _MB_body_text,
				'type' => 'textarea',
				'input' => $fmxRender->mktextarea('thank_body', 5, 60, 'virtual', $form['thank_body']),
				'htmlTag' => $fmxRender->getHtmlTag(),
				'required' => 0,
				'restrictions' => array(),
				'description' => _MB_Heading_in_bold
			)
		),
		'description' => _MB_URL_if_present
	),
	*/
//	2011/10/18 S.Uchi 内部の名前なので、自動で付与、ユーザーに入力させない。
//  hiddenで隠す。	
	'email' => array(
		'label' => _MB_Email,
		//'type' => 'text',
		'type' => 'hidden',
		'input' => $fmxRender->mktext('email', 30, 0, $form['email']),
		'htmlTag' => $fmxRender->getHtmlTag(),
		'required' => 0,
		'restrictions' => array(),
		'description' => _MB_Sends_a_copy
	),
	'from_option' => array(
		'label' => _MB_From_Option,
		//'type' => 'select',
		'type' => 'hidden',
		'input' => $fmxRender->mkselect('from_option',$send_array, $form['from_option']),
		'htmlTag' => $fmxRender->getHtmlTag(),
		'required' => 0,
		'restrictions' => array(),
		'description' =>  _MD_FROM_OPTION
	),
	'response_id' => array(
		'label' => _MB_Default_Response,
		//'type' => 'text',
		'type' => 'hidden',
		'required' => 0,
		'input' => $fmxRender->mktext('response_id',10,0, $form['response_id']),
		'htmlTag' => $fmxRender->getHtmlTag(),
		'description' => _MD_FROM_DEFRES
	),
	'resume' => array(
				'label' => _MB_Allow_to_save,
				'type' => 'hidden',
				'input' => $fmxRender->mkhidden('resume',1),
				'htmlTag' => $fmxRender->getHtmlTag(),
				'required' => 0,
				'restrictions' => array()
	),
	'navigate' => array(
				'label' => _MB_Allow_to_forward,
				'type' => 'hidden',
				'input' => $fmxRender->mkhidden('navigate',1),
				'htmlTag' => $fmxRender->getHtmlTag(),
				'required' => 0,
				'restrictions' => array()
	)
	/*
	'options' => array(
		'label' => _MB_Options,
		'type' => 'composition',
		'subInfos' => array(
			'resume' => array(
				'label' => _MB_Allow_to_save,
				'type' => 'hidden',
				'input' => $fmxRender->mkhidden('resume',1),
				'htmlTag' => $fmxRender->getHtmlTag(),
				'required' => 0,
				'restrictions' => array()
			),
			'navigate' => array(
				'label' => _MB_Allow_to_forward,
				'type' => 'hidden',
				'input' => $fmxRender->mkhidden('navigate',1),
				'htmlTag' => $fmxRender->getHtmlTag(),
				'required' => 0,
				'restrictions' => array()
			)
		)
	)*/
);
$tpl_vars['langs']['required'] = _MB_Required;
$tpl_vars['langs']['or'] = _MB_OR;
?>