<?php
?>
    <div id="tab-3">        	
             <div class="row" style="padding-bottom:10px;">
             	<div id="loading4" style="display:none;"><img src="img/loading.gif" /></div>
             	<!--<div class="widget clearfix"> -->               	
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
                                    <th>Phone Number</th>
                                    <th>Campaign Name</th>
                                    <th>Total Phone Numbers in Campaign</th>                            
                                    <th>Last Used</th>
                                    <th>Unused Since Days</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php
                               $i=1;	
                               foreach($data['arr_numbers'] as $number)
                               {
                                    echo"<tr id='row_".$i."'>";
                                        echo"<td class='center' style='width:60px;'>".$i++."</td>";
                                        echo"<td class='center'>".$number['friendly_name']."</td>";
                                        echo"<td>".$number['name']."</td>";
                                        echo"<td>".$number['total_no']."</td>";
                                        echo"<td>".$number['last_used']."</td>";								
                                        echo"<td>".$number['day_diff']."</td>";
										echo"<td><a href=\"javascript:number_release('".$number['number_id']."',".($i-1).")\" class=\"tiptip-top\" title=
								\"Release\" style='color:#0774A7'>Release</a></td>";
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
function number_release(number_id,row)
{
	$("#loading4").show();
	$("#tab-3").css({ opacity: 0.6 });
	$("#loading4").css({ opacity: 1 });
	$.ajax({
			url:"ajax.php",
			type:"POST",
			data: {
					page 	 	  : 'reports',
					function 	  : 'ajax_number_release',
					number_id 	  : number_id,					
				  },		
			success:function(resp)
			{						
				alert(error.Campaign.num_released);				
				$("#tab-3").css({ opacity: 1 });	
				$("#loading4").hide();
				$("#row_"+row).hide();				
			}
		});	
}
</script>