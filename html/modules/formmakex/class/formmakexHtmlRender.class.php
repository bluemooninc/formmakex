<?php
# $Id: esphtml.forms.php,v 1.1.1.1 2005/08/10 12:14:03 yoshis Exp $
// First written by James Flemer For eGrad2000.com <jflemer@alum.rpi.edu>
// string	mkwarn(char *warning);
// string	mkerror(char *error);
// string	mkradio(char *name, char *value);
// string	mkcheckbox(char *name, char *value);
// string	mktext(char *name);
// string	mktextarea(char *name, int rows, int cols, char *wordwrap);
// string	mkselect(char *name, char *options[]);
// string	mkfile(char *name);
include_once('formmakexResponseLoader.class.php');

class formmakexHtmlRender extends ResponseLoader{
	var $htmlTag;
	var $has_required = FALSE;
	var $questions = array();
	var $sections = array();
	var $pages = 1;
	
	function formmakexHtmlRender () {
		$this->htmlTag = array();
	}
	function getHtmlTag(){
		$ret = $this->htmlTag;
		$this->htmlTag = array();
		return $ret;
	}
	/* {{{ proto string mkerror(string message)
	   Returns HTML format for an error message. */
	function mkerror ($msg) {
		return("<font color=\"". $GLOBALS['FMXCONFIG']['error_color'] ."\" size=\"+1\">[ ${msg} ]</font>\n");
	}
	/* }}} */
	/* {{{ proto string mkwarn(string message)
   Returns HTML format for an warning message. */
	function mkwarn ($msg) {
		return("<font color=\"". $GLOBALS['FMXCONFIG']['warn_color'] ."\" size=\"+1\">${msg}</font>\n");
	}
	function mkother($_name,$value){
		$res = "<input type=\"text\" size=\"20\" name=\"$_name\" onKeyPress=\"other_check(this.name)\"";
		$res .= "value=\"". htmlspecialchars($value)."\"";
		$res .= ">";
		$this->htmlTag[] = array(
			'type'=>"text",
			'name'=>htmlspecialchars($_name),
			'value'=>htmlspecialchars($value),
			'onKeyPress'=>"other_check(this.name)",
		);	
		return $res;
	}
	function mkrankTitle($_name,$value){
		$this->htmlTag[] = array(
			'type'=>null,
			'name'=>htmlspecialchars($_name),
			'value'=>htmlspecialchars($value),
		);	
		return $value;
	}
	function maxbyte_str($maxbyte){
		if ($maxbyte>=1000000){
			$maxbyte_str=sprintf("%d M",$maxbyte/1000000);
		} elseif ($maxbyte>=1000){
			$maxbyte_str=sprintf("%d K",$maxbyte/1000);
		} else {
			$maxbyte_str=sprintf("%d ",$maxbyte);
		}
		return $maxbyte_str;
	}
	function mkattach($_name,$size,$maxfilesize){
		$res = "<INPUT TYPE='hidden' NAME='MAX_FILE_SIZE' VALUE='".$maxfilesize."'>".
			"<INPUT type='file' size='".$size."' name='".$_name."' /> Max ".$this->maxbyte_str($maxfilesize)."Byte.";
		$this->htmlTag[] = array(
			'type'=>'file',
			'name'=>htmlspecialchars($_name),
			'size'=>$size,
			'max'=>$maxfilesize
		);	
		return $res;
	}
	/* {{{ proto string mkradio(string name, string value)
	   Returns HTML format for a radio button. */
	function mkradio ($_name, $value, $varr = null, $message=null ) {
	    if ($varr == null)
	        $varr =& $_POST;
		$str = '<input type="radio" name="' . htmlspecialchars($_name) .'" value="' . htmlspecialchars($value) .'"';
		$checked = false;
		if (is_array($varr)){
			if((isset($varr[$_name])) && (in_array($value,$varr))){
				$checked = true;
			}
			$str .= $checked ? " checked" : "";
		}else{
			if(strcmp($value,$varr)==0) $checked = true;
		}
		$str .= ' />';
		$this->htmlTag[] = array(
			'type'=>"radio",
			'name'=>htmlspecialchars($_name),
			'value'=>htmlspecialchars($value),
			'checked'=>$checked,
			'message'=>$message
		);	
		return($str);
	}
	function mkradioCancel ($_name, $value ) {
		$this->htmlTag[] = array(
			'type'=>"button",
			'name'=>"Button",
			'value'=>htmlspecialchars($value),
			'onclick'=>'uncheckRadio(\''.$_name.'\')'
		);	
		return '<input type="button" name="Button" value="' . $value . '" onclick="uncheckRadio(\''.$_name.'\')" />';
	}
	/* }}} */
		
