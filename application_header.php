<div id="wrap">
	<div id="main">
            <header class="container">
                <div class="row clearfix">
                    <div class="left">
                        <a href="javascript:callPage('dashboard','view')" id="logo">muse</a>
                    </div>

                    
                    <div class="right">
                        <ul id="toolbar">
                            <li><span>Logged in as</span> <a class="user" href="#"><?php echo $_SESSION['user_name']; ?></a> <a id="loginarrow" href="#"></a></li>
                            <li><a id="messages" href="#">Messages</a></li>
                            <li><a id="settings" href="#">Settings</a></li>
                            <li><a id="search" href="#">Search</a></li>
                        </ul>

                        <div id="logindrop">
                            <ul>
                                <li id="editprofile"><a href="javascript:callPage('home','view_profile')">Edit Profile</a></li>
                                <li id="logoutprofile"><a href="javascript:userLogout('')">Logout</a></li>
                            </ul>
                        </div>
                        <div id="searchdrop">
                            <input type="text" id="searchbox" placeholder="Search...">
                        </div>
                        
                    </div>  
                </div>
            </header>
            <?php //include("user_menu.php"); ?>
           <!-- <nav class="container">
                <select class="mobile-only row" onChange="window.open(this.options[this.selectedIndex].value,'_top')">
                    <option value="">Dashboard</option>
                    <option value="">Users</option>
                    <option value="">Settings</option>
                </select>

                <ul class="sf-menu mobile-hide row clearfix">
                    <li class="">
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
						
                    </li>
					
                </ul>
            </nav>-->