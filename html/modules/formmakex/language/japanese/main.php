<?php
// $Id: main.php,v 1.1.1.1 2005/08/10 12:14:04 yoshis Exp $
//  ------------------------------------------------------------------------ //
//                Bluemoon.Multi-Form                                      //
//                    Copyright (c) 2005 Yoshi.Sakai @ Bluemoon inc.         //
//                       <http://www.bluemooninc.biz/>                       //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //


if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'FORMMAKEX_MB_LOADED' ) ) {

define( 'FORMMAKEX_MB_LOADED' , 1 ) ;

define('_MB_ORDER', '�¤ӽ�');
define('_MB_TILDE', '����');
define('_MB_NUMBERSTRING', '��');
define('_MB_ALL', '��');
define('_MB_FILTER', '�ʤ�����');
define('_MB_FILTER_ON', '�ʤ����');
define('_MB_FILTER_OFF', '�ʹ����򥯥ꥢ');
define('_MB_STATUS_EDIT',    0x00);
define('_MB_STATUS_ACTIVE',  0x01);
define('_MB_STATUS_DONE',    0x02);
define('_MB_STATUS_DELETED', 0x04);
define('_MB_STATUS_TEST',    0x08);
define('_MB_ORDER_NEW', '�����');
define('_MB_LIST_CHECKED', '��');
define('_MD_FORMMAKEX_DETAIL', '�ܺ�');
define('_MB_From_Option','���пͥ��ɥ쥹');
define('_MD_FROM_OPTION','������̤�����������κ��пͥ��ɥ쥹�ʥ��󥱡��ȤΥ��ɥ쥹�ϥ⥸�塼������ΰ�����������ꤷ�ޤ���');
define('_MD_FROM_OPTION_0','���󥱡��ȤΥ��ɥ쥹');
define('_MD_FROM_OPTION_1','��Ͽ�桼���Υ��ɥ쥹');
define('_MD_FROM_OPTION_2',"���󥱡������'email'��");
define('_MB_Default_Response','�����ν����');
define('_MD_FROM_DEFRES',"���Ͻ���ͤΥ쥹�ݥ�ID�ʽ�������פξ��϶���ˤ��ޤ���");
define('_MB_LIST_UNAME', '������');
define('_MB_LIST_DATE', '��Ͽ����');
define('_MD_FORMMAKEX_THANKS_ENTRY', '���󥱡��Ȥؤε������꤬�Ȥ��������ޤ�����');
define('_MD_FORMMAKEX_CAN_WRITE_USER_ONLY', '�����ȥ桼���ϥ��󥱡��Ȥ��Խ����뤳�ȤϤǤ��ޤ���');
define('_MD_FORMMAKEX_YOU_DONT_HAVE_A_PERMISSION', '���ε�ǽ�����Ѥ��븢�¤�����ޤ���');
define('_MD_ASTERISK_REQUIRED', '�������ꥹ����<font color="#FF0000">��</font>���դι��ܤ�����ɬ�ܹ��ܤǤ���');
define('_MD_MAIL_TITLE', '���ϥ��󥱡��ȡ�');
define('_MD_DENYRESULT','������Ƥ�̵���ˤ���');
define('_MD_DENYRESULTSURE','������Ƥ�̵���ˤ��ޤ���������Ǥ�����');
define('_MD_DENYRESULTDONE','������Ƥ�̵���ˤ��ޤ�����');
define('_MD_DEFAULTRESULT','������Ƥ����Ͻ���ͤ˥��åȤ���');
define('_MD_EDITRESULT','������Ƥ��Խ�����');
define('_MD_DEFAULTRESULTDONE','�����ν���ͤ򥻥åȤ��ޤ�����');
define('_MD_RESPONDENT','��Ƽ�̾');
define('_MD_QUESTION_OTHER','����¾');
define('_MD_FORMMAKEX_FORMATERR', ' �����������Ϥ���Ƥ��ޤ���');
define('_MD_FORMMAKEX_DIGITERR', ' ��Ⱦ�Ѥǿ��������Ϥ��Ƥ�������');
define('_MD_FORMMAKEX_MAXOVER', '�ϡ�%u���ܰʾ�����å��Ǥ��ޤ���');
define('_MD_FORMMAKEX_CHECKANY', '��ʣ������ġ�');
define('_MD_FORMMAKEX_CHECKLIMIT', '��%u�Ĥޤ�����ġ�');
define('_MD_FORMMAKEX_CHECKRESET', '������');
define('_MD_SUBMIT_FORM', '����');
define('_MD_NEXT_PAGE', '���ڡ���');
define('_MD_FORMMAKEX_COPY_TITLE_PREFIX', '���ԡ� %s�� ');
define("_MD_FORM_LIST","���������");

define('_MD_POP_KEY_M','���С�');
define('_MD_POP_KEY_U','�Ȥ���');
define('_MD_POP_KEY_Q','���󥱡���');
define('_MD_POP_KEY_ERR','POP-Key Error');
define('_MD_POP_CMD_NEW','������Ͽ');
define('_MD_POP_CMD_INP','����');
define('_MD_POP_CMD_DEL','���');
define('_MD_POP_MNEW_ENTRY','������̾ %s �ǥ桼����Ͽ���ޤ�����');
define('_MD_POP_MNEW_AREADY','���Υ�����̾�ϴ�����Ͽ����Ƥ��ޤ����̤�̾������Ͽ���Ƥ���������');
define('_MD_POP_QINP_HEADER','�ֿ��᡼����������[]�ޤ���()��������Ϥ���������������
1�Ԥ�ʣ������[]()�ϥ����å����ܤǤ���[]��ʣ����()��1�Ĥ���Ǥ�դ�1ʸ�������Ϥ���������
1�Ԥ�1�Ĥ�����[]�ϥƥ��������Ϲ��ܤǤ���ʸ��������ϲ�������
----

');
define('_MD_POP_QINP_FAILEDLOGIN','�桼��̾��ǧ�ڥ����ɤ��㤤�ޤ���');
define('_MD_POP_QINP_SUCCEEDED','%s ����β�������Ͽ���ޤ�����');
define('_MD_POP_QINP_DELETEIT','���Υ��󥱡��Ȥϴ��˲����ѤߤǤ������Υ᡼����ֿ�����Ⱦõ�Ǥ��ޤ���');
define('_MD_POP_QDEL_SUCCEEDED','%s ����β����������ޤ�����');

define('_AM_FORMMAKEX_MANAGE','���󥱡��ȴ���');
define('_AM_FORMMAKEX_SEEARESULT','���̤β����򸫤�');
define('_AM_FORMMAKEX_COPYQUESTION','���󥱡��Ȥ������򥳥ԡ�');
define('_AM_FORMMAKEX_SELECTSTATUS','���֤�����');
define('_AM_FORMMAKEX_RATECOUNT','�������ɽ��');
define('_AM_FORMMAKEX_NORESPONSE','̵����');
define('_AM_FORMMAKEX_TOTAL','���');
define('_AM_FORMMAKEX_QUESTIONNUMBER','����<BR>�ֹ�');
define('_AM_FORMMAKEX_ARCHIVED','�ݴ���');
define('_AM_FORMMAKEX_TEST','�ƥ���');
define('_AM_FORMMAKEX_EXPIRATION','��λ');
define('_AM_FORMMAKEX_ACTIVE','�����ƥ���');
define('_AM_FORMMAKEX_EDIT','�Խ�');
define('_AM_FORMMAKEX_PURGE','�˴�');
define('_AM_FORMMAKEX_VALUE','�����');
define('_AM_FORMMAKEX_COUNT','������');
define('_AM_FORMMAKEX_NA','̵����');

//
// From /locale/messages.po
//
define("_MB_Unable_to_open_include_file","����ե����뤬�����ޤ���Ǥ�����INI�ե������������ǧ���Ƥ������������Ǥ��ޤ���");
define("_MB_Service_Unavailable","�����ӥ������ѤǤ��ޤ���");
define("_MB_Your_progress_has_been_saved","���ʤ���������Υǡ�������¸���ޤ����� ���ʤ��Ϥ��ĤǤ⡢��äơ�����Ĵ����λ�Ǥ��ޤ�����������ˤϡ��ʲ��Υ�󥯤�֥å��ޡ������ɲä��Ƥ����������ޤ����Ƴ�����ˤϥ����󤹤�ɬ�פ�����ޤ���");
define("_MB_Resume_form","���󥱡��ȤκƳ�");
define("_MB_Invalid_argument","�����ʰ����Ǥ�");
define("_MB_Error_opening_form","���󥱡��Ȥ������ޤ���Ǥ���");
define("_MB_Error_opening_forms","���İʾ�Υ��󥱡��Ȥϳ����ޤ��󡣸��ߤ��Խ�������꽪λ�פ��Ĥ��Ʋ�������");
define("_MB_No_responses_found","No responses found.");
define("_MB_TOTAL","���");
define("_MB_No_questions_found","���䤬1�Ĥ⤢��ޤ���");
define("_MB_Page_d_of_d","�ڡ��� %d / %d");
define("_MB_Yes","�Ϥ�");
define("_MB_No","������");
define("_MB_1","1");
define("_MB_2","2");
define("_MB_3","3");
define("_MB_4","4");
define("_MB_5","5");
define("_MB_NA","̵����");
define("_MB_AVERAGE","ʿ����");
define("_MB_SUBJECT","�����");
define("_MB_MAX","������");
define("_MB_MIN","�Ǿ���");
define("_MB_MEDIAN","�����");
define("_MB_SUM","���");
define("_MB_Page","�ڡ���");
define("_MB_of","��");
define("_MB_Error_system_table_corrupt","�����ʥ����ƥ�ơ��֥�Ǥ�");
define("_MB_Table","�ơ��֥�");
define("_MB_Report_for","��ݡ���");
define("_MB_ID","ID");
define("_MB_Num","#");
define("_MB_Req_d","ɬ��");
define("_MB_Public","�ѥ֥�å�");
define("_MB_Content","����ƥ��");
define("_MB_Previous","����");
define("_MB_Next","����");
define("_MB_Navigate_Individual_Respondent_Submissions","Ĵ����̤ΰ����ɽ��");
define("_MB_Error_cross_analyzing_Question_not_valid_type","�����ͭ���ʷ��ǤϤ���ޤ���");
define("_MB_Cross_analysis_on_QID","����ʬ�� QID:");
define("_MB_Sorry_please_fill_out_the_name","�¹�����̾�������롼�ס������ȥ�ι��ܤ����Ƥ���������");
define("_MB_Sorry_name_already_in_use","���Ǥˤ���̾���ϻȤ��Ƥ��ޤ���¾��̾����ȤäƤ���������");
define("_MB_Sorry_that_name_is_already_in_use","���Ǥˤ���̾���ϻȤ��Ƥ��ޤ���");
define("_MB_Warning_error_encountered","���顼��ȯ�����ޤ�����");
define("_MB_Please_enter_text","������������ˤ�����ɬ�פʾ�������Ϥ��Ƥ�������");
define("_MB_Sorry_you_must_select_a_type_for_this_question","�������������Ф����������򤷤ʤ���Фʤ�ޤ���");
define("_MB_New_Field","�������ե������");
define("_MB_Sorry_you_cannot_change_between_those_types_of_question","������Υ��󥱡��ȥ����פˤ��ѹ��Ǥ��ޤ��󡣿����������������Ƥ���������");
define("_MB_Sorry_you_need_at_least_one_answer_option_for_this_question_type","���ߤޤ��󡢤��ʤ��Ͼ��ʤ��Ȥ�1�Ĥ��������ץ����򤳤μ��䥿���פ�ɬ�פȤ��ޤ���");
define("_MB_Error_cross_tabulating","�������֡����顼�Ǥ���");
define("_MB_Error_same_question","�Ԥ���������Ʊ����������򤷤Ƥ��ʤ�����ǧ���Ƥ���������");
define("_MB_Error_column_and_row","�Ԥ����ξ�������򤷤Ƥ��뤫��ǧ���Ƥ���������");
define("_MB_Error_analyse_and_tabulate","���Ϥȥ������֤�Ʊ���˼¹Ԥ��뤳�ȤϽ���ޤ���");
define("_MB_Error_processing_form_Security_violation","���顼���������ƥ���ȿ");
define("_MB_Unable_to_execute_query_for_access","���������ѤΥ������¹ԤǤ��ޤ���");
define("_MB_Unable_to_execute_query_respondents","�������ѤΥ������¹ԤǤ��ޤ���");
define("_MB_Unauthorized","̤ǧ��");
define("_MB_Incorrect_User_ID_or_Password","�桼����ID�ȥѥ���ɤ��ְ�äƤ��ޤ뤫����������Ȥ������ԲĤ������ڤ�ˤʤäƤ��ޤ�");
define("_MB_Your_account_has_been_disabled","���ʤ��Υ�������Ȥ�̵���ˤ��줿�����ޤ��Ϥ��ʤ��ϴ��ˤ���Ĵ����λ���ޤ�����");
define("_MB_Unable_to_load_ACL","ACL����ɤ��뤳�Ȥ��Ǥ��ޤ���");
define("_MB_Management_Interface","��������");
define("_MB_This_account_does_not_have_permission","���¤����Ĥ���Ƥ���ޤ���");
define("_MB_Go_back_to_Management_Interface","�������̤����");
define("_MB_Submit","����");
define("_MB_Rank","���");
define("_MB_Response","�쥹�ݥ�");
define("_MB_Average_rank","ʿ�ѥ��");
define("_MB_You_are_missing_the_following_required_questions","�ʲ��ι��ܤ���������Ƥ��ޤ���");
define("_MB_Form_Design_Completed","���󥱡��ȥǥ�������");
define("_MB_You_have_completed_this_form_design","���󥱡��ȥǥ����󤬴������ޤ���");
define("_MB_To_insert_this_form_into_your_web_page","���Υ��󥱡��Ȥ�PHP����������������ˤϡ����Υƥ����Ȥ򥳥ԡ���Ž���դ��Ƥ���������");
define("_MB_Once_activated_you_can_also_access_the_form_directly_from_the_following_URL","���٥����ƥ��֤ˤ�����ϡ��ʲ���URL���饢�󥱡��Ȥ�ľ�ܥ��������Ǥ��ޤ���");
define("_MB_You_must_activate_this_form","���󥱡��Ȥ���Ѥ���ˤϥ��ơ�������֥����ƥ��֡פˤ���ɬ�פ�����ޤ������ơ��������ѹ��ϡȥ��󥱡��Ȥξ��֤��ѹ�����ɥڡ������ԤäƲ��������֥����ƥ��֡פˤ�����ϰ��ڤ��ѹ����Բ�ǽ�ˤʤ�ޤ���");
define("_MB_The_information_on_this_tab_applies_to_the_whole_form","���Υ��֤����ꤷ������ϥ��󥱡������Τ�ȿ�Ǥ���ޤ������Υ��֤����ꤷ���塢�ƥ��֤Ǹġ������ԤäƤ���������");
define("_MB_Name","̾��");
define("_MB_Required","ɬ�ܹ���");
define("_MB_Form_filename","�ե�����͡���");
define("_MB_This_is_used_for_all_further_access_to_this_form","���Υ��󥱡��ȤΥե�����͡��ࡣ");
define("_MB_no_spaces","���ڡ��������Բ�");
define("_MB_alpha_numeric_only","Ⱦ�ѱѿ����Τ�");
define("_MB_Owner","�Խ�����");
define("_MB_User_and_Group_that_owns_this_form","���Υ��󥱡��Ȥ����Ϸ�̤������������Ǥ��륰�롼�פ�����");
define("_MB_Respondents","�������롼��");
define("_MB_User_and_Group_that_input_this_form","���Υ��󥱡��Ȥ˲����Ǥ��륰�롼�פ�����");
define("_MB_Title","�����ȥ�");
define("_MB_Title_of_this_form","���󥱡��ȤΥ����ȥ�");
define("_MB_This_appears_at","���󥱡��ȥڡ����Υȥåפ˾��ɽ������ޤ�");
define("_MB_free_form_including_spaces","�����ޤ�ʸ�����");
define("_MB_Subtitle","���֥����ȥ�");
define("_MB_Subtitle_of_this_form","���󥱡��ȤΥ��֥����ȥ�");
define("_MB_Appears_below_the_title","�����ȥ�β���ɽ������ޤ�");
define("_MB_Additional_Info","�ղþ���");
define("_MB_Text_to_be_displayed_on_this_form_before_any_fields","���Ϲ��ܤ�����ɽ�������ʸ�ϡ�����ʸ����HTML�ġ�");
define("_MB_Confirmation_Page","��ǧ�ڡ���");
define("_MB_URL","(URL)");
define("_MB_The_URL_to_which_a_user_is_redirected_after_completing_this_form","���Υ��󥱡��Ȥ�λ������˥�����쥯�Ȥ�����URL������");
define("_MB_OR","�⤷����");
define("_MB_heading_text","�إå�ʸ����");
define("_MB_body_text","��ʸ");
define("_MB_Heading_in_bold","���󥱡��ȴ������ɽ�������ֳ�ǧ�ץڡ����ѤǤ��������������ɽ������ޤ���");
define("_MB_URL_if_present","URL�����������ϡ���ǧ�Υƥ�����ʸ���ξ��ɽ������ޤ���");
define("_MB_Email","�Żҥ᡼��");
define("_MB_Sends_a_copy","������̤���������᡼�륢�ɥ쥹�ʶ���ξ�������̵����");
define("_MB_Theme","�ơ���");
define("_MB_Select_a_theme","���Υ��󥱡��Ȥǻ��Ѥ���ơ��ޡ�CSS�ˤ����򤷤Ƥ�������");
define("_MB_Options","���ץ����");
define("_MB_Allow_to_save","�����Ԥؤ���¸���Ƴ��ε���(������ɬ��)");
define("_MB_Allow_to_forward","�����Ԥإ��󥱡��ȶ��ڤ���ư�������");
define("_MB_Change_the_order","�ꥹ�Ȥ��ֹ���ѹ����뤳�Ȥˤ�ꡢ����ν��֤��¤��ؤ��뤳�Ȥ��Ǥ��ޤ���");
define("_MB_Section_Break","-----�������������-----");
define("_MB_Remove","���");
define("_MB_Edit","�Խ�");
define("_MB_Add_Section_Break","���������������ɲ�");
define("_MB_This_is_a_preview","���󥱡��ȤΥץ�ӥ塼�Ǥ����ѹ��Τʤ��������꽪λ���֤���Խ���λ�����������̤���äƤ���������");
define("_MB_Section","���������");
define("_MB_Previous_Page","���Υڡ���");
define("_MB_SaveAsDefault","�����ͤ������Ȥ�����¸");
define("_MB_Save","��¸����");
define("_MB_Next_Page","���Υڡ���");
define("_MB_Submit_Form","��Ƥ���");
define("_MB_Edit_this_field","���Υե�����ɤ��Խ����뤫���Խ��������ե�����ɤ��ֹ�򥯥�å����Ƥ���������");
define("_MB_Field","�ե������");
define("_MB_Field_Name","�ե�����ɥ͡���");
define("_MB_Type","������");
define("_MB_Length","����ʸ����");
define("_MB_Precision","����ʸ������");
define("_MB_Enter_the_possible_answers","�����κǸ�˼�ͳ��������������ˤ� %s ������ޤ���");
define("_MB_Add_another_answer_line","�������ܤ��ɲ�");
define("_MB_Please_select_a_group","���롼�פ�����Ǥ�������");
define("_MB_Private","�ץ饤�١���");
define("_MB_Form_Access","���󥱡��ȥ�������");
define("_MB_This_lets_you_control","���󥱡��ȤؤΥ��������������Ԥ��ޤ����֥ѥ֥�å��פ�ï�Ǥ⥢����������ǽ�Ǥ����֥ץ饤�١��ȡפ����ꤷ�����롼�פ������������Ǥ��ޤ���");
define("_MB_Note","�Ρ���");
define("_MB_You_must_use","�ץ饤�١��ȥ��󥱡��ȤǤ� %s ����Ѥ��ʤ���Фʤ�ޤ���");
define("_MB_Group","���롼��");
define("_MB_Max_Responses","����쥹�ݥ�");
define("_MB_Save_Restore","��¸���Ƴ�");
define("_MB_Back_Forward","��롿�ʤ�");
define("_MB_Add","�ɲ�");
define("_MB_Make_Public","�ѥ֥�å����ѹ�");
define("_MB_Make_Private","�ץ饤�١��Ȥ��ѹ�");
define("_MB_to_access_this_group","���Υ��롼�פ˥�����������");
define("_MB_Cannot_delete_account","��������Ȥ����Ǥ��ޤ���");
define("_MB_Username_are_required.","�桼�����͡��ࡢ�ѥ���ɡ�����ӥ��롼�פ�ɬ�פǤ�");
define("_MB_Error_adding_account","����������ɲå��顼");
define("_MB_Cannot_change_account_data","��������ȥǡ������ѹ��Ǥ��ޤ���");
define("_MB_Account_not_found","��������Ȥ����Ĥ���ޤ���Ǥ���");
define("_MB_Designer_Account_Administration","�ǥ����ʡ���������ȴ���");
define("_MB_Username","�桼�����͡���");
define("_MB_Password","�ѥ����");
define("_MB_First_Name","̾");
define("_MB_Last_Name","��");
define("_MB_Expiration","��λ");
define("_MB_year","ǯ");
define("_MB_month","��");
define("_MB_day","��");
define("_MB_count","������");
define("_MB_Disabled","�����Բ�");
define("_MB_Update","���åץǡ���");
define("_MB_Cancel","����󥻥�");
define("_MB_Delete","���");
define("_MB_Design_Forms","�ǥ����󥢥󥱡���");
define("_MB_Change_Form_Status","���֤��ѹ�");
define("_MB_Activate_End","����������λ");
define("_MB_Export_Form_Data","���󥱡��ȥǡ����򥨥����ݡ���");
define("_MB_Group_Editor","���롼�ץ��ǥ���");
define("_MB_may_edit","���ꤷ����硢���롼�פǤ��Խ�����ǽ�ˤʤ�ޤ���");
define("_MB_Administer_Group_Members","���롼�ץ��С������");
define("_MB_Administer_Group_Respondents","���롼�ײ����Ԥ����");
define("_MB_Respondent_Account_Administration","�����ԥ�������Ȥδ���");
define("_MB_to_access_this_form","���Υ��󥱡��ȤؤΥ�������");
define("_MB_Error_copying_form","���󥱡��ȤΥ��ԡ����顼");
define("_MB_Copy_Form","���ԡ����󥱡���");
define("_MB_Choose_a_form","���ԡ����ꤿ�����󥱡��Ȥ�����Ǥ������������ԡ��������󥱡��Ȥ��Խ���ǽ�Ǥ����������ˤ�ư���ǧ��ԤäƤ���������");
define("_MB_Status","����");
define("_MB_Archived","�ݴ���");
define("_MB_Ended","�����");
define("_MB_Active","������");
define("_MB_Testing","�ƥ�����");
define("_MB_Editing","�Խ���");
define("_MB_You_are_attempting","���Ϥȥ�������ɽ������٤˼¹Ԥ�����ϤǤ��ޤ���");
define("_MB_Only_superusers_allowed","�����ѡ��桼�����Τ����Ѳ�ǽ�Ǥ���");
define("_MB_No_form_specified","���󥱡��Ȥ����ꤵ��Ƥ��ޤ���");
define("_MB_Manage_Web_Form_Designer_Accounts","���󥱡��ȥǥ����ʡ��δ���");
define("_MB_Click_on_a_username_to_edit","�桼��̾�򥯥�å������Խ����뤫���ʲ��˿������桼�����ɲä��ޤ���");
define("_MB_disabled","�����Բ�");
define("_MB_Add_a_new_Designer","�������ǥ����ʡ����ɲä��Ƥ�������");
define("_MB_Bulk_Upload_Designers","��������Ȥȥ��롼�׾���Υ��åץ���");
define("_MB_Invalid_form_ID","̵���Υ��󥱡���ID�Ǥ�");
define("_MB_DBF_download_not_yet_implemented","DBF��������ɤ�̤��ȯ�Ǥ���");
define("_MB_The_PHP_dBase_Extension_is_not_installed","dBase��ĥ�ϥ��󥹥ȡ��뤵��Ƥޤ���");
define("_MB_Edit_a_Form","���󥱡����Խ�");
define("_MB_Pick_Form_to_Edit","�Խ����������󥱡��Ȥ�����");
define("_MB_Export_Data","�������ݡ��ȥǡ���");
define("_MB_Format","�ե����ޥå�");
define("_MB_CSV","CSV");
define("_MB_download","���������");
define("_MB_DBF","DBF");
define("_MB_HTML","HTML");
define("_MB_Testing_Form","�ƥ��ȥ��󥱡��ȡ�");
define("_MB_SID","SID");
define("_MB_Form_exported_as","���󥱡��ȤΥ������ݡ��ȡ�");
define("_MB_Error_exporting_form_as:","���󥱡��ȤΥ������ݡ��Ȥ˼��Ԥ��ޤ�����");
define("_MB_Error_adding_group","���롼�פ��ɲå��顼");
define("_MB_Error_deleting_group","���롼�פκ�����顼");
define("_MB_Group_is_not_empty","���롼�פ϶��ǤϤ���ޤ���");
define("_MB_Manage_Groups","���롼�״���");
define("_MB_Description","����");
define("_MB_Members","���С�");
define("_MB_Users_guide_not_found","�桼�������������ɤϸ��Ĥ���ޤ���Ǥ���");
define("_MB_Log_back_in","�����Хå�");
define("_MB_Superuser","�����ѡ��桼����");
define("_MB_Choose_a_function","��ǽ������Ǥ�������");
define("_MB_Create_a_New_Form","���������󥱡��Ȥ���");
define("_MB_Edit_an_Existing_Form","���󥱡��Ȥ��Խ�����");
define("_MB_Test_a_Form","���󥱡��Ȥ�ƥ��Ȥ���");
define("_MB_Copy_an_Existing_Form","��¸�Υ��󥱡��Ȥ򥳥ԡ�����");
define("_MB_Change_the_Status_of_a_Form","���󥱡��Ȥξ��֤��ѹ�����");
define("_MB_active_end_delete","(�����ƥ���/��λ/�ݴ�)");
define("_MB_Change_Access_To_a_Form","���󥱡��ȤΥ������������ѹ�����");
define("_MB_Limit_Respondents","����������");
define("_MB_View_Results_from_a_Form","���󥱡��Ȥη�̤򸫤�");
define("_MB_Cross_Tabulate_Form_Results","���󥱡��ȷ�̤Υ�������ɽ��");
define("_MB_View_a_Form_Report","���󥱡��Ȥ����Ƥ�ߤ�");
define("_MB_Export_Data_to_CSV","CSV�ǡ����򸫤�");
define("_MB_Change_Your_Password","���ʤ��Υѥ���ɤ��ѹ����Ƥ�������");
define("_MB_Manage_Designer_Accounts","�ǥ����ʡ���������Ȥδ���");
define("_MB_Manage_Respondent_Accounts","�����ԥ�������Ȥδ���");
define("_MB_View_the_list_of_things_still_to_do","�ޤ����Ƥ��뤳�ȤΥꥹ�Ȥ򸫤Ƥ���������");
define("_MB_development_goals","�ʳ�ȯ�Υ������");
define("_MB_View_the_User_Administrator_Guide","�桼����&�����ԥ����ɤ򸫤�");
define("_MB_Log_out","��������");
define("_MB_SIDS","SIDS");
define("_MB_Error!","���顼��");
define("_MB_You_need_to_select_at_least_two_forms!","����2�ĤΥ��󥱡��Ȥ�����Ǥ���������");
define("_MB_Merge_Form_Results","���󥱡��ȷ�̤Υޡ���");
define("_MB_Pick_Forms_to_Merge","�ޡ������뤿��ˡ����󥱡��Ȥ�����Ǥ�������");
define("_MB_List_of_Forms","���󥱡��ȤΥꥹ��");
define("_MB_Forms_to_Merge","�ޡ������륢�󥱡���");
define("_MB_Change_Password","�ѥ���ɤ��ѹ�");
define("_MB_Your_password_has_been_successfully_changed","���ʤ��Υѥ���ɤ��ѹ�����ޤ���");
define("_MB_Password_not_set","�ѥ���ɤϥ��åȤ���ޤ��󡣺��ޤǤΥѥ���ɤ��ǧ��������");
define("_MB_New_passwords_do_not_match_or_are_blank","�������ѥ���ɤ��������ʤ�������Ǥ���");
define("_MB_Old_Password","�դ뤤�Τ�");
define("_MB_New_Password","�����餷���Τ�");
define("_MB_Confirm_New_Password","��ä���");
define("_MB_Purge_Forms","���󥱡��Ȥξõ�");
define("_MB_This_page_is_not_directly","���Υڡ����ϴ��ʤΤǥᥤ���˥塼����ľ�ܸƤӽФ����ȤϽ���ޤ��󡣤����Ǿõ����硢Ĵ����̤�ޤ�ǡ����١������鴰���˾õ��ޤ����ο���̵�����Ϥ��β��̤Ǥϲ������ʤ��Ǥ����������õ�ܥ���򲡤�����硢�Ƴ�ǧ̵���¹Ԥ�������μ��ʤϤ���ޤ���");
define("_MB_Qs","# �����");
define("_MB_Clear_Checkboxes","�����å��ܥå����򥯥ꥢ");
define("_MB_README_not_found","README�����Ĥ���ޤ���");
define("_MB_Go_back_to_Report_Menu","��ݡ��ȥ�˥塼����äƲ�����");
define("_MB_View_Form_Report","���󥱡��Ȥ����Ƥ򸫤�");
define("_MB_Pick_Form_to_View","���Ƥ򸫤������󥱡��Ȥ�����");
define("_MB_Add_a_new_Respondent","�����������Ԥ��ɲä��Ƥ�������");
define("_MB_Bulk_Upload_Respondents","�����������Ԥ��ɲä��Ƥ�������");
define("_MB_Cross_Tabulation","��������");
define("_MB_Test_Form","�ƥ��ȥ��󥱡���");
define("_MB_Reset","�ꥻ�å�");
define("_MB_Cross_Tabulate","����������");
define("_MB_View_Form_Results","���󥱡��ȷ�̤򸫤�");
define("_MB_Pick_Form_to_Cross_Tabulate","�������֤�ɽ�����륢�󥱡��Ȥ�����");
define("_MB_Respondent","������");
define("_MB_Resp","�쥹�ݥ�");
define("_MB_Can_not_set_form_status","���󥱡��ȥ��ơ�����������Ǥ��ޤ���");
define("_MB_Form_Status","���󥱡��ȥ��ơ�����");
define("_MB_Test_transitions","<b>�֥ƥ��ȡ�</b>�ĥƥ��ȥ⡼�ɤǤ������󥱡��ȤΥƥ��Ȥ��̤�ɽ������ǽ�Ǥ����ƥ��ȥ⡼�ɻ��ϥ��󥱡��Ȥ��Խ����뤳�Ȥ��Ǥ��ޤ���");
define("_MB_Activate_transitions","<b>�֥����ƥ��֡�</b>�ĥ����ƥ��֥⡼�ɤǤ������Υ⡼�ɼ¹���ϥ��󥱡��Ȥ��ºݤ˲�ư���Ƥ��ޤ����ƥ��ȥ⡼�ɻ��Υ��󥱡��ȷ�̤�ȿ�Ǥ���ޤ��󡣥����ƥ��֥⡼�ɼ¹Ը塢���󥱡��Ȥ��Խ��ϰ��ڤǤ��ޤ���");
define("_MB_End_transitions","<b>�ֽ�λ��</b>�Ĳ�ư��Υ��󥱡��Ȥ�λ���ޤ������Υ⡼�ɼ¹Ը�ϥ��󥱡��Ȥ��뤳�Ȥ��Ǥ��ޤ��󡣷�̤ϴ�����˥塼����ɽ����ǽ�Ǥ���");
define("_MB_Archive_removes","<b>���ݴɡ�</b>�ĥ��󥱡��Ȥ������ޤ����ǡ����ϥǡ����١����˻Ĥ�ޤ������ʸ���ڤ����Բ�ǽ�ˤʤ�ޤ����ޤ������������֤��줿���󥱡��Ȥη�̤ϸ��뤳�Ȥ��Ǥ��ޤ���");
define("_MB_Test","�ƥ���");
define("_MB_Activate","����");
define("_MB_End","���");
define("_MB_Archive","�ݴ�");
define("_MB_No_tabs_defined_Please_check_your_INI_settings","̵���ʥ��֡�INI���������å����Ƥ�������");
define("_MB_Help","�إ��");
define("_MB_General","���󥱡�������");
define("_MB_Questions","�������");
define("_MB_Order","������");
define("_MB_Preview","�ץ�ӥ塼");
define("_MB_Finish","���꽪λ");
define("_MB_Click_cancel_to_cancel","���Υ��󥱡��Ȥ򥭥�󥻥뤹����ϡ�Cancel�פ򥯥�å����Ƥ����������٤Υ��֤˿ʤ���ϡ�Continue�פ򥯥�å����Ƥ���������");
define("_MB_The_form_title_and_other","���󥱡��ȥ����ȥ뤪���¾�ΰ��̾���Υե�����ɤϡ�<b>�֥��󥱡��������</b>���֤ˤ���ޤ��� <b>�ּ��������</b>���֤�������ɲá������򤹤뤳�Ȥ��Ǥ��ޤ��� <b>�ּ�������</b>���֤���ϼ�����Խ��������Ԥ��ޤ���<b>�֥ץ�ӥ塼��</b>���֤Ϻ����������󥱡��ȤΥץ�ӥ塼��Ԥ��ޤ����ѹ��Τʤ�����<b>�����꽪λ��</b>���֤���Խ���λ�����������̤���äƤ���������");
define("_MB_Click_here_to_open_the_Help_window","�إ�ץ�����ɥ��򳫤�");
define("_MB_View_Results","��̤򸫤�");
define("_MB_Pick_Form_to_Test","�ƥ��Ȥ��륢�󥱡��Ȥ�����");
define("_MB_Export","�������ݡ���");
define("_MB_Results","���");
define("_MB_Todo_list_not_found","Todo�ꥹ�Ȥϸ��Ĥ���ޤ���Ǥ���");
define("_MB_An_error_Rows_that_failed_are_listed_below","�ʲ��ꥹ�ȤΥ��åץ��ɥ��顼�Ǥ���");
define("_MB_An_error_Please_check_the_format_of_your_text_file","���åץ�����˥��顼��ȯ�����ޤ������ƥ����ȥե�����Υե����ޥåȤ��ǧ���Ƥ���������");
define("_MB_An_error_Please_complete_all_form_fields","���åץ�����˥��顼��ȯ�����ޤ��������ƤΥ��󥱡��ȥե�����ɤ˵�������������");
define("_MB_Upload_Account_Information","��������Ⱦ���򥢥åץ��ɤ��ޤ�����");
define("_MB_All_fields_are_required","�ޡ����μ����ɬ�ܹ��ܤǤ���");
define("_MB_File_Type","�ե����륿����");
define("_MB_Tab_Delimited","���ֶ��ڤ�");
define("_MB_File_to_upload","���åץ��ɥե�����");
define("_MB_Thank_You_For_Completing_This_Form","���������꤬�Ȥ��������ޤ�����");
define("_MB_Please_do_not_use_the_back_button","�֥饦�������ܥ���򲡤��ʤ��ǲ�����");
define("_MB_Unable_to_find_the_phpESP_%s_directory_\t\t\tPlease_check_%s_to_ensure_that_all_paths_are_set_correctly","");
define("_MB_Gettext_Test_Failed","GetText�Υ��顼�Ǥ�");
define("_MB_Form_not_specified","���顼�����󥱡��Ȥ����򤵤�Ƥ��ޤ���");
define("_MB_Form_is_not_active","��ա����Υ��󥱡��ȤϤޤ���������Ƥ��ޤ���");

define("_MB_Sorry_the_account_request_form_is_disabled","��������Ƚ����ϸ��߶ػߤ���Ƥ��ޤ���");
define("_MB_Please_complete_all_required_fields","���Ƥ�ɬ�����Ϥι��ܤ˲�����������");
define("_MB_Passwords_do_not_match","�ѥ���ɤ��㤤�ޤ�");
define("_MB_Request_failed,_please_choose_a_different_username","�ꥯ�����ȼ��ԡ��㤦�桼���������򲼤�����");
define("_MB_Your_account_has_been_created","���ʤ��Υ�������ȤϺ�������ޤ�����");
define("_MB_Account_Request_Form","����������׵ᥢ�󥱡���");
define("_MB_Please_complete_the_following","�ʲ����ѻ�˵������ơ���������Ȥ��׵ᤷ�Ƥ��������� %s �ǥޡ������줿���ܤ�ɬ�ܤǤ���");
define("_MB_Email_Address","�Żҥ᡼�륢�ɥ쥹");
define("_MB_Confirm_Password","�ѥ���ɤκƳ�ǧ");


define('FORMMAKEX_INDEX_PAGETITLE', '���󥱡��Ȱ���');
define('_MB_LIST_TITLE', '�����ȥ�');
define('_MB_LIST_SUBTITLE', '���֥����ȥ�');
define('_MB_LIST_NAME', '���󥱡���̾');
define('_MB_LIST_OWNER', '������');
define('_MB_LIST_UPDATE', '��������');
define('_MB_LIST_SUBMITTED', '�����Ѥ�');
define('_MB_LIST_SUBMITTED_DESC', '���Υ��󥱡��ȤϤ��Ǥ˲������Ƥ��ޤ���');
define('_MB_LIST_COL_DATA', '���󥱡��Ⱦ���');
define('_MB_LIST_COL_RESULTS', '���');
define('_MB_LIST_COL_RESULTS_RESPONDENTS', '�����Կ�');
define('_MB_LIST_COL_RESULTS_ANALYZE', 'ʬ��');
define('_MB_LIST_COL_RESULTS_SPREADSHEET', '����ɽ');
define('_MB_LIST_COL_RESULTS_CROSS', '�������ס�����ʬ��');
define('_MB_LIST_COL_RESULTS_DOWNLOAD', '���������');
define('_MB_LIST_COL_CONTROL', '����');
define('_MB_LIST_COL_CONTROL_MODIFY', '�ѹ�');
define('_MB_LIST_COL_CONTROL_STATUS', '���֤δ���');
define('_MB_LIST_COL_CONTROL_ACCESS', '����');
define('_MB_LIST_COL_CONTROL_ACCESS_PUBLIC', '���̸���');
define('_MB_LIST_COL_CONTROL_ACCESS_LIMITED', '�������');
define('_MB_LIST_COL_CONTROL_ACCESS_2PUBLIC', '���̸���');
define('_MB_LIST_COL_CONTROL_ACCESS_2LIMITED', '�������');
define('_MB_LIST_COL_CONTROL_ACCESS_SETPERM', '��������������');
define('_MB_LIST_COL_CONTROL_COPY', '���ԡ�');
define('_MB_LIST_COL_CONTROL_EDIT', '�Խ�');
define('FORMMAKEX_TABS_QUESTIONS_QUESTION_CONTENT', '����ʸ');
define('FORMMAKEX_TABS_QUESTIONS_FIELD_NUM', '�ե�������ֹ�');
define('FORMMAKEX_TABS_QUESTIONS_FIELD_CONTROL', '�ե���������');
define('FORMMAKEX_TABS_QUESTIONS_QUESTION_INFOS', '���������');
define('FORMMAKEX_TABS_QUESTIONS_CHOICES_INFOS', '����������');
define('FORMMAKEX_TABS_QUESTIONS_CHOICES_DESC', '�����κǸ�˼�ͳ��������������ˤ� !other ������ޤ�');
define('FORMMAKEX_TABS_ORDER_COL_NAME_TYPE', '�ե������̾���ե�����ɥ�����');
define('FORMMAKEX_TABS_ORDER_COL_MOVE', '����ѹ�');
define('FORMMAKEX_TABS_ORDER_COL_EDIT', '�Խ�');
define('FORMMAKEX_TABS_ORDER_COL_REMOVE', '���');

define('FORMMAKEX_QTYPE_1','�Ϥ�/������');
define('FORMMAKEX_QTYPE_2','�ƥ����ȡ��ܥå���');
define('FORMMAKEX_QTYPE_3','�ƥ����ȡ����ꥢ');
define('FORMMAKEX_QTYPE_4','ñ����ܤ�����');
define('FORMMAKEX_QTYPE_5','ʣ�����ܤ�����');
define('FORMMAKEX_QTYPE_6','�ɥ�åץ����󡦥ꥹ�Ȥ��飱������');
define('FORMMAKEX_QTYPE_7','Rating');
define('FORMMAKEX_QTYPE_8','���ʳ�ɾ�����飱������');
define('FORMMAKEX_QTYPE_9','��������');
define('FORMMAKEX_QTYPE_10','��������');
define('FORMMAKEX_QTYPE_40','ź�եե�����');
define('FORMMAKEX_QTYPE_99','���ڡ���');
define('FORMMAKEX_QTYPE_100','���Ф������ȥ�');

}

?>