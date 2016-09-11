<?php
include("campaign_helper.php"); 
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
	exit();
}
?>
<input type="hidden" id="campaign_id" name="campaign_id" value="<?php if(isset($data['arr_ivrs'])) echo $data['arr_ivrs']['camp_id']; ?>"/>
<input type="hidden" id="edit_id" name="edit_id" value="<?php if(isset($data['arr_ivrs'])) echo $data['arr_ivrs']['camp_id']; ?>"/>
<input type="hidden" name="mainfunction" id="mainfunction" value="<?php if(isset($data['mainfunction'])) echo $data['mainfunction']; else  echo $mainfunction; ?>" />
<input type="hidden" name="subfunction_name" id="subfunction_name" value="<?php if(isset($data['subfunction_name'])) echo $data['subfunction_name']; else echo $subfunction_name; ?>" />

<!-- Main Container-->
<div class="container" id="actualbody">
	<div class="row">
        <div class="widget clearfix tabs">
            <ul>
                <li><h2><a id="tab_title1" href="#tab-1">Step 1</a></h2></li>
                <li><h2><a id="tab_title2" href="#tab-2">Step 2</a></h2></li>
                <li><h2><a id="tab_title3" href="#tab-3">Step 3</a></h2></li>
                <li><h2><a id="tab_title4" href="#tab-4">Step 4</a></h2></li>
            </ul>
            <div class="widget_inside">
            	<div id="tab-1">
                	<div class="row">
        				<div class="widget clearfix">
                    		<div class="widget_inside">
                    			<div class="form" style="border:none" >
                         			<div class="clearfix no_border less_padding">                        
                            			<label>Campaign Name<span class="mandatory">*</span></label>
                            			<div class="input">
                                			<input type="text"  id="name" name="name" maxlength="" class="xlarge" value="<?php if(isset($data['arr_ivrs'])){ echo $data['arr_ivrs']['name']; } ?>"/>
                            			</div>
                        			</div>
                                    <div class="clearfix no_border less_padding">                        
                            			<label>Postback Link (URL)<span class="mandatory">*</span></label>
                            			<div class="input">
                                			<input type="text"  id="offer_url" name="offer_url" maxlength="" class="xlarge" value="<?php if(isset($data['arr_ivrs'])){ echo $data['arr_ivrs']['offer_url']; } ?>"/>
                            			</div>
                        			</div>
                                    <div class="clearfix no_border less_padding">
                                        <label>Campaign Description</label>
                                        <div class="input">
                                        	<textarea  id="description" name="description" class="xxlarge"><?php if(isset($data['arr_ivrs'])){ echo $data['arr_ivrs']['description']; } ?></textarea>
                                         </div>
                                    </div>
                                    <div class="clearfix no_border less_padding">
                                        <label>Status<span class="mandatory">*</span></label>
                                        <div class="input">
                                        	<?php $selected = "selected='selected'";?>
                                            <select id="status" name="status">
                                            	<option value="">Please Select</option>
                                                <option value="1" <?php if(isset($data['arr_ivrs'])) { if($data['arr_ivrs']['status'] == 1) echo $selected; }?>>Active</option>
                                                <option value="2" <?php if(isset($data['arr_ivrs'])) { if($data['arr_ivrs']['status'] == 2) echo $selected; }?>>Inactive</option>
                                                <option value="3" <?php if(isset($data['arr_ivrs'])) { if($data['arr_ivrs']['status'] == 3) echo $selected; }?>>Archived</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix no_border less_padding">                        
                            			<label>Call center number<span class="mandatory">*</span></label>
                            			<div class="input">
                                			<input type="text"  id="call_center_number" name="call_center_number" maxlength="" class="xlarge" value="<?php if(isset($data['arr_ivrs'])){ echo $data['arr_ivrs']['call_center_number']; } ?>"/>
                            			</div>
                        			</div>
                                    <div class="clearfix no_border less_padding">                        
                            			<label>Extra numbers for survey<span class="mandatory">*</span></label>
                            			<div class="input">
                                        	<input type="radio" name="extra_number" id="extra_number" value="0" <?php if(!isset($data['arr_ivrs']) || (isset($data['arr_ivrs']) && ($data['arr_ivrs']['extra_number'] == 0)))  echo 'checked="checked"'; ?> /> Tollfree
                                       		<input type="radio" name="extra_number" id="extra_number" value="1"  <?php if(isset($data['arr_ivrs'])) { if($data['arr_ivrs']['extra_number'] == 1) echo 'checked="checked"'; }?>/> Local                                			
                            			</div>
                        			</div>
                                    <div class="clearfix no_border less_padding">                        
                            			<label>Survey number deallocation time </br> (in minutes)<span class="mandatory">*</span></label>
                            			<div class="input">
                                        	<input type="text"  id="deallocation_time" name="deallocation_time" maxlength="" class="xlarge" value="<?php if(isset($data['arr_ivrs'])){ echo $data['arr_ivrs']['deallocation_time']; } ?>"/>                                       		
                            			</div>
                        			</div>
                                    <div class="clearfix no_border less_padding">                        
                            			<label>Call Conversion Criteria </br> (in seconds)<span class="mandatory">*</span></label>
                            			<div class="input">
                                        	<input type="text"  id="call_conversion_criteria" name="call_conversion_criteria" maxlength="" class="xlarge" value="<?php if(isset($data['arr_ivrs'])){ echo $data['arr_ivrs']['call_conversion_criteria']; } ?>"/>                                       		
                            			</div>
                        			</div>
                                    
                                    <!-- for super admin login : group id-3 -->
                                    <?php if($logArray['group_id'] == 3 || $logArray['group_id'] == 1 ){ ?>
                                    	<div class="clearfix no_border less_padding">                        
                                            <label>Manager<span class="mandatory">*</span></label>
                                            <div class="input">
                                            	<?php
													if(isset($data['arr_ivrs'])){														
														createComboBox('manager_id','user_id','user_name', $data['all_managers'],'Please select',$data['arr_ivrs']['manager_id']['user_manager']);
													}
													else
														createComboBox('manager_id','user_id','user_name', $data['all_managers'],'Please select');
												?>
                                            </div>
                                     	</div>
                                        <div class="clearfix no_border less_padding" id="user_dropdown"  style="display:none;">                        
                                            <label>Users<span class="mandatory">*</span></label>
                                            <div class="input">
                                            	<?php
													if(isset($data['arr_ivrs'])){
														createComboBox('user_id','user_id','user_name', $data['user_of_manager'],'Please select',$data['arr_ivrs']['user_id']);
														
													}else{
														echo"<select id='user_id' name='user_id'></select>";	
													}
												?>
                                            </div>
                                     	</div>
                                    <? } ?>
                                    <!-- END for manager login -->
                                    
                                    <!-- for manager login : group id-2 -->
                                    <?php if($logArray['group_id'] == 2){ ?>
                                    	<div class="clearfix no_border less_padding">                        
                                            <label>Users<span class="mandatory">*</span></label>
                                            <div class="input">
                                            	<?php
													if(isset($data['arr_ivrs']))
														createComboBox('user_id','user_id','user_name', $data['all_user_of_manager'],'Please select',$data['arr_ivrs']['user_id']);
													else
														createComboBox('user_id','user_id','user_name', $data['all_user_of_manager'],'Please select');
												?>
                                            </div>
                                     	</div>
                                    <? } ?>
                                    <!-- END for manager login -->
                                    <div class="clearfix">
                                        <div class="input no-label">
                                        	<input type="button" value="Next" class="button blue" id="next2" onClick="addCampaign('campaign','save_campaign','1')" />
                                        </div>
                                   	</div>
                                    
                    			</div>
                           	</div>
                     	</div>
                  	</div>
                </div>
				<!-- 
                	START editing any IVRS
                -->
                 <?php if(isset($data['arr_ivrs'])){ //echo "<pre>"; print_r($data['arr_ivrs']);   
					$style='';
				 ?>
				<div id="tab-2">
                	<div class="clearfix"><div class="input"><input type="checkbox" id="" name="is_recordcall" value="1" <? if($data['arr_ivrs']['is_recordcall'] == 1) echo 'checked="checked"'?>>Record call</input></div></div>
                    <div class="row">
        				<div class="widget clearfix">
                    		<div class="widget_inside">
                            	<?php
									if(isset($notificationArray))
										populateNotification($notificationArray); 
								?>
                    			<div class="form" style="border:none">
									<div class="clearfix no_border less_padding">
                                        <label class="bold">At the start of the call</label>
                                        <div class="input">
                                            <select name="action_type_id" id="start_action">
                                            	<option value="" <? if(!isset($data['arr_ivrs']['details'])) echo "selected='selected'"?>>Please select</option>
                                                <option value="1" <? if(isset($data['arr_ivrs']['details']) && $data['arr_ivrs']['details']['action_type_id'] == 1) echo "selected='selected'"?>>Hang up</option>
                                                <option value="2" <? if(isset($data['arr_ivrs']['details']) && $data['arr_ivrs']['details']['action_type_id'] == 2) echo "selected='selected'"?>>Forward to call center</option>		                                    <option value="3" <? if(isset($data['arr_ivrs']['details']) && $data['arr_ivrs']['details']['action_type_id'] == 3) echo "selected='selected'"?>>Ask a question</option>
                                                <option value="4" <? if(isset($data['arr_ivrs']['details']) && $data['arr_ivrs']['details']['action_type_id'] == 4) echo "selected='selected'"?>>Verify location of caller</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div id="ivr1" class="ivr_keys_content" style="display:block;">                                    	
										<?php 
											if(isset($data['arr_ivrs']['details'])) {
											if($data['arr_ivrs']['details']['action_type_id'] == 4 ) {?>
											<div class="clearfix no_border less_padding verify_location">
												<div class="location_msg">
													Use "###" for placing location in the text.
												</div>
											</div>
										<?php } ?>
                                       	<div class="clearfix no_border less_padding">
                                            <div class="input">
                                                <input type="checkbox" id="" name="details[is_promt_recording]" value="1" <? if($data['arr_ivrs']['details']['is_promt_recording'] == 1) echo 'checked="checked"'?>>Play prompt</input>
                                            </div>
                                        </div>
                                        <div class="clearfix no_border less_padding">
                                            <div class="input">
                                                <textarea id="" name="details[description]" class="xxlarge" style="min-height:55px;"><? echo $data['arr_ivrs']['details']['description']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix no_border less_padding">
                                           <div class="input">
                                           		<input type="checkbox" id="" name="details[is_play_mp3]" value="1" <? if(isset($data['arr_ivrs']['details']['is_play_mp3']) && $data['arr_ivrs']['details']['is_play_mp3'] == 1) echo 'checked="checked"'?>>Play mp3</input>
                                                Add prompt recording : <input type="file" id="" name="file_name[]" onchange="save_filename(this.value,'file0_<?php echo $data['arr_ivrs']['details']['action_type_id']; ?>');"/>
                                                <input type="hidden" id="file0_<?php echo $data['arr_ivrs']['details']['action_type_id']; ?>" name="details[file_name]"  value="<?php echo $data['arr_ivrs']['details']['file_name']; ?>"/>
                                                 <?php 
												 if( $data['arr_ivrs']['details']['file_name'] != '')
												 {
													echo "<div class='file0_".$data['arr_ivrs']['details']['action_type_id']."'  style='float:left; max-width:200px'>".$data['arr_ivrs']['details']['file_name']."</div>"; 
												?>
                                                <!--<input type="checkbox" name="file_unlink" value="1" onchange="unlink_file(this,'< ?php echo $data['arr_ivrs']['details']['file_name'];?>','file0_< ?php echo $data['arr_ivrs']['details']['action_type_id']; ?>')" style="float:left; width:20px"/> -->
                                                	<div class="file0_<?php echo $data['arr_ivrs']['details']['action_type_id']; ?>" style="float:left; width:100px;" ><a class="delete_file" href="javascript:onclick:unlink_file(this,'<?php echo $data['arr_ivrs']['details']['file_name'];?>','file0_<?php echo $data['arr_ivrs']['details']['action_type_id']; ?>')">Delete File</a></div>
                                                <?php  
												 }
												 ?> 
                                                 
                                            </div>
                                        </div>
										
                                   	<? if($data['arr_ivrs']['details']['action_type_id'] == 2 || $data['arr_ivrs']['details']['action_type_id'] == 4 ){
                                    ?>		
                                        <div class="clearfix no_border less_padding">
                                            <div class="input">
                                                Phone number : <input type="text" id="call_center_number" name="details[call_forword_no]" value="<? echo $data['arr_ivrs']['details']['call_forword_no'] ?>"/>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <? if($data['arr_ivrs']['details']['action_type_id'] == 3 )
										{ 
											$key_details = get_ivrsdetails($data['arr_ivrs']['details']['ivrs_id']);
									?>
                                       	<div class="clearfix no_border less_padding">
                                            <label class="bold" style="width:27em;">Please select number of keys do you want for this IVR</label>
                                            <div class="input">
                                                <select name="details[keys]" class="keys" onchange="show_keys(this,0)">
                                                	<option value="">Please select</option>
                                                    <?php
													for($option=1; $option <10; $option++)
													{
														$selected = '';
														if($option == count($key_details))
															$selected = "selected='selected'";
													?>
													<option value="<?php echo $option; ?>" <?php echo $selected; ?>><?php echo $option; ?></option>
													<?php 	
													}
													?>      
                                                </select>
                                            </div>
                                        </div>
                                        <div class="key_lable">
                                            <label style="margin:10px;" class="bold">key</label><label class="bold">Action</label>
                                        </div>
                                        <div class="show_keys">
                                        	<?php 
												//$key_details = get_ivrsdetails($data['arr_ivrs']['details']['ivrs_id']);
											   // echo "<pre>"; print_r($data['arr_ivrs']);											   
											 ?>
                                        	<?php 
											$file_cnt = 0;
											for($i=0; $i<count($key_details); $i++)
											{
												$file_cnt++;												
											?>
                                            	<div class="ivr">
                                                	<div class="number_box" onclick=show_div(this) style="cursor:text;float:left;"><span><?php echo $i+1;?></span></div>
                                                	
                                                    <div class="ivr_content" style="display:block;">
                                                        <div class="close_img" onclick=closediv(this)>
                                                            <img src="img/close.png" />
                                                        </div>
                                                        <div class="clearfix no_border less_padding">
                                                            <div class="input">
                                                            <select name="key_<?php echo $i+1; ?>[details][action_type_id]" class="action" onchange=take_action(this,<?php echo $i+1; ?>)>
                                                                <option value="">Please select</option>
                                                                <option value="2" <? if($key_details[$i]['action_type_id'] == 2) echo "selected='selected'"?>>Forward to call center</option>
                                                                <option value="1" <? if($key_details[$i]['action_type_id'] == 1) echo "selected='selected'"?>>Hang up</option>
                                                                <option value="3" <? if($key_details[$i]['action_type_id'] == 3) echo "selected='selected'"?>>Ask a question</option>
                                                                <option value="4" <? if($key_details[$i]['action_type_id'] == 4) echo "selected='selected'"?>>Verify location of caller</option></select>
                                                            </div>
                                                        </div>
                                                        <?php
															$style1 = '';
															if($key_details[$i]['action_type_id'] == 4 ) {
																$style1="style='display:block'";
															}else{
																$style1="style='display:none'";
															}
														?>
														<div class="clearfix no_border less_padding verify_location" <?php echo $style1; ?>>
															<div class="location_msg">
																Use "###" for placing location in the text.
															</div>
														</div>
                                                        
                                                        <div class="clearfix no_border less_padding">
                                                            <div class="input">
                                                                <input type="checkbox" id="" name="key_<?php echo $i+1; ?>[details][is_promt_recording]" value="1" <? if($key_details[$i]['is_promt_recording'] == 1) echo 'checked="checked"'?>>Play prompt first</input>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix no_border less_padding">
                                                            <div class="input">
                                                                <textarea id="" name="key_<?php echo $i+1; ?>[details][description]" class="xxlarge" style="min-height:55px;"><? echo $key_details[$i]['description'] ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="clearfix no_border less_padding">
                                                            <div class="input">
                                                            	<input type="checkbox" id="" name="key_<?php echo $i+1; ?>[details][is_play_mp3]" value="1" <? if(isset($key_details[$i]['is_play_mp3']) &&  $key_details[$i]['is_play_mp3'] == 1) echo 'checked="checked"'?>>Play mp3</input>
                                                                Add prompt recording : <input type="file" id="" name="file_name[]" onchange="save_filename(this.value,'file_name<?php echo $file_cnt; ?>');"  />
                                                                 <?php 
																 if( $key_details[$i]['file_name'] != '')
																 {
																	echo "<div class='file_name".$file_cnt."' style='float:left; max-width:200px'>".$key_details[$i]['file_name']."</div>"; 
																?>
																	<!--<input type="checkbox" name="file_unlink" value="1" onchange="unlink_file(this,'< ?php echo $key_details[$i]['file_name'];?>','file_name< ?php echo $file_cnt; ?>')"  style="float:left; width:20px"/> -->
                                                                	<div class="file_name<?php echo $file_cnt; ?>" style="float:left; width:100px"><a class="delete_file" href="javascript:onclick:unlink_file(this,'<?php echo $key_details[$i]['file_name'];?>','file_name<?php echo $file_cnt; ?>')">Delete File</a></div>
																<?php  
																 }
																 ?> 
                                                                <input type="hidden" value="<?php echo $key_details[$i]['file_name']; ?>" name="key_<?php echo $i+1; ?>[details][file_name]" id="file_name<?php echo $file_cnt; ?>" value="">
                                                            </div>
                                                        </div>
														
													<?php
													if($key_details[$i]['action_type_id'] == 2 || $key_details[$i]['action_type_id'] == 4)
													{ 
														$style="style='display:block'";
													}else{
														$style="style='display:none'";	
													}
													?>
                                                    <div class="forward_call" <?php echo $style; ?>>
                                                        <div class="clearfix no_border less_padding">
                                                            <div class="input">Phone number : <input type="text" id="" name= "key_<?php echo $i+1; ?>[details][call_forword_no]"  value="<?php echo $key_details[$i]['call_forword_no']?>"/></div>
                                                        </div>
                                                    </div> 
													
													<?php
													if($key_details[$i]['action_type_id'] == 3)
													{ ?>
                                                    <?php 													
													$file_cnt = show_ivrs($key_details[$i],($i+1),$file_cnt);
													//echo $file_cnt;
													?>
													<?php
													}else{
														echo"<div class='new_ivr ivr_keys_content'></div>";
													}
													?> 
                                                    <div style="float:none; clear:both;"></div>	                                                   
                                        		</div></div>
                                    		<? } ?>
                                    	</div>
                                    <?php
									}
									?>                                    
									</div>
                                    <?php } ?>
									
								</div>
                                <div class="clearfix no_border less_padding">
                                    <div class="input no-label">
                                        <input type="button" class="button blue" value="Submit" onclick="validateFormFields('<? echo $data['arr_ivrs']['camp_id']?>','campaign','save_campaign_step2')"></input>
                                    </div>
                                </div>
							</div>      
						</div>
					</div>
				</div>	
                <!-- 
                	END editing any IVRS
                -->
               <?php } else{ ?>
                <div id="tab-2">
                	<div class="clearfix"><div class="input"><input type="checkbox" id="" name="is_recordcall" value="1">Record call</input></div></div>
                    <div class="row">
        				<div class="widget clearfix">
                    		<div class="widget_inside">
                    			<div class="form" style="border:none" >
                                	  <div class="clearfix no_border less_padding">
                                        <label class="bold">At the start of the call</label>
                                        <div class="input">
                                            <select name="action_type_id" id="start_action">
                                            	<option value="">Please select</option>
                                                <option value="1">Hang up</option>
                                                <option value="2">Forward to call center</option>                                                
                                                <option value="3">Ask a question</option>
                                                <option value="4">Verify location of caller</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="ivr1" class="ivr_keys_content"></div>
                                    <div class="clearfix no_border less_padding">
                                        <div class="input no-label">
                                              <input type="button" class="button blue" value="Submit" onclick="validateFormFields('0','campaign','save_campaign_step2')"></input>                                        </div>
                                    </div>
                                </div>
							</div>
						</div>      
					</div>
				</div>
               <?php } ?>
               
               <div id="tab-3">
                    <div class="row">
                        <div class="widget clearfix">
                            <div class="widget_inside">
                            	<div id="loading3" style="display:none;"><img src="img/loading.gif" /></div>
                                <div class="form" style="border:none" >
                                   <!-- <div class="clearfix no_border less_padding">                        
                                        <label>Campaign Id<span class="mandatory">*</span></label>
                                        <div class="input">
                                            <input type="text"  id="hasoffer_camp_id" name="hasoffer_camp_id" maxlength="" class="xlarge" value="< ?php if(isset($data['arr_ivrs'])){ echo $data['arr_ivrs']['hasoffer_camp_id']; } ?>"/>
                                        </div>
                                    </div>-->
                                     <?php 
									for($i=1;$i<=5;$i++)
									{
									?>
                                    <div class="clearfix no_border less_padding">                        
                                        <label>SubId<?php echo $i; ?> ( subid<?php echo $i; ?> )</label>
                                        <div class="input">
                                            <input type="text"  id="subId<?php echo $i; ?>" name="subId<?php echo $i; ?>" maxlength="" class="xlarge" value="<?php if(isset($data['arr_subid'])){ echo $data['arr_subid'][$i-1]['value']; } ?>"/>
                                        </div>
                                    </div>                                    
                                    <?php	
									}
									?>
                                   <!-- <div class="clearfix no_border less_padding">                        
                                        <label>SubId<span class="mandatory">*</span></label>
                                        <div class="input">
                                            <input type="text"  id="subId" name="subId" maxlength="" class="xlarge" value="< ?php if(isset($data['arr_ivrs'])){ echo $data['arr_ivrs']['subId']; } ?>"/>
                                        </div>
                                    </div>-->
                                    <div class="clearfix no_border less_padding input no-label">                        
                                       <input type="button" class="button blue" name="save_step3" id="save_step3" value="Save" onclick="save_third_step()" />
                                    </div>   
                                </div>
                             </div>
                         </div>
                      </div>
                </div>
                
                <div id="tab-4">
                    <div class="row">
                        <div class="widget clearfix">
                            <div class="widget_inside">
                            	<div id="loading4" style="display:none;"><img src="img/loading.gif" /></div>
                                <div class="form" style="border:none" >
                                    <div class="clearfix no_border less_padding">                        
                                        <label>Please specify the size of number pool<span class="mandatory">*</span></label>
                                        <div class="input">
                                            <input type="text"  id="number_cnt" name="number_cnt" maxlength="" class="xlarge"/>
                                        </div>
                                    </div>
                                    <div class="clearfix no_border less_padding input no-label">                        
                                    	<input type="radio" name="num_type" id="num_type" value="0" checked="checked" /> Tollfree
                                        <input type="radio" name="num_type" id="num_type" value="1"  /> Local
                                    </div>
                                    <div class="clearfix no_border less_padding input no-label">                        
                                       <input type="button" class="button blue" name="number_pool" id="number_pool" value="Pool Numbers" onclick="pool_numbers()" />
                                    </div>
                                    <div id="number_pool_list" class="<?php if(isset($data['arr_numbers']) && (count($data['arr_numbers']) > 0)) echo 'widget clearfix'; ?>">
                                    	<div id="all_numbers">  
											<?php 
                                            if(isset($data['arr_numbers']) && (count($data['arr_numbers']) > 0))
                                            {
                                                ?>
                                             <div id="number_list_lable">The following numbers have been pooled:</div>
                                             <div><input type="checkbox" name="chk_all_numbers" id="chk_all_numbers" value="1" onchange="check_numbers(this)" /><span id="chk_all_txt">Check All</span></div>
                                             
                                                 <div class="number_list">
                                                 <?php
                                                    foreach($data['arr_numbers'] as $k=>$v)
                                                    {
                                                 ?>
                                                    <div>
                                                        <input type="checkbox" name="number[]" id="number" value="<?php echo $v['number']?>" onchange="add_div(this)" /><?php echo $v['friendly_name']?>
                                                    </div>
                                                 <?php
                                                    }
                                                 ?>
                                                 </div>
                                                 <div> 
                                                    <input type="button" class="button blue" name="release_number" id="release_number" value="Release Numbers" onclick="release_numbers()" />				
                                                 </div>
                                        </div>
                                                <?php
                                            }
                                            ?>
									</div>                  	
                                </div>  
                            </div>
                        </div>
                    </div>
                </div> 
				<!-- END of tab-4 -->
            </div>
               <?php //step23(); ?>
               
        </div>
    </div>
</div>
<!--</div> -->

<!-- END Main Container-->
<script>
	
	function add_div(this_check)
	{
		if($(this_check).is(':checked')){
			if(!$(this_check).parent().hasClass('checked')){
				$(this_check).parent().addClass('checked');
			}
		}else{
			if($(this_check).parent().hasClass('checked')){
				$(this_check).parent().removeClass('checked');
			}
		}
		
		var chk = false;
		$("input[name='number[]']").each(function()
		{	
			if(($(this).is(':checked')))
			{
				chk = true;																		  				
			}
			else
			{
				chk = false;	
				$("#chk_all_txt").text("Check All");
				$("#chk_all_numbers").removeAttr('checked');	
				return false; 		 
			}
		});
		if(chk == true)
		{
			$("#chk_all_txt").text("Uncheck All");
			$("#chk_all_numbers").attr('checked','checked');
		}
		
	}
	
	<? if(isset($data['arr_ivrs'])){?>
		$("#user_dropdown").show();
	<? } ?>
	
	$(document).ready(function(e) {
	
	<? if($function == 'save_campaign_step2' && isset($notificationArray['message'])) {		
		?>
		goToTab(2);		
	<? } 
	 	elseif($function == 'save_campaign_step2') {		
		?>
		goToTab(3);		
	<? } ?>
	
		
	$('#manager_id').change(function(){
		manager_id = $('#manager_id').val();
		$.ajax({
			url:"ajax.php",
			type:"POST",
			data:"page=campaign&function=get_users_of_manager&manager_id="+manager_id,
			success:function(resp){
				var user_select='<option value="">Plese Select</option>';
				for(var i=0; i<resp.length; i++){
					user_select+="<option value="+resp[i].user_id+">"+resp[i].user_name+"</option>";
				}
				$("#user_id").html(user_select);
				$("#user_id").change();
				$("#user_dropdown").show();
			}
		});	
		});	
		call_center_number = $('#call_center_number').val();	
	//	At the start of call drop-down change code 
	   $('#start_action').change(function(){
			var start_string = "";
			
			if($('#start_action option:selected').val() == "2")
			{
				start_string = '<div class="clearfix no_border less_padding">\
									<div class="input">\
										<input type="checkbox" id="" name="details[is_promt_recording]" value="1" checked="checked">Play prompt first</input>\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										<textarea id="" name="details[description]" class="xxlarge" style="min-height:55px;"></textarea>\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										<input type="checkbox" id="" name="details[is_play_mp3]" value="1">Play mp3</input>\
										Add prompt recording : <input type="file" id="" name="file_name[]" onchange="save_filename(this.value,\'file0_2\');" />\
										<input type="hidden" id="file0_2" name="details[file_name]" value="" />\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										Phone number : <input type="text" id="" name="details[call_forword_no]" value="'+call_center_number+'"/>\
									</div>\
								</div>'; 
			$('#ivr1').html(start_string).show();
			}else if($('#start_action option:selected').val() == "3")
			{
				start_string = '<div class="clearfix no_border less_padding">\
									<div class="input">\
										<input type="checkbox" id="" name="details[is_promt_recording]" value="1" checked="checked">Play prompt first</input>\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										<textarea id="" name="details[description]" class="xxlarge"></textarea>\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										<input type="checkbox" id="" name="details[is_play_mp3]" value="1">Play mp3</input>\
										Add prompt recording : <input type="file" id="" name="file_name[]" onchange="save_filename(this.value,\'file0_3\');" />\
										<input type="hidden" id="file0_3" name="details[file_name]" value="" />\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<label class="bold" style="width:27em;">Please select number of keys do you want for this IVR</label>\
									<div class="input">\
										<select name="details[keys]" class="keys" onchange="show_keys(this,0)">\
											<option value="">please select</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option>\
										</select>\
									</div>\
								</div>\
								<div class="key_lable">\
									<label style="margin:10px;" class="bold">key</label><label class="bold">Action</label>\
								</div>\
								<div class="show_keys"></div>';
				$('#ivr1').html(start_string).show();
			}else if($('#start_action option:selected').val() == "1")
			{
				start_string = '<div class="clearfix no_border less_padding">\
									<div class="input">\
										<input type="checkbox" id="" name="details[is_promt_recording]" value="1" checked="checked">Play prompt first</input>\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										<textarea id="" name="details[description]" class="xxlarge" style="min-height:55px;"></textarea>\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										<input type="checkbox" id="" name="details[is_play_mp3]" value="1">Play mp3</input>\
										Add prompt recording : <input type="file" id="" name="file_name[]" onchange="save_filename(this.value,\'file0_1\');" />\
										<input type="hidden" id="file0_1" name="details[file_name]" value="" />\
									</div>\
								</div>';
				$('#ivr1').html(start_string).show(); //file type name = details[file_name]
			}else if($('#start_action option:selected').val() == "4"){
				start_string = '<div class="clearfix no_border less_padding">\
									<div class="input">\
										<input type="checkbox" id="" name="details[is_promt_recording]" value="1" checked="checked">Play prompt first</input>\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="location_msg">\
										Use "###" for placing location in the text.\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										<textarea id="" name="details[description]" class="xxlarge" style="min-height:55px;"></textarea>\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										<input type="checkbox" id="" name="details[is_play_mp3]" value="1">Play mp3</input>\
										Add prompt recording : <input type="file" id="" name="file_name[]" onchange="save_filename(this.value,\'file0_4\');"/>\
										<input type="hidden" id="file0_4" name="details[file_name]" value="" />\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										Phone number : <input type="text" id="" name="details[call_forword_no]"  value="'+call_center_number+'"/>\
									</div>\
								</div>';
				$('#ivr1').html(start_string).show();
			}else{
				$('#ivr1').html('').show();
			}
		});
		// END At the start of call drop-down change code 
	});
	
	function save_filename(element_val, id)
	{			
		
		var fileNameIndex = element_val.lastIndexOf("\\") + 1;
		var filename = element_val.substr(fileNameIndex);
		//alert(filename); 
		var extIndex = filename.lastIndexOf(".") + 1;
		var ext = element_val.substr(extIndex);
		//alert(ext);
		/*if(ext != 'mp3')
		{
			alert(error.Campaign.only_audio);
		}
		else
		{*/		
			$("#"+id).val(filename);		
		//}
	}
	
		//This function get called when we select "Ask a question" action from drop-down
		//Not while at the start of call action drop-down
	
	function create_new_ivr(this_element,level)
	{
		
		level_str = level;		
		if(isNaN(level_str))
		{
			var arr_level = level_str.split('_');
		}
		else
		{
			var arr_level = [level_str];
		}
		
		var str_name = '';
		for(var j=0;j<arr_level.length;j++)
		{
			if(arr_level.length != 1)
			{
				var attch = '';
				if(j != 0)
				{
					str_name +=  '[ivrs][key_'+arr_level[j]+']';		
				}
				else
				{
					str_name +=  'key_'+arr_level[j];	
				}					
			}
			else
				str_name += 'key_'+arr_level[j];				
		}
			
		new_ivr_string = '<div class="clearfix no_border less_padding">\
								<label class="bold" style="width:29.6em;">Please select number of keys do you want for this SUB-IVR</label>\
								<div class="input">\
									<select name="'+str_name+'[details][keys]" class="keys" onchange="show_keys(this,\''+level+'\')" >\
										<option value="">Please select</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option>\
									</select>\
								</div>\
							</div>\
							<div class="key_lable">\
								<label style="margin:10px;" class="bold">key</label><label class="bold">Action</label>\
							</div>\
							<div class="show_keys"></div>';
		$(this_element).closest('.ivr_content').find('.new_ivr').html(new_ivr_string).show();
	}
	
	
		//This function get called when we select any action to take from drop-down
		//Not while at the start of call action drop-down
	
	function take_action(this_element,level){
		//2-Forword to call center  4-Verify location of caller  3-Ask a question  1-Hang up
		if($(this_element).closest('.ivr .ivr_content').find('.action option:selected').val() == "2" ||
			$(this_element).closest('.ivr').find('.action option:selected').val() == "4")
		{
			$(this_element).closest('.ivr .ivr_content').find('.forward_call').show();
			if($(this_element).closest('.ivr').find('.action option:selected').val() == "4"){
				$(this_element).closest('.ivr .ivr_content').find('.verify_location').show();
			}else{
				$(this_element).closest('.ivr .ivr_content').find('.verify_location').hide();
				$(this_element).closest('.ivr .ivr_content').find('.ivr_keys_content select').removeClass('keys');
			}
		}
		else
		{
			$(this_element).closest('.ivr .ivr_content').find('.forward_call').hide();
			$(this_element).closest('.ivr .ivr_content').find('.verify_location').hide();
			$(this_element).closest('.ivr .ivr_content').find('.ivr_keys_content select').removeClass('keys');
		}
		
		if($(this_element).closest('.ivr .ivr_content').find('.action option:selected').val() == "3"){
			create_new_ivr(this_element,level);
		}else{
			$(this_element).closest('.ivr_keys_content .ivr_content').find('.new_ivr').hide();
		}
		
		if($(this_element).closest('.ivr').find('.action option:selected').val() == "1"){
			$(this_element).closest('.ivr .ivr_content').find('.forward_call').hide();
			$(this_element).closest('.ivr .ivr_content').find('.verify_location').hide();
			$(this_element).closest('.ivr .ivr_content').find('.ivr_keys_content select').removeClass('keys');
		}
	}
	
	 
		//This function get called when we select a number from drop-down.
		//Depending on selection that number of keys generated for that IVR
	
	var file_cnt = 0;
	function show_keys(this_element,level)
	{
		var ivr_string="";
		no_of_keys = $(this_element).find('option:selected').text();
	
		for(var i=1; i<=no_of_keys; i++)
		{
			if(level != 0)
				var level_str = level+'_'+i;
			else
				var level_str = i;
			
			if(isNaN(level_str))
			{
				var arr_level = level_str.split('_');
			}
			else
			{
				var arr_level = [level_str];
			}
			
			var str_name = '';
			for(var j=0;j<arr_level.length;j++)
			{
				if(arr_level.length != 1)
				{
					var attch = '';
					if(j != 0)
					{
						str_name +=  '[ivrs][key_'+arr_level[j]+']';		
					}
					else
					{
						str_name +=  'key_'+arr_level[j];	
					}					
				}
				else
					str_name += 'key_'+arr_level[j];				
			}
			
			file_cnt++;
			ivr_string += '<div class="ivr"><div class="number_box" onclick=show_div(this)><span>'+i+'</span></div>';
			ivr_string += '<div class="ivr_content">\
								<div class="close_img" onclick=closediv(this)>\
									<img src="img/close.png" />\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										<select name="'+str_name+'[details][action_type_id]" class="action" onchange=take_action(this,"'+level_str+'")>\
											<option value="">Please select</option><option value="2">Forward to call center</option><option value="1">Hang up</option><option value="3">Ask a question</option><option value="4">Verify location of caller</option>\
										</select>\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										<input type="checkbox" id="" name="'+str_name+'[details][is_promt_recording]" value="1" checked="checked">Play prompt first</input>\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding verify_location" style="display:none;">\
									<div class="location_msg">\
										Use "###" for placing location in the text.\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										<textarea id="" name="'+str_name+'[details][description]" class="xxlarge" style="min-height:55px;"></textarea>\
									</div>\
								</div>\
								<div class="clearfix no_border less_padding">\
									<div class="input">\
										<input type="checkbox" id="" name="'+str_name+'details[is_play_mp3]" value="1">Play mp3</input>\
										Add prompt recording : <input type="file" id="" name="file_name[]" onchange="save_filename(this.value,\'file_name'+file_cnt+'\');"/>\
										<input type="hidden" id="file_name'+file_cnt+'" name="'+str_name+'[details][file_name]" value="" />\
									</div>\
								</div>\
								<div class="new_ivr ivr_keys_content"></div>\
								<div class="forward_call">\
									<div class="clearfix no_border less_padding">\
										<div class="input">Phone number : <input type="text" id="" name= "'+str_name+'[details][call_forword_no]"  value="'+call_center_number+'"/>\
									</div>\
								</div>\
							</div></div>\
							<div style="float:none;clear:both;"></div></div>';
		}
		
		$(this_element).closest('.ivr_keys_content').find('.show_keys').html(ivr_string);
	}
	
	function show_div(this_element)
	{
		$(this_element).css('cursor','text');
		$(this_element).css('float','left');
		$(this_element).closest('.ivr').find('.ivr_content').show('fast');
	}
	
	function closediv(this_element)
	{
		$(this_element).closest('.ivr').find('.number_box').css('cursor','pointer');
		$(this_element).closest('.ivr').find('.ivr_content').hide('fast');
	}
	function check_numbers(this_element)
	{
		if($(this_element).is(':checked'))
		{
			$("#chk_all_txt").text("Uncheck All");
			$("input[name='number[]']").each(function()
				{
					$(this).attr('checked','checked');		   
				});
		}
		else
		{
			$("#chk_all_txt").text("Check All");
			$("input[name='number[]']").each(function()
				{
					$(this).removeAttr('checked');		   
				});
		}
	}
	function unlink_file(this_element,file_name,id_to_remove)
	{	
		var msg = error.Campaign.confirm_unlink_file;
		var div = $("<div>"+msg+"</div>");
		div.dialog({
			title: "Confirm",
			buttons: [
				{
					text: "Yes",
					click: function () {
						div.dialog("close");
						var campaign_id = $("#campaign_id").val();			
						$.ajax({
								url:"ajax.php",
								type:"POST",
								data:{
									'page' : 'campaign',
									'function' : 'unlink_file',
									'file_name' : file_name,
									'camp_id' : campaign_id,
									},
								success:function(resp){										
									
								}
							});	
							$("#"+id_to_remove).val(''); 
							$("."+id_to_remove).html('');
							$(this_element).hide();	
					}
				},
				{
					text: "No",
					click: function () {
						div.dialog("close");
					}
				}
			]
		});
		
	}
</script>