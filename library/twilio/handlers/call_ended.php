<?php 
require_once('../../Config.php');
require_once('../../constants.php');
require_once('../../commonObject.php');
include("log_request.php");

require_once('../../campaignManager.php');
$campaignObject = new CampaignManager();

$mt_id = $campaignObject->save_call($_REQUEST,1);
$options = $campaignObject->get_postback_options($mt_id);
$is_postback = $campaignObject->check_is_postback($mt_id);

//log_request("call_ended.php ***".$mt_id."***".$options['tid']."***".$options['offer_url']."***".$options['call_conversion_criteria']);
//log_request("call_ended.php ***".$mt_id);

/*$is_postback = false;
if(isset($_REQUEST['CallDuration']))
{
	$call_conversion_criteria = ($options['call_conversion_criteria'] == "") ? 0 : $options['call_conversion_criteria'] ;
	if($_REQUEST['CallDuration'] >= $call_conversion_criteria)
		$is_postback = true;
}

if($is_postback)
{
	$campaignObject->update_is_postback($mt_id);
	/// for postback to hasoffer working
	$post = curl_init();
	$url = str_replace("{transaction_id}", "{".$options['tid']."}", $options['offer_url']);
	log_request("postback url :".$url);
	//$url = $options['offer_url']."&transaction_id={$options['tid']}"; //"http://tracking.mobilesecuredtrack.com/aff_lsr?transaction_id={$tid}";
	curl_setopt($post, CURLOPT_URL, $url);
	//curl_setopt($post, CURLOPT_POST, count($data));
	//curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($post);
	/////////////////////////////////////
}*/

?>