	/* {{{ proto string mkcheckbox(string name, string value)
	   Returns HTML format for a check box. */
	function mkcheckbox ($_name, $value, $varr=null, $message=null) {
	    if ($varr == null) $varr =& $_POST;
		$checked = false;
		$str = '<input type="checkbox" name="' . htmlspecialchars($_name) .'[]" value="' . htmlspecialchars($value) .'"';
		if((in_array($value,$varr))){
			$checked = true;
		}
		$str .= $checked ? " checked" : "";
		$str .= ' >';
		$this->htmlTag[] = array(
			'type'=>"checkbox",
			'name'=>htmlspecialchars($_name),
			'value'=>htmlspecialchars($value),
			'checked'=>$checked,
			'message'=>$message
		);
		return($str);
	}
	/* }}} */
	
	/* {{{ proto string mktext(string name, int size)
	   Returns HTML format for a text entry line. */
	function mktext ($_name, $size = 20, $max = 0, $value = NULL) {
		//if ($value == null) $value =& $_POST[$_name];
		$size = intval($size);
		$max  = intval($max);
		$str = "size=\"$size\"";
		if ($max)	$str .= " maxlength=\"$max\"";
		$this->htmlTag[] = array(
			'type'=>"text",
			'name'=>htmlspecialchars($_name),
			'value'=>$value
		);
		$rstr = '<input type="text" '. $str .' name="'. htmlspecialchars($_name) .'"';
		$rstr .= $value ? ' value="' . $value .'"' : '';
		$rstr .= ' />';
		return $rstr;
	}
	/* }}} */
	
	/* {{{ proto string mkpass(string name)
	   Returns HTML format for a password entry line. */
	function mkpass ($_name) {
		return('<input type="password" name="'. htmlspecialchars($_name) .'" />');
	}
	/* }}} */
	
	/* {{{ proto string mkhidden(string name)
	   Returns HTML format for a hidden form field. */
	function mkhidden ($_name, $varr = null) {
	    if ($varr == null) $varr = $_POST;
		if (isset($varr[$_name])){
			$ret = '<input type="hidden" name="'. $_name .'" value="'. $varr[$_name] .'" />';
		}else{
			$ret = '<input type="hidden" name="' . $_name .'" value="'. $varr .'" />';
		}
		return $ret;
	}
	/* }}} */
	
	/* {{{ proto string mktextarea(string name, int rows, int cols, string wrap_type)
	   Returns HTML format for a text entry box. */
	function mktextarea ($_name, $rows, $cols, $wrap, $value = null) {
/*		if ($value == null) $value =& $_POST[$_name];
		$this->htmlTag[] = array(
			'type'=>"textarea",
			'name'=>htmlspecialchars($_name),
			'rows'=>$rows,
			'cols'=>$cols,
			'value'=>$value,
			'wrap'=>$wrap
		);*/	
		$str = '<textarea name="' . htmlspecialchars($_name) .'"';
		if($rows > 0)
			$str .= ' rows="' . $rows . '"';
		if($cols > 0)
			$str .= ' cols="' . $cols . '"';
		if($wrap != '')
			$str .= ' wrap="' . strtolower($wrap) . '"';
		$str .= '>';
		$str .= $value;
		$str .= '</textarea>';
		$this->htmlTag[] = array(
			'type'=>"textarea",
			'name'=>htmlspecialchars($_name),
			'rows'=>$rows,
			'cols'=>$cols,
			'value'=>$value,
			'wrap'=>$wrap
		);	
		return($str);
	
	}
	/* }}} */
	
	/* {{{ proto string mkselect(string name, array options)
	   Returns HTML format for a select box (dropdown). */
	function mkselect ($_name, $options, $varr = null, $addblank=1) {
	    if ($varr == null) $varr =& $_POST;
		$str  = "<select name=\"${_name}\">\n";
		if ($addblank>0) $str .= "<option></option>\n";
		$opt=array();
		while(list($cid, $content) = each($options)) {
			$checked = '';
			if(is_array($varr)){
				if (isset($varr[$_name])){
					if ( is_array($varr[$_name]) )
						$nm = $varr[$_name][0];
					else
						$nm = $varr[$_name];
					if ($nm == $cid) $checked = ' selected';
				}
			}else{
				if (strcmp($cid,$varr)==0) $checked = ' selected';
			}
			$str .= "<option value=\"${cid}\"${checked}>${content}</option>\n";
			$opt[]=array(
				'value'=>$cid,
				'checked'=>$checked,
				'content'=>$content
			);
		}
		$str .= "</select>\n";
		$this->htmlTag[] = array(
			'type'=>"select",
			'name'=>htmlspecialchars($_name),
			'value'=> $opt
		);			
		return($str);
	}
	/* }}} */
	
