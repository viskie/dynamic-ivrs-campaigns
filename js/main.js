// JavaScript Document
var month=new Array();
month[0]="Jan";
month[1]="Feb";
month[2]="Mar";
month[3]="Apr";
month[4]="May";
month[5]="Jun";
month[6]="Jul";
month[7]="Aug";
month[8]="Sep";
month[9]="Oct";
month[10]="Nov";
month[11]="Dec";

var error = {
 	"User": {
		"user_name": "Please enter a user name!",
		"alpha_num": "Please enter alphanumeric characters only!",
		"email": "Please enter the email address!",
		"user": "Please enter a user name!",
		"password": "Please enter password!",
		"phone_no": "Please enter the user's phone number!",
		"deluser" : "Do you really want to delete the user :"	
	},
	"Group": {
		"group_name": "Please enter the group name!",
		"delgroup": "Do you really want to delete the group :"		
	},
	"Campaign": {
		"id_exist": "Campaign Id does not exist!",
		"hcampaign_id": "Please enter a campaignId!",
		"campaign_detail": "Please first enter campaign details!",
		"subsel_num_release": "Please select atleast one number to release!",
		"num_released": "Selected numbers are released successfully!",
		"num_check": "Please enter how many numbers you want to buy!",
		"subid": "Please enter a subid!",
		"name" : "Please select campaign name!",
		"postback_url" : "Please enter postback url!",
		"valid_postback_url" : "Offer id is required to be present in postback url!",
		"status" : "Please select status!",
		"call_center_number" : "Please enter call center number!",
		"deallocation_time" : "Please enter deallocation time in minutes!",
		"valid_deallocation_time" : "Please enter valid deallocation time in minutes!",
		"conversion_criteria" : "Please enter call conversion criteria!",
		"valid_conversion_criteria" : "Please enter valid call conversion criteria in seconds!",
		"valid_call_center_number" : "Please enter valid call center number!",
		"ten_digit_only" : "Please enter minimum 10 digit call center number!",
		"manager" : "Please select manager!",
		"user" : "Please select user!",
		"sel_campaign" : "Please select campaign!",
		"sel_duration" : "Please select duration condition!",
		"only_audio" : "Please upload only audio format file!",
		"subid_exist" : "SubId already exist for other campaign!",
        "offerid_exist" : "Offer id exist for other campaign!",
		"hasofferid_exist" : "HasOffer id does not exist or your IP is not white-listed.",
		"confirm_unlink_file" : "Are you sure You want to delete this file."
	},
	"Lead": {
		"download": "Please select atleast 1 coloum!"
	},
	"Restore": "Do you want to restore this Entry ?",
	"Delete": "Do you really want to delete "
};


function show_records(setval, page, func)
	{
		//alert(setval);
		$("#show_status").val(setval);
		//alert($("#show_status").val());
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}


function submitenter(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	
	if (keycode == 13)
	{
	   myfield.form.submit();
	   return false;
	}
	else
	   return true;
}

function callPage(page,function_name)
{
	/*if($("#mainForm").validationEngine()){
		alert("in validation");	
	}else{
		alert("in validation else");	
		return false;	
	}*/
	//return false;
	//$("#mainForm").validationEngine('detach');
	if(page != "")
	{
		document.getElementById('page').value = page;
		if(function_name==""){
			document.getElementById('function').value = "view";
		}else{
			document.getElementById('function').value = function_name;
		}
		$("#show_status").val('1');
		
		document.getElementById('mainForm').submit();
	}

}

