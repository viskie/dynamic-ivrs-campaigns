<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
	exit();
}
?>

<!-- Main Container-->
<div class="container" id="actualbody">
	<div class="row">
    	<div class="widget clearfix">
    		<h2>Add User</h2>
           <?php
				if(isset($notificationArray))
					populateNotification($notificationArray);
            ?>
            <div class="row clearfix">
            	<input type="hidden" name="user_main_group" id="user_main_group" value="<?php echo $data['user_main_group']?>" />
                <div class="col_8" style="margin:0 18%">
                    <div class="widget clearfix">
                        <h2>Account Information</h2>
                        <div class="widget_inside">
                        	<div class="form" style="border:none" >
                                 <div class="clearfix no_border less_padding">                        
                                    <label>Username</label>
                                    <div class="input">
                                        <input type="text"  id="user_name" name="user_name" maxlength="35" <?php if(isset($data['userDetails'])) echo"disabled='disabled'" ?> class="validate[required,custom[onlyAlphaNumeric]] xlarge" value="<?php if(isset($is_exist) && ($is_exist == true)) 
                                                                                            { echo $data['userVariables']['user_name']; }
                                                                                            elseif(isset($data['userDetails']))
                                                                                                echo $data['userDetails']['user_name'];
                                                                                            ?>" />
                                    </div>
                                </div>
                                 <div class="clearfix no_border less_padding">
                                    <label>Your Name</label>
                                    <div class="input">
                                       <input type="text" id="name" name="name" maxlength="100" class="validate[required,custom[onlyLetter]] xlarge" value="<?php if($is_exist == true) 
                                                                                                                    { echo $data['userVariables']['name']; }
                                                                                                                    elseif(isset($data['userDetails']))
                                                                                                                        echo $data['userDetails']['name'];
                                                                                                                    ?>"/>
                                    </div>
                                </div>
                                <div class="clearfix no_border less_padding">
                                    <label>Your Email address</label>
                                    <div class="input">
                                         <input type="text" id="user_email" name="user_email" maxlength="45" class="validate[required,custom[email]] xlarge" value="<?php if($is_exist == true) 
                                                                                                                                    { echo $data['userVariables']['user_email']; }
                                                                                                                                     elseif(isset($data['userDetails']))
                                                                                                                                        echo $data['userDetails']['user_email'];
                                                                                                                                    ?>"/>
                                    </div>    
                                </div>
                                <div class="clearfix no_border less_padding">
                                    <label>Your Password</label>
                                    <div class="input">
                                        <input type="password" id="password"  name="user_password" maxlength="30" class="validate[required,length[6,20]] xlarge" value="<?php if(isset($data['userDetails']))
                                                                                                                                        echo '********';
                                                                                                                                    ?>" />
                                    </div>
                                </div>
                                <div class="clearfix no_border less_padding">
                                    <label>Confirm Your Password</label>
                                    <div class="input">
                                        <input type="password" id="password2"  class="validate[required,equals[password]] xlarge" value="<?php if(isset($data['userDetails']))
                                                                                                                                        echo '********';
                                                                                                                                    ?>" />
                                    </div>
                                </div>
								<div class="clearfix less_padding no_border">
                                <label>Select Manager</label>
                                <div class="input less_margin">
                                	<select name="selmanager" class="validate[required]">
                                    	<option value="0">Please Select</option>
                                    	<?php
										foreach($data['arr_manager'] as $k=>$v)
										{
											$selected = '';
											if(isset($userDetails)){
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
                              </div>
                        </div>
                    </div>
                </div>                
            </div>
            <?php
			if($data['user_main_group'] == 4)
			{
			?>
            <div class="row clearfix">
           		<div class="col_8 last" style="margin:0 18%">
                    <div class="widget clearfix">
                        <h2>Company Information (if applicable)</h2>
                        <div class="widget_inside">
                        	<div class="form" style="border:none">
                            <div class="clearfix no_border less_padding">                        
                                <label>Company Name</label>
                                <div class="input">
                                    <input id="company_name" name="company_name" type="text" class="xlarge" value="<?php if(isset($is_exist) && ($is_exist == true)) 
																													{ echo $data['userVariables']['company_name']; }
																													elseif(isset($data['userDetails']))
																														echo $data['userDetails']['company_name'];
																													?>" />
                                </div>
                            </div>
                            <div class="clearfix no_border less_padding">
                                <label>Telephone Number</label>
                                <div class="input">
                                    <input type="text" id="user_phone" name="user_phone" class="validate[required,length[10,15],custom[phone]] xlarge" value="<?php if($is_exist == true)
                                                                                                                             { echo $data['userVariables']['user_phone']; }
                                                                                                                                elseif(isset($data['userDetails']))
                                                                                                                             echo $data['userDetails']['user_phone'];
                                                                                                                            ?>"/>
                                </div>
                            </div>
                            <div class="clearfix no_border less_padding">                        
                                <label>Address #1</label>
                                <div class="input">
                                    <textarea id="address1" name="address1" class="xlarge"><?php if($is_exist == true)
																								 { echo $data['userVariables']['address1']; }
																									elseif(isset($data['userDetails']))
																								 echo $data['userDetails']['address1'];
																								?></textarea>                            	
                                </div>
                            </div>
                            <div class="clearfix no_border less_padding">                        
                                <label>Address #2</label>
                                <div class="input">
                                    <textarea id="address2" name="address2" class="xlarge"><?php if($is_exist == true)
																								 { echo $data['userVariables']['address2']; }
																									elseif(isset($data['userDetails']))
																								 echo $data['userDetails']['address2'];
																								?></textarea>                            	
                                </div>
                            </div>
                            <div class="clearfix no_border less_padding">                        
                                <label>City</label>
                                <div class="input">
                                    <input id="city" name="city" type="text"  class="xlarge" value="<?php if($is_exist == true)
																								 { echo $data['userVariables']['city']; }
																									elseif(isset($data['userDetails']))
																								 echo $data['userDetails']['city'];
																								?>" />
                                </div>
                            </div>
                            <div class="clearfix no_border less_padding">                        
                                <label>State</label>
                                <div class="input">
                                    <input id="state" name="state" type="text" class="xlarge" value="<?php if($is_exist == true)
																										 { echo $data['userVariables']['state']; }
																											elseif(isset($data['userDetails']))
																										 echo $data['userDetails']['state'];
																										?>" />
                                </div>
                            </div>
                             <div class="clearfix no_border less_padding">                        
                                <label>Zip Code</label>
                                <div class="input">
                                    <input id="zip_code" name="zip_code" type="text" class="xlarge" value="<?php if($is_exist == true)
																											 { echo $data['userVariables']['zip_code']; }
																												elseif(isset($data['userDetails']))
																											 echo $data['userDetails']['zip_code'];
																											?>" />
                                </div>
                            </div>
                            <div class="clearfix no_border less_padding">                        
                                <label>Country</label>
                                <div class="input">
                                    <input id="country" name="country" type="text" class="xlarge" value="<?php if($is_exist == true)
																											 { echo $data['userVariables']['country']; }
																												elseif(isset($data['userDetails']))
																											 echo $data['userDetails']['country'];
																											?>" />
                                </div>
                            </div>
                            </div>                           
                        </div>
                    </div>
                </div>
             </div>
             <?php } ?>
             <div class="clearfix grey-highlight">
                <div class="input no-label" style="margin:0 42% !important;">
                     <?php if(isset($data['userDetails'])){
						echo "<input type=\"submit\" class=\"button blue\" value=\"Update\" onclick=\"javascript:validateFormFields('".$data['userDetails']['user_id']."','users','save_user')\"></input>";
					}else{
						echo "<input type=\"submit\" class=\"button blue\" value=\"Insert\" onclick=\"validateFormFields('0','users','save_user')\"></input>";	
					}
					 echo "<input type=\"submit\" class=\"button\" value=\"Cancel\" onclick=\"callPage('users','view_users')\"></input>";	
					?>
                </div>
            </div>
    	</div>
    </div>
</div>
<!-- END Main Container-->