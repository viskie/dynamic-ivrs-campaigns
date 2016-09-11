<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
	exit();
}
extract($data['get_coloums']);
if(isset($old_data))
	extract($old_data);
?>
<input type="hidden" name="mainfunction" id="mainfunction" value="<?php if(isset($data['mainfunction'])) echo $data['mainfunction']; else  echo $mainfunction; ?>" />
<input type="hidden" name="subfunction_name" id="subfunction_name" value="<?php if(isset($data['subfunction_name'])) echo $data['subfunction_name']; else echo $subfunction_name; ?>" />
<input type="hidden" name="download_type" id="download_type" value="" />
<!-- Main Container-->
<div class="container" id="actualbody">
	<div class="row clearfix">
    <div class="col_12">
    	<div class="widget clearfix">
    		<h2>Leads Details</h2>
           <?php
				if(isset($notificationArray))
					populateNotification($notificationArray);
            ?>
            <div class="row clearfix">
            	<div class="col_6" style="margin: 1% 1.3%;">
                    <div class="widget clearfix">
                        <h2>Filters</h2>
                        <div class="widget_inside">
                        	<div class="form" style="border:none" >
                            	 
                                 <!--<div class="clearfix ">                        
                                    <label class="view_lable">User Type :</label>
                                    <div class="input">
                                       <select name="sel_usertype" id="sel_usertype">
                                       		<option value="">Select</option>
                                      		<option value="business" < ?php if(isset($sel_usertype) && ($sel_usertype == 'business')) echo "selected='selected'"; ?>>Business</option>
                                            <option value="cafe" < ?php if(isset($sel_usertype) && ($sel_usertype == 'cafe')) echo "selected='selected'"; ?>>Cafe</option>
                                            <option value="cellular" < ?php if(isset($sel_usertype) && ($sel_usertype == 'cellular')) echo "selected='selected'"; ?>>Cellular</option>
                                            <option value="college" < ?php if(isset($sel_usertype) && ($sel_usertype == 'college')) echo "selected='selected'"; ?>>College</option>
                                            <option value="contentDeliveryNetwork" < ?php if(isset($sel_usertype) && ($sel_usertype == 'contentDeliveryNetwork')) echo "selected='selected'"; ?>>ContentDeliveryNetwork</option>
                                            <option value="government" < ?php if(isset($sel_usertype) && ($sel_usertype == 'government')) echo "selected='selected'"; ?>>Government</option>
                                            <option value="hosting" < ?php if(isset($sel_usertype) && ($sel_usertype == 'hosting')) echo "selected='selected'"; ?>>Hosting</option>
                                            <option value="library" < ?php if(isset($sel_usertype) && ($sel_usertype == 'library')) echo "selected='selected'"; ?>>Library</option>
                                            <option value="military" < ?php if(isset($sel_usertype) && ($sel_usertype == 'military')) echo "selected='selected'"; ?>>Military</option>
                                            <option value="residential" < ?php if(isset($sel_usertype) && ($sel_usertype == 'residential')) echo "selected='selected'"; ?>>Residential</option>
                                            <option value="router" < ?php if(isset($sel_usertype) && ($sel_usertype == 'router')) echo "selected='selected'"; ?>>Router</option>
                                            <option value="school" < ?php if(isset($sel_usertype) && ($sel_usertype == 'school')) echo "selected='selected'"; ?>>School</option>
                                            <option value="searchEngineSpider" < ?php if(isset($sel_usertype) && ($sel_usertype == 'searchEngineSpider')) echo "selected='selected'"; ?>>SearchEngineSpider</option>
                                            <option value="traveler" < ?php if(isset($sel_usertype) && ($sel_usertype == 'traveler')) echo "selected='selected'"; ?>>Traveler</option>                                            
                                       </select>
                                    </div>
                                </div>  -->                              
                                 <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Date :</label>
                                    <div class="input">
                                       <input type="text" name="from_dur" id="from_dur" class="from_dur" value="<?php  if(isset($from_dur)) echo $from_dur; ?>" placeholder="From" />
                                        <input type="hidden" name="from_dur_alt" class="from_dur_alt"  value="<?php  if(isset($from_dur_alt)) echo $from_dur_alt; ?>"/>
                                       <input type="text" name="to_dur" id="to_dur" class="to_dur" value="<?php  if(isset($to_dur)) echo $to_dur; ?>" placeholder="To"/>
                                       <input type="hidden" name="to_dur_alt" class="to_dur_alt" value="<?php  if(isset($to_dur_alt)) echo $to_dur_alt; ?>"/>
                                    </div>
                                </div>
                                <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Select Campaign :</label>
                                    <div class="input">
                                        <select name="selcamp" id="selcamp" style="width:97%">
                                        	<option value="">Select</option>
                                            <?php
											foreach($data['arr_campaigns'] as $k=>$v)
											{
												$selected = '';
												if(isset($selcamp) && ($selcamp == $v['camp_id']))
													$selected = 'selected="selected"';
											?>
                                            <option value="<?php echo $v['camp_id']; ?>" <?php echo $selected; ?>><?php echo $v['name']; ?></option>
                                            <?php	
											}
											?>
                                        </select>
                                    </div>
                                </div> 
                                <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Subid2 (Affiliate ID) :</label>
                                    <div class="input lead_text">
                                       <input type="text" name="txtsubid2" id="txtsubid2" value="<?php  if(isset($txtsubid2)) echo $txtsubid2; ?>" class="lead_input" />
                                       </br>Enter comma seperated numbers.
                                    </div>
                                </div>                                
                                <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Call To :</label>
                                    <div class="input lead_text">
                                       <input type="text" name="txtcallto" id="txtcallto" value="<?php  if(isset($txtcallto)) echo $txtcallto; ?>" class="lead_input" />
                                       </br>Enter comma seperated numbers.
                                    </div>
                                </div> 
                                <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">MTid :</label>
                                    <div class="input lead_text">
                                       <input type="text" name="txtmid" id="txtmid" value="<?php  if(isset($txtmid)) echo $txtmid; ?>" class="lead_input" />
                                       </br>Enter comma seperated mids.
                                    </div>
                                </div>
                                <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Tid :</label>
                                    <div class="input lead_text">
                                       <input type="text" name="txttid" id="txttid" value="<?php  if(isset($txttid)) echo $txttid; ?>" class="lead_input" />
                                       </br>Enter comma seperated tids.
                                    </div>
                                </div>
                                 <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Caller ID ( Call From ) :</label>
                                    <div class="input lead_text">
                                       <input type="text" name="txtcallfrom" id="txtcallfrom" value="<?php  if(isset($txtcallfrom)) echo $txtcallfrom; ?>" class="lead_input" />
                                       </br>Enter comma seperated numbers.
                                    </div>
                                </div> 
                                <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Converted Calls Only :</label>
                                    <div class="input">
                                    	<input type="radio" name="chkconverted" id="chkconverted" value="Yes" checked="checked"/>Yes
                                        <input type="radio" name="chkconverted" id="chkconverted" value="No" />No                                                                              
                                    </div>
                                </div>                               
                                <!--<div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">City :</label>
                                    <div class="input">
                                       <input type="text" name="call_city" id="call_city" value="< ?php  if(isset($call_city)) echo $call_city; ?>" placeholder="Call City" />
                                       <input type="text" name="click_city" id="click_city" value="< ?php  if(isset($click_city)) echo $click_city; ?>" placeholder="Click City"/>
                                       </br>Enter comma seperated numbers.
                                    </div>
                                </div>
                                <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Region Name :</label>
                                    <div class="input lead_text">
                                       <input type="text" name="txtregion" id="txtregion" value="< ?php  if(isset($txtregion)) echo $txtregion; ?>" class="lead_input" />
                                       </br>Enter comma seperated reqions.
                                    </div>
                                </div>
                                 <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Area Code :</label>
                                    <div class="input lead_text">
                                       <input type="text" name="txtareacode" id="txtareacode" value="< ?php  if(isset($txtareacode)) echo $txtareacode; ?>" class="lead_input" />
                                        </br>Enter comma seperated area codes.
                                    </div>
                                </div>
                                 <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Postal Code :</label>
                                    <div class="input lead_text">
                                       <input type="text" name="txtpostalcode" id="txtpostalcode" value="< ?php  if(isset($txtpostalcode)) echo $txtpostalcode; ?>" class="lead_input" />
                                       </br>Enter comma seperated postal codes.
                                    </div>
                                </div>-->                                
                                <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Name (in survey) :</label>
                                    <div class="input lead_text">
                                       <input type="text" name="txtname" id="txtname" value="<?php  if(isset($txtname)) echo $txtname; ?>" class="lead_input" />                                       
                                    </div>
                                </div>
                                <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Address (in survey) :</label>
                                    <div class="input lead_text">
                                       <input type="text" name="txtaddress" id="txtaddress" value="<?php  if(isset($txtaddress)) echo $txtaddress; ?>" class="lead_input" />                                       
                                    </div>
                                </div>
                                <div class="clearfix ">                        
                                    <label class="view_lable margin_top_lable">Email (in survey) :</label>
                                    <div class="input lead_text">
                                       <input type="text" name="txtemail" id="txtemail" value="<?php  if(isset($txtemail)) echo $txtemail; ?>" class="lead_input" />  
                                        </br>Enter comma seperated emails.                                     
                                    </div>
                                </div>
                                						
                              </div>
                        </div>
                    </div>
                </div>
                <div style="float:left; width:46%;margin: 1% 1.3%;">
                    <div class="widget clearfix">
                        <h2>Show Details</h2>
                        <div class="widget_inside">
                        
                        	<div class="form lead_form">
                            	<div class="clearfix ">
                                	<div style="float:left; width:20px;"><input type="checkbox" name="chk1" id="chk1" value="" onchange="check_all(this, 'chk_calls');" <?php if(isset($chk_calls) && count($chk_calls) == count($calls)) echo "checked='checked'"; ?> /></div>                       
                                    <label style="text-align:center; width:80%;"><strong>All Calls</strong></label>                                           
                                </div>
                                <?php //echo "<pre>"; print_r($chk_click);
								$cheched_arr = array('CallFrom','CallTo','FromCity','CallStartTime');
								foreach($calls as $k=>$v)
								{ 
								?>
                                <div class="clearfix "> 
                                	<div style="float:left; width:20px;float:left;">
                                    	<input type="checkbox" name="chk_calls[]" id="chk_calls" value="<?php echo $v; ?>" onchange="check_field(this, 'chk1');" <?php if((isset($chk_calls) &&  isset($chk_calls) && in_array($v,$chk_calls)) || (in_array($v,$cheched_arr))) echo 'checked="checked"';?> />
                                    </div>                      
                                    <label style="text-align:center; width:80%;"><?php echo $v; ?></label>                                           
                                </div>	
                                <?php
								}
								?>
                             </div>
                             
                             <div class="form lead_form">
                            	<div class="clearfix ">
                                	<div style="float:left; width:20px;"><input type="checkbox" name="chk2" id="chk2" value="1" onchange="check_all(this,'chk_click');" <?php if(isset($chk_click) && count($chk_click) == count($clicks)) echo "checked='checked'"; ?>/></div>                       
                                    <label style="text-align:center; width:80%;"><strong>All Clicks</strong></label>                                           
                                </div>
                                <?php
								$click_cheched_arr = array('subid2');
								foreach($clicks as $k=>$v)
								{ 
								?>
                                <div class="clearfix "> 
                                	<div style="float:left; width:20px;"><input type="checkbox" name="chk_click[]" id="chk_click" value="<?php echo $v; ?>" onchange="check_field(this, 'chk2');" <?php if((isset($chk_click) && isset($chk_click) && in_array($v,$chk_click))  || in_array($v,$click_cheched_arr)) echo 'checked="checked"';?> /></div>                      
                                    <label style="text-align:center; width:80%;"><?php echo $v; ?></label>                                           
                                </div>	
                                <?php
								 
								}
								?>                               			
                             </div>
                             
                             <div class="form lead_form">
                            	<div class="clearfix ">
                                	<div style="float:left; width:20px;"><input type="checkbox" name="chk3" id="chk3" value="1"  onchange="check_all(this,'other');" <?php if((isset($chk_locations) && count($chk_locations) == count($locations)) && (count($chk_devices) == count($devices))) echo "checked='checked'"; ?> /></div>                       
                                    <label style="text-align:center; width:80%;"><strong>All Others</strong></label>                                           
                                </div>
                                <?php
								foreach($locations as $k=>$v)
								{ 
								?>
                                <div class="clearfix "> 
                                	<div style="float:left; width:20px;"><input type="checkbox" name="chk_locations[]" id="chk_locations" value="<?php echo $v; ?>" onchange="check_field(this, 'chk3');"  <?php if(isset($chk_locations) && isset($chk_locations) && in_array($v,$chk_locations) ) echo 'checked="checked"';?>/></div>                      
                                    <label style="text-align:center; width:80%;"><?php echo $v; ?></label>                                           
                                </div>
                                <?php
								}								
								foreach($devices as $k=>$v)
								{ 
								?>
                                <div class="clearfix "> 
                                	<div style="float:left; width:20px;"><input type="checkbox" name="chk_devices[]" id="chk_devices" value="<?php echo $v; ?>" onchange="check_field(this, 'chk3');" <?php if(isset($chk_devices) && isset($chk_devices) && in_array($v,$chk_devices) ) echo 'checked="checked"';?>/></div>                      
                                    <label style="text-align:center; width:80%;"><?php echo $v; ?></label>                                           
                                </div>
                                <?php
								}
								?>				
                             </div>
                             
                        </div>
                    </div>
                </div>
                
              </div>              
              	<div class="clearfix">
                    <div class="input no-label">
                        <input type="button" class="button blue" value="Download CSV" onClick="download('csv')" style="margin-left:25%;"></input>
                        <input type="button" class="button blue" value="Download XML" onClick="download('xml')" style="margin-left:1%;"></input>
                        <input type="button" class="button blue" value="View Data" onClick="callPage('leads','show_data')" style="margin-left:1%;"></input>
                    </div>                   
                </div>            	        
            </div>
            <?php
			if(isset($data['arr_coloum']) && isset($data['download_data']))
			{	
			?>
            <div class="col_12" style="margin-top:40px;" id="filter_table">
    			<div class="widget clearfix">
                 <h2>Filtered Details</h2>
                    <div class="widget_inside">
                        <div style="overflow:scroll;">
                
                        <table class='dataTable'>
                                <thead>
                                    <tr>
                                        <th class="serial_number center">Sr.No.</th>
                                        <?php
                                        for($i=0;$i<count($data['arr_coloum']); $i++)
                                        {
                                        ?>
                                        <th><?php echo $data['arr_coloum'][$i]; ?></th>
                                        <?php 	
                                        }
                                        ?>                               
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        for($i=0;$i<count($data['download_data']); $i++)
                                        {
                                        ?>
                                        <tr>
                                            <td><?php echo ($i+1); ?></td>
                                        <?php
                                            foreach($data['download_data'][$i] as $k=>$v)
                                            { 
                                            ?>
                                            <td><?php echo $v; ?></td>
                                            <?php
                                            } ?>
                                        </tr>
                                        <?php 	
                                        }
                                        ?>
                                </tbody>
                            </table>
                        </div>
                      </div>
            	</div>
            </div>
            <?php } ?>
		</div>
    </div>
