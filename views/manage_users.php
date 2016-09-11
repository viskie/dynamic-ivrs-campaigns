<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
	exit();
}
?>
<input type="hidden" name="show_status" id="show_status" value="<?php if(isset($data['show_status']) && ($data['show_status'] != '')) echo $data['show_status']; else echo '1'; ?>" />
<input type="hidden" name="mainfunction" id="mainfunction" value="<?php if(isset($data['mainfunction'])) echo $data['mainfunction']; else  echo $mainfunction; ?>" />
<input type="hidden" name="subfunction_name" id="subfunction_name" value="<?php if(isset($data['subfunction_name'])) echo $data['subfunction_name']; else echo $subfunction_name; ?>"
<!-- Main Container-->
<div class="container" id="actualbody">
	<div class="row clearfix">
		<div class="col_3">
    	<div class="widget clearfix">
    		<h2><?php 	if($function == "view" || $function=="save_user" || $function=="show_user_form" || $function=="delete_user" || $function=="restore_user")
						{	
							if(isset($data['userDetails'])) echo " Edit user"; else echo "Add User";
						}
						else if($function == "view_manager" || $function=="save_manager" || $function=="show_manager_form" || $function=="delete_manager" || $function=="restore_manager")
						{
							if(isset($data['userDetails'])) echo " Edit manager"; else echo "Add manager";
						}	
						else if($function == "view_super_user" || $function=="save_super_user" || $function=="show_super_user_form" || $function=="delete_super_user" || $function=="restore_super_user")
						{
							if(isset($data['userDetails'])) echo " Edit Super Admin"; else echo "Add Super Admin";
						}	
						else if($function == "view_custom_user" || $function=="save_custom_user" || $function=="show_custom_user_form" || $function=="delete_custom_user" || $function=="restore_custom_user")
						{
							if(isset($data['userDetails'])) echo " Edit Custom User"; else echo "Add Custom User";
						}	
						?>
			</h2>
            <div class="widget_inside">
          		
                    	<div class="form stacked">
                            <div class="clearfix less_padding no_border">
                                <label>Username</label>
                                <div class="input less_margin">
                                    <input type="text"  id="user_name" name="user_name" maxlength="35" <?php if(isset($data['userDetails'])) echo"disabled='disabled'" ?> class="validate[required,custom[onlyAlphaNumeric]] large" value="<?php if(isset($is_exist) && ($is_exist == true)) 
																								{ echo $data['userVariables']['user_name']; }
																								elseif(isset($data['userDetails']))
																									echo $data['userDetails']['user_name'];
																								?>" />
                                </div>
                            </div>
                            <div class="clearfix less_padding no_border">
                                <label>Password</label>
                                <div class="input less_margin">
                                    <input type="password" id="password"  name="user_password" maxlength="30" class="validate[required,length[6,20]] large" value="<?php if(isset($data['userDetails']))
																																			echo '********';
																																		?>" />
                                </div>
                            </div>
                             <div class="clearfix less_padding no_border">
                                <div class="input less_margin">
                                    <label>Confirm password</label>
                                    <div class="input"><input type="password" id="password2"  class="validate[required,equals[password]] large" value="<?php if(isset($data['userDetails']))
																																			echo '********';
																																		?>" /></div>
                                </div>
                            </div>
                            <div class="clearfix less_padding no_border">
                                <label>Email</label>
                                <div class="input less_margin">
                                    <input type="text" id="user_email" name="user_email" maxlength="45" class="validate[required,custom[email]] large" value="<?php if($is_exist == true) 
																																		{ echo $data['userVariables']['user_email']; }
																																	 	 elseif(isset($data['userDetails']))
																																			echo $data['userDetails']['user_email'];
																																		?>"/>
                                </div>    
                            </div>
                            <div class="clearfix less_padding no_border">
                                <label>Name</label>
                                <div class="input less_margin">
                                    <input type="text" id="name" name="name" maxlength="100" class="validate[required,custom[onlyLetter]] large" value="<?php if($is_exist == true) 
																														{ echo $data['userVariables']['name']; }
																													  	elseif(isset($data['userDetails']))
																															echo $data['userDetails']['name'];
																														?>"/>
                                </div>
                            </div>
                            <div class="clearfix less_padding no_border">
                                <label>Phone</label>
                                <div class="input less_margin">
                                    <input type="text" id="user_phone" name="user_phone" maxlength="15" class="validate[required,length[10,15],custom[phone]] large" value="<?php if($is_exist == true)
																																 { echo $data['userVariables']['user_phone']; }
																															  		elseif(isset($data['userDetails']))
																																 echo $data['userDetails']['user_phone'];
																																?>"/>
                                </div>
                            </div>
                            <?php if($function == "view_custom_user" || $function=="save_custom_user" || $function=="show_custom_user_form" || $function=="delete_custom_user" || $function=="restore_custom_user") {?>
                            <div class="clearfix less_padding no_border">
                                <label>Role</label>
                                <div class="input less_margin">
                                    <?php 
										if((isset($data['userDetails'])))
										{
											if((isset($is_exist) && $is_exist == true))
												$selected_val = $data['userVariables']['user_group'];
											else
												$selected_val = $data['userDetails']['user_group'];
											createComboBox('user_group','group_id','group_name',  $data['allGroups'],false,$selected_val);
										}
										else
											createComboBox('user_group','group_id','group_name', $data['allGroups'],false);
									?>
                                </div>
                            </div>
                            <?php } ?>
                            <?php if($function == "view" || $function == "view_users" || $function=="save_user" || $function=="show_user_form" || $function=="delete_user" || $function=="restore_user") {?>
                            <div class="clearfix less_padding no_border">
                                <label>Select Manager</label>
                                <div class="input less_margin">
                                	<select name="selmanager" class="validate[required]">
                                    	<?php
										foreach($data['arr_manager'] as $k=>$v)
										{
											$selected = '';
											if($_SESSION['user_main_group'] == 2 ){
												if($v['user_id'] == $logArray['user_id']) 
													$selected = "selected='selected'";
												else
													$selected = "disabled='disabled'";
											}else{
												if($v['user_id'] == $logArray['user_id']) 
													$selected = "selected='selected'";
												else
													$selected = "selected='selected'";
											}
										?>
                                        	<option value="<?php echo $v['user_id']; ?>" <?php echo $selected; ?> ><?php echo $v['user_name']; ?></option>
                                        <?php 
										}
										?>
                                    </select>                                    
                                </div>
                            </div>
                            <?php } ?>
                            
                           
                            
                            <div class="clearfix grey-highlight">
                                <div class="input no-label">
                                <?php if(isset($data['userDetails'])){
									if($function=="view" || $function=="save_user" || $function=="show_user_form" || $function=="delete_user" || $function=="restore_user"){
                                    	echo "<input type=\"submit\" class=\"button blue\" value=\"Update\" onclick=\"javascript:validateFormFields('".$data['userDetails']['user_id']."','users','save_user')\"></input>";
									}else if($function=="view_manager" || $function=="save_manager" || $function=="show_manager_form" || $function=="delete_manager" || $function=="restore_manager"){
										echo "<input type=\"submit\" class=\"button blue\" value=\"Update\" onclick=\"javascript:validateFormFields('".$data['userDetails']['user_id']."','users','save_manager')\"></input>";
									}else if($function=="view_super_user" || $function=="save_super_user" || $function=="show_super_user_form" || $function=="delete_super_user" || $function=="restore_super_user"){
										echo "<input type=\"submit\" class=\"button blue\" value=\"Update\" onclick=\"javascript:validateFormFields('".$data['userDetails']['user_id']."','users','save_super_user')\"></input>"; }
									else if($function=="view_custom_user" || $function=="save_custom_user" || $function=="show_custom_user_form" || $function=="delete_custom_user" || $function=="restore_custom_user"){
										echo "<input type=\"submit\" class=\"button blue\" value=\"Update\" onclick=\"javascript:validateFormFields('".$data['userDetails']['user_id']."','users','save_custom_user')\"></input>";
									}		
								}else{
									if($function=="view" || $function=="save_user" || $function=="show_user_form" || $function=="delete_user" || $function=="restore_user"){
										echo "<input type=\"submit\" class=\"button blue\" value=\"Insert\" onclick=\"validateFormFields('0','users','save_user')\"></input>"; }
									else if($function=="view_manager"  || $function=="save_manager" || $function=="show_manager_form" || $function=="delete_manager" || $function=="restore_manager") {
										echo "<input type=\"submit\" class=\"button blue\" value=\"Insert\" onclick=\"validateFormFields('0','users','save_manager')\"></input>"; }
									else if($function=="view_super_user" || $function=="save_super_user" || $function=="show_super_user_form" ||$function=="delete_super_user" || $function=="restore_super_user") {
										echo "<input type=\"submit\" class=\"button blue\" value=\"Insert\" onclick=\"validateFormFields('0','users','save_super_user')\"></input>";			 }
									else if($function=="view_custom_user"  || $function=="save_custom_user" || $function=="show_custom_user_form" ||$function=="delete_custom_user" || $function=="restore_custom_user") {
										echo "<input type=\"submit\" class=\"button blue\" value=\"Insert\" onclick=\"validateFormFields('0','users','save_custom_user')\"></input>";			 }	
								}
                                ?>
								</div>
                            </div>
                    </div>
                </div>
			</div>
		</div>
	
					
					
		
	<div class="col_9 last">
		<div class="widget clearfix">
			<h2>
				<?php if($function == "view" || $function=="save_user" || $function=="show_user_form" || $function=="delete_user" || $function=="restore_user")
						echo "Users";
					else if($function == "view_manager" || $function=="save_manager" || $function=="show_manager_form" || $function=="delete_manager" || $function=="restore_manager")
						echo "Managers";
					else if($function == "view_super_user" || $function=="save_super_user" || $function=="show_super_user_form" || $function=="delete_super_user" || $function=="restore_super_user")
						echo "Super Admins"; 
					else if($function == "view_custom_user" || $function=="save_custom_user" || $function=="show_custom_user_form" || $function=="delete_custom_user" || $function=="restore_custom_user")
						echo "Custom Users";?>
			</h2>
			<?php
				if(isset($notificationArray))
					populateNotification($notificationArray);
            ?>
            <div class="widget_inside position_relative">
				<div class="position_relative">
					<div class="show_links">
							<?php 
							//echo "---".$function;
							if($function == "view" || $function == "save_user" || $function=="show_user_form" || $function=="delete_user" || $function=="restore_user"){
								echo "<a href=\"javascript:show_records(0, 'users', 'view')\"";
								if(isset($_POST['show_status'])) if($_POST['show_status'] == 0) { echo "style=\"color:black;\"" ;} echo ">All(".$data['rec_counts']['all'].")</a><span> | </span>";  
								echo "<a href=\"javascript:show_records(1, 'users', 'view')\"";
								if((isset($_POST['show_status']) && $_POST['show_status'] == 1)) { echo "style=\"color:black;\"" ;} echo ">Active(".$data['rec_counts']['active'].")</a><span> | </span>";  
								echo "<a href=\"javascript:show_records(2, 'users', 'view')\"";
								if(isset($_POST['show_status']))if($_POST['show_status'] == 2) { echo "style=\"color:black;\"" ;} echo ">Deleted(".$data['rec_counts']['deleted'].")</a>";
							}
							else if($function == "view_manager"  || $function == "save_manager" || $function=="show_manager_form" || $function=="delete_manager" || $function=="restore_manager"){
								echo "<a href=\"javascript:show_records(0, 'users', 'view_manager')\"";
								if(isset($_POST['show_status']))if($_POST['show_status'] == 0) { echo "style=\"color:black;\"" ;} echo ">All(".$data['rec_counts']['all'].")</a><span> | </span>";  
								echo "<a href=\"javascript:show_records(1, 'users', 'view_manager')\"";
								if(isset($_POST['show_status']))if($_POST['show_status'] == 1) { echo "style=\"color:black;\"" ;} echo ">Active(".$data['rec_counts']['active'].")</a><span> | </span>";  
								echo "<a href=\"javascript:show_records(2, 'users', 'view_manager')\"";
								if(isset($_POST['show_status']))if($_POST['show_status'] == 2) { echo "style=\"color:black;\"" ;} echo ">Deleted(".$data['rec_counts']['deleted'].")</a>";
							}
							else if($function == "view_super_user"  || $function == "save_super_user" || $function=="show_super_user_form" ||$function=="delete_super_user" || $function=="restore_super_user"){
								echo "<a href=\"javascript:show_records(0, 'users', 'view_super_user')\"";
								if(isset($_POST['show_status']))if($_POST['show_status'] == 0) { echo "style=\"color:black;\"" ;} echo ">All(".$data['rec_counts']['all'].")</a><span> | </span>";  
								echo "<a href=\"javascript:show_records(1, 'users', 'view_super_user')\"";
								if(isset($_POST['show_status']))if($_POST['show_status'] == 1) { echo "style=\"color:black;\"" ;} echo ">Active(".$data['rec_counts']['active'].")</a><span> | </span>";  
								echo "<a href=\"javascript:show_records(2, 'users', 'view_super_user')\"";
								if(isset($_POST['show_status']))if($_POST['show_status'] == 2) { echo "style=\"color:black;\"" ;} echo ">Deleted(".$data['rec_counts']['deleted'].")</a>";
							}
							else if($function == "view_custom_user"  || $function == "save_custom_user" || $function=="show_custom_user_form" ||$function=="delete_custom_user" || $function=="restore_custom_user"){
								echo "<a href=\"javascript:show_records(0, 'users', 'view_custom_user')\"";
								if(isset($_POST['show_status']))if($_POST['show_status'] == 0) { echo "style=\"color:black;\"" ;} echo ">All(".$data['rec_counts']['all'].")</a><span> | </span>";  
								echo "<a href=\"javascript:show_records(1, 'users', 'view_custom_user')\"";
								if(isset($_POST['show_status']))if($_POST['show_status'] == 1) { echo "style=\"color:black;\"" ;} echo ">Active(".$data['rec_counts']['active'].")</a><span> | </span>";  
								echo "<a href=\"javascript:show_records(2, 'users', 'view_custom_user')\"";
								if(isset($_POST['show_status']))if($_POST['show_status'] == 2) { echo "style=\"color:black;\"" ;} echo ">Deleted(".$data['rec_counts']['deleted'].")</a>";
							}
							?>
					</div>
                    	<table class='dataTable'>
                			<thead>
                    		    <tr>
                                	<th>Sr.No.</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                        		</tr>
                    		</thead>
                    		<tbody>
                                <?php
									$i=1;
								   foreach($data['allUsers'] as $value)
								   {
									   echo "<tr class='gradeX'>
									   	<td>".$i++."</td>
										<td class=\"backcolor\">".$value['user_name']."</td>
										<td>".$value['user_email']."</td>
										<td>".$value['name']."</td>
										<td>".$value['user_phone']."</td>
										<td>
										";
										if($value['is_active'] != 0){
											if($function == "view" || $function== "show_user_form"  || $function=="save_user" || $function=="delete_user" || $function=="restore_user"){
											echo "<a href=\"javascript:showForm('".$value['user_id']."','users','show_user_form')\" class=\"tiptip-top\" title=\"Edit\"><img src=\"img/icon_edit.png\" alt=\"edit\" /></a>"; 
											echo "&nbsp;&nbsp;&nbsp;<a href=\"javascript:deleteRecord('".$value['user_id']."','".$value['user_name']."','users','delete_user')\" class=\"tiptip-top\" title=\"Delete\"><img src=\"img/icon_bad.png\" alt=\"delete\"></a>";}
											else if($function == "view_manager" || $function== "show_manager_form" || $function=="save_manager" || $function=="delete_manager" || $function=="restore_manager"){
											echo "<a href=\"javascript:showForm('".$value['user_id']."','users','show_manager_form')\" class=\"tiptip-top\" title=\"Edit\"><img src=\"img/icon_edit.png\" alt=\"edit\" /></a>"; 
											echo "&nbsp;&nbsp;&nbsp;<a href=\"javascript:deleteRecord('".$value['user_id']."','".$value['user_name']."','users','delete_manager')\" class=\"tiptip-top\" title=\"Delete\"><img src=\"img/icon_bad.png\" alt=\"delete\"></a>";}
											else if($function == "view_super_user" || $function== "show_super_user_form" || $function=="save_super_user" || $function=="delete_super_user" || $function=="restore_super_user"){
											echo "<a href=\"javascript:showForm('".$value['user_id']."','users','show_super_user_form')\" class=\"tiptip-top\" title=\"Edit\"><img src=\"img/icon_edit.png\" alt=\"edit\" /></a>"; 
											echo "&nbsp;&nbsp;&nbsp;<a href=\"javascript:deleteRecord('".$value['user_id']."','".$value['user_name']."','users','delete_super_user')\" class=\"tiptip-top\" title=\"Delete\"><img src=\"img/icon_bad.png\" alt=\"delete\"></a>";}
											else if($function == "view_custom_user" || $function== "show_custom_user_form" || $function=="save_custom_user" || $function=="delete_custom_user" || $function=="restore_custom_user"){
											echo "<a href=\"javascript:showForm('".$value['user_id']."','users','show_custom_user_form')\" class=\"tiptip-top\" title=\"Edit\"><img src=\"img/icon_edit.png\" alt=\"edit\" /></a>"; 
											echo "&nbsp;&nbsp;&nbsp;<a href=\"javascript:deleteRecord('".$value['user_id']."','".$value['user_name']."','users','delete_custom_user')\" class=\"tiptip-top\" title=\"Delete\"><img src=\"img/icon_bad.png\" alt=\"delete\"></a>";}
											
										}else{
											if(isset($_POST['show_status'])){
												if($function == "view" || $function== "show_user_form"  || $function=="save_user" || $function=="delete_user" || $function=="restore_user"){
													echo "<a href=\"javascript:restoreEntry('".$value['user_id']."','users','restore_user')\" class=\"tiptip-top\" title=\"Restore\"><img src=\"img/Restore_Value.png\" alt=\"restore\"></a>"; }
												else if($function == "view_manager" || $function== "show_manager_form" || $function=="save_manager" || $function=="delete_manager" || $function=="restore_manager"){ echo "<a href=\"javascript:restoreEntry('".$value['user_id']."','users','restore_manager')\" class=\"tiptip-top\" title=\"Restore\"><img src=\"img/Restore_Value.png\" alt=\"restore\"></a>"; }	
												else if($function == "view_super_user" || $function== "show_super_user_form" || $function=="save_super_user" || $function=="delete_super_user" || $function=="restore_super_user"){ echo "<a href=\"javascript:restoreEntry('".$value['user_id']."','users','restore_super_user')\" class=\"tiptip-top\" title=\"Restore\"><img src=\"img/Restore_Value.png\" alt=\"restore\"></a>"; }
												else if($function == "view_custom_user" || $function== "show_custom_user_form" || $function=="save_custom_user" || $function=="delete_custom_user" || $function=="restore_custom_user"){ echo "<a href=\"javascript:restoreEntry('".$value['user_id']."','users','restore_custom_user')\" class=\"tiptip-top\" title=\"Restore\"><img src=\"img/Restore_Value.png\" alt=\"restore\"></a>"; }	
											}
										}	
										echo "</td></tr>";
									}
								  ?>
                        	</tbody>
						</table>
                </div>
            </div>
		</div>
	</div>
</div>
</div>