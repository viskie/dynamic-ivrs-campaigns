<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
}
$PAGE_LEVELS=array('1','2','3','4');

$SUPER_LEVEL_ACCESS = array('1','2');

define('developer_grpid','1');
define('DOC_ROOT',$_SERVER['DOCUMENT_ROOT'].'/mTrack/');
define('HTTP_PATH','http://vishak.com/mTrack/');
define('MaxMind_licensekey','DxdKyleQ6k90');

define('SALT','HG@#GG'); // If you make change in this, Apply change in index.php also
// for changing msgs for add, edit, delete, make changes in function of name 'showmsg' written in commonFunction.php
$arr_msg = array(
			'group' => array(
						'delpermission' => "You do not have permission to delete this group!"				
				)
			);