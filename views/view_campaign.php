<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
	exit();
}
?>
<input type="hidden" name="show_status" id="show_status" value="" />
<input type="hidden" name="mainfunction" id="mainfunction" value="<?php if(isset($data['mainfunction'])) echo $data['mainfunction']; else  echo $mainfunction; ?>" />
<input type="hidden" name="subfunction_name" id="subfunction_name" value="<?php if(isset($data['subfunction_name'])) echo $data['subfunction_name']; else echo $subfunction_name; ?>" />

<div class="container" id="actualbody">

<div class="row clearfix">
    <div class="col_12">
        <div class="widget clearfix">
            <h2>Dashboard  (<?php echo $data['campaign']['name']; ?>)</h2>
            <div class="row clearfix">
                <div class="col_4" style="margin: 1% 1.3%;">
                    <div class="widget clearfix">
                        <h2><?php echo $data['campaign']['name']; ?></h2>
                        <div class="widget_inside">
                        	<div style="margin-top: 5px;">
                            	<span style="margin-right: 39.1%;">Calls</span>
                                <span style="margin-right: 10%;">
									<?php 
										if(isset($data['getCompoundStatsCampaigns']['calls'])) 
											echo $data['getCompoundStatsCampaigns']['calls'];
										else
											echo "0";	
									?>
                                </span>
                            </div><br/>
                            <div style="margin-top: 5px;">
                            	<span style="margin-right: 24%;">Transcations</span>
                                <span style="margin-right: 10%;">
									<?php 
										if(isset($data['getCompoundStatsCampaigns']['transactions'])) 
											echo $data['getCompoundStatsCampaigns']['transactions'];
										else
											echo "0";	
									?>
                                </span>
                            </div><br/>
                            <div style="margin-top: 5px;">
                            	<span style="margin-right: 17%;">Conversion ratio</span>
                                <span style="margin-right: 10%;">
									<?php 
										if(isset($data['getCompoundStatsCampaigns']['conversion_ratio'])) 
											echo intval($data['getCompoundStatsCampaigns']['conversion_ratio'])." %";
										else
											echo "0 %";	
									?>
                                </span>
                            </div><br/>
                        </div>
                    </div>
                </div>
                <div class="col_7 last" style="margin: 1% 1.3%;">	
                	<!-- Start col_12 -->
                    <div class="col_12">
                        <div id="placeholder" style="height:250px;"></div>
                        <div id="overview" style="height:50px;margin-left:3%;"></div>
                    </div>
                    <!-- End col_12 -->
                    
                </div>
                <!--<div class="col_4" style="margin: 1% 1.3%;">
                    <div class="widget clearfix">
                        <h2>Quick Links</h2>
                        <div class="widget_inside">
                        	 <a href="javascript:showForm(0,'campaign','show_campaign_form');">Create A New Campaign</a>                          
                        </div>
                    </div>
                </div>
                <div class="col_4 last" style="margin: 1% 1.3%;">
                    <div class="widget clearfix">
                        <h2>Notifications</h2>
                        <div class="widget_inside">
                            <p>A last class is added for the last column in a row to remove margin-right</p>
                            <p>A last class is added for the last column in a row to remove margin-right last class is added ast class is added for the last </p>
                                                     
                        </div>
                    </div>
                </div>-->
            </div>
		</div>
	</div>
</div>

