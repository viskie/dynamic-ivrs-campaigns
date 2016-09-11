<?php

/*function step23()
{
?>
    <div id="tab-3">
        <div class="row">
            <div class="widget clearfix">
                <div class="widget_inside">
                    <div class="form" style="border:none" >
                        <div class="clearfix no_border less_padding">                        
                            <label>Campaign Id<span class="mandatory">*</span></label>
                            <div class="input">
                                <input type="text"  id="hasoffer_camp_id" name="hasoffer_camp_id" maxlength="" class="xlarge"/>
                            </div>
                        </div>
                        <div class="clearfix no_border less_padding">                        
                            <label>SubId<span class="mandatory">*</span></label>
                            <div class="input">
                                <input type="text"  id="subId" name="subId" maxlength="" class="xlarge"/>
                            </div>
                        </div>
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
                    <div class="form" style="border:none" >
                        <div class="clearfix no_border less_padding">                        
                            <label>Enter how many numbers you want to buy<span class="mandatory"></span></label>
                            <div class="input">
                                <input type="text"  id="number_cnt" name="number_cnt" maxlength="" class="xlarge"/>
                            </div>
                        </div>
                        <div class="clearfix no_border less_padding input no-label">                        
                           <input type="button" class="button blue" name="number_pool" id="number_pool" value="Pool Numbers" onclick="pool_numbers()" />
                        </div>
                        <div id="number_pool_list">                    	
                        </div>  
                    </div>
                 </div>
             </div>
          </div>
    </div>
<?php
}*/
function get_ivrsdetails($ivrs_id ='', $ivrs_action_id='')
{
	//echo "---ivrs id".$ivrs_id;
	$arr_data = array();
	if($ivrs_action_id != '')
	{
		$arr_ivrs = getRow("SELECT ivrs_id FROM ivrs WHERE ivrs_action_id='".$ivrs_action_id."'");
		$ivrs_id =  $arr_ivrs['ivrs_id'];
	}
	
	$arr_ivrs_action = getData("SELECT * FROM ivrs_action WHERE ivrs_id='".$ivrs_id."'");
	$arr_data = $arr_ivrs_action;
	//print_r($arr_data);exit;
	//$arr_data['details']['keys'] = count($arr_ivrs_action);		
	
	return $arr_data;	
}
function show_ivrs ($key_details,$level,$file_cnt)
{
	//echo "<pre>"; print_r($key_details); //exit;
	//echo $level;
	$arr_level = explode('_',$level);
	$str_name = '';
	for($j=0; $j<count($arr_level); $j++)
	{
		if($j == 0)
			$str_name .= 'key_'.$arr_level[$j];
		else
			$str_name .=  '[ivrs][key_'.$arr_level[$j].']';																
	}
	
	$arr_key = get_ivrsdetails('',$key_details['ivrs_action_id']);
	//echo count($arr_key);
	//echo "<pre>"; print_r($arr_key); exit;
	?>
   <!-- <div class="new_ivr ivr_keys_content"> -->
	<div class="new_ivr ivr_keys_content" style="display:block;">
    <div class="clearfix no_border less_padding">
   		<label class="bold" style="width:27em;">Please select number of keys do you want for this IVR</label>
    	<div class="input">
			<select name="<?php echo $str_name; ?>[details][keys]" class="keys" onchange="show_keys(this,'<?php echo $level; ?>')">
				<option value="0">please select</option>
				<?php
				for($option=1; $option <10; $option++)
				{
					$selected = '';
					if($option == count($arr_key))
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
     	<label class="bold" style="margin:10px;">key</label><label class="bold">Action</label>							
     </div>
    <div class="show_keys">
		<?php
		if($key_details['action_type_id'] == 3)
			$str_name .= '[ivrs]';
		//echo $str_name.'***';
        for($i=0; $i<count($arr_key); $i++)
        { 
		//echo "<pre>"; print_r($arr_key[$i]);
		if($key_details['action_type_id'] == 3)
			$str_name1 = $str_name.'[key_'.($i+1).']';
		else
        	$str_name1 = $str_name.'[key_'.($i+1).']'; 
       //echo $str_name1;
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
                        <select name="<?php echo $str_name1; ?>[details][action_type_id]" class="action" onchange=take_action(this,'<?php echo $i; ?>')>
                            <option value="">Please select</option>
                            <option value="2" <? if($arr_key[$i]['action_type_id'] == 2) echo "selected='selected'"?>>Forward to call center</option>
                            <option value="1" <? if($arr_key[$i]['action_type_id'] == 1) echo "selected='selected'"?>>Hang up</option>
                            <option value="3" <? if($arr_key[$i]['action_type_id'] == 3) echo "selected='selected'"?>>Ask a question</option>
                            <option value="4" <? if($arr_key[$i]['action_type_id'] == 4) echo "selected='selected'"?>>Verify location of caller</option>
                        </select>
                        </div>
                    </div>
                   
                    <div class="clearfix no_border less_padding">
                        <div class="input">
                            <input type="checkbox" id="" name="<?php echo $str_name1; ?>[details][is_promt_recording]" value="1" <? if($arr_key[$i]['is_promt_recording'] == 1) echo 'checked="checked"'?>>Play prompt first</input>
                        </div>
                    </div>
                     <?php if($arr_key[$i]['action_type_id'] == 4 ) {?>
                        <div class="clearfix no_border less_padding verify_location">
                            <div class="location_msg">
                                Use "###" for placing location in the text.
                            </div>
                        </div>
                    <?php } ?>
                    <div class="clearfix no_border less_padding">
                        <div class="input">
                            <textarea id="" name="<?php echo $str_name1; ?>[details][description]" class="xxlarge" style="min-height:55px;"><? echo $arr_key[$i]['description'] ?></textarea>
                        </div>
                    </div>
                    <div class="clearfix no_border less_padding">
                        <div class="input">
                        	<input type="checkbox" id="" name="<?php echo $str_name1; ?>[details][is_play_mp3]" value="1" <? if(isset($arr_key[$i]['is_play_mp3']) &&  $arr_key[$i]['is_play_mp3'] == 1) echo 'checked="checked"'?>>Play mp3</input>
                            Add prompt recording : <input type="file" id="" name="file_name[]" onchange="save_filename(this.value,'file_name<?php echo $file_cnt; ?>');" />
                            <?php 
							 if( $arr_key[$i]['file_name'] != '')
							 {
								echo "<div class='file_name".$file_cnt."' style='float:left; max-width:200px'>".$arr_key[$i]['file_name']."</div>"; 
							?>
							<!--<input type="checkbox" name="file_unlink" value="1" onchange="unlink_file(this,'< ?php echo $arr_key[$i]['file_name'];?>','file_name< ?php echo $file_cnt; ?>')" style="float:left; width:20px"/> -->
                            	<div class="file_name<?php echo $file_cnt; ?>" style="float:left; width:100px"><a class="delete_file" href="javascript:onclick:unlink_file(this,'<?php echo $arr_key[$i]['file_name'];?>','file_name<?php echo $file_cnt; ?>')">Delete File</a></div>
							<?php  
							 }
							 ?> 
                            <input type="hidden" value="<?php echo $arr_key[$i]['file_name']; ?>" name="<?php echo $str_name1; ?>[details][file_name]" id="file_name<?php echo $file_cnt; ?>">
                        </div>
                    </div>
                     <?php
					if($arr_key[$i]['action_type_id'] == 3)
					{ ?>
					<?php 													
  					//$file_cnt = show_ivrs($arr_key[$i],($i+1),$file_cnt);					
					?>
					<?php
					}
					?>  
                    <?php
					if($arr_key[$i]['action_type_id'] != 3)
					{
						echo "<div class='new_ivr ivr_keys_content'></div>"; 
					}
					?>
                    <div style="float:none; clear:both;"></div>
                  </div>
                <?php
                
                if($arr_key[$i]['action_type_id'] == 2 || $arr_key[$i]['action_type_id'] == 4)
                { 
					$style="style='display:block'";
				}else{
					$style="style='display:none'";
				}?>
                <div class="forward_call" <?php echo $style; ?>>
                    <div class="clearfix no_border less_padding">
                        <div class="input">Phone number : <input type="text" id="" name= "<?php echo $str_name1; ?>[details][call_forword_no]"  value="<?php echo $arr_key[$i]['call_forword_no']?>"/>
						</div>
					</div>
				</div>	
                <?php
               
                if($arr_key[$i]['action_type_id'] == 3)
                { 	
                    $level = $level.'_'.($i+1);												
                    //$file_cnt = show_ivrs($arr_key[$i],$level,$file_cnt);    
					show_ivrs($arr_key[$i],$level,$file_cnt);                  
                }				
                ?>                                                    
            </div>				
			<?php	
			}
			?>
        </div>
    </div>
  <!--  </div> -->
    <?php
	//echo 'here'.$file_cnt; exit;
	 return $file_cnt+1;                       
	
}
?>

