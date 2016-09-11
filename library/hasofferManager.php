<?php 
// This class is written to handle all hasoffer functionalities
// First check authentication details of hasoffer defined in gethasofferCreds function
class hasofferManager
{
		
	// gethasofferCreds : Use this method to get has offer credentials 
	function gethasofferCreds() 
	{
		$hasoffer_cred = array();
		$hasoffer_cred['NetworkID'] = "mobilefused";
		$hasoffer_cred['APIKey'] = "NETgfHZHdCYQoxd33tOD00ZngerBmr";
		$hasoffer_cred['APIDomain'] = "http://mobilefused.api.hasoffers.com";
		$hasoffer_cred['base'] = $this->getBaseUrl();
		return $hasoffer_cred;
	}
	
	// getBaseUrl : Use this method to get base url for all has offer requests
	function getBaseUrl()
	{
		$base = 'https://api.hasoffers.com/Api?';
		return $base;
	}
	
	// makeRequest: Use this method to make any request
	function makeRequest($extra_params)
	{
		$hasoffer_cred = $this->gethasofferCreds();
		
		$params = array(
			'Format' => 'json'
			,'Target' => 'Application'
			,'Service' => 'HasOffers'
			,'Version' => 2
			,'NetworkId' => $hasoffer_cred['NetworkID']
			,'NetworkToken' => $hasoffer_cred['APIKey']			
		);
		$arr_params = array_merge($params, $extra_params);
		//echo "<pre>"; print_r($arr_params); exit;		
		$url = $hasoffer_cred['base'] . http_build_query( $arr_params );
		$result = file_get_contents( $url );
		$result = json_decode( $result );
		return $result;		
	}
	
	// authentication : Use this method to check authentication 
	function authentication()
	{	
		$hasoffer_cred = $this->gethasofferCreds();	
		$params = array(
						'Method' => 'validNetworkApiKey',
						'api_key' => $hasoffer_cred['APIKey']
						); 	
		$result = $this->makeRequest($params);
		$result = $this->object_to_array($result);				 		
		return $result;
	}
	
	// addWhiteListIP : Use this method to add IP address to whitelist
	function addWhiteListIP($ip)
	{	
		$hasoffer_cred = $this->gethasofferCreds();	
		$params = array(
						'Method' => 'whitelistNetworkApiIp',
						'ip_address' => $ip
						);
		$result = $this->makeRequest($params);	
		$result = $this->object_to_array($result);			 		
		return $result;	
	}
	
	// getAllCampaign :  Use this method to get all campaigns
	function getAllCampaign()
	{		
		$params = array(
					'Target' => 'AdManager'	,				
					'Method' => 'findAllCampaigns'															
				);
		$result = $this->makeRequest($params);
		$result = $this->object_to_array($result);				 		
		return $result;	
	}
	
	// createCampaign : Use this method to create campaign
	// name : name of the campaign
	// type : Type of campaign to create *** Values: banner, text 
	// height : Only required for campaigns with the type parameter 'banner'. Specifics height used to display the ad tag
	// width : Only required for campaigns with the type parameter 'banner'. Specifics width used to display the ad tag.
	// interface :  Type of account to attached campaign. *** Values: affiliate, network 
	// account_id : Id of affiliate if interface parameter is 'affiliate' or blank if interface parameter is 'network'
	// status : Values: active, paused, deleted     
	function createCampaign($data=array())
	{
		$data = array(
						'name' => 'New Campaign Test'
						,'type' => 'banner'
						,'interface' => 'network'
						,'status' => 'active'
						,'height' => '300'
						,'width' => '250'
					);
		$params = array(
					'Target' => 'AdManager',				
					'Method' => 'createCampaign',
					'data' => $data					
				);
		$result = $this->makeRequest($params);
		$result = $this->object_to_array($result);				 		
		return $result;			
	}
	
	// deleteCampaign : Use this method to delete campaign
	function deleteCampaign($id)
	{
		$params = array(
					'Target' => 'AdManager',
					'Method' => 'updateCampaign',
					'id' => $id,
					'data' => array(
						'status' => 'deleted'						
					)
				);
		$result = $this->makeRequest($params);
		$result = $this->object_to_array($result);				 		
		return $result;
	}
	
	// updateCampaignField :  Use this method to update campaign field
	// $id : id of the campaign which is require to be updated
	// field : field which is to be updated
	// $value : value set to that field
	function updateCampaignField($id, $field,$value)
	{
		$params = array(
					'Target' => 'AdManager',					
					'Method' => 'updateCampaignField',
					'id' => $id,
					'field' => $field,
					'value' => $value
				);
		$result = $this->makeRequest($params);
		$result = $this->object_to_array($result);				 		
		return $result;
	}
	
	// getCampaign : get all campaign details by id
	function getCampaign($id)
	{
		//echo $id;
		$params = array(
					'Target' => 'AdManager',					
					'Method' => 'findCampaignById',
					'id' => $id					
				);
		$result = $this->makeRequest($params);
		$result = $this->object_to_array($result);			
		return $result;
	}
	
	//function to add the offer whitelist ip address
	//$ipAddresses = array('123.237.73.47','115.124.127.224');
	//$offerID = array(32,34,44); 
	/*function addWhitelistIP($NetworkID, $NetworkToken, $ApiDomain, $offerID, $ipAddresses){
		$base = $ApiDomain.'/Api?';
	
		$params = array(
		'Format' => 'json'
		,'Target' => 'OfferWhitelist'
		,'Method' => 'create'
		,'Service' => 'HasOffers'
		,'Version' => 2
		,'NetworkId' => $NetworkID
		,'NetworkToken' => $NetworkToken
		,'data' => array(
			'offer_id' => $offerID
			,'type' => 'postback'
			,'content_type' =>'ip_address'
			,'content' => $ipAddresses
		)
		);
	
		$url = $base . http_build_query( $params );
		$result = file_get_contents( $url );
		$array = json_decode($result, true);
		$array = $array['response'];
		return $array;
	}*/
	
	// function for object to array conversion
	function object_to_array($data)
	{
		if (is_array($data) || is_object($data))
		{
			$result = array();
			foreach ($data as $key => $value)
			{
				$result[$key] = $this->object_to_array($value);
			}
			return $result;
		}
		return $data;
	}
	
	
	
	
}
?>