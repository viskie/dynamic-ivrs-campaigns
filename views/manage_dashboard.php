<div class="container" id="actualbody">

<div class="row clearfix"><div class="col_12">
    <div class="widget clearfix">
        <h2>Today's Statistics</h2>
        <div class="widget_inside">
            
            <!--<h3></h3>-->
            
<!--            <div class="report">
                <div class="button up">
                    <span class="value">1,337</span>
                    <span class="attr">Views</span>
                </div>
                <div class="button down">
                    <span class="value">9,001</span>
                    <span class="attr">Pageviews</span>
                </div>
                <div class="button">
                    <span class="value">3.142</span>
                    <span class="attr">Pages/Views</span>
                </div>
                <div class="button">
                    <span class="value">83%</span>
                    <span class="attr">Bounce Rate</span>
                </div>
                <div class="button">
                    <span class="value">00:00:33</span>
                    <span class="attr">Avg. Time on Site</span>
                </div>
                <div class="button">
                    <span class="value">42%</span>
                    <span class="attr">New Visits</span>
                </div>
            </div>-->
            
            <div class="col_12">
                
                <div id="chart" style="width: 100%; height: 400px;"></div>
                <div id="sub_chart" style="width: 98.5%; height: 80px; margin-left:1.4%; float:left;"></div>
                <!--<div id="chart" style="width: 80%; height: 400px; float:left;"></div>
                <div  id="legendholder" style="float:left; width:18%;"></div>
                <div id="sub_chart" style="width: 78.6%; height: 80px; margin-left:1.4%; float:left;"></div>-->
                <div class="clearfix"></div>
                <script type="text/javascript">
                    $(function () {
								var main_selector="#chart",sub_selector="#sub_chart";

								camp_dataset=[];
								
							<?php
							$show_points=",lines: {show: true, fill: 0},points: {show: true}";								
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
							
							
							/*if(isset($data['arr_details']) && (count($data['arr_details'])> 0)){
								$show_points=(count($data['arr_details'])< $min_count)?",lines: {show: true, fill: 0},points: {show: true}":"";
								foreach($data['arr_details'] as $key => $value){
									$camp_dataset1 ="{
											label:'Campaigns', //{$value['name']}
											data:[{$value['data']}]{$show_points}
										}";			
									echo "camp_dataset.push({$camp_dataset1});";
								}
								//echo" console.log(camp_dataset);";
							}*/
							?>
							var camp_dataset_less=[];
							for(var i=0;i<camp_dataset.length;i++){
								camp_dataset_less[i]= $.extend({}, camp_dataset[i]);
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
								},
								/*legend: { 
									show: true, 
									container: '#legendholder',
									sorted : 'ascending' 
								}*/
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
                            
                        });
                </script>
            </div>
        </div>
    </div>
</div></div>