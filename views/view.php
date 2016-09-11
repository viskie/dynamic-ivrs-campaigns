<!-- Main Content -->

<div id="content" class="clearfix">
	<div id="main-content" class="singlecolm_form" style="text-align:center">
    <input type="hidden" name="mainfunction" id="mainfunction" value="<?php if(isset($data['mainfunction'])) echo $data['mainfunction']; elseif(isset($mainfunction))  echo $mainfunction; ?>" />
	<input type="hidden" name="subfunction_name" id="subfunction_name" value="<?php if(isset($data['subfunction_name'])) echo $data['subfunction_name']; elseif(isset($subfunction_name)) echo $subfunction_name; ?>" />
    <?php 
	extract($data);
	//echo "<pre>"; print_r($arr_alldata);
	//if(isset($check_from)) echo $check_from;
	?>
    <!-- Messages --> 
    	<!--<h2></h2>-->
    	<div class="view_maindiv">
        	
               <!-- <input type="hidden" name="from_menu" id="from_menu" value="< ?php if(isset($check_from)) echo $check_from; ?>" />-->
                <?php 
                foreach($arr_alldata as $k=>$v)
                {					
                ?>
                <div style="float:left">
                <a href="javascript:callPage('<?php echo $module; ?>','<?php echo $v['function_name'] ?>')">
                	<div class="view_box">
                    	<div class="view_img"><img src="img/icon1.png" width="15px" height="15px"/></div>
						<div><?php echo $v['friendly_name']; ?></div>
                        <div style="clear:both;"></div>
                    </div>
                </a>
                </div>
                <?php 
                }
                ?>               
    			<div style="clear:both;"></div>
 		 </div>
       </div>
</div>                           
                          
