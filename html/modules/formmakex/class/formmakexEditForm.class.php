<?php
include_once('formmakexQuestionLoader.class.php');

class formmakexEditForm extends questionLoader{
	var $generalInfo = array();
	var $editInfo = array();
	var $accessLevel = array();
	var $edit_qid = 0;
	function formmakexEditForm( $form_id=0 ) {
		$this->setAccessLevel();
		$this->editInfo['where']     	= 'tab';
		$this->editInfo['form_id']  	= $form_id;
		$this->editInfo['new_form'] 	= 0;
		$this->editInfo['last_tab']   	= 'general';
		$this->initGeneralInfo();
		$this->initQuestionInfo();
	}
	function start_editing($newid=0) {
		$this->editInfo['form_id']  	= $newid;
		$this->editInfo['last_tab']   	= '';
		$this->editInfo['new_form'] 	= false;
	}
	function start_new() {
		$this->initGeneralInfo();
		$this->initQuestionInfo();
		$this->editInfo['where']     	= 'tab';
		$this->editInfo['form_id']   = NULL;
		$this->editInfo['response_id'] = '';
		$this->editInfo['new_form']  = true;
		$this->editInfo['last_tab']    = '';
	}
	function isUpdated() {
		return $this->editInfo['update'];
	}
	function initQuestionInfo() {
		$this->editInfo['curr_qNumber']  	= 1;
		$this->editInfo['update']  	= 0;
	}
	function initGeneralInfo() {
		$this->generalInfo['name']= NULL;
		$this->generalInfo['title']= NULL;
		$this->generalInfo['subtitle']= NULL;
		$this->generalInfo['info']= NULL;
		$this->generalInfo['thanks_page']= NULL;
		$this->generalInfo['thank_head']= NULL;
		$this->generalInfo['thank_body']= NULL;
		$this->generalInfo['email']= NULL;
		$this->generalInfo['from_option']= NULL;
		$this->generalInfo['response_id']= NULL;
	}
	function setGeneralInfo($name,$value) {
		$this->generalInfo[$name] = $value;
	}
	function setAccessLevel() {
		global $xoopsUser,$member_handler;
		if ( $xoopsUser ){
			$superuser = $xoopsUser->isAdmin() ? 'Y' : 'N';
			$uid = $xoopsUser->uid();
		}else{
			$superuser = 'N';
			$uid = 0;
		}
		$this->accessLevel = array (
			'superuser' => $superuser,
			'username'  => $uid,
			'pdesign'   => $member_handler->getGroupList(),
			'pdata'     => $member_handler->getGroupsByUser( $uid ),
			'pstatus'   => array('none'),
			'pall'      => $member_handler->getGroupsByUser( $uid ),
			'pgroup'    => array('none'),
			'puser'     => array('none'),
			'disabled'  => 'N',
			'home'  => XOOPS_ROOT_PATH
		);
	}
	function getHiddens() {
		$hiddens = array();
		if (isset($this->generalInfo)){
			foreach($this->generalInfo as $key => $val){
				$hiddens[]=array('name' => $key, 'value' => $val);
			}
		}
		if (isset($this->editInfo)){
			foreach($this->editInfo as $key => $val){
				$hiddens[]=array('name' => $key, 'value' => $val);
			}
		}
		if (isset($this->accesLevel)){
			foreach($this->accesLevel as $key => $val){
				$hiddens[]=array('name' => $key, 'value' => $val);
			}
			//var_dump($this->accesLevel);die;
		}
		//var_dump($hiddens);die;
		return $hiddens;
	}
	function setFromPost($posts){
		$fields = array('form_name','realm','respondents','title','subtitle','email','theme','thanks_page','thank_head','thank_body','info','public','response_id');
		foreach($fields as $f){
			if (isset($posts[$f]))
				$this->generalInfo[$f] = htmlspecialchars($posts[$f],ENT_QUOTES); 
		}
		$fields = array('where','form_id','response_id','new_form','last_tab','curr_qNumber','update');
		foreach($fields as $f){
			if (isset($posts[$f]))
				$this->editInfo[$f] = htmlspecialchars($posts[$f],ENT_QUOTES); 
		}
		$fields = array('superuser','username','pdesign','pdata','pstatus','pall','pgroup','puser','disabled','home');
		foreach($fields as $f){
			if (isset($posts[$f]))
				$this->accessLevel[$f] = htmlspecialchars($posts[$f],ENT_QUOTES); 
		}
	}
	// INSERT row in the DB for new question
	// set the position to the end
	function post2question($clear=0){
		global $_POST;
		$myrow = array();
		if ($clear==0){
			if (isset($_POST["name"])) 		$myrow["name"]     =  "'". addslashes($_POST["name"]) ."'";
			if (isset($_POST["type_id"])) 	$myrow["type_id"]  =  "'". addslashes($_POST["type_id"]) ."'";
			if (isset($_POST["length"])) 	$myrow["length"]   = "'". addslashes($_POST["length"]) ."'";
			if (isset($_POST["precise"])) 	$myrow["precise"]  = "'". addslashes($_POST["precise"]) ."'";
			if (isset($_POST["required"])) 	$myrow["required"] = "'". addslashes($_POST["required"]) ."'";
			if (isset($_POST["content"])) 	$myrow["content"]  = "'". addslashes($_POST["content"]) ."'";
		}else{
			if (isset($_POST["name"])) 		$myrow["name"]     = $_POST["name"] = NULL;
			if (isset($_POST["type_id"])) 	$myrow["type_id"]  = $_POST["type_id"] = NULL;
			if (isset($_POST["length"])) 	$myrow["length"]   = $_POST["length"] = NULL;
			if (isset($_POST["precise"])) 	$myrow["precise"]  = $_POST["precise"] = NULL;
			if (isset($_POST["required"])) 	$myrow["required"] = $_POST["required"] = NULL;
			if (isset($_POST["content"])) 	$myrow["content"]  = $_POST["content"] = NULL;
		}
		return $myrow;
	}
	function exsistQuestion($edit_qid){
		global $xoopsDB;
		$sql = "SELECT count(*) FROM ".TABLE_QUESTION." WHERE id='${edit_qid}'";
		
		$result = $xoopsDB->query($sql);
		list($cnt)=$xoopsDB->fetchRow($result);
		return $cnt;
	}
	function UpdateQuestion($myrow=NULL,$edit_qid){
		global $xoopsDB;
	
		$valstr="";
		foreach($myrow as $key => $val){
			$valstr .= ( $valstr ? "," : "" ) . $key."=".$val;
		}
		
		// UPDATE row in the DB for the current question
		$sql = "UPDATE ".TABLE_QUESTION." SET " . $valstr . " WHERE id='${edit_qid}'";
		$result = $xoopsDB->queryF($sql);
		return $result;
	}
	function InsertQuestion($myrow=NULL){
		global $_POST, $xoopsDB, $errstr;
		if (isset($_POST["form_id"])) $form_id = intval($_POST["form_id"]);
		$curr_qNumber = $this->getNewPosition($form_id);
		//$myrow["form_id"] = $this->editInfo['form_id'];
		$myrow["form_id"] =  "'". $form_id ."'";
		$myrow["position"] = "'". $curr_qNumber ."'";
		$keystr=$valstr="";
		foreach($myrow as $key => $val){
			$keystr .= ( $keystr ? "," : "" ) . $key;
			$valstr .= ( $valstr ? "," : "" ) . $val;
		}
		$sql = "INSERT INTO ".TABLE_QUESTION." (" . $keystr . ") VALUES (" . $valstr .")";
		$ret = $xoopsDB->query($sql);
		$edit_qid = NULL;
		if($ret){
			$edit_qid = $xoopsDB->getInsertID();
		}
		return $edit_qid;
	}
	function getNewPosition($form_id){
		global $_POST,$xoopsDB;
		$sql = "SELECT MAX(position)+1 FROM ".TABLE_QUESTION." WHERE form_id='${form_id}'";
		$ret = $xoopsDB->fetchRow($xoopsDB->query($sql));
		if($ret)
			list($curr_qNumber) = $xoopsDB->fetchRow($xoopsDB->query($sql));
		else
			$curr_qNumber=0;
		return $curr_qNumber;
	}
	/* {{{ proto bool form_update(int* form_id, string* tab, string old_tab)
	* Reads current form variables from _POST.
	* Returns an true on sucess, else returns false and
	* sets global $errstr with an error message. 
	*/
	function form_update(&$form_id, &$tab, $old_tab, &$copy_question) {
		global $_POST, $_SERVER, $errstr;
		global $xoopsDB,$fmxEditForm;

		
		$this->editInfo['update'] = 0;
		// do not need update
		if(empty($old_tab)){
			$this->editInfo['update'] = 1;
			return(1);
		}
		$f_arr = array();
		$v_arr = array();
	
		// new form
		if(empty($form_id)) {
			if (isset($_POST['form_name'])) {
			$_POST['form_name'] = eregi_replace(
				"[^A-Z0-9]+", "_", trim($_POST['form_name']) );
			$_POST['form_name'] = ereg_replace('_$',"",$_POST['form_name']);
			}
	
			// need to fill out at least some info on 1st tab before proceeding
			if(empty($_POST['form_name']) || empty($_POST['title'])
					|| empty($_POST['realm'])) {
				$tab = "general";
				$errstr = _MB_Sorry_please_fill_out_the_name;
				return(0);
			}
			// create a new form in the database
			$fields = array(
				'form_name'=>'name',
				'realm'      =>'realm',
				'respondents'=>'respondents',
				'title'      =>'title',
				'subtitle'   =>'subtitle',
				'email'      =>'email',
				'from_option'=>'from_option',
				'response_id'=>'response_id',
				'theme'      =>'theme',
				'thanks_page'=>'thanks_page',
				'thank_head' =>'thank_head',
				'thank_body' =>'thank_body',
				'info'       =>'info'
				);
			foreach($fields as $key => $val) {
				if(isset($_POST[$key])) {
					$f_arr[] = $val;
					$v_arr[] = "'".addslashes($_POST[$key])."'";
					if (strcmp($val,'response_id')==0) $fmxEditForm->editInfo['response_id'] = intval($_POST[$key]);
				}
			}
			$f_arr[] = 'owner';
			$v_arr[] = "'".$fmxEditForm->accessLevel['username']."'";
			$sql = "INSERT INTO ".TABLE_FORM." (" . join(',',$f_arr) . ") VALUES (" . join(',',$v_arr) . ")";
			$result = @$xoopsDB->queryF ($sql);
			if(!$result) {
				$tab = "general";
				$errstr = _MB_Sorry_name_already_in_use .' [ ' .$xoopsDB->errno().': '.$xoopsDB->error().' ]';
				return(0);
			}
			$sql = "SELECT id FROM ".TABLE_FORM." WHERE name='".  addslashes($_POST['form_name']) ."'";
			list($form_id) = $xoopsDB->fetchRow($xoopsDB->query($sql));
			$this->editInfo['update'] = 1;
			$this->editInfo['form_id'] = $form_id;
			return(1);
		}
		// form already started
		switch($old_tab) {
			// coming from the general tab ...
			case "general":
				if (isset($_POST['form_name'])) {
				$_POST['form_name'] = eregi_replace(
					"[^A-Z0-9]+", "_", trim($_POST['form_name']) );
				$_POST['form_name'] = ereg_replace('_$',"",$_POST['form_name']);
				}
	
				if(empty($_POST['form_name']) || empty($_POST['title'])
						|| empty($_POST['realm'])) {
					$tab = "general";
					$errstr = _MB_Sorry_please_fill_out_the_name;
					return(0);
				}
				$fields = array('form_name','realm','respondents','title','subtitle','email','from_option','response_id','thanks_page','thank_head','thank_body','info');	//'theme',
				$sql = "SELECT name FROM ".TABLE_FORM." WHERE id='${form_id}'";
				list($name) = $xoopsDB->fetchRow($xoopsDB->query($sql));
				// trying to change form name
				if($name != $_POST['form_name']) {
					$sql = "SELECT COUNT(*) FROM ".TABLE_FORM." WHERE name='" . addslashes($_POST['form_name']) ."'";
					list($count) = $xoopsDB->fetchRow($xoopsDB->query($sql));
					if($count != 0) {
						$tab = "general";
						$errstr = _MB_Sorry_that_name_is_already_in_use;
						return(0);
					}
				}
				// UPDATE the row in the DB with current values
				foreach($fields as $f) {
					$pval = addslashes($_POST[$f]);
					if ( strcmp('form_name',$f)==0){
						$filedname = 'name';
						$val = !is_null($pval) ? $pval : $name;
					}else{
						$filedname = $f;
						$val = $pval;
					}
					$f_arr[] = $filedname ."='" . $val. "'";
				}
				$sql = "UPDATE ".TABLE_FORM." SET " . join(', ',$f_arr) . " WHERE id='${form_id}'";
				$result = $xoopsDB->queryF($sql);
				if(!$result) {
					$tab = "general";
					$errstr = _MB_Warning_error_encountered .' [ '.$xoopsDB->errno().': '.$xoopsDB->error().' ]'.$sql;
					return(0);
				}
				$this->editInfo['update'] = 1;
				break;
	
			// coming from the questions tab ...
			case "questions":
				$edit_qid = isset($_POST['id']) ? intval($_POST['id']) : 0;
				$curr_qNumber = isset($_POST['curr_qNumber']) ? intval($_POST['curr_qNumber']) : 0;
				
				// if the question box is empty ... ignore everything else
				if(empty($_POST['content']) && empty($_POST['name'])){
					$this->editInfo['update'] = 1;
					return(1);
				}
				if(empty($_POST['content'])) {
					$tab = 'questions';
					$errstr = _MB_Please_enter_text;
					return(0);
				}
				// constraint: fieldname must be not empty
				//   generate it from the content if empty
				//   validate/repair fieldname
				if(empty($_POST['name'])) {
					$_POST['name'] = $_POST['id'];
				}
				$_POST['name'] = strtoupper(substr( eregi_replace(
					"[^A-Z0-9]+", "_", trim($_POST['name'])), 0, 30));
				$_POST['name'] = ereg_replace('_$',"",$_POST['name']);
	
				// constraint: question type required
				if(empty($_POST['type_id'])) {
					$tab = 'questions';
					$errstr= _MB_Sorry_you_must_select_a_type_for_this_question;
					return(0);
				}
	
				// constraint: can not change between question w/ answer choices and one w/o
				$has_choices = $this->esp_type_has_choices();
				if($curr_qNumber>0) {
					$sql =  "SELECT Q.type_id FROM ".TABLE_QUESTION." Q WHERE Q.form_id='${form_id}' AND Q.id='${edit_qid}'";
					list($old_type_id) = $xoopsDB->fetchRow($xoopsDB->query($sql));
					// trying to change between incompatible question types
/*					if($has_choices[$_POST['type_id']] != $has_choices[$old_type_id]) {
						$tab = "questions";
						$_POST['type_id'] = $old_type_id;
						$errstr = _MB_Sorry_you_cannot_change_between_those_types_of_question;
						return(0);
					}*/
				}
	
				// constraint: length must be int
				$_POST['length']  = intval($_POST['length']) or 0;
	
				// constraint: precise must be int
				$_POST['precise'] = intval($_POST['precise']) or 0;
	
				// defaults for length field
				if(empty($_POST['length']) && $_POST['type_id'] < 50) {
					$arr = array(
						0,		// 0: unused
						0,		// 1: Yes/No
						20,		// 2: Text Box  (width)
						60,		// 3: Essay Box (width)
						0,		// 4: Radio Buttons
						0,		// 5: Check Boxes (minumum)
						0,		// 6: Dropdown Box (length)
						5,		// 7: Rating (# cols)
						5,		// 8: Rate (# cols)
						0,		// 9: Date
						10		// 10: Numeric (digits)
					);
					$arr['40'] = 20;	// 40: Attach
					$_POST['length'] = $arr[$_POST['type_id']];
				}
	
				// defaults for precision field
				if(empty($_POST['precise']) && $_POST['type_id'] < 50) {
					$arr = array(
						0,		// 0: unused
						0,		// 1: Yes/No
						0,		// 2: Text Box
						5,		// 3: Essay Box (height)
						10,		// 4: Radio Buttons
						0,		// 5: Check Boxes (maximum)
						0,		// 6: Dropdown Box
						0,		// 7: Rating (use N/A)
						0,		// 8: Rate (use N/A)
						0,		// 9: Date
						0		// 10: Numeric (decimal)
					);
					$arr['40'] = 0;	// 40: Attach
					$_POST['precise'] = $arr[$_POST['type_id']];
				}
				/*
				 *  Update current question
				 */
				if($curr_qNumber>0){
					$myrow = $this->post2question();
					if ($edit_qid!=0) {
						$this->UpdateQuestion($myrow,$edit_qid);
					}elseif(isset($_POST['save']) && $edit_qid==0){
						$result = $this->InsertQuestion($myrow);
						$this->editInfo['edit_qid'] = $xoopsDB->getInsertId();
					}
				}				
				/*
				 *  Add new question
				 */
				if ( isset($_POST['addnew']) && strcmp($_POST['addnew'],_MB_New_Field) == 0 ){
					$this->post2question(1);
					$edit_qid=0;
					$result = $this->InsertQuestion();
					$this->editInfo['curr_qNumber'] = $this->getNewPosition($form_id);
					$this->editInfo['edit_qid'] = $xoopsDB->getInsertId();
					$_POST['position'] = $this->editInfo['curr_qNumber'];
					if(!$result) {
						$tab = 'questions';
						$errstr = _MB_Warning_error_encountered .' InsertQuestion[ '.$xoopsDB->errno().': '.$xoopsDB->error().' ]';
						return(0);
					}
				}elseif ($edit_qid==0 && isset($_POST['extra_choices'])){
					$myrow = $this->post2question();
					$result = $this->InsertQuestion($myrow);
					$this->editInfo['edit_qid'] = $xoopsDB->getInsertId();
					if(!$result) {
						$tab = 'questions';
						$errstr = _MB_Warning_error_encountered .' InsertQuestion[ '.$xoopsDB->errno().': '.$xoopsDB->error().' ]';
						return(0);
					}
				}
				// UPDATE or INSERT rows for each of the question choices for this question
				$type_id = isset($_POST['type_id']) ? intval($_POST['type_id']) : 0;
				$has_c = isset($has_choices[$type_id]) ? $has_choices[$type_id] : 0; 
				if($has_c) {
					$cids = array();
					$sql = "SELECT c.id FROM ".TABLE_QUESTION." q, ".TABLE_QUESTION_CHOICE." c WHERE q.id=c.question_id AND q.form_id=${form_id}";
					$result = $xoopsDB->query($sql);
					while ( list($cid) = $xoopsDB->fetchRow($result) ) {
						$cids[] = $cid;
					}
					
					$count = 0;
					for($i=1;$i<$_POST['num_choices']+1;$i++) {
						$sql='';
						$choice_id      = isset($_POST["choice_id_${i}"]) ? intval($_POST["choice_id_${i}"]) : '';
						$choice_content = addslashes($_POST["choice_content_${i}"]);
						// each of the submitted choices
						if($choice_id=='' && $choice_content!='') {
							// insert (new)
							$sql = "INSERT INTO ".TABLE_QUESTION_CHOICE." (question_id,content) VALUES ('${edit_qid}','${choice_content}')";
							++$count;
						} elseif($choice_id!='' && $choice_content=='') {
							// delete (old)
							$sql = "DELETE FROM ".TABLE_QUESTION_CHOICE." WHERE id='${choice_id}'";
						} elseif($choice_id!='' && $choice_content!='' && in_array($choice_id, $cids)) {
							// update (old)
							$sql = "UPDATE ".TABLE_QUESTION_CHOICE." SET content='${choice_content}' WHERE id='${choice_id}'";
							++$count;
						}
						if($sql != '') {
							$result = $xoopsDB->query($sql);
							if(!$result) {
								$tab = 'questions';
								$errstr = _MB_Warning_error_encountered .' [ '.$xoopsDB->errno().': '.$xoopsDB->error().' ]';
								return(0);
							}
						}
					}
					if(!$count && !isset($_POST['extra_choices'])) {
						$tab = 'questions';
						$errstr = _MB_Sorry_you_need_at_least_one_answer_option_for_this_question_type .
							' [ '. _MB_ID .': '. $_POST['type_id'] .' ]';
						return(0);
					}
				}
				if (!isset($_POST['extra_choices'])) $this->editInfo['update'] = 1;
				break;

			case "preview":
				// submit from preview
				if ( isset($_POST['rid']) && isset($_POST['save']) ){
					$rid = intval($_POST['response_id']);
					if($rid!=0){
						$sql = "UPDATE ".TABLE_FORM." SET response_id = '${rid}' WHERE id='${form_id}'";
						$result = $xoopsDB->query($sql);
						$errstr = _MD_DEFAULTRESULTDONE;
					}
				}
				break;
	
			case "order":
				// it updates the DB itself
				$this->editInfo['update'] = 1;
				break;
		}
		return($this->editInfo['update']);
		echo $tab.".".$old_tab."<br>";
	}	
}
?>
