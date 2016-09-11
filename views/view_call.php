<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
	exit();
}
?>
<input type="hidden" name="mainfunction" id="mainfunction" value="<?php if(isset($data['mainfunction'])) echo $data['mainfunction']; else  echo $mainfunction; ?>" />
<input type="hidden" name="subfunction_name" id="subfunction_name" value="<?php if(isset($data['subfunction_name'])) echo $data['subfunction_name']; else echo $subfunction_name; ?>" />
<!-- Main Container-->
<div class="container" id="actualbody">
	<div class="row clearfix">
    <div class="col_12">
    	<div class="widget clearfix">
    		<h2>Call Details</h2>
           <?php
				if(isset($notificationArray))
					populateNotification($notificationArray);
            ?>
            <div class="row clearfix">
            	<div class="col_4" style="margin: 1% 1.3%;">
                    <div class="widget clearfix">
                        <h2>Call Information (<?php echo $data['campaign']['name'];?>)</h2>
                        <div class="widget_inside">
                        	<div class="form" style="border:none" >
                            	 <div class="clearfix ">                        
                                    <label class="view_lable">Mtid :</label>
                                    <div class="input">
                                        <?php echo $data['call']['mt_id']; ?>
                                    </div>
                                 </div>
                                 <div class="clearfix ">                        
                                    <label class="view_lable">Call From :</label>
                                    <div class="input">
                                        <?php echo $data['call']['CallFrom']; ?>
                                    </div>
                                </div>
                                 <div class="clearfix ">
                                    <label class="view_lable">Call To :</label>
                                    <div class="input">
                                       <?php echo $data['call']['CallTo']; ?>
                                    </div>
                                </div>
                                <div class="clearfix ">
                                    <label class="view_lable">Call Start Time :</label>
                                    <div class="input">	
                                        <?php echo $data['call']['CallStartTime']; ?>
                                    </div>    
                                </div>
                                <div class="clearfix ">
                                    <label class="view_lable">City :</label>
                                    <div class="input">
                                        <?php echo $data['call']['ToCity']; ?> 
                                    </div>
                                </div>
                                <div class="clearfix ">
                                    <label class="view_lable">Country :</label>
                                    <div class="input">
                                       	<?php echo $data['call']['ToCountry']; ?>
                                    </div>
                                </div>
                                 <div class="clearfix ">
                                    <label class="view_lable">Zip :</label>
                                    <div class="input">
                                       	<?php echo $data['call']['ToZip']; ?>
                                    </div>
                                </div>
                                 <div class="clearfix ">
                                    <label class="view_lable">Call Duration :</label>
                                    <div class="input">
                                       	<?php echo $data['call']['CallDuration']; ?> seconds
                                    </div>
                                </div>
                                 <div class="clearfix ">
                                    <label class="view_lable">Recording URL :</label>
                                    <div class="input">
                                    	<a href="<?php echo $data['call']['RecordingUrl']; ?>" target="_blank">
                                       		<?php echo $data['call']['RecordingUrl']; ?>
                                        </a>
                                    </div>
                                </div>								
                              </div>
                        </div>
                    </div>
                </div>
                <div class="col_4" style="margin: 1% 1.3%;">
                    <div class="widget clearfix">
                        <h2>MaxMind Details</h2>
                        <div class="widget_inside">
                        	<div class="form" style="border:none" >
                            	<?php if($data['click_location'] != "") {?>
                            	 <div class="clearfix ">                        
                                    <label class="view_lable">Country Code :</label>
                                    <div class="input">
                                        <?php echo $data['click_location']['country_code']; ?>
                                    </div>
                                 </div>
                                 <div class="clearfix ">                        
                                    <label class="view_lable">Country Name :</label>
                                    <div class="input">
                                        <?php echo $data['click_location']['country_name']; ?>
                                    </div>
                                </div>
                                 <div class="clearfix ">
                                    <label class="view_lable">Region Name :</label>
                                    <div class="input">
                                       <?php echo $data['click_location']['region_name']; ?>
                                    </div>
                                </div>                                
                                <div class="clearfix ">
                                    <label class="view_lable">City Name :</label>
                                    <div class="input">
                                       <?php echo $data['click_location']['city_name']; ?>
                                    </div>
                                </div>
                                <div class="clearfix ">
                                    <label class="view_lable">Metro Code :</label>
                                    <div class="input">
                                       <?php echo $data['click_location']['metro_code']; ?>
                                    </div>
                                </div>
                                 <div class="clearfix ">
                                    <label class="view_lable">Area Code :</label>
                                    <div class="input">
                                       <?php echo $data['click_location']['area_code']; ?>
                                    </div>
                                </div>
                                <div class="clearfix ">
                                    <label class="view_lable">Postal Code :</label>
                                    <div class="input">
                                       <?php echo $data['click_location']['postal_code']; ?>
                                    </div>
                                </div>
                                <div class="clearfix ">
                                    <label class="view_lable">Domain :</label>
                                    <div class="input">
                                       <?php echo $data['click_location']['domain']; ?>
                                    </div>
                                </div>
                                <div class="clearfix ">
                                    <label class="view_lable">User Type :</label>
                                    <div class="input">
                                       <?php echo $data['click_location']['user_type']; ?>
                                    </div>
                                </div>
                               <?php } ?> 						
                              </div>
                        </div>
                    </div>
                </div>
                <div class="col_4 last" style="margin: 1% 1.3%;">
                    <div class="widget clearfix">
                        <h2>Survey Details</h2>
                        <div class="widget_inside">
							<div class="form" style="border:none" >
                        	<?php
								if(isset($data['survey_info']) && $data['survey_info'] != "")
								{
									foreach($data['survey_info'] as $k=>$v){
										echo "<div class='clearfix'>
												<label class='view_lable'>".ucfirst($k)."</label>
												<div class='input'>
												   {$v}
												</div>
											</div>";
									}
								}
								foreach($data['survey_details'] as $question){
									echo "<div class='clearfix'>
											<label class='view_lable'>{$question['question']}</label>
											<div class='input'>
											   {$question['answer']}
											</div>
										</div>";
								} 
							?>
							</div>
                        </div>
                    </div>
                </div>
              </div>
              <div class="col_12" style="margin: 1% 1.3%; width:97%;">
                    <div class="widget clearfix">
                        <h2>Click Details</h2>
                        <div class="widget_inside">
                        
                         <div class="col_6" style="margin: 1% 1.3%;">
							<div class="form" style="border:none" >
                        		<!-- <div class="clearfix ">
                                    <label class="view_lable">SubId :</label>
                                    <div class="input">
                                       < ?php echo $data['click']['sub_id']; ?>
                                    </div>
                                </div>-->
                                <div class="clearfix ">
                                    <label class="view_lable">Tid :</label>
                                    <div class="input">
                                       <?php echo $data['click']['tid']; ?>
                                    </div>
                                </div>
                                <div class="clearfix ">
                                    <label class="view_lable">Ip Address :</label>
                                    <div class="input">
                                       <?php echo $data['click']['ip_address']; ?>
                                    </div>
                                </div>
                                <div class="clearfix ">
                                    <label class="view_lable">User Agent :</label>
                                    <div class="input">
                                       <?php echo $data['click']['user_agent']; ?>
                                    </div>
                                </div>
                                <div class="clearfix ">
                                    <label class="view_lable">Click Time :</label>
                                    <div class="input">
                                       <?php if($data['click']['click_time'] != "0000-00-00 00:00:00") echo date('Y-m-d H:i:s', strtotime($data['click']['click_time'])); ?>
                                    </div>
                                </div>
							</div>
                          </div>
                          
                          <div class="col_4" style="margin: 1% 1.3%;">
							<div class="form" style="border:none" >
                        		 <div class="clearfix ">
                                    <label class="view_lable">Brand Name :</label>
                                    <div class="input">
                                       <?php echo $data['device_details']['brand_name']; ?>
                                    </div>
                                </div>
                                <div class="clearfix ">
                                    <label class="view_lable">Model Name :</label>
                                    <div class="input">
                                       <?php echo $data['device_details']['model_name']; ?>
                                    </div>
                                </div>
                                <div class="clearfix ">
                                    <label class="view_lable">Marketing Name :</label>
                                    <div class="input">
                                       <?php echo $data['device_details']['marketing_name']; ?>
                                    </div>
                                </div>
							</div>
                          </div>
                            
                        </div>
                    </div>
                </div>
              	<div class="clearfix">
                    <div class="input no-label">
                        <input type="button" class="button blue" value="Back" onClick="goBack()" style="margin-left:40%;"></input>
                    </div>
                </div>            	        
            </div>            
         
		</div>
    </div>
</div>
<!-- END Main Container-->