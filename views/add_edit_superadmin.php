<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
	exit();
}
?>
<input type="hidden" name="mainfunction" id="mainfunction" value="<?php if(isset($data['mainfunction'])) echo $data['mainfunction']; else  echo $mainfunction; ?>" />
<input type="hidden" name="subfunction_name" id="subfunction_name" value="<?php if(isset($data['subfunction_name'])) echo $data['subfunction_name']; else echo $subfunction_name; ?>" />
<!-- Main Container-->
<div class="container" id="actualbody">
	<div class="row">
    	<div class="widget clearfix">
    		<h2> <?php if(isset($data['userDetails'])) echo"Edit Super Admin"; else echo "Add Super Admin"; ?></h2>
           <?php
				if(isset($notificationArray))
					populateNotification($notificationArray);
            ?>
            <div class="row clearfix">
            	<div class="col_8" style="margin:0 18%">
                    <div class="widget clearfix">
                        <h2>Account Information</h2>
                        <div class="widget_inside">
                        	<div class="form" style="border:none" >
                                 <div class="clearfix no_border less_padding">                        
                                    <label>Username<span class="mandatory">*</span></label>
                                    <div class="input">
                                        <input type="text"  id="user_name" name="user_name" maxlength="35" <?php if(isset($data['userDetails'])) echo"disabled='disabled'" ?> class="validate[required,custom[onlyAlphaNumeric]] xlarge" value="<?php if(isset($is_exist) && ($is_exist == true)) 
                                                                                            { echo $data['userVariables']['user_name']; }
                                                                                            elseif(isset($data['userDetails']))
                                                                                                echo $data['userDetails']['user_name'];
                                                                                            ?>" />                                                                        
                                    </div>
                                </div>
                                 <div class="clearfix no_border less_padding">
                                    <label>Your Name<span class="mandatory">*</span></label>
                                    <div class="input">
                                    	
										<input type="text" id="name" name="name" maxlength="100" class="validate[required,custom[onlyLetter]] xlarge" value="<?php if($is_exist == true) 
                                                                                                                    { echo $data['userVariables']['name']; }
                                                                                                                    elseif(isset($data['userDetails']))
                                                                                                                        echo $data['userDetails']['name'];
                                                                                                                    ?>" <?php if(isset($data['view']) && ($data['view'] == 1))
																																{  echo "disabled='disabled'";  } ?>/>										
                                       
                                    </div>
                                </div>
                                <div class="clearfix no_border less_padding">
                                    <label>Your Email address<span class="mandatory">*</span></label>
                                    <div class="input">
                                    	
										<input type="text" id="user_email" name="user_email" maxlength="45" class="validate[required,custom[email]] xlarge" value="<?php if($is_exist == true) 
																															{ echo $data['userVariables']['user_email']; }
																															 elseif(isset($data['userDetails']))
																																echo $data['userDetails']['user_email'];
																														?>" <?php if(isset($data['view']) && ($data['view'] == 1))
																																{  echo "disabled='disabled'";  } ?>/>
										                                         
                                    </div>    
                                </div>
                                <div class="clearfix no_border less_padding">
                                    <label>Your Password<span class="mandatory">*</span></label>
                                    <div class="input">
                                        <input type="password" id="password"  name="user_password" maxlength="30" class="validate[required,length[6,20]] xlarge" value="<?php if(isset($data['userDetails']))
                                                                                                                                        echo '********';
                                                                                                                          ?>" <?php if(isset($data['view']) && ($data['view'] == 1))
																																{  echo "disabled='disabled'";  } ?> />
                                    </div>
                                </div>
                                <div class="clearfix no_border less_padding">
                                    <label>Confirm Your Password<span class="mandatory">*</span></label>
                                    <div class="input">
                                        <input type="password" id="password2"  class="validate[required,equals[password]] xlarge" value="<?php if(isset($data['userDetails']))
                                                                                                                                        echo '********';
                                                                                                                          ?>" <?php if(isset($data['view']) && ($data['view'] == 1))
																																{  echo "disabled='disabled'";  } ?> />
                                    </div>
                                </div>								
                              </div>
                        </div>
                    </div>
                </div>                
            </div>
            
            <div class="row1 clearfix">
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
																													?>" <?php if(isset($data['view']) && ($data['view'] == 1))
																																{  echo "disabled='disabled'";  } ?> />
                                    
                                </div>
                            </div>
                            <div class="clearfix no_border less_padding">
                                <label>Telephone Number</label>
                                <div class="input">
                                    <input type="text" id="user_phone" name="user_phone" class="validate[optional,length[10,15],custom[phone]] xlarge" value="<?php if($is_exist == true)
                                                                                                                             { echo $data['userVariables']['user_phone']; }
                                                                                                                                elseif(isset($data['userDetails']))
                                                                                                                             echo $data['userDetails']['user_phone'];
                                                                                                                          ?>" <?php if(isset($data['view']) && ($data['view'] == 1))
																																{  echo "disabled='disabled'";  } ?>/>
                                </div>
                            </div>
                            <div class="clearfix no_border less_padding textarea_height">                        
                                <label>Address #1</label>
                                <div class="input">
                                    <textarea id="address1" name="address1" class="xlarge textarea_height" <?php if(isset($data['view']) && ($data['view'] == 1))
																												{  echo "disabled='disabled'";  } ?>><?php if($is_exist == true)
																								 { echo $data['userVariables']['address1']; }
																									elseif(isset($data['userDetails']))
																								 echo $data['userDetails']['address1'];
																								?></textarea>                            	
                                </div>
                            </div>
                            <div class="clearfix no_border less_padding textarea_height">                        
                                <label>Address #2</label>
                                <div class="input">
                                    <textarea id="address2" name="address2" class="xlarge textarea_height" <?php if(isset($data['view']) && ($data['view'] == 1))
																											{  echo "disabled='disabled'";  } ?>><?php if($is_exist == true)
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
																								?>" <?php if(isset($data['view']) && ($data['view'] == 1))
																										{  echo "disabled='disabled'";  } ?>/>
                                </div>
                            </div>
                            <div class="clearfix no_border less_padding">                        
                                <label>State</label>
                                <div class="input">
                                    <input id="state" name="state" type="text" class="xlarge" value="<?php if($is_exist == true)
																										 { echo $data['userVariables']['state']; }
																											elseif(isset($data['userDetails']))
																										 echo $data['userDetails']['state'];
																										?>" <?php if(isset($data['view']) && ($data['view'] == 1))
																												{  echo "disabled='disabled'";  } ?>/>
                                </div>
                            </div>
                             <div class="clearfix no_border less_padding">                        
                                <label>Zip Code</label>
                                <div class="input">
                                    <input id="zip_code" name="zip_code" type="text" class="xlarge" value="<?php if($is_exist == true)
																											 { echo $data['userVariables']['zip_code']; }
																												elseif(isset($data['userDetails']))
																											 echo $data['userDetails']['zip_code'];
																											?>" <?php if(isset($data['view']) && ($data['view'] == 1))
																													{  echo "disabled='disabled'";  } ?>/>
                                </div>
                            </div>
                            <div class="clearfix no_border less_padding">                        
                                <label>Country</label>
                                <div class="input">
                                    <input id="country" name="country" type="text" class="xlarge" value="<?php if($is_exist == true)
																											 { echo $data['userVariables']['country']; }
																												elseif(isset($data['userDetails']))
																											 echo $data['userDetails']['country'];
																											?>" <?php if(isset($data['view']) && ($data['view'] == 1))
																																{  echo "disabled='disabled'";  } ?>/>
                                </div>
                            </div>
                            </div>                           
                        </div>
                    </div>
                </div>
             </div>
             
             <div class="clearfix grey-highlight">
                <div class="input no-label" style="margin:0 42% !important;">
                     <?php 
					 if(!isset($data['view']))
					 { 	
						 if(isset($data['userDetails']))
						 {
							echo "<input type=\"submit\" class=\"button blue\" value=\"Update\" onclick=\"javascript:validateFormFields('".$data['userDetails']['user_id']."','users','save_super_user')\"></input>";
						}else{
							echo "<input type=\"submit\" class=\"button blue\" value=\"Insert\" onclick=\"validateFormFields('0','users','save_super_user')\"></input>";	
						}
					} 
					echo "<input type=\"submit\" class=\"button\" value=\"Cancel\" onclick=\"callPage('users','view_super_user')\"></input>";	
					?>
                </div>
            </div>
    	</div>
    </div>
</div>
<!-- END Main Container-->