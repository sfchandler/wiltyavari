<div id="logo-group">
    <span id="" style="padding: 10px 5px 10px 5px;"><img src="./img/logo.png" height="30" alt=""></span>
</div><div align="center" style="color:red; font-style:normal; margin-left:0%; margin-top:10px; width:200px; height:25px;">
                <?php $error_msg = false;
				if(!empty($_REQUEST['error_msg'])){
					$error_msg = base64_decode($_REQUEST['error_msg']);
				}
				echo '<div class="errMsg" style="color:red; font-weight:bold; font-size:12px">'.$error_msg.'</div>';
				?>
</div>
<!-- pulled right: nav area -->
			<div class="pull-right">

            	<!-- clock -->
                <div id="clock" style="margin-top:12px;float:left;">
                	
                </div>
				<!-- collapse menu button -->
				<div id="hide-menu" class="btn-header pull-right">
					<span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span></div>
				<!-- end collapse menu -->
				
				<!-- #MOBILE -->
				<!-- Top menu profile link : this shows only when top menu is active -->
				<ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
					<li class="">
						<a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown"> 
							<img src="img/avatars/<?php echo getAvatarImage($mysqli,$_SESSION['userSession']);?>" class="online" /> Welcome, <?php echo $_SESSION['userSession']; ?>
						</a>
						<ul class="dropdown-menu pull-right">
							<li>
								<a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
							</li>
                            <li class="divider"></li>
                            <li>
                                <a href="#" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
                                <ul>
                                    <li><a href="changeCredentials.php" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> Change Password</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="divider"></li>
							<li>
								<a href="logout.php?csrf=<?php echo $_SESSION['token']; ?>" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
							</li>
						</ul>
					</li>
				</ul>

				<!-- logout button -->
				<div id="logout" class="btn-header transparent pull-right">
					<span> <a href="login.php" title="Sign Out" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i></a> </span>
				</div>
				<!-- end logout button -->

				<!-- search mobile button (this is hidden till mobile view port) -->
				<div id="search-mobile" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
				</div>
				<!-- end search mobile button -->

				<!-- fullscreen button -->
				<div id="fullscreen" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
				</div>
				<!-- end fullscreen button -->
			</div>
			<!-- end pulled right: nav area -->
            <?php //if(basename($_SERVER['PHP_SELF']) != 'scheduleMain.php'){  } ?>