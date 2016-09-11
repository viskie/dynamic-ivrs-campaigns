<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
	exit();
}
?>
<input type="hidden" name="mainfunction" id="mainfunction" value="<?php if(isset($data['mainfunction'])) echo $data['mainfunction']; else  echo $mainfunction; ?>" />
<input type="hidden" name="subfunction_name" id="subfunction_name" value="<?php if(isset($data['subfunction_name'])) echo $data['subfunction_name']; else echo $subfunction_name; ?>" />
<?php if($function == "edit_group" || ($is_exist == true && $is_edit == true)) {  $groupid =  $data['groupDetails']['group_id']; ?>

<?php } 
extract($data);
?>

<!-- Main Container-->
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
               		<div class="form">
               		<table cellpadding="0" cellspacing="0" border="0" align="center" style="width:80%; margin:44px 107px;" class="permission_table">
                <tr>
                    <td align="left">
                    	<div class="clearfix">
                            <label for="group_name">Group Name</label>
                            <div class="input">
                            <input type="text" id="group_name" name="group_name"  class="large" maxlength="35" value='<? if($function == "edit_group" || $is_exist == true) { echo $data['groupDetails']['group_name']; }?>' />
                            </div>
                        </div>
                    </td>
                </tr>
               <tr>
                    <td align="left">
                    	<div class="clearfix">
                             <label for="comments">Comments </label>
                             <div class="input">
                             	<textarea name="comments" id="comments" class="xxlarge"><? if($function == "edit_group" || $is_exist == true) { echo $data['groupDetails']['comments']; }?></textarea>
                             </div>
                        </div> 
                    </td>
                </tr>
                <tr>
                	<td align="center" class="perm_li">
                       	<?php //echo "<pre>"; print_r($arr_perm_data); ?>
                       <div class="">
                        	<label>Manage Permissions</label>
                        	 <span style="float:right; margin-right:50px"> 
								<?php
                                    $checked = '';
                                    if(isset($groupid))
                                    {	
                                        $check_permission = check_permission('all', $groupid); 
                                        if($check_permission === true)
                                            $checked = 'checked=checked';
                                    }
                                ?>                     	                    	
                                <input type="checkbox" id="check_all_pages" class="check_all_pages" name="check_all_pages" value="1" onchange="check_pages()" <?php echo $checked; ?>>
                               <span class="check_all_text"><?php if(isset($groupid) && isset($check_permission) && ($check_permission === true)) echo "Uncheck All"; else echo "Check All"; ?></span>
                            </span>
                        </div>
                        <ol class="pages_list">                        	
							<?php 
                            for($j=0; $j<count($arr_perm_data); $j++)
                            {
							?>
                            <li class="page_li">
                            	<span  class="close_child_page"></span>
                                <?php
									$checked = '';
									if(isset($groupid))
									{	
										$check_permission = check_permission('', $groupid, $arr_perm_data[$j]['page_id']);
										if($check_permission)
										{
											$check_fun = 1;
											$checked = 'checked=checked';
										}
									}
								?>
                                <input  type="checkbox" name="page_perm[]" value="<?php echo $arr_perm_data[$j]['page_id'];?>" class="page"  onchange="check_functions(this,<?php echo $arr_perm_data[$j]['page_id'];?>)" <?php echo $checked; ?>>
                                <div class="perm_lable"><?php echo $arr_perm_data[$j]['module_name']; ?></div>
                                	<?php 
									if(isset($arr_perm_data[$j]['functions']) && count($arr_perm_data[$j]['functions'])>0)
									{
									?>
									<ol class="function_list" style="display:none">
									<?php 
										foreach($arr_perm_data[$j]['functions'] as $k=>$v)
										{
										?>
                                        <li class="func_li">
                                    		<span  class="close_child_page"></span>
                                            <?php
												$checked = '';
												if(isset($groupid) && isset($check_fun))
												{	
													$check_permission = check_permission('', $groupid, $arr_perm_data[$j]['page_id'],$v['function_id']);
													if($check_permission)
													{
														$check_subfun = 1;
														$checked = 'checked=checked';
													}
												}
											?>
                                            <input  type="checkbox" name="function_<?php echo $arr_perm_data[$j]['page_id'];?>[]" class="function_<?php echo $arr_perm_data[$j]['page_id'];?>" onchange="check_subfunction(this, <?php echo $arr_perm_data[$j]['page_id'];?>,<?php echo $v['function_id'];?>)" value="<?php echo $v['function_id'];?>" <?php echo $checked; ?>>
                                            <div class="perm_lable"><?php echo $v['friendly_name']; ?></div>
                                            <?php 
											if(isset($v['subfunction']) && count($v['subfunction'])>0)
											{
											?>
											<ol class="subfunc_list" style="display:none">
												<?php 
												foreach($v['subfunction'] as $k1=>$v1)
												{
												?>
                                                <li class="subfunc_li">
                                                	<div style="float:left; width:35%;"> 
                                                    	<?php
															$checked = '';
															if(isset($groupid) && isset($check_fun))
															{	
																$check_permission = check_permission('subfunction', $groupid, $arr_perm_data[$j]['page_id'],$v['function_id'],$v1['function_id'],'view');
																if($check_permission)
																{
																	$check_action = 1;
																	$checked = 'checked=checked';
																}
															}
														?>
                                                        <input  type="checkbox" name="subfunction_<?php echo $arr_perm_data[$j]['page_id'];?>_<?php echo $v['function_id']; ?>[]" class="subfunction_<?php echo $arr_perm_data[$j]['page_id'];?>_<?php echo $v['function_id']; ?>" onchange="change_parent(this,<?php echo $arr_perm_data[$j]['page_id'];?>,<?php echo $v['function_id'];?>,<?php echo $v1['function_id'];?>)" value="<?php echo $v1['function_id'];?>" <?php echo $checked; ?>>
                                                        <div class="perm_sub_lable"><?php echo $v1['friendly_name']; ?></div>
                                                    </div>
                                                    <?php 
													if($v1['is_crud'] == 1)
													{
													?>
                                                    	<div class="crud_perm">
															<?php
                                                                $checked = '';
                                                                if(isset($groupid) && isset($check_action))
                                                                {	
                                                                    $check_permission = check_permission('subfunction', $groupid, $arr_perm_data[$j]['page_id'],$v['function_id'],$v1['function_id'],'add');
                                                                    if($check_permission)
                                                                    {
                                                                        $check_action = 1;
                                                                        $checked = 'checked=checked';
                                                                    }
                                                                }
                                                            ?>                                                     	                                          	
                                                            <input  type="checkbox" name="subfunction_<?php echo $arr_perm_data[$j]['page_id'];?>_<?php echo $v['function_id']; ?>_<?php echo $v1['function_id'];?>[]" value="add" class="subfunction_<?php echo $arr_perm_data[$j]['page_id'];?>_<?php echo $v['function_id']; ?>" onchange="change_parent(this,<?php echo $arr_perm_data[$j]['page_id'];?>,<?php echo $v['function_id'];?>,<?php echo $v1['function_id'];?>)" <?php echo $checked; ?>>
                                                            <div class="perm_sub_lable">Add</div>
                                                        </div>
                                                        <div class="crud_perm">
                                                        	<?php
                                                                $checked = '';
                                                                if(isset($groupid) && isset($check_action))
                                                                {	
                                                                    $check_permission = check_permission('subfunction', $groupid, $arr_perm_data[$j]['page_id'],$v['function_id'],$v1['function_id'],'edit');
                                                                    if($check_permission)
                                                                    {
                                                                        $check_action = 1;
                                                                        $checked = 'checked=checked';
                                                                    }
                                                                }
                                                            ?>                                                       	                                          	
                                                            <input  type="checkbox" name="subfunction_<?php echo $arr_perm_data[$j]['page_id'];?>_<?php echo $v['function_id']; ?>_<?php echo $v1['function_id'];?>[]" value="edit" class="subfunction_<?php echo $arr_perm_data[$j]['page_id'];?>_<?php echo $v['function_id']; ?>" onchange="change_parent(this,<?php echo $arr_perm_data[$j]['page_id'];?>,<?php echo $v['function_id'];?>,<?php echo $v1['function_id'];?>)" <?php echo $checked; ?>>
                                                            <div class="perm_sub_lable">edit</div>
                                                        </div>
                                                        <div class="crud_perm"> 
                                                        	<?php
                                                                $checked = '';
                                                                if(isset($groupid) && isset($check_action))
                                                                {	
                                                                    $check_permission = check_permission('subfunction', $groupid, $arr_perm_data[$j]['page_id'],$v['function_id'],$v1['function_id'],'delete');
                                                                    if($check_permission)
                                                                    {
                                                                        $check_action = 1;
                                                                        $checked = 'checked=checked';
                                                                    }
                                                                }
                                                            ?>                                                      	                                          	
                                                            <input  type="checkbox" name="subfunction_<?php echo $arr_perm_data[$j]['page_id'];?>_<?php echo $v['function_id']; ?>_<?php echo $v1['function_id'];?>[]" value="delete" class="subfunction_<?php echo $arr_perm_data[$j]['page_id'];?>_<?php echo $v['function_id']; ?>" onchange="change_parent(this,<?php echo $arr_perm_data[$j]['page_id'];?>,<?php echo $v['function_id'];?>,<?php echo $v1['function_id'];?>)" <?php echo $checked; ?>>
                                                           <div class="perm_sub_lable">Delete</div>
                                                        </div>                                                  
                                                        <div class="crud_perm">
                                                        	<?php
                                                                $checked = '';
                                                                if(isset($groupid) && isset($check_action))
                                                                {	
                                                                    $check_permission = check_permission('subfunction', $groupid, $arr_perm_data[$j]['page_id'],$v['function_id'],$v1['function_id'],'restore');
                                                                    if($check_permission)
                                                                    {
                                                                        $check_action = 1;
                                                                        $checked = 'checked=checked';
                                                                    }
                                                                }
                                                            ?>                                                      	                                          	
                                                            <input  type="checkbox" name="subfunction_<?php echo $arr_perm_data[$j]['page_id'];?>_<?php echo $v['function_id']; ?>_<?php echo $v1['function_id'];?>[]" value="restore" class="subfunction_<?php echo $arr_perm_data[$j]['page_id'];?>_<?php echo $v['function_id']; ?>" onchange="change_parent(this,<?php echo $arr_perm_data[$j]['page_id'];?>,<?php echo $v['function_id'];?>,<?php echo $v1['function_id'];?>)" <?php echo $checked; ?>>
                                                            <div class="perm_sub_lable">Restore</div>
                                                        </div>
                                                    <?php
													}												
													?>
                                                </li>
                                                <?php 
												}
												?>
                                            </ol>
                                            <?php
											}
											?>
                                        </li>
                                        <?php  
										}
									?>
                                    </ol>
                                    <?php
									}
									?>
                            	</li>
                            <?php 
							}
                            ?>	
                         </ol>
                    </td>                    
                </tr>                
           		<tr>
                    <td>
                    	<div class="clearfix">
    	                    <div class="input no-label">
                            <?php if($function == "edit_group" || ($is_exist == true && $is_edit == true)) { 
	                         	    echo"<input type=\"button\" value=\"Submit\" class=\"button blue\"  onclick=\"javascript:showForm('".$data['groupDetails']['group_id']."','users','save_group')\" />";
								}
								else{
									 echo"<input type=\"button\" value=\"Submit\" class=\"button blue\"  onclick=\"javascript:showForm('0','users','save_group')\" />";
								}
							?>	 
        	                     <input type="button" value="Cancel" class="button grey"  onclick="javascript:callPage('users','view_groups')" /> 
                            </div>
                    </div>     
                    </td>
                </tr>
            </table>
            </div>
               </div>
            </div>
        </div>
    </div>