	/* {{{ proto string mkfile(string name)
	   Returns HTML format for a file selection button. */
	function mkfile ($_name) {
		return('<input type="file" name="'.htmlspecialchars($_name) .'" />');
	}
	/* }}} */
	
	/* {{{ proto string mksubmit(string name, string value)
	   Returns HTML format for a submit button. */
	function mksubmit($_name, $_value = null) {
	    if ($_value == null)
	        $_value = _MB_Submit;
	    if (!empty($_value))
	        $_value = ' value="'.htmlspecialchars($_value).'"';
	    return '<input type="submit" name="'.htmlspecialchars($_name).'"'.$_value.' />';
	}
	/* }}} */
	function other_text($content){
		return preg_replace(array("/^!other=/","/^!other/"),array('',_MD_QUESTION_OTHER),$content);
	}
	function formRender_smarty($sid, $pageNumber = 1, $defaultRid=NULL, $postdata=NULL) {
		global $xoopsDB;
		global $xoopsTpl,$maxfilesize,$tpl_vars;
		global $_POST,$xoopsModuleConfig;
		@reset($_POST);
		$has_choices = $this->esp_type_has_choices();
		$defval = "";
		//  modify data okamoto
		if (empty($defaultRid )) {      
	    	/*if (!isset($_POST['submit'])){
				$defaultRid = $form['response_id'];
				$defval = response_select_defval($sid,$defaultRid);
			}
			*/
		} else {
			//$defval = response_select_defval($sid,$defaultRid);
		}
		$qnum = 1;
		$formRender_smarty = array();
		// load form questions
		$sql = "SELECT * FROM ".TABLE_QUESTION." WHERE form_id = $sid AND deleted = 'N' ORDER BY position,id";
		$result = $xoopsDB->query($sql);
		if(!$result) return(false);
		$section_id = "";
		$section = array();
		while($question = $xoopsDB->fetchArray($result)) {
			$question['section_top'] = 0;
			if ($pageNumber>$this->pages){
				// Page Break
				if ($question['type_id']<99) $qnum++;
				if ($question['type_id']=='99') $this->pages++;
				continue;	// Skip to pageNumber
			}
			if ($pageNumber<$this->pages) break;	// Stop over the pageNumber
			// process each question
			$type_id = isset($question['type_id']) ? $question['type_id'] : 0;
			if( $type_id>0 && $has_choices[$type_id] ) {
				$sql = "SELECT * FROM ".TABLE_QUESTION_CHOICE." WHERE question_id='${question['id']}' AND content NOT LIKE '!other%' ORDER BY id";
				$choices_result = $xoopsDB->query($sql);
				if( in_array($question['type_id'],array(4,5)) ) {
					$sql = "SELECT * FROM ".TABLE_QUESTION_CHOICE." WHERE question_id='${question['id']}' AND content LIKE '!other%' ORDER BY id";
					$others_result = $xoopsDB->query($sql);
					$others = $xoopsDB->getRowsNum($others_result);
				}
			} else {
				$choices_result = ''; 
			}
			$qname = "Q" . $question['id'];
			if($question['required']=='Y') { 
				$this->has_required = TRUE;
			}
			$defaultValue = NULL;
			$defaultValueArray = array();
			if ($defaultRid){
				$this->loadDefaultValue($question['type_id'],$defaultRid);
				if(isset($this->responseValues[$question['id']])){
					if(is_array($this->responseValues[$question['id']])){
						$defaultValueArray = $this->responseValues[$question['id']];
					}else{
						if (isset( $this->responseValues[$question['id']]))
							$defaultValue = htmlspecialchars($this->responseValues[$question['id']]);
					}
				}
			}
			if ($postdata){
				if (isset($postdata[$question['id']])){
					if (is_array($postdata[$question['id']]))
						$defaultValueArray = $postdata[$question['id']];
					else
						$defaultValue = $postdata[$question['id']];
				}
			}
			switch($question['type_id']) {
				case '1':	// Yes/No
					$this->mkradio($qname,'Y',$defaultValue,_MB_Yes);
					$this->mkradio($qname,'N',$defaultValue,_MB_No);
					if ($xoopsModuleConfig['RESET_RB']) $this->mkradioCancel($qname,_MD_FORMMAKEX_CHECKRESET);
					break;
				case '2':	// single text line
					$this->mktext($qname, $question['length'], $question['precise'],$defaultValue);
					break;
				case '3':	// essay
					$this->mktextarea($qname, $question['precise'], $question['length'], 'VIRTUAL',$defaultValue);
					break;
				case '4':	// radio
					while($choice = $xoopsDB->fetchArray($choices_result)) {
						$this->mkradio($qname,$choice['id'],$defaultValueArray,$choice['content']);
					}
					while($other = $xoopsDB->fetchArray($others_result)) {
						$this->mkradio($qname,"other_${other['id']}",$defaultValueArray,$this->other_text($other['content']));
						$cid = "Q${question['id']}_${other['id']}";
						$value = null;
						if (isset($defaultValueArray[$cid])) $value = $defaultValueArray[$cid];
						if (isset($_POST[$cid])) $value = $_POST[$cid];
						$this->mkother($cid,$value);
					}
					if ($xoopsModuleConfig['RESET_RB']) $this->mkradioCancel($qname,_MD_FORMMAKEX_CHECKRESET);
					break;
				case '5':	// check boxes
					while($choice = $xoopsDB->fetchArray($choices_result)) {
						$this->mkcheckbox($qname,$choice['id'],$defaultValueArray,$choice['content']);
					}
					//die;
					while($other = $xoopsDB->fetchArray($others_result)) {
						$this->mkcheckbox($qname,"other_${other['id']}",$defaultValueArray,$this->other_text($other['content']));
						$cid = $qname = "Q${question['id']}_${other['id']}";
						$value = null;
						if (isset($defaultValueArray[$cid])) $value = $defaultValueArray[$cid];
						if (isset($_POST[$cid])) $value = $_POST[$cid];
						$this->mkother($cid,$value);
					}
					break;
				case '6':	// dropdown box
					$options = array();
					while($choice = $xoopsDB->fetchArray($choices_result)) {
						$options[$choice['id']] = $choice['content'];
					}
					$this->mkselect($qname,$options,$defaultValueArray);
					break;
				case '7':	// rating
					$this->mkradio($qname,1);
					$this->mkradio($qname,2);
					$this->mkradio($qname,3);
					$this->mkradio($qname,4);
					$this->mkradio($qname,5);
					$this->mkradio($qname,'N/A');
					break;
				case '8':	// ranking
					// for ranking title
					$patterns = "/\[TH](.*)\[\/TH\]/si";
					$question['th_title'] = array();
					if (preg_match($patterns, $question['content'], $thstr))
						$th_title = explode( "," , $thstr[1] );
					else
						$th_title = array(1,2,3,4,5);
					$question['th_title'] = $th_title;
					$replacements = '';
					$question['content'] = preg_replace($patterns, $replacements, $question['content']);
					if(!isset($question['length']) || $question['length']>=count($th_title) ) $question['length'] = count($th_title);
					$rankArray=array();
					while($choice = $xoopsDB->fetchArray($choices_result)) {
						$qname = "Q${question['id']}_${choice['id']}";
						if (isset($postdata["${question['id']}_${choice['id']}"]) )
							$pdat = $postdata["${question['id']}_${choice['id']}"];
						else
							$pdat = NULL;
						$this->mkrankTitle($qname,$choice['content']);
						if ($xoopsModuleConfig['RESET_RB']) $this->mkradioCancel($qname,_MD_FORMMAKEX_CHECKRESET);
						for ($j = 0; $j <$question['length'] ; $j++) {
							$this->mkradio($qname,$j,$pdat,$th_title[$j]);
						}
						if ($question['precise']) {
							$this->mkradio($qname,'N/A');
						}
						$rankArray[] = $this->getHtmlTag();
					}
					$this->htmlTag = $rankArray;
					break;
				case '9':	// date
					$varr[$qname] = date(_SHORTDATESTRING, time());
					$this->mktext($qname, 10, 10, $defaultValue);
					break;
				case '10':	// numeric
					$question['length']++; // for sign
					if($question['precise']) $question['length'] += 1 + $question['precise'];
					$this->mktext($qname, $question['length'], $question['length'],$defaultValue);
					break;
				case '40':	// Attache
					$this->mkattach($qname,$question['length'],$maxfilesize);
					break;
				case '99':	// Page Break
					$this->pages++;
					$question['content'] = NULL;
					break;
				case '100':	// Section Text
					if ($section_id=="") $question['section_top'] = 1;
					$section_id = 'tab-'.$question['id'];
					$question['section_id'] = 'tab-'.$question['id'];
					break;
			}
			$question['qnum'] = $qnum;
			$question['section_id'] = $section_id;
			$formRender_smarty[$question['id']] = $question;
			$formRender_smarty[$question['id']]['htmlTag'] = $this->getHtmlTag();
			if ($question['type_id']<99) $qnum++;
			if ($question['type_id']==100) $section[$question['id']] = $question;
		}
		$this->sections = $section;
		return $formRender_smarty;
	}

}
?>