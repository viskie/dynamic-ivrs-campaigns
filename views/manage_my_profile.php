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
    		<h2>My Profile</h2>
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
                                    <label>Username<span class="mandatory">*</span></label>
                                    <div class="input">
                                        <input type="text" disabled="disabled" class="xlarge" value="<?php echo $data['userDetails']['user_name']; ?>" />
                                    </div>
                                </div>
                                 <div class="clearfix no_border less_padding">
                                    <label>Your Name<span class="mandatory">*</span></label>
                                    <div class="input">
                                        <input type="text" id="name" name="name" class="validate[required,custom[onlyLetter]] xlarge" value="<?php echo $data['userDetails']['name']; ?>"/>
                                    </div>
                                </div>
                                <div class="clearfix no_border less_padding">
                                    <label>Your Email address<span class="mandatory">*</span></label>
                                    <div class="input">
                                        <input type="text" id="user_email" name="user_email" class="validate[required,custom[email]] xlarge" value="<?php echo $data['userDetails']['user_email']; ?>"/>
                                    </div>    
                                </div>
                                <div class="clearfix no_border less_padding">
                                    <label>Your Password<span class="mandatory">*</span></label>
                                    <div class="input">
                                        <input type="password" id="password"  name="user_password" class="validate[required,length[6,20]] xlarge" value="********" />
                                    </div>
                                </div>
                                 <div class="clearfix no_border less_padding">
                                    <label>Confirm Your Password<span class="mandatory">*</span></label>
                                    <div class="input">
                                        <input type="password" id="password"  name="user_password" class="validate[required,equals[password]] xlarge" value="********" />
                                    </div>
                                </div>
                              </div>
                        </div>
                    </div>
                </div>                
            </div>
            <?php
			//if($data['user_main_group'] == 4)
			//{
			?>
            <div class="row1 clearfix">
           		<div class="col_8 last" style="margin:0 18%">
                    <div class="widget clearfix">
                        <h2>Company Information (if applicable)</h2>
                        <div class="widget_inside">
                        	<div class="form" style="border:none">
                            	<div class="clearfix less_padding no_border">                        
                                <label>Company Name</label>
                                <div class="input">
                                    <input id="company_name" name="company_name" type="text" class="xlarge" value="<?php echo $data['userDetails']['company_name']; ?>" />
                                </div>
                            </div>
                            <div class="clearfix less_padding no_border">
                                <label>Telephone Number</label>
                                <div class="input">
                                    <input type="text" id="user_phone" name="user_phone" class="validate[optional,length[10,15],custom[phone]] xlarge" value="<?php echo $data['userDetails']['user_phone']; ?>"/>
                                </div>
                            </div>
                            <div class="clearfix less_padding no_border">                        
                                <label>Address #1</label>
                                <div class="input">
                                    <textarea id="address1" name="address1" class="xlarge textarea_height"><?php echo $data['userDetails']['address1']; ?></textarea>                            	
                                </div>
                            </div>
                            <div class="clearfix less_padding no_border">                        
                                <label>Address #2</label>
                                <div class="input">
                                    <textarea id="address2" name="address2" class="xlarge textarea_height" ><?php echo $data['userDetails']['address2']; ?></textarea>                            	
                                </div>
                            </div>
                            <div class="clearfix less_padding no_border">                        
                                <label>City</label>
                                <div class="input">
                                    <input id="city" name="city" type="text"  class="xlarge" value="<?php echo $data['userDetails']['city']; ?>" />
                                </div>
                            </div>
                            <div class="clearfix less_padding no_border">                        
                                <label>State</label>
                                <div class="input">
                                    <input id="state" name="state" type="text" class="xlarge" value="<?php echo $data['userDetails']['state']; ?>" />
                                </div>
                            </div>
                             <div class="clearfix less_padding no_border">                        
                                <label>Zip Code</label>
                                <div class="input">
                                    <input id="zip_code" name="zip_code" type="text" class="validate[optional,custom[onlyNumber]] xlarge" value="<?php echo $data['userDetails']['zip_code']; ?>" />
                                </div>
                            </div>
                            <div class="clearfix less_padding no_border">                        
                                <label>Country</label>
                                <div class="input">
                                    <input id="country" name="country" type="text" class="xlarge" value="<?php echo $data['userDetails']['country']; ?>" />
                                </div>
                            </div>
                            </div>                           
                        </div>
                    </div>
                </div>
             </div>
             <?php //} ?>
             <div class="clearfix grey-highlight">
                <div class="input no-label" style="margin:0 42% !important;">
                    <input type="submit" class="button blue" value="Submit" onclick="validateFormFields('0','home','edit_profile')"></input>
                    <input type="button" class="button grey" value="Back" onclick="callPage('dashboard','view')"></input>
                </div>
            </div>
    	</div>
    </div>
</div>
<!-- END Main Container-->