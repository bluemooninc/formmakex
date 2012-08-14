<?php
# $Id: espresponse.php,v 0.96 2008/11/25 16:31:00 yoshis Exp $
//
// Modified by Y.Sakai @ bluemooninc.biz
// Since 2004/5/27 Maninly add for attach
//
// Original by James Flemer
// <jflemer@alum.rpi.edu>

/* {{{ proto string response_check_required(int form_id, int section)
   Reads current form variables from _POST.
   Returns an empty string if all required fields are
   completed, else returns a message string indicating which
   questions need to be completed. */
include_once('formmakexQuestionLoader.class.php');

class responseSaver extends questionLoader{
	var $postdata = array();
	function responseSaver(){
		$contents = array();
	}
	function array_to_insql($array) {
		if (count($array))
			return("IN (".ereg_replace("([^,]+)","'\\1'",join(",",$array)).")");
		return 'IS NULL';
	}
	function get_attach($uname,$qid) {
		global $_FILES,$FMXCONFIG;
		// Upload folder
		$file_charset = formmakexTable::getXoopsModuleConfig('FILE_CHARSET');
		$uploads = XOOPS_ROOT_PATH.$FMXCONFIG['attach_path'];
		$upurl = XOOPS_URL.$FMXCONFIG['attach_path'];
		$upfile_tmp = $upfile_url = "";
		// You must set at php.ini 'register_globals = on;'
		if ($_FILES){
			$upfile	  = $_FILES["Q".$qid];				//upload file object 
			$upfile_tmp  = $_FILES["Q".$qid]['tmp_name'];	//tmp file name 
		    $upfile_name  = basename($_FILES["Q".$qid]['name']);	//Local File Name ( Use basename for security )
		    $upfile_name  = (get_magic_quotes_gpc()) ? stripslashes($upfile_name) : $upfile_name;
			$upfile_size = $_FILES["Q".$qid]['size'];		//File Size
			$upfile_type = $_FILES["Q".$qid]['type'];		//File MIME Content-Type
		}
		//Disp File Infomation for debug
		//print("Start get_attach: $uname,$qid<BR>\n");
		//print("File Infomation:<BR>\n");
		//print("File From : $upfile_tmp<BR>\n");
		//print("File To : $upfile_name<BR>\n");
		//print("File Size - $upfile_size<BR>\n");
		//print("File type - $upfile_type<BR>\n");
		if ($upfile_tmp != "" ){
			if (eregi($FMXCONFIG['subtype'], $upfile_type)){ $deny=0; } else { $deny=1; };
			$ext = strtolower(end(explode(".",$upfile_name))); 
			if (eregi($FMXCONFIG['viri'], $ext)) $deny = 1;
			if (eregi($FMXCONFIG['imgtype'], $upfile_type)){
				$size = getimagesize($upfile_tmp);
				if ( !$size || !strcmp($size['type'],$upfile_type) ) return "getimagesize error!";
				$upfile_localname = $uname."_".$qid."_".time()."_".$this->cnv_mbstr($upfile_name, $file_charset);	// convert for mbstrings
				$upfile_url = "<img src='".$upurl.rawurlencode($upfile_localname)."'>".$upfile_name;	//rawurlencode
				move_uploaded_file( $upfile_tmp, $uploads.$upfile_localname );
			} elseif ($deny==0) {
				$upfile_localname= $qid.$uname."_".$upfile_name.".".time();
				$upfile_url = "<a href='".XOOPS_URL."/modules/'.$mydirname.'/download.php?url=/".rawurlencode($upfile_localname)."' target='_blank'>".$upfile_name."</a>";
				move_uploaded_file($upfile_tmp,$uploads.$upfile_localname);
			} else {
				$upfile_url = "Upload Denied...".$upfile_type." ext ".$ext;
			}
		}
		return $upfile_url;
	}
	/* {{{ proto string form_select_section_sql(int form_id, int section)
	   Returns a string of a SQL fragment to limit questions to
	   specified section. */
	function form_select_section_sql($sid, $section, $table = '') {
		global $xoopsDB;
		if(!empty($table)) $table .= '.';
		$sql = "SELECT position FROM ".TABLE_QUESTION." WHERE form_id='${sid}' AND type_id='99' AND deleted='N' ORDER BY position,id";
		$result = $xoopsDB->query($sql);
		$num_sections = $xoopsDB->getRowsNum($result) + 1;
	
		while(list($arr[])=$xoopsDB->fetchRow($result));
		if($section > $num_sections)
			return('');	// invalid section
	
		$ret = array("${table}form_id='${sid}'", "${table}deleted='N'");
		if($section>1 && $num_sections>1){
			$ret[] = "${table}position>" . $arr[$section-2];;
		}
		if($section<$num_sections && $num_sections>1){
			$ret[] = "${table}position<" . $arr[$section-1];
		}
		
		return('WHERE ' . join(' AND ',$ret) . ' ');
	}
	function response_check_multiple($qid) {
		global $xoopsDB;
		$sql = "SELECT id FROM ".TABLE_QUESTION_CHOICE." WHERE question_id='$qid'";
		$cid_result = $xoopsDB->query($sql);
		$cid_count = 0;
		while(list($cid) = $xoopsDB->fetchRow($cid_result)) {
			if( isset($_POST["Q${qid}_${cid}"]) ) {
				$this->postdata["${qid}_${cid}"] = htmlspecialchars($_POST["Q${qid}_${cid}"],ENT_QUOTES);
				$cid_count++;
			}
		}
		return $cid_count;
	}
	function response_check_required($sid, $section) {
		global $_POST,$_FILES,$xoopsDB;
	
		$sql = "SELECT id,type_id,name,content,required FROM ".TABLE_QUESTION." ".
			$this->form_select_section_sql($sid,$section)." AND deleted='N' ORDER BY position";
		$result = $xoopsDB->query($sql);
		if($xoopsDB->getRowsNum($result) < 1) {
			// no required fields! so no need to continue
			return('');
		}
		$missing = array();	// array of missing questions
		
		while(list($qid,$typeid,$name,$content,$required) = $xoopsDB->fetchRow($result)) {
			//echo "qid[".$qid."],typeid[".$typeid."],name[".$name."],content[".$content."]<br>";
			// Check for type of question
			if( isset($_POST["Q".$qid]) ){
				if(is_array($_POST["Q".$qid])){
					$this->postdata[$qid] = $_POST["Q".$qid];
				}else{
					$this->postdata[$qid] = htmlspecialchars($_POST["Q".$qid],ENT_QUOTES);
				}
			}
			if ( $required != 'Y' ) continue;
			switch ($typeid){
			case 1:		// Yes/No
			case 3:		// Essay Box
			case 4:		// Radio
			case 6:		// DropDown
			case 9:		// Date
				if ( empty($_POST["Q".$qid]) ) $missing[$qid] = $content;
				break;
			case 2:		// single text line
				if ( eregi("email",$content) || eregi("mailfrom|mailto",$name) ){ 
					// email address check
					if ( !checkEmail( $_POST["Q".$qid] ) ) {
						$missing[$qid] =  $content._MD_FORMMAKEX_FORMATERR;
					}
				}
				if ( empty($_POST["Q".$qid]) ) $missing[$qid] = $content;
				break;
			case 5:		// Multiple
				if ( empty($_POST["Q".$qid]) )
					$missing[$qid] = $content;
				else{
					$cid_count = $this->response_check_multiple($qid);
					$sql = "SELECT precise FROM ".TABLE_QUESTION." WHERE id='$qid'";
					list($max_checkes) = $xoopsDB->fetchRow($xoopsDB->query($sql));
					if ($max_checkes>0){
						if ( count($_POST["Q".$qid]) > $max_checkes )
							$missing[$qid] = sprintf($content._MD_FORMMAKEX_MAXOVER,$max_checkes);
					}
				}
				break;
			case 7:	// rating
				$cid_count = response_check_multiple($qid);
				if ( $cid_count==0 ) $missing[$qid] = $content;
				break;
			case 8:	// Rank
				$cid_count = $this->response_check_multiple($qid);
				if ( $cid_count==0 ){
					$patterns = "/\[TH](.*)\[\/TH\]/si";
					$replacements = '';
					$missing[$qid] = preg_replace($patterns, $replacements, $content);
				}
				break;
			case 10:	// Numeric
				if ( !ctype_digit($_POST["Q".$qid]) ){
					$missing[$qid] =  $content._MD_FORMMAKEX_DIGITERR;
				} elseif ( empty($_POST["Q".$qid]) && $_POST["Q".$qid]!="0" )
					$missing[$qid] = $content;
				break;
			case 40:	// Attach file
				if ( !isset($_FILES["Q".$qid]['tmp_name']) && empty($_POST["Q".$qid]) ) $missing[$qid] = $content;
				break;
			}
		}	
		//var_dump($missing); die;
		if(count($missing) > 0) {
			// missing required variables
			//$message = _MB_You_are_missing_the_following_required_questions ."<br>\n";

// mod start 2012/01/08 T2.kuri for #758
//			$message = _MB_You_are_missing_the_following_required_questions ."<br>\n";
			$message = "<font color='red'>"._MB_You_are_missing_the_following_required_questions ."</font><br>\n";

			while(list($qid,$content)=each($missing)) {
				if($GLOBALS['FMXCONFIG']['DEBUG'])
					$message .= "<!-- ${qid} -->";
//				$message .= "${content}<br>\n";
				$message .= "<font color='red'>${content}<br></font>\n";
// mod start 2012/01/08 T2.kuri for #758				
			}
			return($message);
		}
		return "";
	}
	/* {{{ proto array form_get_sections(int form_id)
		Returns 2D-array with question id's of sections. */
	function form_get_sections($sid, $field = 'id') {
		global $xoopsDB;
	
		$sql = "SELECT $field, type_id FROM ".TABLE_QUESTION." WHERE form_id = $sid AND deleted = 'N' ORDER BY position";
		if (!($result = $xoopsDB->query($sql)))	return array();
		$ret = array();
		$sec = array();
		while (list($key, $type) = $xoopsDB->fetchRow($result)) {
			if ($type != 99) {
				$sec[] = $key;
			} else {
				$ret[] = $sec;
				$sec = array();
			}
		}
		$ret[] = $sec;
		return $ret;
	}
	
