<?php
require_once('../../Config.php');
require_once('../../constants.php');
require_once('../../commonObject.php');
include("log_request.php");

require_once('../../campaignManager.php');
$campaignObject = new CampaignManager();

//log_request("Postback.php ");
$results = $campaignObject->save_call($_REQUEST,1,1);
$options = $campaignObject->get_postback_options($results['mt_id']);
//log_request("postback.php ***".$options['mt_id'].'***'.$options['tid']."***".$options['offer_url']."***".$options['call_conversion_criteria']);

$is_postback = false;
if(isset($_REQUEST['DialCallDuration']))
{
    $call_conversion_criteria = ($options['call_conversion_criteria'] == "") ? 0 : $options['call_conversion_criteria'] ;
	$chk_conversion_days = $campaignObject->chk_conversion_days($options['camp_id'],$_REQUEST['From']);
    if($chk_conversion_days && $_REQUEST['DialCallDuration'] >= $call_conversion_criteria)
        $is_postback = true;
}
//log_request($is_postback);
if($is_postback)
{
	$campaignObject->postback_hasoffer($results['call_id']);
    // log_request("in is postback");
    /// for postback to hasoffer working
    $post = curl_init();
    //$url = str_replace("{transaction_id}", "{".$options['tid']."}", $options['offer_url']);
    $url = str_replace("{transaction_id}", $options['tid'], $options['offer_url']);	
    //$url = $options['offer_url']."&transaction_id={$options['tid']}"; //"http://tracking.mobilesecuredtrack.com/aff_lsr?transaction_id={$tid}";
    curl_setopt($post, CURLOPT_URL, $url);
    //curl_setopt($post, CURLOPT_POST, count($data));
    //curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
    //log_request("curl result :".$result." url :".$url);
    /////////////////////////////////////
}
header("content-type:application/xml");
?>
<Response></Response>