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
    <div class="widget clearfix">
        <h2>Groups</h2>
         <?php
				if(isset($notificationArray))
					populateNotification($notificationArray);
            ?>
        <div class="widget_inside">
           <div class="col_12">
				<div class="show_links">
                    <a href="javascript:show_records(0, 'users', 'view_groups')" <? if(isset($data['show_status']))if($data['show_status'] == 0) {?>style="color:black;"<? } ?>>All(<?=$data['rec_counts']['all']?>)</a><span> | </span>
                    <a href="javascript:show_records(1, 'users', 'view_groups')" <? if(isset($data['show_status']))if($data['show_status'] == 1) {?>style="color:black;"<? } ?>>Active(<?=$data['rec_counts']['active']?>)</a><span> | </span>
                    <a href="javascript:show_records(2, 'users', 'view_groups')" <? if(isset($data['show_status']))if($data['show_status'] == 2) {?>style="color:black;"<? } ?>>Deleted(<?=$data['rec_counts']['deleted']?>)</a>
                </div>
                <?php if($data['arr_permission'][0]['add_perm'] == 1) { ?>
                <a href="javascript:callPage('users','show_add_group');" class="for_links">Add Group</a>
                <?php } ?>
                <table class='dataTable'>
                	<thead>
                        <tr>
                            <th class="serial_number center">Sr.No.</th>
                            <th>Group Name</th>
                            <th>Description</th>
                            <th class="action_column center">Action</th>
                       	</tr>
                    </thead>
                    <tbody>
                       <?php
					   $i=1;	
					   foreach($data['allGroups'] as $value)
					   {
						if($value['group_id'] != developer_grpid){
						   ?>
							<tr>
                            	<td class="serial_number center"><?=$i++?></td>
								<td><?=$value['group_name']; ?></td>
								<td><?=$value['comments']; ?></td>
								<td class="action_column center">
								<? if($value['is_active'] != 0) {?>
                                <?php if($data['arr_permission'][0]['edit_perm'] == 1) { ?>	
								<a href="javascript:openEditGroup('<?=$value['group_id']?>','users','edit_group')" class="tiptip-top" title="Edit"><img src="img/icon_edit.png" alt="edit" /></a>
                               
								<?php } if($data['arr_permission'][0]['delete_perm'] == 1) { ?>			
                                &nbsp;&nbsp;&nbsp;<a href="javascript:deleteGroup('<?=$value['group_id']?>','<?=$value['group_name']?>','users','delete_group')" class="tiptip-top" title="Delete"><img src="img/icon_bad.png" alt="delete"></a>
                                <? } 
								 }else{ 
									if(isset($_REQUEST['show_status']))
										if($data['arr_permission'][0]['restore_perm'] == 1) { 
											echo "<a href=\"javascript:restoreEntry('".$value['group_id']."','users','restore_group')\" class=\"tiptip-top\" title=\"Restore\"><img src=\"img/Restore_Value.png\" alt=\"restore\"></a>";
										}
								}
								?>
								</td>
							</tr>
						   <?
						   }
					   }
					  ?>	
                    </tbody>
                 </table>
              </div>
        </div>
    </div>
</div>    