<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
	exit();
}
?>
<input type="hidden" name="show_status" id="show_status" value="<?php if(isset($data['show_status']) && ($data['show_status'] != '')) echo $data['show_status']; else echo '1'; ?>" />
<input type="hidden" name="mainfunction" id="mainfunction" value="<?php if(isset($data['mainfunction'])) echo $data['mainfunction']; else  echo $mainfunction; ?>" />
<input type="hidden" name="subfunction_name" id="subfunction_name" value="<?php if(isset($data['subfunction_name'])) echo $data['subfunction_name']; else echo $subfunction_name; ?>" />

<div class="container" id="actualbody">

<div class="row clearfix">
    <div class="col_12">
        <div class="widget clearfix" style="padding-bottom: 9px">
            <h2>Dashboard</h2>
            <div class="clearfix">
                <div class="col_4" style="margin: 1% 1.3%;">
                    <div class="widget clearfix">
                        <h2>All Campaigns</h2>
                        <div class="widget_inside less_padding">
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
                <div class="col_4" style="margin: 1% 1.3%;">
                    <div class="widget clearfix">
                        <h2>Quick Links</h2>
                        <div class="widget_inside less_padding">
                        	<?php if($data['arr_permission'][0]['add_perm'] == 1) { ?>
                        	<a href="javascript:showForm(0,'campaign','show_campaign_form');">Create A New Campaign</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col_4 last" style="margin: 1% 1.3%;">
                    <div class="widget clearfix">
                        <h2>Notifications</h2>
                        <div class="widget_inside less_padding">
                            <p>A last class is added for the last column in a row to remove margin-right</p>
                            <p>A last class is added for the last column in a row to remove margin-right last class is added ast class is added for the last </p>
                                                     
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
<div class="row clearfix" style="padding: 0px 0px 40px 0;">
    <div class="widget clearfix">
        <h2>Active Campaigns</h2>
         <?php
				if(isset($notificationArray))
					populateNotification($notificationArray);
            ?>
        <div class="widget_inside">
           <div class="col_12">
				<div class="show_links">
                    <a href="javascript:show_records(1, 'campaign', 'view_campaign')" <? if(isset($data['show_status']))if($data['show_status'] == 1) {?>style="color:black;"<? } ?>>Active(<?php echo $data['rec_counts']['active'] ?>)</a><span> | </span>
                    <a href="javascript:show_records(2, 'campaign', 'view_campaign')" <? if(isset($data['show_status']))if($data['show_status'] == 2) {?>style="color:black;"<? } ?>>Inactive(<?php echo $data['rec_counts']['inactive'] ?>)</a><span> | </span>
                    <a href="javascript:show_records(3, 'campaign', 'view_campaign')" <? if(isset($data['show_status']))if($data['show_status'] == 3) {?>style="color:black;"<? } ?>>Archived(<?php echo $data['rec_counts']['archived'] ?>)</a>
                </div>
                <table class='dataTable'>
                	<thead>
                        <tr>
                            <th class="serial_number center" style="width:30px;">Sr.no.</th>
                            <th style="max-width:120px; word-wrap:normal;">Campaign Name</th>
                            <th>Status</th>
                            <th style="max-width:180px; word-wrap:normal;">Postback Link</th>
                            <th>Calls</th>
                            <th>Transactions</th>
                            <th>Conv. ratio ( % )</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       <?php
					   $i=1;
					   foreach($data['active_campaigns'] as $campaign)
					   {
							echo"<tr>";
						   		echo"<td>".$i++."</td>";
								echo"<td><a href=\"javascript:show_details('".$campaign['camp_id']."','campaign','show_campaign_details')\">".$campaign['name']."</a></td>";
								echo"<td>";
										if($campaign['status'] == 1)
											echo "Active";
										else if($campaign['status'] == 2)
											echo "Inactive";
										else if($campaign['status'] == 3)
											echo "Archived";		
								echo"</td>";
								echo"<td>".$campaign['offer_url']."</td>";
								echo"<td>"; if($campaign['call_cnt'] != NULL) echo $campaign['call_cnt']; else echo "0"; echo"</td>";
								echo"<td>"; if($campaign['clicks_cnt'] != NULL) echo $campaign['clicks_cnt']; else echo "0"; echo"</td>";
								echo"<td>";
								if($campaign['clicks_cnt'] != NULL)
									$conversion_ratio = ($campaign['call_cnt']/$campaign['clicks_cnt'])*100;
								else
									$conversion_ratio = 0;
								echo intval($conversion_ratio);	
								echo"</td>";
								echo"<td>";
						   		if($data['arr_permission'][0]['edit_perm'] == 1) {
								echo"<a href=\"javascript:showForm('".$campaign['camp_id']."','campaign','show_campaign_form')\" class=\"tiptip-top\" title=
								\"Edit\"><img src=\"img/icon_edit.png\" alt=\"edit\"></a>";
								}								
								if($data['arr_permission'][0]['edit_perm'] == 1) {
									echo "&nbsp;&nbsp;&nbsp;<a href=\"javascript:deleteRecord('".$campaign['camp_id']."','".$campaign['name']."','campaign','delete_campaign')\" class=\"tiptip-top\" title=\"Delete\"><img src=\"img/icon_bad.png\" alt=\"delete\"></a>";
								echo"</td>";
								}
							echo"</tr>";
					   }
					  ?>	
                    </tbody>
                 </table>
              </div>
        </div>
    </div>
    