</div>
<!-- END Main Container-->

<script type="text/javascript" language="javascript">
$(document).ready(function(){
	$(".close_child_page,.open_child_page").click(function(){
			$(this).parent().find(">ol").slideToggle();
			$(this).toggleClass("close_child_page");
			$(this).toggleClass("open_child_page");	
	});
		
});
	function check_pages()
	{	
		if($('input[name=check_all_pages]').is(":checked"))
		{	
			$('.page').attr("checked","checked").change();			
			$('.check_all_text').text("Uncheck all");
		}
		else
		{
			$('.page').removeAttr("checked").change();			
			$('.check_all_text').text("Check all");
		}
		
	}
	function check_functions(chkval,pageid)
	{	
		if($(chkval).is(':checked'))
		{	
			$(chkval).closest('.page_li').find('input[type="checkbox"]').attr("checked","checked");
		}
		else
		{
			$(chkval).closest('.page_li').find('input[type="checkbox"]').removeAttr("checked");
		}
		change_attr($(chkval).closest('.page_li'));
		// if uncheck all function, page will also unchecked	
		 var chk = true;
		 $(".page_li").find('input[type="checkbox"]').each(function(){
			if(!($(this).is(':checked')))
			{
				chk = false;					
				return false; 										  				
			}
		 }); 
		 if(chk == true)
		 {
			$('input[name=check_all_pages]').attr("checked","checked");
			$('.check_all_text').text("Uncheck all");	
		 }
		 else
		 {
			 $('input[name=check_all_pages]').removeAttr("checked");
			 $('.check_all_text').text("Check all");	
		 }
		 change_attr($('.perm_li'));
	}
	function check_subfunction(this_element, pageid,functionid)
	{
		var my_check_box=this_element;
		if($(my_check_box).is(":checked"))
		{
			$('.subfunction_'+pageid+'_'+functionid).attr("checked","checked").change();						
		}
		else
		{
			$(this).removeAttr("checked").change();
			$('.subfunction_'+pageid+'_'+functionid).removeAttr("checked").change();
		}		
	}
	function change_parent(this_element, pageid, functionid, subfunctionid)
	{	
		var my_check_box=this_element;
		if($(my_check_box).is(":checked"))
		{	
			$('.subfunction_'+pageid+'_'+functionid).closest('.func_li').find('.function_'+pageid).attr("checked","checked"); 			
			$('.subfunction_'+pageid+'_'+functionid).closest('.page_li').find('.page').attr("checked","checked"); 			
			
			if(my_check_box.value != 'add' && my_check_box.value != 'edit'  && my_check_box.value != 'delete' && my_check_box.value != 'restore'){
				$("input[name='subfunction_"+pageid+'_'+functionid+'_'+subfunctionid+"[]']").each(function(){				
				$(this).attr("checked","checked");
			 	});
			}
			  else
			 {
				 $(my_check_box).closest('.subfunc_li').find('.subfunction_'+pageid+'_'+functionid).first().attr("checked","checked");				 
			 }
			 
			var pageli = $('.subfunction_'+pageid+'_'+functionid).closest('.page_li');
			change_attr(pageli); 			
		}
		else
		{	
			if(my_check_box.value != 'add' && my_check_box.value != 'edit'  && my_check_box.value != 'delete' && my_check_box.value != 'restore')
				$("input[name='subfunction_"+pageid+'_'+functionid+'_'+subfunctionid+"[]']").each(function(){				
				$(this).removeAttr("checked").change();
				//change_attr(this);
			 });
					
		}
		// if uncheck all subfunction, function also unchecked	
		var chk = false;
		$(".subfunction_"+pageid+'_'+functionid).each(function(){
			if($(this).is(':checked'))
			{
				chk = true;					
				return false; 										  				
			}
		 }); 
		 if(chk == false)
		 {
			$('.subfunction_'+pageid+'_'+functionid).closest('.func_li').find('.function_'+pageid).removeAttr("checked"); 			
			//$('.subfunction_'+pageid+'_'+functionid).closest('.page_li').find('.page').removeAttr("checked");
			var func_li = $('.subfunction_'+pageid+'_'+functionid).closest('.func_li');
			change_attr(func_li);			
		 }
		 
		 // if uncheck all function, page also unchecked	
		 var chk = false;
		$(".function_"+pageid).each(function(){
			if($(this).is(':checked'))
			{
				chk = true;					
				return false; 										  				
			}
		 }); 
		 if(chk == false)
		 {
			$('.subfunction_'+pageid+'_'+functionid).closest('.page_li').find('.page').removeAttr("checked");
			var pageli = $('.subfunction_'+pageid+'_'+functionid).closest('.page_li');
			change_attr(pageli);			
		 }	
		
		
	}
	
	function change_attr(pageli)
	{
		$(pageli).find('input[type=checkbox]').each( function(){
				var this_check=$(this);
				if($(this_check).is(':checked')){
					if(!$(this_check).parent().hasClass('checked')){
						$(this_check).parent().addClass('checked');
					}
				}else{
					if($(this_check).parent().hasClass('checked')){
						$(this_check).parent().removeClass('checked');
					}
				}
			})
	}
</script>