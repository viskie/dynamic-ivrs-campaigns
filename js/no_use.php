

function updateClock (){
	var d=new Date();
	var n = month[d.getMonth()]; 
	
	var currentTime = new Date ( );
	
	var currentHours = currentTime.getHours ( );
	var currentMinutes = currentTime.getMinutes ( );
//	var currentSeconds = currentTime.getSeconds( );
	
	var currentDate = currentTime.getDate( );
	var currentMonth = currentTime.getMonth( );
	var currentYear = currentTime.getFullYear();
	
	// Pad the minutes and seconds with leading zeros, if required
	currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
//	currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;
	
	// Choose either "AM" or "PM" as appropriate
	var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";
	
	// Convert the hours component to 12-hour format if needed
	currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;
	
	// Convert an hours component of "0" to "12"
	currentHours = ( currentHours == 0 ) ? 12 : currentHours;
	
	// Compose the string for display
	var currentTimeString =currentDate +"-"+ n +"-" + currentYear+"   "+currentHours + ":" + currentMinutes +" " + timeOfDay;
	
	// Update the time display
	document.getElementById("clock").firstChild.nodeValue = currentTimeString;
	var attendance_clock=document.getElementById("clock1");
	if(attendance_clock){
		attendance_clock.firstChild.nodeValue = currentTimeString;
	}
}
function startClock(){
	setInterval('updateClock()', 60000);
}

function openEditPage(page_id,page,func)
{
		$('#edit_id').val(page_id);
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
}

function deletePage(id,title,page,func)
{
	if(confirm(error.Setting.delpage+" "+title))
	{	$('#edit_id').val(id);
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
}

function addPage(page, func)
{
	var v=/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/;
	//var v=/^[a-zA-Z0-9_]*$/;
	if($('#description').val() == "")
	{
    	alert(error.Setting.page_description);			
		$('#description').focus();
		return false;
	}
	else if($('#module_name').val() == "")
	{
    	alert(error.Setting.page_module_name);			
		$('#module_name').focus();
		return false;
	}
	else if($('#module_name').val().search(v)==-1)
	{	
    	alert(error.Setting.page_module_name_valid);			
    	$('#module_name').focus();
		return false;
	}
	else if($('#title').val() == "")
	{
    	alert(error.Setting.page_title);			
		$('#title').focus();
		return false;
	}
	else if($('#file').val() == "")
	{
    	alert(error.Setting.page_file);			
		$('#file').focus();
		return false;
	}
	else
	{
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
	
}

function updatePage(id,page,func)
{	
	
	var v=/^[A-Za-z][A-Za-z0-9]*(?:_[A-Za-z0-9]+)*$/;
	//var v=/^[a-zA-Z0-9_]*$/;
	if($('#description').val() == "")
	{
		alert(error.Setting.page_description);		
		$('#description').focus();
		return false;
	}
	else if($('#module_name').val() == "")
	{
		alert(error.Setting.page_module_name);	
		$('#module_name').focus();
		return false;
	}
	else if($('#module_name').val().search(v)==-1)
	{	
		alert(error.Setting.page_module_name_valid);
		$('#module_name').focus();
		return false;
	}
	else if($('#title').val() == "")
	{
    	alert(error.Setting.page_title);			
		$('#title').focus();
		return false;
	}
	else
	{
		$('#edit_id').val(id);
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
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

function addGroup(page, func)
{
	if($('#group_name').val() == "")
	{
    	alert(error.Group.group_name);		
		$('#group_name').focus();
	}
	else
	{
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
	return false;
}

function updateGroup(id,page,func)
{	 if($('#group_name').val()=="")
		{
        	alert(error.Group.group_name);			
			$('#group_name').focus();
		}
		else
		{
			$('#edit_id').val(id);
			$('#page').val(page);
			$('#function').val(func);
			$('#mainForm').submit();
		}
}

function addUser(page, func)
{
	var r = "^[a-zA-Z0-9]*$";
	
	if($('#user_name').val() == "")
	{
    	alert(error.User.user_name);
		$('#user_name').focus();		
	}
	else if($('#user_name').val().search(r)==-1)
	{
    	alert(error.User.alpha_num);
		$('#user_name').focus();		
	}
	else if($('#user_email').val() == "")
	{
    	alert(error.User.email);
		$('#user_email').focus();		
	}
	else if($('#name').val() == "")
	{
    	alert(error.User.user);
		$('#name').focus();	
	}
	else if($('#user_password').val() == "")
	{
	   	alert(error.User.password);
		$('#user_password').focus();	
	}
	else if($('#user_phone').val() == "")
	{
    	alert(error.User.phone_no);
		$('#user_phone').focus();
	}
	else
	{
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
	return false;
}


function editUser(page, func)
{
	var r = "^[a-zA-Z0-9]*$";
	if($('#user_name').val() == "")
	{
    	alert(error.User.user_name);
		$('#user_name').focus();		
	}
	else if($('#user_name').val().search(r)==-1)
	{
    	alert(error.User.alpha_num);
		$('#user_name').focus();	
	}
	else if($('#user_email').val() == "")
	{
    	alert(error.User.email);
		$('#user_email').focus();			
	}
	else if($('#user_password').val() == "")
	{
    	alert(error.User.password);	
		$('#user_password').focus();	
	}
	else if($('#name').val() == "")
	{
    	alert(error.User.user);
		$('#name').focus();
	}	
	else if($('#user_phone').val() == "")
	{
    	alert(error.User.phone_no);	
		$('#user_phone').focus();	
	}
	else
	{
    	$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
}



function deleteUser(id,uname,page,func)
{
	if(confirm(error.User.deluser+" "+uname))
	{	$('#edit_id').val(id);
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
}

function deleteFunction(id,fname,page,func)
{
	if(confirm(error.Setting.delfunction+" "+fname))
	{	
		$('#edit_id').val(id);
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
}


function updateFunction(id,page,func)
{
	 if($('#function_name').val() == "")
	{
		alert(error.Setting.function_name);	
		$('#function_name').focus();
		return false;
	}
	else if($('#friendly_name').val() == "")
	{
		alert(error.Setting.function_friendly_name);	
		$('#friendly_name').focus();
		return false;
	}
	else if($('#page_id').val() == "0")
	{
		alert(error.Setting.page_module_name);	
		$('#page_id').focus();
		return false;
	}
	else
	{	$('#edit_id').val(id);
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
	}
}

function openEditUser(user_id,page,func)
{
	$('#edit_id').val(user_id);
		$('#page').val(page);
		$('#function').val(func);
		$('#mainForm').submit();
}

function openEditFunction(function_id,page,func)
{	
	$('#edit_id').val(function_id);
	$('#page').val(page);
	$('#function').val(func);
	$('#mainForm').submit();
}


	function addFunction(page,func)
	{
		//alert(page);
		  if($('#function_name').val() == "")
		{
			alert(error.Setting.function_name);			
			$('#function_name').focus();
			return false;
		}
		else if($('#friendly_name').val() == "")
		{
			alert(error.Setting.function_friendly_name);				
			$('#friendly_name').focus();
			return false;
		}
		else if($('#page_id').val() == "0")
		{
			alert(error.Setting.page_module_name);				
			$('#page_id').focus();
			return false;
		}
		else
		{
			$('#page').val(page);
			$('#function').val(func);
			$('#mainForm').submit();
		}
	}