</div>
<!-- END Main Container-->
<script language="javascript" type="text/javascript">

$(document).ready( function () {
	$('.from_dur').datepicker({
				dateFormat: "d M, yy",
				altField: ".from_dur_alt",
				altFormat: "yy-mm-dd"
		});
	$('.to_dur').datepicker({
				dateFormat: "d M, yy",
				altField: ".to_dur_alt",
				altFormat: "yy-mm-dd"
		});		
	//$('#mainForm').attr('action', 'download_lead.php'); 
	
});
function download(type,page,func)
{
	var chk_checked = false;
	$("input[name='chk_calls[]']").each(function(){
		if($(this).is(':checked')) 
		{ chk_checked = true; return false;}	
	});	
	if(chk_checked == false)
	{
		$("input[name='chk_click[]']").each(function(){
			if($(this).is(':checked')) 
			{ chk_checked = true; return false;}	
		});	
	}
	if(chk_checked == false)
	{
		$("input[name='chk_locations[]']").each(function(){
			if($(this).is(':checked')) 
			{ chk_checked = true; return false;}	
		});
	}
	if(chk_checked == false)
	{
		$("input[name='chk_devices[]']").each(function(){
			if($(this).is(':checked')) 
			{ chk_checked = true; return false;}	
		});
	}
	if(chk_checked == false)
	{
		alert(error.Lead.download);
		return false;
	}
	else
	{			
		$("#filter_table").hide();
		$('#mainForm').attr('action', 'download_lead.php'); 
		$("#download_type").val(type);
		document.getElementById('mainForm').submit();
		$('#mainForm').attr('action', '');
	}
}
function check_all(this_check, check)
{	
	if($(this_check).is(':checked'))
	{
		if(check == 'other')
		{
			$("input[name='chk_locations[]']").each(function(){
				$(this).attr('checked', 'checked');		
			});	
			$("input[name='chk_devices[]']").each(function(){
				$(this).attr('checked', 'checked');		
			});	
		}
		else
		{
			$("input[name='"+check+"[]']").each(function(){
				$(this).attr('checked', 'checked');		
			});	
		}
	}
	else
	{
		if(check == 'other')
		{
			$("input[name='chk_locations[]']").each(function(){
				$(this).removeAttr('checked', 'checked');	
			});	
			$("input[name='chk_devices[]']").each(function(){
				$(this).removeAttr('checked', 'checked');		
			});	
		}
		else
		{
			$("input[name='"+check+"[]']").each(function(){
				$(this).removeAttr('checked', 'checked');			
			});	
		}
	}
}
function check_field(this_check,allname)
{
	var name = $(this_check).attr('name');
	var chk_check = false;
	var chk = false;
	$("input[name='"+name+"']").each(function(){
		if($(this).is(':checked')) 
		{
			chk = true;									
		}
		else
		{
			chk_check = true;
			if($("input[name='"+allname+"']").is(':checked'))
			{
			 	$("input[name='"+allname+"']").removeAttr('checked', 'checked');				
			}
			return false;
		}
	});	
	if(chk == true && chk_check == false)
	{
		$("input[name='"+allname+"']").attr('checked', 'checked');		
	}
	
}
</script>