<?php
/**
 * TestLink Open Source Project - http://testlink.sourceforge.net/ 
 * This script is distributed under the GNU General Public License 2 or later. 
 *
 * @filesource	attachmentdownload.php
 *
 *
 * Downloads the attachment by a given id
 */
@ob_end_clean();
require_once('../../config.inc.php');
require_once('../functions/common.php');
require_once('../functions/attachments.inc.php');
testlinkInitPage($db);

$args = init_args();
checkRights($db,$_SESSION['currentUser'],$args);


if ($args->id)
{
	$attachmentRepository = tlAttachmentRepository::create($db);
	$attachmentInfo = $attachmentRepository->getAttachmentInfo($args->id);
	if ($attachmentInfo && checkAttachmentID($db,$args->id,$attachmentInfo))
	{
		$content = $attachmentRepository->getAttachmentContent($args->id,$attachmentInfo);
		if ($content != "")
		{
			@ob_end_clean();
			header('Pragma: public');
			header("Cache-Control: ");
			if (!(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on" && preg_match("/MSIE/",$_SERVER["HTTP_USER_AGENT"])))
				header('Pragma: no-cache');
			header('Content-Type: '.$attachmentInfo['file_type']);
			header('Content-Length: '.$attachmentInfo['file_size']);
			header("Content-Disposition: attachment; filename=\"{$attachmentInfo['file_name']}\"");
			header("Content-Description: Download Data");
			echo $content;
			exit();
		}
	}
}
$smarty = new TLSmarty();
$smarty->display('attachment404.tpl');

/**
 * @return object returns the arguments for the page
 */
function init_args()
{
	//the id (attachments.id) of the attachment to be downloaded
	$iParams = array(
		"id" => array(tlInputParameter::INT_N),
	);
	$args = new stdClass();
	G_PARAMS($iParams,$args);
	
	// BUGID 4066 - take care of proper escaping when magic_quotes_gpc is enabled
	$_REQUEST=strings_stripSlashes($_REQUEST);

	return $args;
}

/**
 */
function checkRights(&$db,&$userObj,$argsObjs)
{
	if(!(config_get("attachments")->enabled))
	{
		redirect($_SESSION['basehref'],"top.location");
		exit();
	}
}
?>