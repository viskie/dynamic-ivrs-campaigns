<?php
?>

    <div id="tab-2">   
    	
        	<div class="row" style="padding-bottom:10px;">               
                <div style="width:26%;float:left;">
                    <div style="float:left;position:relative;text-align:center;">
                        <div class="label_with_button" >
                            <div style="float:left;">
                                <label class="up-align" style="max-width:50px;line-height:24px;">Select Campaigns</label>
                            </div>
                            <div style="float:left;margin-left:10px;">
                               <select name="sel_campaign" id="sel_campaign">
                                    <option value="0">Select Campaign</option>
                                    <?php
                                    for($i=0; $i<count($data['campaigns']);$i++)
                                    {
										$selected = "";
										if(isset($data['sel_options']['sel_campaign']) && ($data['sel_options']['sel_campaign'] != "") )
										{											
											if($data['campaigns'][$i]['camp_id'] == $data['sel_options']['sel_campaign'])
												$selected = "selected='selected'";
										}
                                    ?>
                                    <option value="<?php echo $data['campaigns'][$i]['camp_id']; ?>" <?php echo $selected; ?>><?php echo $data['campaigns'][$i]['name']; ?></option>
                                    <?php 
                                    }
                                    ?>
                                </select>
                            </div>                        
                        </div>
                    </div>
               </div>
               
                <div style="width:17%; float:left;">
                    <label class="up-align" style="max-width:50px;line-height:24px;">Duration</label>
                    <select name="camp_sel_condition" id="camp_sel_condition" onchange="showDatepickers(this,2);">
                    	<option value="0" <?php if(!isset($data['sel_options']['camp_sel_condition']) ||($data['sel_options']['camp_sel_condition'] == '0')) echo "selected='selected'"; ?>>Select Duration</option>
                        <option value="1" <?php if(isset($data['sel_options']['camp_sel_condition']) && ($data['sel_options']['camp_sel_condition'] == '1')) echo "selected='selected'"; ?>>Today</option>
                        <option value="2" <?php if(isset($data['sel_options']['camp_sel_condition']) && ($data['sel_options']['camp_sel_condition'] == '2')) echo "selected='selected'"; ?>>Yesterday</option>
                        <option value="3" <?php if(isset($data['sel_options']['camp_sel_condition']) && ($data['sel_options']['camp_sel_condition'] == '3')) echo "selected='selected'"; ?>>Last 7 days</option>
                        <option value="4" <?php if(isset($data['sel_options']['camp_sel_condition']) && ($data['sel_options']['camp_sel_condition'] == '4')) echo "selected='selected'"; ?>>Last 30 days</option>
                        <option value="5" <?php if(isset($data['sel_options']['camp_sel_condition']) && ($data['sel_options']['camp_sel_condition'] == '5')) echo "selected='selected'"; ?>>This Month</option>
                        <option value="6" <?php if(isset($data['sel_options']['camp_sel_condition']) && ($data['sel_options']['camp_sel_condition'] == '6')) echo "selected='selected'"; ?>>This Quarter</option>
                        <option value="7" <?php if(isset($data['sel_options']['camp_sel_condition']) && ($data['sel_options']['camp_sel_condition'] == '7')) echo "selected='selected'"; ?>>This Year</option>
                        <option value="8" <?php if(isset($data['sel_options']['camp_sel_condition']) && ($data['sel_options']['camp_sel_condition'] == '8')) echo "selected='selected'"; ?>>All Years</option>
                        <option value="9" <?php if(isset($data['sel_options']['camp_sel_condition']) && ($data['sel_options']['camp_sel_condition'] == '9')) echo "selected='selected'"; ?>>Custom</option>
                   </select>
                </div>
                <!-- Start widget_inside -->
                <div class="widget_inside clearfix" style="">
                    <div class="col_3" style="text-align:center;">
                         <input type="button" value="Submit" class="button blue" onclick="showReports('reports','campaign_report',2);" />                        
                    </div>
                </div>
                <!-- End widget_inside -->
                <!-- End col_3 -->
                <div class="custom_from_div1" style="visibility:hidden; float:left; width:41%;margin-left: 1.6%;">
                    <label class="up-align" style="max-width:50px;line-height:24px;margin-left:10%"">From</label>
                    <input type="text" name="camp_custom_from" class="camp_custom_from" />
                    <input type="hidden" name="camp_custom_from_alt" class="camp_custom_from_alt" />
                    
                    <label class="up-align" style="max-width:50px;line-height:24px;margin-left:15%">To</label>
                    <input type="text" name="camp_custom_to" class="camp_custom_to" />
                    <input type="hidden" name="camp_custom_to_alt" class="camp_custom_to_alt" />
                </div>
                <!-- End col_3 -->   
                        
                <!-- End widget_inside -->
                
                <?php if($data['is_single_campaign']) { ?>
                 <div style="text-align:right; padding:5px 25px 10px 5px;">
                 	Show in Reports : 
                	<input type="checkbox" name="chk_call" id="chk_call" value="1" checked="checked"  onchange="show_graph()"/> Calls
                    <input type="checkbox" name="chk_click" id="chk_click" value="2" checked="checked" onchange="show_graph()" /> Clicks
                   <?php } ?>
                </div>           
                <!-- Start widget_inside -->
                <div class="widget_inside clearfix">
                    <!-- Start col_12 -->
                    <div class="col_12">
                        <div id="camp_placeholder" style="height:400px;"></div>
                    </div>
                    <div class="col_12">
                        <div id="camp_overview" style="height:100px;"></div>
                    </div>
                    <!-- End col_12 -->
                    
                </div>           
       		</div>
        <?php if(isset($data['is_single_campaign']) && $data['is_single_campaign']) { ?>
            <div class="row" style="padding-bottom:10px;">
             	<div class="widget clearfix">
                	<h2 >All Transactions</h2>
                	<div class="widget_inside">
                   		<div class="col_12">
                        <!--<div class="show_links">
                            <a href="javascript:show_records(0, 'campaign', 'view_campaign')" < ? if(isset($_REQUEST['show_status']))if($_REQUEST['show_status'] == 0) {?>style="color:black;"< ? } ?>>All(1)</a><span> | </span>
                            <a href="javascript:show_records(1, 'campaign', 'view_campaign')" < ? if(isset($_REQUEST['show_status']))if($_REQUEST['show_status'] == 1) {?>style="color:black;"< ? } ?>>Active(1)</a><span> | </span>
                            <a href="javascript:show_records(2, 'campaign', 'view_campaign')" < ? if(isset($_REQUEST['show_status']))if($_REQUEST['show_status'] == 2) {?>style="color:black;"< ? } ?>>Deleted(1)</a>
                        </div>
                        <a href="javascript:callPage('campaign','show_campaign_form');" class="for_links">Add Campaign</a>-->
                        <table class='dataTable'>
                            <thead>
                                <tr>
                                    <th class="serial_number center"  style='width:60px;'>Sr.no.</th>
                                    <th>mtid</th>
                                   <!-- <th>subid</th>-->
                                    <th>tid</th>                            
                                    <th>ip address</th>
                                    <th>user agent</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php
                               $i=1;	
                               foreach($data['table_clicks'] as $click)
                               {
                                    echo"<tr>";
                                        echo"<td class='center' style='width:60px;'>".$i++."</td>";
                                        echo"<td class='center'><a href=\"javascript:show_details('".$click['mt_id']."','campaign','show_click_details')\">".$click['mt_id']."</a></td>";
                                        //echo"<td><a href=\"javascript:show_details('".$click['mt_id']."','campaign','show_click_details')\">".$click['sub_id']."</a></td>";
                                        echo"<td>".$click['tid']."</td>";
                                        echo"<td>".$click['ip_address']."</td>";								
                                        echo"<td>".$click['user_agent']."</td>";
                                    echo"</tr>";
                               }
                              ?>	
                            </tbody>
                         </table>
                      </div>
                </div>
            </div>           
		 </div>
         
         <div class="row" style="padding-bottom:10px;">
            <div class="widget clearfix">
                <h2>All Calls </h2>
                <div class="widget_inside">
                   <div class="col_12">
                        <!--<div class="show_links">
                            <a href="javascript:show_records(0, 'campaign', 'view_campaign')" < ? if(isset($_REQUEST['show_status']))if($_REQUEST['show_status'] == 0) {?>style="color:black;"< ? } ?>>All(1)</a><span> | </span>
                            <a href="javascript:show_records(1, 'campaign', 'view_campaign')" < ? if(isset($_REQUEST['show_status']))if($_REQUEST['show_status'] == 1) {?>style="color:black;"< ? } ?>>Active(1)</a><span> | </span>
                            <a href="javascript:show_records(2, 'campaign', 'view_campaign')" < ? if(isset($_REQUEST['show_status']))if($_REQUEST['show_status'] == 2) {?>style="color:black;"< ? } ?>>Deleted(1)</a>
                        </div>
                        <a href="javascript:callPage('campaign','show_campaign_form');" class="for_links">Add Campaign</a>-->
                        <table class='dataTable'>
                            <thead>
                                <tr>
                                    <th class="serial_number center" style='width:60px;'>Sr.no.</th>                            
                                    <th>mtid</th>
                                    <th>Call From</th>
                                    <th>Call To</th> 
                                    <th>Call Start Time</th>                           
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Country</th>
                                    <!--<th>Type</th>-->
                                    <th>Duration</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php
                               $i=1;	
                               foreach($data['table_calls'] as $call)
                               {
                                    echo"<tr>";
                                        echo"<td class='center' style='width:60px;'><a href=\"javascript:show_details('".$call['call_id']."','campaign','show_call_details')\">".$i++."</a></td>";								
                                        echo"<td class='center'><a href=\"javascript:show_details('".$call['call_id']."','campaign','show_call_details')\">".$call['mt_id']."</a></td>";
                                        echo"<td><a href=\"javascript:show_details('".$call['call_id']."','campaign','show_call_details')\">".$call['CallFrom']."</a></td>";
                                        echo"<td>".$call['CallTo']."</td>";	
                                        echo"<td>".$call['CallStartTime']."</a></td>";							
                                        echo"<td>".$call['ToCity']."</td>";
                                        echo"<td>".$call['ToState']."</td>";
                                        echo"<td>".$call['ToCountry']."</td>";
                                        echo"<td>".$call['CallDuration']." seconds</td>";								
                                    echo"</tr>";
                               }
                              ?>
                            </tbody>
                         </table>
                      </div>
                	</div>
           		</div>
           </div>
           </div>
		<?php } ?>         
  
