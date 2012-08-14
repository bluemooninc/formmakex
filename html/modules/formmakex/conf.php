<?php
global $xoopsDB;

// for "Duplicatable"
$mydirname = basename( dirname( __FILE__ ) ) ;
if( ! preg_match( '/^(\D+)(\d*)$/' , $mydirname , $regs ) ) echo ( "invalid dirname: " . htmlspecialchars( $mydirname ) ) ;
$mydirnumber = $regs[2] === '' ? '' : intval( $regs[2] ) ;


define('TABLE_REALM', $xoopsDB->prefix("formmakex{$mydirnumber}_realm"));
define('TABLE_RESPONDENT', $xoopsDB->prefix("formmakex{$mydirnumber}_respondent"));
define('TABLE_DESIGNER', $xoopsDB->prefix("formmakex{$mydirnumber}_designer"));
define('TABLE_FORM', $xoopsDB->prefix("formmakex{$mydirnumber}_form" ));
define('TABLE_QUESTION_TYPE', $xoopsDB->prefix("formmakex{$mydirnumber}_question_type" ));
define('TABLE_QUESTION', $xoopsDB->prefix("formmakex{$mydirnumber}_question" ));
define('TABLE_QUESTION_CHOICE', $xoopsDB->prefix("formmakex{$mydirnumber}_question_choice" ));
define('TABLE_ACCESS', $xoopsDB->prefix("formmakex{$mydirnumber}_access" ));
define('TABLE_RESPONSE', $xoopsDB->prefix("formmakex{$mydirnumber}_response" ));
define('TABLE_RESPONSE_BOOL', $xoopsDB->prefix("formmakex{$mydirnumber}_response_bool" ));
define('TABLE_RESPONSE_SINGLE', $xoopsDB->prefix("formmakex{$mydirnumber}_response_single" ));
define('TABLE_RESPONSE_MULTIPLE', $xoopsDB->prefix("formmakex{$mydirnumber}_response_multiple" ));
define('TABLE_RESPONSE_RANK', $xoopsDB->prefix("formmakex{$mydirnumber}_response_rank" ));
define('TABLE_RESPONSE_TEXT', $xoopsDB->prefix("formmakex{$mydirnumber}_response_text" ));
define('TABLE_RESPONSE_OTHER', $xoopsDB->prefix("formmakex{$mydirnumber}_response_other" ));
define('TABLE_RESPONSE_DATE', $xoopsDB->prefix("formmakex{$mydirnumber}_response_date" ));
define('TABLE_', $xoopsDB->prefix("formmakex{$mydirnumber}_" ));

define('STATUS_EDIT',    0x00);
define('STATUS_ACTIVE',  0x01);
define('STATUS_DONE',    0x02);
define('STATUS_DELETED', 0x04);
define('STATUS_TEST',    0x08);

// Max upload file size
// If you want set upper 2M, You must change php.ini
//   memory_limit = 8M (default)
//   post_max_size = 8M (default)
//   upload_max_filesize =2M (default)
$maxfilesize = 2000000;			// default 2MB

if(
	!defined('XOOPS_ROOT_PATH') ||
	!defined('XOOPS_CACHE_PATH') ||
	!is_dir(XOOPS_CACHE_PATH)
){
	exit();
}
if(!empty($xoopsConfig)){
	if(file_exists(XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/language/'.$xoopsConfig['language'].'/main.php')){
		require_once XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/language/'.$xoopsConfig['language'].'/main.php';
	}
}

/*
$formmakex_question_type = array(
	array('id'=>1  	,'type'=>'Yes/No'  			,'has_choices'=>'N','response_table'=>'response_bool'),
	array('id'=>2 	,'type'=>'Text Box' 		,'has_choices'=>'N','response_table'=>'response_text'),
	array('id'=>3 	,'type'=>'Essay Box' 		,'has_choices'=>'N','response_table'=>'response_text'),
	array('id'=>4 	,'type'=>'Radio Buttons' 	,'has_choices'=>'Y','response_table'=>'response_single'),
	array('id'=>5 	,'type'=>'Check Boxes' 		,'has_choices'=>'Y','response_table'=>'response_multiple'),
	array('id'=>6 	,'type'=>'Dropdown Box' 	,'has_choices'=>'Y','response_table'=>'response_single'),
	array('id'=>8 	,'type'=>'Rate (scale 1..5)','has_choices'=>'Y','response_table'=>'response_rank'),
	array('id'=>9 	,'type'=>'Date' 			,'has_choices'=>'N','response_table'=>'response_date'),
	array('id'=>10 	,'type'=>'Numeric' 			,'has_choices'=>'N','response_table'=>'response_text'),
	array('id'=>40 	,'type'=>'Attach' 			,'has_choices'=>'N','response_table'=>'response_text'),
	array('id'=>99 	,'type'=>'Page Break' 		,'has_choices'=>'N','response_table'=>NULL),
	array('id'=>100 	,'type'=>'Section Text' 	,'has_choices'=>'N','response_table'=>NULL),
);
*/

?>