<div class="row clearfix">
    <div class="widget clearfix">
        <h2>All Transactions</h2>
         <?php
				if(isset($notificationArray))
					populateNotification($notificationArray);
            ?>
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
                            <th>Mtid</th>
                            <!--<th>SubId</th>
                             <th>SubId2</th>-->
                            <th>Tid</th>                            
                            <th>Ip Address</th>
                            <th>User Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php
					   $i=1;	
					   foreach($data['clicks'] as $click)
					   {
							echo"<tr>";
						   		echo"<td class='center' style='width:60px;'>".$i++."</td>";
								//echo"<td class='center'><a href=\"javascript:show_details('".$click['mt_id']."','campaign','show_click_details')\">".$click['mt_id']."</a></td>";
								//echo"<td><a href=\"javascript:show_details('".$click['mt_id']."','campaign','show_click_details')\">".$click['sub_id']."</a></td>";
								echo"<td><a href=\"javascript:show_details('".$click['mt_id']."','campaign','show_click_details')\">".$click['subid2']."</a></td>";
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

<div class="row clearfix">
    <div class="widget clearfix">
        <h2>All Calls </h2>
         <?php
				if(isset($notificationArray))
					populateNotification($notificationArray);
            ?>
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
                            <th>Mtid</th>
                            <th>Call From</th>
                            <th>Call To</th> 
                            <th>Call Start Time</th>                           
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <!--<th>Type</th>-->
                            <th>Duration</th>
                            <th>Is Converted</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php
					   $i=1;	
					   foreach($data['calls'] as $call)
					   {
							echo"<tr>";
						   		echo"<td class='center' style='width:60px;'>".$i++."</td>";								
								echo"<td class='center'><a href=\"javascript:show_details('".$call['call_id']."','campaign','show_call_details')\">".$call['mt_id']."</a></td>";
								echo"<td><a href=\"javascript:show_details('".$call['call_id']."','campaign','show_call_details')\">".$call['CallFrom']."</a></td>";
								echo"<td>".$call['CallTo']."</td>";	
								echo"<td>".$call['CallStartTime']."</a></td>";							
								echo"<td>".$call['ToCity']."</td>";
								echo"<td>".$call['ToState']."</td>";
								echo"<td>".$call['ToCountry']."</td>";
								//echo"<td></td>";
								echo"<td>".$call['CallDuration']." seconds</td>";
                                if($call['is_postback'] == 1)
                                    echo"<td>Yes</td>";
                                else
                                    echo"<td>No</td>";
							echo"</tr>";
					   }
					  ?>	
                    </tbody>
                 </table>
              </div>
        </div>
    </div>
</div> 
<script language="javascript" type="text/javascript">
$(document).ready(function(e) {
	runCampaignReport();
});
	function runCampaignReport()
	{
		var main_selector="#placeholder",sub_selector="#overview";
		camp_dataset=[];
		<?php
				$min_count=15;
				$show_points=",lines: {show: true, fill: 0},points: {show: true}";
				// for calls
				if(isset($data['arr_details']) && (count($data['arr_details'])> 0)){
					foreach($data['arr_details'] as $key => $value){
						$camp_dataset1 ="{
								label:'Calls',
								data:[{$value['data']}]{$show_points}
							}";			
						
					}
				}
				else
				{
					$camp_dataset1 = "{}";
				}
				// for clicks
				if(isset($data['arr_details_clicks']) && (count($data['arr_details_clicks'])> 0)){
					foreach($data['arr_details_clicks'] as $key => $value){
						$camp_dataset2 ="{
								label:'Clicks',
								data:[{$value['data']}]{$show_points}
							}";	
					}
				}
				else
				{
					$camp_dataset2 = "{}";
				}
				echo "camp_dataset= [ ".$camp_dataset1.",".$camp_dataset2."];";
			
		?>
			var camp_dataset_less=new Array();
			for(var i=0;i<camp_dataset.length;i++){
				camp_dataset_less[i]= $.extend({}, camp_dataset[i]);
//				camp_dataset_less[i]=clone camp_dataset[i];
				delete camp_dataset_less[i].label;
			}

			//console.log(camp_dataset_less);
			options = {
				xaxis: {
					mode: "time",
					tickLength: 5
				},
				selection: {
					mode: "x"
				},
				grid: {
					hoverable: true,
					backgroundColor: {colors: ["#FFFFFF", "#EEEEEE"]}
				}
			};
			
			var previousPoint = null;
			$(main_selector).bind("plothover", function (event, pos, item) {
					if (item) {
						if (previousPoint != item.datapoint) {
							previousPoint = item.datapoint;
							
							$("#tooltip").remove();
							var x = item.datapoint[0].toFixed(2),
								y = item.datapoint[1].toFixed(2);
							
							showTooltip(item.pageX, item.pageY,y);
						}
					}
					else {
						$("#tooltip").remove();
						clicksYet = false;
						previousPoint = null;            
					}
			});
			
			plot = $.plot(main_selector,  camp_dataset,  options);
	
			overview = $.plot(sub_selector, camp_dataset_less, {
				series: {
					lines: {
						show: true,
						lineWidth: 1
					},
					shadowSize: 0
				},
				xaxis: {
					ticks: [],
					mode: "time"
				},
				yaxis: {
					ticks: [],
					min: 0,
					autoscaleMargin: 0.1
				},
				selection: {
					mode: "x"
				},
				grid: {
					hoverable: true,
					backgroundColor: {colors: ["#FFFFFF", "#EEEEEE"]}
				}
			});
	
			// now connect the two
	
			$(main_selector).bind("plotselected", function (event, ranges) {
	
				// do the zooming
	
				plot = $.plot(main_selector, camp_dataset, $.extend(true, {}, options, {
					xaxis: {
						min: ranges.xaxis.from,
						max: ranges.xaxis.to
					}
				}));
	
				// don't fire event on the overview to prevent eternal loop
	
				overview.setSelection(ranges, true);
			});
	
			$(sub_selector).bind("plotselected", function (event, ranges) {
				plot.setSelection(ranges);
			});
	}

</script>