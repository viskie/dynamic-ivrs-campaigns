<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
	exit();
}
?>
<input type="hidden" name="show_status" id="show_status" value="<?php if(isset($data['show_status']) && ($data['show_status'] != '')) echo $data['show_status']; else echo '1'; ?>" />
<input type="hidden" name="onlyview" id="onlyview" value="" />
<input type="hidden" name="mainfunction" id="mainfunction" value="<?php if(isset($data['mainfunction'])) echo $data['mainfunction']; else  echo $mainfunction; ?>" />
<input type="hidden" name="subfunction_name" id="subfunction_name" value="<?php if(isset($data['subfunction_name'])) echo $data['subfunction_name']; else echo $subfunction_name; ?>" />
	
<!-- Main Container-->
<div class="container" id="actualbody">
	<div class="row">
    	<div class="widget clearfix">
    		<h2>Super Admins</h2>
           <?php
				if(isset($notificationArray))
					populateNotification($notificationArray);
            ?>
            <div class="widget_inside">
				<div class="col_12">
                	<div class="show_links">
                        <a href="javascript:show_records(0, 'users', 'view_super_user')" <? if(isset($data['show_status']))if($data['show_status'] == 0) {?>style="color:black;"<? } ?>>All(<?=$data['rec_counts']['all']?>)</a><span> | </span>
                        <a href="javascript:show_records(1, 'users', 'view_super_user')" <? if(isset($data['show_status']))if($data['show_status'] == 1) {?>style="color:black;"<? } ?>>Active(<?=$data['rec_counts']['active']?>)</a><span> | </span>
                        <a href="javascript:show_records(2, 'users', 'view_super_user')" <? if(isset($data['show_status']))if($data['show_status'] == 2) {?>style="color:black;"<? } ?>>Deleted(<?=$data['rec_counts']['deleted']?>)</a>
                    </div>
                    <?php if($data['arr_permission'][0]['add_perm'] == 1) { ?>
                    <a href="javascript:showForm('0','users','show_super_user_form');" class="for_links">Add User</a>
                    <?php } ?>
                    <table class='dataTable'>
                        <thead>
                            <tr>
                                <th class="serial_number center">Sr.No.</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Name</th>
                                <th>Phone</th>                                
                                <th class="action_column center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $i=1;
                               foreach($data['allUsers'] as $value)
                               {
                                   echo "<tr class='gradeX'>
                                    <td class='serial_number center'>".$i++."</td>
                                    <td class=\"backcolor\">".$value['user_name']."</td>
                                    <td>".$value['user_email']."</td>
                                    <td>".$value['name']."</td>
                                    <td>".$value['user_phone']."</td>									
                                    <td class='action_column center'>
                                    ";
									if($data['group_id'] == 3 && ($data['user_id'] != $value['user_id']))
									{
										echo "<a href=\"javascript:showForm('".$value['user_id']."','users','show_super_user_form','view')\" class=\"tiptip-top\" title=\"View\"><img src=\"img/icon_view.png\" alt=\"view\" /></a>";
										
									}
									else
									{
										if($value['is_active'] != 0){
											if($data['arr_permission'][0]['edit_perm'] == 1) { 	
												echo "<a href=\"javascript:showForm('".$value['user_id']."','users','show_super_user_form')\" class=\"tiptip-top\" title=\"Edit\"><img src=\"img/icon_edit.png\" alt=\"edit\" /></a>"; 
											}
											if($data['arr_permission'][0]['delete_perm'] == 1) { 
												echo "&nbsp;&nbsp;&nbsp;<a href=\"javascript:deleteRecord('".$value['user_id']."','".$value['user_name']."','users','delete_super_user')\" class=\"tiptip-top\" title=\"Delete\"><img src=\"img/icon_bad.png\" alt=\"delete\"></a>";
											}
										}else{
											if(isset($_POST['show_status'])){
												if($data['arr_permission'][0]['restore_perm'] == 1) { 
													echo "<a href=\"javascript:restoreEntry('".$value['user_id']."','users','restore_super_user')\" class=\"tiptip-top\" title=\"Restore\"><img src=\"img/Restore_Value.png\" alt=\"restore\"></a>";
												}
											}
										}	
									}
                                    echo "</td></tr>";
                                }
                              ?>
                        </tbody>
                    </table>
                </div>
                <!-- END col_12 -->
            </div>
            <!-- END widget_inside -->
        </div>
	</div>
</div>
<!-- END Main Container-->