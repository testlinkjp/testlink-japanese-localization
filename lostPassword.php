<?php
/**
 * TestLink Open Source Project - http://testlink.sourceforge.net/ 
 * This script is distributed under the GNU General Public License 2 or later. 
 *
 * Filename $RCSfile: lostPassword.php,v $
 *
 * @version $Revision: 1.29 $
 * @modified $Date: 2008/11/18 20:54:42 $ $Author: schlundus $
 *
 * rev: 20080212 - franciscom - fixed minor bug on call to logAuditEvent
**/
require_once('config.inc.php');
require_once('common.php');
require_once('users.inc.php');
require_once('email_api.php');

$_POST = strings_stripSlashes($_POST);
$login = isset($_POST['login']) ? $_POST['login']: null;

$op = doDBConnect($db);
//@TODO: schlundus, this kind of code should be contained within doDBConnect!
if ($op['status'] == 0)
{
	$smarty = new TLSmarty();
	$smarty->assign('title', lang_get('fatal_page_title'));
	$smarty->assign('msg', $op['dbms_msg']);
	$smarty->display('fatal_error.tpl');
	exit();
}
$note = lang_get('your_info_for_passwd');
if (strlen($login))
{
	$userID = tlUser::doesUserExist($db,$login);
	if (!$userID)
		$note = lang_get('bad_user');
	else
	{
		$result = resetPassword($db,$userID,$note);
		if ($result >= tl::OK)
		{
		  	$user = new tlUser($userID);
		  	if ($user->readFromDB($db) >= tl::OK)
		  		logAuditEvent(TLS("audit_pwd_reset_requested",$user->login),"PWD_RESET",$userID,"users");
			redirect(TL_BASE_HREF ."login.php?note=lost");
			exit();
		}
		else if ($result == tlUser::E_EMAILLENGTH)
			$note = lang_get('mail_empty_address');
		else if (!strlen($note))
			$note = getUserErrorMessage($result);
	}
}

$smarty = new TLSmarty();
$smarty->assign('note',$note);
$smarty->assign('external_password_mgmt',tlUser::isPasswordMgtExternal());
$smarty->assign('page_title',lang_get('page_title_lost_passwd'));
$smarty->display('loginLost.tpl');
?>
