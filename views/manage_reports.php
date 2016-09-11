<?php
include("campaign_helper.php"); 
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
	exit();
}
?>
<!-- Main Container-->
<div class="container" id="actualbody">
	<div class="row">
        <div class="widget clearfix tabs">
            <ul>
                <li><h2><a id="tab_title1" href="#tab-1">Multi-Campaign Reports</a></h2></li>
                <li><h2><a id="tab_title2" href="#tab-2">Campaign Reports</a></h2></li> 
                <li><h2><a id="tab_title3" href="#tab-3">Numberwise Report</a></h2></li> 
            </ul>
            <!--<div class="widget_inside">-->
            	<div id="tab-1">
                
                	<div class="row">                                      
                        <div class="col_3">
                            <div style="float:left;position:relative;text-align:center;">
                                <div class="label_with_button" >
                                    <div style="float:left;">
                                        <span class="close_child_page" style="height:24px;"></span>
                                        <label class="up-align" style="max-width:50px;line-height:24px;">Campaigns</label>
                                    </div>
                                    <div style="float:left;margin-left:10px;">
                                        <ul class="switch_container" id="on_off_btn" style="margin:0px">
                                            <li class="on"><a id="switch_off" href="#">None</a></li>
                                            <li ><a id="switch_on" href="#">All</a></li>
                                        </ul>
                                    </div>
                                    <div style="clear:both;"></div>
                                    <div style="padding-top:3px;text-align:left;" class="selected_text"><span>None</span> selected.</div>
                                    <div id="campaign_dw_list" style="display:none">
                                    	<?php
											$selected_campaigns=isset($data['sel_options']['selected_campaigns'])?$data['sel_options']['selected_campaigns']:array();
											foreach($data['campaigns'] as $campaign){
	                                        	echo '<div style="float:left;min-width:160px;padding-right:10px"><input type="checkbox" name="camp_id[]" class="campaigns" value="'.$campaign['camp_id'].'" camp_name="'.$campaign['name'].'" />'.$campaign['name'].'</div>';
											}
										?>
                                    </div>
                                </div>
                            </div>
                       </div>
                         
                         <!-- End col_3 -->
                        <div class="col_3">
                            <label class="up-align" style="max-width:50px;line-height:24px;">Duration</label>
                            <select name="sel_condition" id="sel_condition" onchange="showDatepickers(this,1);">
                                <option value="0" <?php if(!isset($data['sel_options']['sel_condition']) ||($data['sel_options']['sel_condition'] == '0')) echo "selected='selected'"; ?>>Select Duration</option>
                                <option value="1" <?php if(isset($data['sel_options']['sel_condition']) && ($data['sel_options']['sel_condition'] == '1')) echo "selected='selected'"; ?>>Today</option>
                                <option value="2" <?php if(isset($data['sel_options']['sel_condition']) && ($data['sel_options']['sel_condition'] == '2')) echo "selected='selected'"; ?>>Yesterday</option>
                                <option value="3" <?php if(isset($data['sel_options']['sel_condition']) && ($data['sel_options']['sel_condition'] == '3')) echo "selected='selected'"; ?>>Last 7 days</option>
                                <option value="4" <?php if(isset($data['sel_options']['sel_condition']) && ($data['sel_options']['sel_condition'] == '4')) echo "selected='selected'"; ?>>Last 30 days</option>
                                <option value="5" <?php if(isset($data['sel_options']['sel_condition']) && ($data['sel_options']['sel_condition'] == '5')) echo "selected='selected'"; ?>>This Month</option>
                                <option value="6" <?php if(isset($data['sel_options']['sel_condition']) && ($data['sel_options']['sel_condition'] == '6')) echo "selected='selected'"; ?>>This Quarter</option>
                                <option value="7" <?php if(isset($data['sel_options']['sel_condition']) && ($data['sel_options']['sel_condition'] == '7')) echo "selected='selected'"; ?>>This Year</option>
                                <option value="8" <?php if(isset($data['sel_options']['sel_condition']) && ($data['sel_options']['sel_condition'] == '8')) echo "selected='selected'"; ?>>All Years</option>
                                <option value="9" <?php if(isset($data['sel_options']['sel_condition']) && ($data['sel_options']['sel_condition'] == '9')) echo "selected='selected'"; ?>>Custom</option>
                           </select>
                        </div>
                        <!-- Start widget_inside -->
                        <div class="widget_inside clearfix">
                            <div class="col_3" style="text-align:center;">
                                <input type="button" value="Submit" class="button blue" onclick="showReports('reports','view_reports',1);" />
                                <input type="hidden" name="is_post_back" id="is_post_back" value="FALSE" />
                            </div>
                        </div>
                        <!-- End widget_inside -->
                        <!-- End col_3 -->
                        <div class="col_3 custom_from_div" style="visibility:hidden;margin-left: 1.6%; padding-bottom:10px;">
                            <label class="up-align" style="max-width:50px;line-height:24px;">From</label>
                            <input type="text" name="custom_from" class="custom_from" />
                            <input type="hidden" name="custom_from_alt" class="custom_from_alt" />
                        </div>
                        <!-- End col_3 -->
                        <div class="col_3 last custom_to_div" style="visibility:hidden; padding-bottom:10px;">
                            <label class="up-align" style="max-width:50px;line-height:24px;">To</label>
                            <input type="text" name="custom_to" class="custom_to" />
                            <input type="hidden" name="custom_to_alt" class="custom_to_alt" />
                        </div>
                        <div class="clearfix"></div>
                        <!-- End col_3 -->   
                                
                        <!-- End widget_inside -->
                       
                        <!-- Start widget_inside -->
                        <div class="widget_inside clearfix">
                        
                        	<div id="placeholder_tr" style="width: 80%; height: 400px; float:left;"></div>
                           	<div  id="legendholder_tr" style="float:left; width:18%;"></div>
                            <div id="overview_tr" style="width: 78.6%; height: 80px; margin-left:1.4%; float:left;"></div>
                        
                            <!-- Start col_12 -->
                           <!-- <div class="col_12">
                                <div id="placeholder_tr" style="height:400px;"></div>
                            </div>
                            <div class="col_12">
                                <div id="overview_tr" style="height:100px;margin-left: 1.4%;"></div>
                            </div>-->
                            <!-- End col_12 -->
                        </div>
                            <!-- End widget_inside -->
                    </div>                  
                    
                    <?php if(isset($data['arr_multi_clicks'])){ ?>
                    <div class="row">
                        <div class="widget clearfix">
                            <h2>All Calls </h2>
                            <div class="widget_inside">
                               <div class="col_12">
                                    <table class='dataTable'>
                                        <thead>
                                            <tr>
                                                <th class="serial_number center"  style='width:60px;'>Sr.no.</th>
                                                <th>mtid</th>
                                                <!--<th>subid</th>-->
                                                <th>tid</th>                            
                                                <th>ip address</th>
                                                <th>user agent</th>
                                                <th>Campaign</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										   <?php
                                           $i=1;	
                                           foreach($data['arr_multi_clicks'] as $click)
                                           {
                                                echo"<tr>";
                                                    echo"<td class='center' style='width:60px;'>".$i++."</td>";
                                                    echo"<td class='center'><a href=\"javascript:show_details('".$click['mt_id']."','campaign','show_click_details')\">".$click['mt_id']."</a></td>";
                                                    //echo"<td><a href=\"javascript:show_details('".$click['mt_id']."','campaign','show_click_details')\">".$click['sub_id']."</a></td>";
                                                    echo"<td>".$click['tid']."</td>";
                                                    echo"<td>".$click['ip_address']."</td>";								
                                                    echo"<td>".$click['user_agent']."</td>";
                                                    echo"<td>".$click['campaign_name']."</td>";
                                                echo"</tr>";
                                           }
                                          ?>
                                        </tbody>
                                     </table>
                                  </div>
                                </div>
                            </div>
                       </div>
                       <?php } ?>
                       
                       <div class="row">
                            <!-- Start widget_inside -->
                            <div class="widget_inside clearfix">
                            	
                                <div id="placeholder" style="width: 80%; height: 400px; float:left;"></div>
                            	<div  id="legendholder" style="float:left; width:18%;">></div>
                                <div id="overview" style="width: 78.6%; height: 80px; margin-left:1.4%; float:left;"></div>
                                
                                <!-- Start col_12 -->
                                <!--<div class="col_12">
                                     <div id="placeholder" style="height:400px;"></div>
                                </div>
                                <div class="col_12">
                                    <div id="overview" style="height:100px;margin-left: 1.4%;"></div>
                                </div>-->
                                <!-- End col_12 -->
                            </div>
                            <!-- End widget_inside -->
                    	</div>
                       <?php if(isset($data['arr_multi_calls'])){ ?>
                       <div class="row">
                        <div class="widget clearfix">
                            <h2>All Clicks </h2>
                            <div class="widget_inside">
                               <div class="col_12">
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
                                                <th>Duration</th>
                                                <th>Campaign</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										   <?php
                                           $i=1;	
                                           foreach($data['arr_multi_calls'] as $call)
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
                                                    echo"<td>".$call['campaign_name']."</td>";
                                                echo"</tr>";
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
               <!-- </div>-->
				              
                <!--<div id="tab-2">                   
                    <div class="row">                         
                    </div>
                </div>-->
                <?php include("campaign_report.php");  ?>               
                <?php include("numberwise_report.php");  ?>               
                </div>
            </div>
        </div>
    </div>

<!-- END Main Container-->
<script>
function showDatepickers(this_select,from){	
	from = from || ''; 
	if($(this_select).val()=="9")
	{	
		if(from == 1)
		{	
			$(".custom_from_div,.custom_to_div").css('visibility','visible');
		}		
		else if(from == 2)
		{			
			$(".custom_from_div1,.custom_to_div1").css('visibility','visible');
		}		
	}
	else
	{
		if(from == 1)
			$(".custom_from_div,.custom_to_div").css('visibility','hidden');		
		else if(from == 2)
			$(".custom_from_div1,.custom_to_div1").css('visibility','hidden');		
	}
}
$(document).ready(function(e) {
	$(".close_child_page,.open_child_page").click(function(){
		//$(this).parent().find(".list").slideToggle();
		$('#campaign_dw_list').slideToggle('fast');
		$(this).toggleClass("close_child_page");
		$(this).toggleClass("open_child_page");		
	 });
	 
	$("#switch_on").click(function(){
		var this_element=$(this);
		$("#campaign_dw_list input.campaigns").each(function() {
			$(this).attr('checked','checked');
		});
//		$(".selected_text span").text("All");
		$("#campaign_dw_list input.campaigns").first().change();
		$("ul li").removeClass("on");
		$(this_element).parent().addClass("on");
		return false;
	});
	$("#switch_off").click(function(){
		var this_element=$(this);
		$("#campaign_dw_list input.campaigns").each(function() {
			$(this).removeAttr('checked');
		});
		
//		$(".selected_text span").text("None");
		$("#campaign_dw_list input.campaigns").first().change();
		$("ul li").removeClass("on");
		$(this_element).parent().addClass("on");
		return false;
	});
	
	$("#campaign_dw_list input.campaigns").change(function(){
		if($("#campaign_dw_list input.campaigns:checked").length===$("#campaign_dw_list input.campaigns").length){
			$("#switch_on").parent(this).addClass("on");
			$("#switch_off").parent(this).removeClass("on");
			$(".selected_text span").text("All");
		}else if($("#campaign_dw_list input.campaigns:checked").length===0){
			$("#switch_off").parent(this).addClass("on");
			$("#switch_on").parent(this).removeClass("on");
			$(".selected_text span").text("None");
		}else{
			$("ul li").removeClass("on");
			if($("#campaign_dw_list input.campaigns:checked").length==1){
				$(".selected_text span").text('"'+$("#campaign_dw_list input.campaigns:checked").attr('camp_name')+'"');
			}else{
				$(".selected_text span").text($("#campaign_dw_list input.campaigns:checked").length+" campaigns");
			}
		}
	});
	
	

<?php	//print_r($data);
		if(isset($data['is_single_campaign']) && $data['is_single_campaign']){	
			echo "runCampaignReport(2);";
		}
		else if(isset($_REQUEST['is_post_back']) && $_REQUEST['is_post_back']=="TRUE"){
			if(isset($data['sel_options']['selected_campaigns']) && count($data['sel_options']['selected_campaigns'])>0){
				foreach($data['sel_options']['selected_campaigns'] as $camp_to_select){
					echo '$("#campaign_dw_list input.campaigns[value=\''.$camp_to_select.'\']").attr("checked","checked");';
				}
			}
			echo '$("#campaign_dw_list input.campaigns").first().change();';
			echo "runCampaignReport(1);";
			
		}
		
		if(isset($data['sel_options']['sel_condition']) && $data['sel_options']['sel_condition'] == '9'){
			if(isset($data['sel_options']['custom_from_alt'])){
				echo "$('.custom_from').datepicker('setDate', parseISO8601('{$data['sel_options']['custom_from_alt']}'));";
			}
			if(isset($data['sel_options']['custom_to_alt'])){
				echo "$('.custom_to').datepicker('setDate', parseISO8601('{$data['sel_options']['custom_to_alt']}'));";
			}
			echo "showDatepickers($('#sel_condition'),1);";
		}
		
		if(isset($data['sel_options']['camp_sel_condition']) && $data['sel_options']['camp_sel_condition'] == '9'){
			if(isset($data['sel_options']['camp_custom_from_alt'])){
				echo "$('.camp_custom_from').datepicker('setDate',  parseISO8601('{$data['sel_options']['camp_custom_from_alt']}'));";
			}
			if(isset($data['sel_options']['camp_custom_to_alt'])){
				echo "$('.camp_custom_to').datepicker('setDate', parseISO8601('{$data['sel_options']['camp_custom_to_alt']}'));";
			}
			echo "showDatepickers($('#camp_sel_condition'),2);";
		}
?>

});

	
	function showTooltip(x, y, contents) {
		$('<div id="tooltip">'+contents+'</div>').css( {
			position: 'absolute',
			display: 'none',
			top: y + 10,
			left: x + 10,
			border: '1px solid #fdd',
			padding: '4px',
			'font-weight':'bold',
			'background-color': '#fee',
			opacity: 0.80
		}).appendTo("body").fadeIn(200);
	}
		
/*	function changeGraph(){
		var duration_value=0;
		var dataset=null;
		var selected_campaigns=new Array();
		
		if(duration_value==1){
			var campaign_today=new Array();
			< ?php
				echo "
					campaign_today['#camp_id']={
						label:'#camp_name',
						data:[";
						
				echo "]
					}
				";
			?>
		}else if(duration_value==2){
		}else if(duration_value==3){
		}else if(duration_value==4){
		}else if(duration_value==5){
		}else if(duration_value==6){
		}else if(duration_value==7){
		}else if(duration_value==8){
		}else if(duration_value==9){
		}
		
		var d11 = [];

		var d12 = [];
				var d11obj={					
						label: "Income",
                        data: d11,
                        lines: {show: true, fill: 0},
                    }
		 var dataSet = [d11obj,d12];//pacCount is a number that increments each call
		 
/*		plot = $.plot("#placeholder",  [
                    {
						label: "Income",
                        data: d11,
                        lines: {show: true, fill: 0},
                        //points: {show: true}
                    },
					 {
						label: "Expense",
                        data: d12,
                        lines: {show: true, fill: 0},
                        //points: {show: true}
                    }
                ],  options);* /
				
		 plot.setData(dataSet);
		 overview.setData(dataSet);
		 plot.setupGrid();
		 overview.setupGrid();
		 plot.draw();
		 overview.draw();
	}*/

function runCampaignReport(tab_number){
	var main_selector="#placeholder",sub_selector="#overview",legent_selector="#legendholder";
	if(tab_number===2){
		main_selector="#camp_placeholder";
		sub_selector="#camp_overview";
		legent_selector="";
		goToTab(2);
	}
	else if(tab_number===3){
		main_selector="#placeholder_num";
		sub_selector="#overview_num";
		legent_selector="";
		goToTab(3);
	}
	var clicks_dataset=[];
	camp_dataset=[];
	<?php
			$show_points=",lines: {show: true, fill: 0},points: {show: true}";
			$min_count=15;
			
			if(isset($data['is_single_campaign']) && $data['is_single_campaign']){
				// for calls
				if(isset($data['arr_details']) && (count($data['arr_details'])> 0)){
					foreach($data['arr_details'] as $key => $value){
						$camp_dataset1 ="{
								label:'Calls',
								data:[{$value['data']}]
							}";			
						
					}
				}
				else
				{
					$camp_dataset1 = "[]";
				}
				// for clicks
				if(isset($data['arr_details_clicks']) && (count($data['arr_details_clicks'])> 0)){
					foreach($data['arr_details_clicks'] as $key => $value){
//						$show_points=(count($data['arr_details'])< $min_count)?",lines: {show: true, fill: 0},points: {show: true}":"";
						$camp_dataset2 ="{
								label:'Clicks',
								data:[{$value['data']}]{$show_points}
							}";	
					}
				}
				else
				{
					$camp_dataset2 = "[]";
				}
				echo "camp_dataset= [ ".$camp_dataset1.",".$camp_dataset2."];";
			}
			else{
				//echo "alert(";print_r($data);echo");";
				if(isset($data['arr_details']) && (count($data['arr_details'])> 0)){
//					$show_points=(count($data['arr_details'])< $min_count)?",lines: {show: true, fill: 0},points: {show: true}":"";
					foreach($data['arr_details'] as $key => $value){
						$camp_dataset1 ="{
								label:'{$value['name']}',
								data:[{$value['data']}]{$show_points}
							}";			
						echo "camp_dataset.push({$camp_dataset1});";
					}
					//echo" console.log(camp_dataset);";
				}
				else
				{
					$camp_dataset1 = "[]";
				}
				if(isset($data['arr_details_clicks']) && (count($data['arr_details_clicks'])> 0)){
					foreach($data['arr_details_clicks'] as $key => $value){
						$camp_dataset2 ="{
								label:'{$value['name']}',
								data:[{$value['data']}]{$show_points}
							}";
						echo "clicks_dataset.push({$camp_dataset2});";
					}
				}
				else
				{
					$camp_dataset2 = "[]";
				}				//echo "camp_dataset= [ ".$camp_dataset1.",".$camp_dataset2."];";
			}
	?>
		var camp_dataset_less=[];
		if(tab_number==1){
			for(var i=0;i<camp_dataset.length;i++){
				camp_dataset_less[i]= $.extend({}, camp_dataset[i]);
//				camp_dataset_less[i]=clone camp_dataset[i];
				delete camp_dataset_less[i].label;
			}
			//console.log()
		}else{
			camp_dataset_less=camp_dataset;
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
			},
			legend: { 
					show: true, 
					container: legent_selector,
					sorted : 'ascending' 
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
		
		
		
		if(tab_number==1){
			
			camp_dataset_less=[];
			for(var i=0;i<clicks_dataset.length;i++){
				camp_dataset_less[i]= $.extend({}, clicks_dataset[i]);
				delete camp_dataset_less[i].label;
			}
			main_selector="#placeholder_tr";
			sub_selector="#overview_tr";
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
				},
				legend: { 
					show: true, 
					container: '#legendholder_tr',
					sorted : 'ascending' 
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
			
			plot = $.plot(main_selector,  clicks_dataset,  options);
	
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
	
				plot = $.plot(main_selector, clicks_dataset, $.extend(true, {}, options, {
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
}
	function show_graph()
	{
		var check_both = [];
		if($("#chk_call").is(':checked'))
			check_both.push(1); 			
		if($("#chk_click").is(':checked'))
			check_both.push(2); 			
				
		if(check_both.length == 2)
		{
			changeGraph(0);
		}
		else
		{
			if($.inArray(1, check_both) != -1)
				changeGraph(1);
			else if($.inArray(2, check_both) != -1)
				changeGraph(2);
			else
				changeGraph(-1);
		}		
		return false;
	}
	
	function changeGraph(option)
	{		
		var dataset=new Array();
		var selected_campaigns=new Array();			
		if(option == 1 || option == 0)	// for calls
		{			
		<?php			
			if(isset($data['arr_details']) && (count($data['arr_details'])> 0)){
				foreach($data['arr_details'] as $key => $value){
					$camp_dataset1 ="{
							label:'Calls',
							data:[{$value['data']}]
						}";			
		
					echo "dataset.push({$camp_dataset1});";

				}
			}
		?>		
		}
		if(option == 2 || option == 0)	// for clicks
		{		
		<?php 			
			if(isset($data['arr_details_clicks']) && (count($data['arr_details_clicks'])> 0)){
				foreach($data['arr_details_clicks'] as $key => $value){
					$camp_dataset2 ="{
							label:'Clicks',
							data:[{$value['data']}]
						}";	
					echo "dataset.push({$camp_dataset2});";
				}
			}
		?>
		}
		if(option == -1)	// for clicks
		{
			dataset.push({});
		}
		 plot.setData(dataset);
		 overview.setData(dataset);
		 plot.setupGrid();
		 overview.setupGrid();
		 plot.draw();
		 overview.draw();
	}
	
</script>