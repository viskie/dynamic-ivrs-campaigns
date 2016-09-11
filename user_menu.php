<?php
 	//$permissionArray = getUserGroupPermissions();  
	
	$arr_pages = get_allmenu($logArray['user_id']);	 
	//echo "<pre>"; print_r($arr_pages);
?>
<nav class="container">
    <select class="mobile-only row" onChange="window.open(this.options[this.selectedIndex].value,'_top')">
    	<?php
		foreach($arr_pages as $k=>$v)
		{
		?>
        <option value=""><a href="javascript:callPage('<?php echo $v['module_name']; ?>','show_box_view')"><?php echo $v['module_name'];?></a></option>
        <?php 
		}
		?>        
    </select>
    
    <ul class="sf-menu mobile-hide row clearfix">
    	<?php
		for($i=0; $i<count($arr_pages); $i++)
		{	
			$module_name = $arr_pages[$i]['module_name'];
			$arr_functions = get_allsubmenu($logArray['user_id'], $arr_pages[$i]['page_id']);
			
			$func_link = 'show_box_view';
			$jsfunction = 'callPage';
			if(count($arr_functions) == 1)
			{				
				$check_functions = get_menudata($logArray['user_id'], $arr_functions[0]['function_id']);
				//echo count($check_functions);
				if(count($check_functions) == 1)
				{
					$jsfunction = 'menu_callPage';
					$func_link = $check_functions[0]['function_name'];
				}
			}
		?>
         <li class="">
            <a href="javascript:<?php echo $jsfunction; ?>('<?php echo $module_name; ?>','<?php echo $func_link; ?>')" <?php if ( $module == $module_name ) 
																				{ echo 'class="active submenu-active"';
																					$breadcrum_page = $arr_pages[$i]['title'];
																					$breadcrum_page_link = 	$func_link;
																				} ?>><span>
					<?php if($i == 0) { ?><img src="img/header/icon_dashboard.png" /><?php } ?>
					<?php echo $arr_pages[$i]['title'];?></span>
            </a>
            
            <?php
			$breadcrum_function = '';			
			if(($func_link == 'show_box_view') && count($arr_functions) > 0)
			{
				?><ul><?php					
				for($j=0; $j<count($arr_functions); $j++)
				{
					$subfunc_link = $arr_functions[$j]['function_name'];
					$show_subfunc = 0;
					$check_subfunctions = get_menudata($logArray['user_id'], $arr_functions[$j]['function_id']);
					if(count($check_subfunctions) == 1)
					{
						$show_subfunc = '1';
						$subfunc_link = $check_subfunctions[0]['function_name'];
					}
					if($module == $module_name && $subfunc_link == $function)
					{
						$breadcrum_function = $arr_functions[$j]['friendly_name'];
					}
					?>
                    <li>
                    	<a href="javascript:menu_callPage('<?php echo $module_name; ?>','<?php echo $subfunc_link; ?>')"><?php echo $arr_functions[$j]['friendly_name'];?></a>
						<?php
                        $arr_subfunctions = get_menudata($logArray['user_id'], $arr_functions[$j]['function_id']);
                        if($show_subfunc == '0' && count($arr_subfunctions) > 0)
                        {
                            ?><ul><?php
                            foreach($arr_subfunctions as $k=>$v)
                            {
                                ?>
                                <li><a href="javascript:callPage('<?php echo $module_name; ?>','<?php echo $v['function_name'] ?>')"><?php echo $v['friendly_name'] ;?></a></li>
                                <?php
                            }
                            ?></ul><?php
                        }
                        ?>                        
                    </li>
                    <?php 
				}
				?></ul><?php 
			}
			?>
       	 </li>
        <?php			
		}
		?>
   		<li>
        	<a href="javascript:userLogout('')"><span>Logout</span>
        </li>
        <!--<li class="">
            <a href="javascript:callPage('dashboard','view')"><span><img src="img/header/icon_dashboard.png" /> Dashboard</span></a>
        </li>
        <li class=""><a href="javascript:callPage('users','view')"><span>Users</span></a>
            <ul>
                <li><a href="javascript:callPage('users','view')">User</a>
                    <ul>
                        <li><a href="javascript:callPage('users','view')">User</a></li>
                        <li><a href="javascript:callPage('users','view_manager')">Manager</a></li>
                        <li><a href="javascript:callPage('users','view_super_user')">Super User</a></li>
                        <li><a href="javascript:callPage('users','view_developer')">Developer</a></li>
                    </ul>
                </li>
                <li><a href="javascript:callPage('users','view_groups')">Groups</a></li>
            </ul>
            
        </li>-->
        
    </ul>
</nav>

<!-- Title of page -->
<div id="titlediv">
    <div class="clearfix container" id="pattern">
        <div class="row">
            <div class="col_12">
                <ul class="breadcrumbs hor-list">
                	<?php 	
					if ($module == 'home')
					{
					?>
                    <li><a href="javascript:callPage('<?php echo $module; ?>','<?php echo $function; ?>')">My Profile</a></li>
                    <?php	
					}
					else
					{
					?>
                    <li><a href="javascript:callPage('<?php echo $module; ?>','<?php echo $breadcrum_page_link; ?>')"><?php echo $breadcrum_page; ?></a></li>
                    <?php
					}
					if($function == 'show_box_view') 
					{
					?>
                    	<li><a href="javascript:callPage('<?php echo $module; ?>',show_box_view')">All modules</a></li>
                    <?php 							
					} 
					elseif($module != 'home' && ($breadcrum_function != ""))
					{							
						echo "<li><a href='#'>".$breadcrum_function."</a></li>"; //$breadcrum_function_link	  onClick='window.location.reload( true );'					
					?>
                    <!--<li><a href="javascript:callPage('< ?php echo $module_name; ?>','< ?php echo $breadcrum_function_link; ?>')">< ?php echo $breadcrum_function; ?></a></li>-->
                    <?php
					}
					//echo "<li><a href='#' onClick='window.location.reload( true );'>".$breadcrum_function."</a></li>"; //$breadcrum_function_link	 						
					?>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- END Title of page -->