	/* {{{ proto void response_delete(int form_id, int section, int response_id)
	   Deletes values for the response. */
	function response_delete($sid, $rid, $sec = null,$flg) {
		global $xoopsDB;
		if (empty($rid)) return;
	
		if ($sec != null) {
			if ($sec < 1) return;
			/* get question_id's in this section */
			$qids = $this->form_get_sections($sid);
			if (!isset($qids[$sec - 1])) return;
			$qids = 'AND question_id '. $this->array_to_insql($qids[$sec - 1]);
		} else {
			/* delete all */
			$qids = '';
		}
		/* delete values */
		foreach (array('bool', 'single', 'multiple', 'rank', 'text', 'other', 'date') as $tbl) {
			$sql = "DELETE FROM ".TABLE_RESPONSE."_$tbl WHERE response_id = '$rid' $qids";
			$res = $xoopsDB->queryF($sql);
		}
        /*   modify response data   */
		if ($flg != "1" ){    
			$sql = "UPDATE ".TABLE_RESPONSE." SET complete='N' WHERE id = '$rid'";
			$xoopsDB->queryF($sql);
		}
	}
	/* }}} */

	
	/* {{{ proto int response_insert(int form_id, int section, int response_id)
	   Reads current form variables from _POST.
	   Returns the ID for the response. */
	function response_insert($sid,$section,$rid) {
		global $_POST,$xoopsUser,$xoopsDB,$FMXCONFIG;
		
	//	$userid = isset($GLOBALS['HTTP_SERVER_VARS']['PHP_AUTH_USER']) ? $GLOBALS['HTTP_SERVER_VARS']['PHP_AUTH_USER'] : '';
		if ($xoopsUser){
			$userid = $xoopsUser->uid();
			$userHander = new XoopsUserHandler($xoopsDB);
			$tUser = $userHander->get($userid);
			$uname = $tUser->uname();
		}else{
			if (empty($userid)) $userid = $_POST['userid'];
			if ($FMXCONFIG['anonymousname']=="0"){
				$uname = "Anonymous";
			}else{
				$uname = $userid;
			}
		}
		if(empty($rid)) {
			// create a uniqe id for this response
			$sql = "INSERT INTO ".TABLE_RESPONSE." (form_id,username) VALUES ( '${sid}','${uname}' )";
			$result = $xoopsDB->queryF($sql);
			$rid = $xoopsDB->getInsertId();
		}
	
		$sql  = "SELECT Q.id, Q.type_id, T.response_table FROM ".TABLE_QUESTION.
			" Q, ".TABLE_QUESTION_TYPE." T ". $this->form_select_section_sql($sid,$section,'Q') .
			" AND Q.form_id='${sid}' AND Q.deleted='N' AND Q.type_id < 50 AND Q.type_id=T.id";
		$q_result = $xoopsDB->query($sql);
		while(list($qid, $tid, $table) = $xoopsDB->fetchRow($q_result)) {
			$bareval = '';
			if (isset($_POST["Q".$qid])) {
				if (is_array($_POST["Q".$qid]))
					$bareval = array_map('stripslashes', $_POST["Q".$qid]);
				else
					$bareval = stripslashes($_POST["Q".$qid]);
			}
			if (is_string($bareval))
				$val = addslashes($bareval);
			else
				$val = implode("|", $bareval); //'';
			switch($table ) {
				case 'response_bool':
					$sql  = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,choice_id ) VALUES ( '${rid}','${qid}','${val}' )";
					if (!empty($val))
						$result = $xoopsDB->queryF($sql);
					break;
				case 'response_text':
					// only insert if non-empty content
					if($tid == 10) { // numeric
						$bareval = ereg_replace("[^0-9.\-]*(-?[0-9]*\.?[0-9]*).*", '\1', $bareval);
					}
					if($tid == 40) { // attach by Y.Sakai
						if ($xoopsUser){
							$userHander = new XoopsUserHandler($xoopsDB);
							$tUser = $userHander->get($xoopsUser->uid());
							$uname = $tUser->uname();
						}else{
							$uname = "Anonymous";
						}
						$bareval = $this->get_attach($uname,$qid);
					}
					if(ereg("[^ \t\n]",$bareval)) {
						$val = addslashes($bareval);
						$sql = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,response ) VALUES ( '${rid}','${qid}','${val}' )";
						$result = $xoopsDB->queryF($sql);
					}
					break;
				case 'response_date':
					// only insert if non-empty content
					if(!empty($bareval)) {
						$aSepList="-/ .";
						ereg("^([0-9]+)[$aSepList]([0-9]+)[$aSepList]([0-9]+)$", $bareval, $regs);
						if($regs) {
							date(_SHORTDATESTRING,strtotime($val));
							$dfmt = ereg_replace("[\/]|[\-]","",_SHORTDATESTRING);
							switch($dfmt){
								case "njY":
									$val=$regs[3]."-".$regs[1]."-".$regs[2];
									break;
								case "jnY":
									$val=$regs[3]."-".$regs[2]."-".$regs[1];
									break;
								case "Ynj":
									$val=$regs[1]."-".$regs[2]."-".$regs[3];
									break;
							}
							$sql  = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,response ) VALUES ( '${rid}','${qid}','${val}' )";
							$result = $xoopsDB->queryF($sql);
						}
					}
					break;
				case 'response_single':
					if(empty($bareval)) {
						$sql = "SELECT id FROM ".TABLE_QUESTION_CHOICE." WHERE question_id='${qid}' AND content LIKE '!other%' ORDER BY id";
						$c_result = $xoopsDB->query($sql);
						while(list($cid) = $xoopsDB->fetchRow($c_result)) {
										if (!isset($_POST["Q${qid}_${cid}"]))
												continue;
							$other = addslashes($_POST["Q${qid}_${cid}"]);
							if(ereg("[^ \t\n]",$other)) {
								$sql = "INSERT INTO ".TABLE_RESPONSE_OTHER."  ( response_id,question_id,choice_id,response ) VALUES ( '${rid}','${qid}','${cid}','${other}' )";
								$result = $xoopsDB->queryF($sql);
								$val = $cid;
								break;
							}
						}
						
					}
					if(ereg("other_([0-9]+)", $bareval, $regs)) {
						$cid=$regs[1];
						if (!isset($_POST["Q${qid}_${cid}"])) break; // out of the case
						$other = addslashes($_POST["Q${qid}_${cid}"]);
						if(ereg("[^ \t\n]",$other)) {
							$sql = "INSERT INTO ".TABLE_RESPONSE_OTHER."  ( response_id,question_id,choice_id,response ) VALUES ( '${rid}','${qid}','${cid}','${other}' )";
							$result = $xoopsDB->queryF($sql);
							$val = $cid;
						}
					}
					$sql  = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,choice_id ) VALUES ( '${rid}','${qid}','${val}' )";
					$result = $xoopsDB->queryF($sql);
					break;
				case 'response_multiple':
					$sql = "SELECT id FROM ".TABLE_QUESTION_CHOICE." WHERE question_id='${qid}' AND content LIKE '!other%'";
					$c_result = $xoopsDB->query($sql);
					while(list($cid) = $xoopsDB->fetchRow($c_result)) {
						if (!isset($_POST["Q${qid}_${cid}"]) || empty($_POST["Q${qid}_${cid}"])) continue;
						if (!isset($_POST["Q".$qid]))
							$_POST["Q".$qid] = array($cid);
						else
							$_POST["Q".$qid] = $cid;
						$other = addslashes($_POST["Q${qid}_${cid}"]);
						if(ereg("[^ \t\n]",$other)) {
							$sql = "INSERT INTO ".TABLE_RESPONSE_OTHER."  ( response_id,question_id,choice_id,response ) VALUES ( '${rid}','${qid}','${cid}','${other}' )";
							$result = $xoopsDB->queryF($sql);
						}
					}
					//if(!isset($_POST["Q".$qid]) && count($_POST["Q".$qid]) < 1)	break;
					if(isset ($_POST["Q".$qid])){
						foreach($_POST["Q".$qid] as $cid) {
							$cid = addslashes($cid);
							if(ereg("other_[0-9]+", $cid)) continue;
							$sql  = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,choice_id ) VALUES ( '${rid}','${qid}','${cid}' )";
							$result = $xoopsDB->queryF($sql);
						}
					}
					break;
				case 'response_rank':
					if($tid == 8) { // Rank
						$sql = "SELECT id FROM ".TABLE_QUESTION_CHOICE." WHERE question_id='${qid}'";
						$cid_result = $xoopsDB->query($sql);
						while(list($cid) = $xoopsDB->fetchRow($cid_result)) {
							if (!isset($_POST["Q${qid}_${cid}"])) continue;
							$val = addslashes($_POST["Q${qid}_${cid}"]);
							if(strtolower($val) == "n/a")
								$rank = -1;
							else
								$rank = intval($val);
							$sql  = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,choice_id,rank ) ";
							$sql .= "VALUES ( '${rid}','${qid}','${cid}','${rank}' )";
							$xoopsDB->queryF($sql);
						}
						break;
					}
					if(strtolower($bareval) == "n/a")
						$rank = -1;
					else
						$rank = intval($bareval);
					$sql  = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,rank ) VALUES ( '${rid}','${qid}','${rank}' )";
					$result = $xoopsDB->queryF($sql);
					break;
			}
		}
		return($rid);
	}
	/* }}} */
	
	/* {{{ proto bool response_commit(int response_id)
	   Marks a response as completed.  Returns true on success.
	 */
	function response_commit($rid) {
		global $xoopsDB; 
		$sql = "UPDATE ".TABLE_RESPONSE." SET complete='Y', submitted = NULL WHERE id='${rid}'";
		if($xoopsDB->queryF($sql)) return(true);
		return(false);
	}
	function response_submitted($sid) {
		global $xoopsDB,$xoopsUser;
		$uname=$xoopsUser->uname();
		$sql = "SELECT submitted FROM ".TABLE_RESPONSE." WHERE complete='Y' AND form_id='${sid}' and username='${uname}'";
		$ret = $xoopsDB->query($sql);
		list($submitted) = $xoopsDB->fetchRow($ret);
		return $submitted;
	}
		
	/* }}} */
	

	function make_multipart($url,$fnm,$boundary,$mail_charset) {
		$fnamedotpos = strrpos($fnm,'.');
		$fext = strtolower(substr($fnm,$fnamedotpos+1));
		if ( function_exists('mb_encode_mimeheader') )
			$fnm = mb_encode_mimeheader( $fnm, $mail_charset, "B" );
		if( !empty( $url) ) {
			$fd = @fopen( $url, "r" );
			if( $fd ) {
				$attach_file = fread( $fd, filesize($url) );
				fclose( $fd );
				//$attach_mimetype = mime_content_type( $url );
			}
		}
		switch($fext) {
			case "pdf": $attach_mimetype = "application/pdf"; break;
			case "doc": $attach_mimetype = "applicaion/msword"; break;
			case "xls": $attach_mimetype = "application/excel"; break;
			case "ppt": $attach_mimetype = "application/powerpoint"; break;
			case "jpg": case "jpeg": $attach_mimetype = "image/jpeg"; break;
			case "gif": $attach_mimetype = "image/gif"; break;
			case "txt": $attach_mimetype = "text/plain"; break;
			default: $attach_mimetype = "application/octet-stream"; break;
		}
		$mimetype = "multipart/mixed;\n\tboundary=\"$boundary\"";
		$message = "--".$boundary."\n";
		$message .= "Content-Type: " . $attach_mimetype . ";\n\tname=\"".$fnm."\"\n";
		$message .= "Content-Transfer-Encoding: base64\n";
		$message .= "Content-Disposition: attachment;\n\tfilename=\"".$fnm."\"\n\n";
		$message .= chunk_split(base64_encode( $attach_file )) ."\n";
			//."--" .$boundary."\n";
		return $message;
	}
	function fnm2mime($fnm) {
		$fnamedotpos = strrpos($fnm,'.');
		$fext = strtolower(substr($fnm,$fnamedotpos+1));
		switch($fext) {
			case "pdf": $attach_mimetype = "application/pdf"; break;
			case "doc": $attach_mimetype = "applicaion/msword"; break;
			case "xls": $attach_mimetype = "application/excel"; break;
			case "ppt": $attach_mimetype = "application/powerpoint"; break;
			case "jpg": case "jpeg": $attach_mimetype = "image/jpeg"; break;
			case "gif": $attach_mimetype = "image/gif"; break;
			case "png": $attach_mimetype = "image/png"; break;
			case "txt": $attach_mimetype = "text/plain"; break;
			default: $attach_mimetype = "application/octet-stream"; break;
		}
		return $attach_mimetype;
	}
	
	//
	// Get mailto info from section text
	//
	function pickup_mail_info($referer) {
		if (preg_match("/\bhref\s*=\s*[\"']?(mailto:[^\s\"'>]*)/i",$referer,$reg)) $referer=$reg[1];
	    $url = parse_url($referer);
		if (!eregi('mailto',$url['scheme'])) return false;
	    $param = array();
	    $param['to'] = $url['path'];
		if(isset($url['query'])){
		    $query = rawurldecode($url['query']);
	    	parse_str($query,$ret);
	    	$param['cc'] = $ret['cc'];
	    	$param['bcc'] = $ret['bcc'];
	    	$param['subject'] = $ret['subject'];
	    	$param['body'] = $ret['body'];
	    }
	    return $param;
	}

	
	/* {{{ proto int response_select_max_pos(int form_id, int response_id)
	   Returns the position of the last answered question in a response.
	 */
	function response_select_max_pos($sid, $rid) {
		global $xoopsDB;
		$max = 0;
		
		foreach (array('bool', 'single', 'multiple', 'rank', 'text', 'other', 'date') as $tbl) {
			$sql = "SELECT MAX(q.position) FROM ".TABLE_."response_$tbl a, question q WHERE a.response_id = '$rid' AND q.id = a.question_id AND q.form_id = '$sid' AND q.deleted = 'N'";
			list($num) = $xoopsDB->fetchRow($xoopsDB->query($sql));
			if ($num > $max) $max = $num;
		}
		return $max;
	}
	/* }}} */
	
	/* {{{ proto int response_select_max_pos(int form_id, int response_id)
	   Returns the number of the section in which questions have been
	   answered in a response.
	 */
	function response_select_max_sec($sid, $rid) {
		global $xoopsDB;
		$pos = response_select_max_pos($sid, $rid);
		$sql = "SELECT COUNT(*)+1 FROM ".TABLE_QUESTION." q WHERE q.form_id = '$sid' AND q.type_id = 99 AND q.position < '$pos' AND q.deleted = 'N'";
		list($max) = $xoopsDB->fetchRow($xoopsDB->query($sql));
		
		return $max;
	}
	/* }}} */
	
	/* {{{ proto void response_import_sec(int form_id, int response_id, int section, &array destination)
	   Populates the destination array with the answers from a given
	   section of a given response.
	 */
	function response_import_sec($sid, $rid, $sec, $varr = null) {
		if ($varr == null)
			$varr =& $GLOBALS['_POST'];
		
		$ids = $this->form_get_sections($sid);
		if ($sec < 1 || !isset($ids[$sec - 1]))
			return;
		$vals = response_select($sid, $rid, 'content', $ids[$sec - 1]);
	
		reset($vals);
		foreach ($vals as $id => $arr) {
			if (isset($arr[0]) && is_array($arr[0])) {
				// multiple
				$varr[$id] = array_map('array_pop', $arr);
			} else {
				$varr[$id] = array_pop($arr);
			}
		}	
	}
	/* }}} */
	/******************************************************************************
	Pickup data from answer mail
	******************************************************************************/
	function resmail_insert($uname,$sid,$l_stack,$section=1,$rid=null) {
		$debug = 0;
	
		if(is_null($rid)) {
			// create a uniqe id for this response
			$sql = "INSERT INTO ".TABLE_RESPONSE." (form_id,username) VALUES ( '${sid}','${uname}' )";
			$result = $xoopsDB->query($sql);
			$rid = $xoopsDB->getInsertId();
		}
		$sql  = "SELECT Q.position, Q.id, Q.type_id, T.response_table FROM ".TABLE_QUESTION.
			" Q, ".TABLE_QUESTION_TYPE." T ". $this->form_select_section_sql($sid,$section,'Q') .
			" AND Q.form_id='${sid}' AND Q.deleted='N' AND Q.type_id < 50 AND Q.type_id=T.id Order By Q.position";
		$q_result = $xoopsDB->query($sql);
		while(list($position,$qid, $tid, $table) = $xoopsDB->fetchRow($q_result)) {
			$bareval = '';
			$qnum = $position + 1;
			if (is_array($l_stack[$qnum])){
				$bareval = array_map('stripslashes', $l_stack[$qnum]);
			} elseif (isset($l_stack[$qnum])) {
				$bareval = stripslashes($l_stack[$qnum]);
			}
			if (is_string($bareval)){
				$val = addslashes($bareval);
			} else {
				$val = $bareval;
			}
			if ($debug){
				echo "<br>${qnum},qid=${qid},bareval=${bareval},table=${table},val=${val}";
			}
			switch($table) {
				case 'response_bool':
					$sql  = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,choice_id ) VALUES ( '${rid}','${qid}','${val}' )";
					if (!empty($val))
						$result = $xoopsDB->query($sql);
					break;
				case 'response_text':
					// only insert if non-empty content
					if($tid == 10) { // numeric
						$bareval = ereg_replace("[^0-9.\-]*(-?[0-9]*\.?[0-9]*).*", '\1', $bareval);
					}
					if($tid == 40) { // attach by Y.Sakai
						$bareval = get_attach($uname,$qid);
					}
					if(ereg("[^ \t\n]",$bareval)) {
						$val = addslashes($bareval);
						$sql = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,response ) VALUES ( '${rid}','${qid}','${val}' )";
						$result = $xoopsDB->query($sql);
					}
					break;
				case 'response_date':
					// only insert if non-empty content
					$aSepList="-/ .";
					ereg("^([0-9]+)[$aSepList]([0-9]+)[$aSepList]([0-9]+)$", $bareval, $regs);
					if($regs) {
						date(_SHORTDATESTRING,strtotime($val));
						$dfmt = ereg_replace("[\/]|[\-]","",_SHORTDATESTRING);
						switch($dfmt){
							case "njY":
								$val=$regs[3]."-".$regs[1]."-".$regs[2];
								break;
							case "jnY":
								$val=$regs[3]."-".$regs[2]."-".$regs[1];
								break;
							case "Ynj":
								$val=$regs[1]."-".$regs[2]."-".$regs[3];
								break;
						}
						$sql  = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,response ) VALUES ( '${rid}','${qid}','${val}' )";
						$result = $xoopsDB->query($sql);
					}
					break;
				case 'response_single':
					if(empty($bareval)) {
						$sql = "SELECT id FROM ".TABLE_QUESTION_CHOICE." WHERE question_id='${qid}'";// AND content LIKE '!other%' ORDER BY id";
						$c_result = $xoopsDB->query($sql);
						while(list($cid) = $xoopsDB->fetchRow($c_result)) {
							$keystr = $qnum . "_" . $cid;
							if (isset( $l_stack["o_".$cid])) {
								$other = addslashes($l_stack["o_".$cid]);
								if (!$other) continue;
								$sql = "INSERT INTO ".TABLE_RESPONSE_OTHER."  ( response_id,question_id,choice_id,response ) VALUES ( '${rid}','${qid}','${cid}','${other}' )";
								$result = $xoopsDB->query($sql);
								$val = $cid;
								break;
							}
							if (!isset($l_stack["$keystr"])) continue;
						}
						
					}
	/*
					if(ereg("o_([0-9]+)", $bareval, $regs)) {
						$cid=$regs[1];
						if (!isset($l_stack["$keystr"])) break; // out of the case
						if(ereg("[^ \t\n]",$other)) {
							$other = addslashes($l_stack["$keystr"]);
							$sql = "INSERT INTO ".TABLE_RESPONSE_OTHER."  ( response_id,question_id,choice_id,response ) VALUES ( '${rid}','${qid}','${cid}','${other}' )";
							$result = $xoopsDB->query($sql);
							$val=$cid;
						}
					}
	*/
					if (isset($val)){
						$sql  = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,choice_id ) VALUES ( '${rid}','${qid}','${val}' )";
						$result = $xoopsDB->query($sql);
					}
					break;
				case 'response_multiple':
					$sql = "SELECT id FROM ".TABLE_QUESTION_CHOICE." WHERE question_id='${qid}'";	// AND content LIKE '!other%'";
					$c_result = $xoopsDB->query($sql);
					$c_val = array();
					while(list($cid) = $xoopsDB->fetchRow($c_result)) {
						$keystr = $qnum . "_" . $cid;
						if(isset( $l_stack["o_".$cid])) {
							$other = addslashes($l_stack["o_".$cid]);
							if (!$other) continue;
							$sql = "INSERT INTO ".TABLE_RESPONSE_OTHER."  ( response_id,question_id,choice_id,response ) VALUES ( '${rid}','${qid}','${cid}','${other}' )";
							$result = $xoopsDB->query($sql);
							array_push($l_stack[$qnum], $cid);
						}
						if (!isset($l_stack["$keystr"]) || empty($l_stack["$keystr"])) continue;
						if (!isset($l_stack[$qnum]))
							$l_stack[$qnum] = array($cid);
						else
							array_push($l_stack[$qnum], $cid);
					}
					
					if(!isset($l_stack[$qnum]) || count($l_stack[$qnum]) < 1) break;
					foreach($l_stack[$qnum] as $cid) {
						$cid = addslashes($cid);
						if(ereg("o_[0-9]+", $cid)) continue;
						$sql  = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,choice_id ) VALUES ( '${rid}','${qid}','${cid}' )";
						$result = $xoopsDB->query($sql);
					}
					break;
				case 'response_rank':
					if($tid == 8) { // Rank
						$sql = "SELECT id FROM ".TABLE_QUESTION_CHOICE." WHERE question_id='${qid}'";
						$cid_result = $xoopsDB->query($sql);
						while(list($cid) = $xoopsDB->fetchRow($cid_result)) {
							if (!isset($l_stack["${qid}_${cid}"])) continue;
							$val = addslashes($l_stack["${qid}_${cid}"]);
							if(strtolower($val) == "n/a")
								$rank = -1;
							else
								$rank = intval($val) - 1;
							$sql  = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,choice_id,rank ) ";
							$sql .= "VALUES ( '${rid}','${qid}','${cid}','${rank}' )";
							$xoopsDB->query($sql);
						}
						
						break;
					}
					if(strtolower($bareval) == "n/a")
						$rank = -1;
					else
						$rank = intval($bareval);
					$sql  = "INSERT INTO ".TABLE_."${table} ( response_id,question_id,rank ) VALUES ( '${rid}','${qid}','${rank}' )";
					$result = $xoopsDB->query($sql);
					break;
			}
		}
		
		return($rid);
	}
}
?>