function restoreEntry(id,page,func)
{
	if(confirm(error.Restore)){
		$('#edit_id').val(id);
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
}

function validateFormFields(id,page,func)
{
	var go_ahead=true;
	$('.keys').each(function(index, element) {
       if($(this).val()==0){
			$(this).focus();
			go_ahead=false;
			return false;
		}
    });
	if(go_ahead == false){
		 alert("Some where you not selected keys for ivrs!");	
	}
	if($('#start_action').val() == 0){
		alert("Please select some action from select drop-down!");
		$('#start_action').focus();
		return false;
	}else{
		if(go_ahead){
			if($('#campaign_id').val() == ""){
				alert("Please fill details in step one!");	
			}else{
				$('#mainForm').validationEngine();
				showForm(id,page,func);	
			}
		}
	}
	return false;
}

function showForm(id,page,func,isview)
{
	isview = isview || '';
	if(isview != '')
	{
		$("#onlyview").val('1');
	}
	//alert($("#onlyview").val());
	$("#edit_id").val(id);
	$('#page').val(page);
	$('#function').val(func);
	$('#mainForm').submit();	
}

function deleteRecord(id,title,page,func)
{
	if(confirm(error.Delete+" "+title))
	{	$('#edit_id').val(id);
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
}


	
function clearUserFields(header)
{
	$('#popHeaderSpn').html(header);
	$('#user_name').val("");
	$('#full_name').val("");
	$('#user_id').val("");
	$('#email').val("");
	$('#user_phone_number').val("");
	document.getElementById('editUser').style.display = "none";
	document.getElementById('addUser').style.display = "";	
}

function clearPhoneFields(header)
{
	$('#popHeaderSpn').html(header);
	$('#phone_number').val("");
	document.getElementById('editNumber').style.display = "none";
	document.getElementById('addNumber').style.display = "";	
}

function setEditUser(id, uname, full_name, email, phone)
{
	$('#popHeaderSpn').html('Edit User Details');
	$('#user_name').val(uname);
	$('#full_name').val(full_name);
	$('#user_id').val(id);
	$('#email').val(email);
	$('#user_phone_number').val(phone);
	document.getElementById('addUser').style.display = "none";
	document.getElementById('editUser').style.display = "";
}

function setEditPhone(id,ph_number,text_message)
{	
	$('#popHeaderSpn').html('Edit Phone Settings');
	$('#phone_number').val(ph_number);
	$('#text_message').val(text_message);
	
	$('#ph_id').val(id);
	document.getElementById('addNumber').style.display = "none";
	document.getElementById('editNumber').style.display = "";
}

function userLogout()
{
	$('#page').val('home');
	$('#function').val('logout');
	$('#mainForm').submit();
}
	function parseISO8601(dateStringInRange) {
		var isoExp = /^\s*(\d{4})-(\d\d)-(\d\d)\s*$/,
			date = new Date(NaN), month,
			parts = isoExp.exec(dateStringInRange);
	
		if(parts) {
		  month = +parts[2];
		  date.setFullYear(parts[1], month - 1, parts[3]);
		  if(month != date.getMonth() + 1) {
			date.setTime(NaN);
		  }
		}
		return date;
	}
	
	$(document).ready( function () {
		
		var curr_time=new Date();
		//setTimeout('startClock()',(60-curr_time.getSeconds())*1000);
		
		$('.data-table').dataTable({
			"iDisplayLength": 15,
			"aLengthMenu": [[15,30,100,-1], [15,30,100,"All"]],
			"sPaginationType": "full_numbers",
			"oLanguage": {"sLengthMenu": "Show:_MENU_"}
		});
		
		$('.user-table').dataTable({
			"iDisplayLength": 10,
			"aLengthMenu": [[10,20,100,-1], [10,20,100,"All"]],
			"sPaginationType": "full_numbers"
		});
		
		$('#set_month_div input:radio').click(function() {
			if ($(this).val() === '1') {
				$('#month_start_date').val('');	
				$( "#slider" ).slider({ disabled: true });
				$('#financial_month').attr("disabled", true);
			} else if ($(this).val() === '2') {
			  	$('#month_start_date').show();
				$( "#slider" ).slider({ disabled: false });
				$('#financial_month').attr("disabled", false);
			} 
		  });
		 
		/*$('select').change(function(){
			var this_select=$(this);
			if($(this).parent().hasClass('selector')){
				$(this_select).parent().find('span').html($(this).find(':selected').html());
			}
		}); 

		$('input:checkbox').change(function(){
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
		});*/
		
		$('.datepicker').datepicker({
				dateFormat: "d M, yy",
				altField: ".date_picker_alt",
				altFormat: "yy-mm-dd"
		});
		$('.camp_custom_from').datepicker({
				dateFormat: "d M, yy",
				altField: ".camp_custom_from_alt",
				altFormat: "yy-mm-dd"
		});
		$('.camp_custom_to').datepicker({
				dateFormat: "d M, yy",
				altField: ".camp_custom_to_alt",
				altFormat: "yy-mm-dd"
		});
		$('.custom_from').datepicker({
				dateFormat: "d M, yy",
				altField: ".custom_from_alt",
				altFormat: "yy-mm-dd"
		});
		$('.custom_to').datepicker({
				dateFormat: "d M, yy",
				altField: ".custom_to_alt",
				altFormat: "yy-mm-dd"
		});
		$('.custom_from,.custom_to,.camp_custom_from,.camp_custom_to').datepicker('setDate', new Date());
	});

	
	function updatePermission(page, func)
	{
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
	
	function openEditGroup(group_id,page,func)
	{
		$('#edit_id').val(group_id);
			$('#page').val(page);
			$('#function').val(func);
			$('#mainForm').submit();
	}
	
	function deleteGroup(id,uname,page,func)
	{
		if(confirm(error.Group.delgroup+" "+uname))
		{	$('#edit_id').val(id);
			$('#page').val(page);
			$('#function').val(func);
			$('#mainForm').submit();
		}
	}
	
	function saveGroup(page,func)
	{	 
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
	
	function menu_callPage(page,function_name)
	{	
		if(page != "")
		{
			document.getElementById('page').value = page;
			if(function_name==""){
				document.getElementById('function').value = "view";
			}else{
				document.getElementById('function').value = function_name;
			}
			$("#show_status").val('1');
			$('#from_menu').val('function'); //alert (('#from_menu').val()); 
			document.getElementById('mainForm').submit();
		}
	
	}
	
	
	function goToTab(tab_number){
		$('.ui-tabs-selected').removeClass('ui-tabs-selected');
		$('.ui-state-active').removeClass('ui-state-active');
		$("#tab_title"+tab_number).closest("li").addClass("ui-tabs-selected ui-state-active");
		$(".ui-tabs-panel.ui-widget-content.ui-corner-bottom").addClass('ui-tabs-hide');
		$("#tab-"+tab_number).removeClass('ui-tabs-hide');
	}
	
	function getUrlVars(url) {
		var vars = {};
		var parts = url.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars;
	}

	function addCampaign(page,func,step)
	{
		var offer_id = getUrlVars($.trim($('#offer_url').val()))["offer_id"];
		if($.trim($('#name').val()) == "")
		{
        	alert(error.Campaign.name);
			$('#name').focus();				
		}
		else if($.trim($('#offer_url').val()) == "")
		{
        	alert(error.Campaign.postback_url);
			$('#offer_url').focus();				
		}
		else if(offer_id===undefined  || offer_id=="")
		{
			alert(error.Campaign.valid_postback_url);
			$('#offer_url').focus();			
		}
		else if($('#status').val() == 0)
		{
        	alert(error.Campaign.status);
			$('#status').focus();				
		}
		else if($('#deallocation_time').val() == "") 
		{
			alert(error.Campaign.deallocation_time);
			$('#deallocation_time').focus();			
		}
		else if(isNaN($('#deallocation_time').val())) 
		{
			alert(error.Campaign.valid_deallocation_time);
			$('#deallocation_time').focus();			
		}
		else if($('#call_conversion_criteria').val() == "") 
		{
			alert(error.Campaign.conversion_criteria);
			$('#call_conversion_criteria').focus();			
		}
		else if(isNaN($('#call_conversion_criteria').val())) 
		{
			alert(error.Campaign.valid_conversion_criteria);
			$('#call_conversion_criteria').focus();			
		}
		else if($('#manager_id').val() == 0)
		{
        	alert(error.Campaign.manager);
			$('#manager_id').focus();				
		}
		else if($('#user_id').val() == 0)
		{
        	alert(error.Campaign.user);
			$('#user_id').focus();				
		}
		else{
			var extra_number = $('input[name=extra_number]:radio:checked').val();
			$("#extra_number").val(extra_number);
			var datastr = "";
			var fields = new Array("campaign_id","name","offer_url","description","status","extra_number","user_id","deallocation_time","call_conversion_criteria","call_conversion_days");
			for(var i=0;i<fields.length;i++){
				datastr+=fields[i]+"=";
				if(fields[i] == "offer_url"){
					datastr+=encodeURIComponent($("#"+fields[i]).val())+"&";
				}else{
					datastr+=$("#"+fields[i]).val()+"&";
				}
			}
			datastr+="offer_id="+offer_id+"&page="+page+"&function="+func+"&step="+step;
			
			$("#loading1").show();
			$("#tab-1").css({ opacity: 0.6 });
			$("#loading1").css({ opacity: 1 });
			$.ajax({
				url:"ajax.php",
				type:"POST",
				data:datastr,
				success:function(resp){
					//alert(resp);
                    if(resp == "exist")
                    {
						$("#loading1").css({ opacity: 0 });
						$("#tab-1").css({ opacity: 1 });
                        alert(error.Campaign.offerid_exist);
                        return false;
                    }
                    else
                    {
                        $("#campaign_id").val(resp);
                        $("#edit_id").val(resp);
                        $("#next2").val('update');
                        $("#next2").attr("onClick","addCampaign('campaign','save_campaign',1)");
						$("#loading1").show();
						$("#loading1").css({ opacity: 0 });
						$("#tab-1").css({ opacity: 1 });						
                        goToTab(2);
                    }
					
				}
			});			
		}
		return false;
	}

	
	function goBack()
	{
		window.history.back()
	}

	function pool_numbers()
	{
		var cnt_number = $("#number_cnt").val();
		var campaign_id = $("#campaign_id").val();
		var num_type = $('input[name=num_type]:radio:checked').val();
		//alert(num_type); return false;
		if(campaign_id == "")
		{
			alert(error.Campaign.campaign_detail);
		}
		else
		{
			if(!cnt_number > 0)
			{
				alert(error.Campaign.num_check);	
			}
			else
			{
				$("#loading4").show();
				$("#tab-4").css({ opacity: 0.6 });
				$("#loading4").css({ opacity: 1 });
				$.ajax({
					url:"ajax.php",
					type:"POST",
					data: {
							page 	 	  : 'campaign',
							'function' 	  : 'buy_number_ajax',
							cnt_number 	  : cnt_number,
							campaign_id   : campaign_id,
							num_type	  : num_type
							//numbers		  : numbers
						  },		
					success:function(resp){
						//alert(resp);
						$("#number_pool_list").addClass("widget clearfix");
						$("#number_pool_list").html(resp);
						$("#tab-4").css({ opacity: 1 });	
						$("#loading4").hide();
						return false;
					}
				});		
			}
		}
	}
	function release_numbers()
	{
		var campaign_id = $("#campaign_id").val();
		var numbers = [];
		$("input[name='number[]']:checked").each(function(){
			if($(this).val() != "")
			{	
				numbers.push($(this).val());
			}
		});		
				
		if(campaign_id == "")
		{
			alert(error.Campaign.campaign_detail);
			return false;
		}
		else
		{		
			if(numbers.length == 0)
			{	
				alert(error.Campaign.subsel_num_release);
				return false;
			}
			else
			{
				var i=0;
				$("input[name='number[]']").each(function()
				{
					$.inArray($(this).val(),numbers)
					{
						i++;
						$(this).closest('.checked').hide();
					}			   
				});
				
				if(i == numbers.length)
				{	
					$("#all_numbers").hide();
					$("#number_pool_list").removeAttr('class');
				}
				$("#loading4").show();
				$("#tab-4").css({ opacity: 0.6 });
				$("#loading4").css({ opacity: 1 });
				$.ajax({
					url:"ajax.php",
					type:"POST",
					data: {
							page 	 	  : 'campaign',
							'function' 	  : 'release_number_ajax',
							numbers 	  : numbers,
							campaign_id   : campaign_id
						  },		
					success:function(resp){						
						alert(error.Campaign.num_released);	
						$("#tab-4").css({ opacity: 1 });	
						$("#loading4").hide();			
					}
				});	
			}
		}	
		
	}
	function save_third_step()
	{	
		var campaign_id = $("#campaign_id").val();
		//var hasoffer_camp_id = $("#hasoffer_camp_id").val();
		//var subId = $("#subId").val();
		
		if(campaign_id == "")
		{
			alert(error.Campaign.campaign_detail);
			return false;
		}
		else
		{	
			/*if($.trim(hasoffer_camp_id) == "" || $.trim(hasoffer_camp_id) == "0")
			{
				alert(error.Campaign.hcampaign_id);
				$("#hasoffer_camp_id").val('');  
				$('#hasoffer_camp_id').focus();
				return false;
			}
			else if($.trim(subId) == "")
			{
				alert(error.Campaign.subid);
				$('#subId').focus();
				return false;			
			}
			else
			{*/
				$("#loading3").show();
				$("#tab-3").css({ opacity: 0.6 });
				$("#loading3").css({ opacity: 1 });
				$.ajax({
						url:"ajax.php",
						type:"POST",
						data: {
								page 	 	  	 : 'campaign',
								'function' 	  	 : 'save_campaign',
								step 			 : '3',
								subid1 	  	 	 : $("#subId1").val(),
								subid2			 : $("#subId2").val(),
								subid3			 : $("#subId3").val(),
								subid4			 : $("#subId4").val(),
								subid5			 : $("#subId5").val(),
								//hasoffer_camp_id : hasoffer_camp_id,
								campaign_id   	 : campaign_id
							  },		
						success:function(resp){	
							if(resp == 'subid_exist')
							{
								alert(error.Campaign.subid_exist);
								$("#tab-3").css({ opacity: 1 });
								$("#loading3").hide();
								$("#subId").val('');							
								return false;
							}
							else if(resp == 'hasofferid_exist')
							{
								alert(error.Campaign.hasofferid_exist);
								$("#tab-3").css({ opacity: 1 });
								$("#loading3").hide();
								$("#hasoffer_camp_id").val('');							
								return false;
							}
							/*if(!resp)
							{
								alert(error.Campaign.id_exist);
								$("#tab-3").css({ opacity: 1 });
								$("#loading3").hide();
								$("#hasoffer_camp_id").val('');							
								return false;
							}*/
							else
							{
								$("#tab-3").css({ opacity: 1 });
								$("#loading3").hide();
								goToTab(4);
							}
							return false;
						}
					});	
					
			//}
		}		
	}
	
	function show_details(id,page,function_name)
	{
		$("#edit_id").val(id);
		$('#page').val(page);
		$('#function').val(function_name);
		$('#mainForm').submit();
	}
		
	function showReports(page, func, from)
	{
		if(from==1){
			if($('#switch_off').closest('li').hasClass('on'))
			{
				alert(error.Campaign.sel_campaign);	
				return false;
			}
			else if($('#sel_condition').val() == '0')
			{
				alert(error.Campaign.sel_duration);	
				$('#sel_condition').focus();
				return false;
			}else{
				$('#is_post_back').val('TRUE');
			}
		}
		else if(from==2){
			if($('#sel_campaign').val() == '0')
			{
				alert(error.Campaign.sel_campaign);	
				$('#sel_campaign').focus();
				return false;
			}
			else if($('#camp_sel_condition').val() == '0')
			{
				alert(error.Campaign.sel_duration);	
				$('#camp_sel_condition').focus();
				return false;
			}	
		}

		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
		
	