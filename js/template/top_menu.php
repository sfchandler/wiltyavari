<div id="logo-group">
    <span id="logo"><img src="img/logo.png" alt="Chandler Services Admin"></span>

        <?php //echo displayConfirmedShiftCountForCurrentWeek($mysqli); ?>
</div>
<div align="center" style="color:red; font-style:normal; margin-left:20%;margin-top:10px; width:200px; height:25px;">
                <?php $error_msg = false;
				if(isset($_REQUEST['error_msg'])){
					$error_msg = base64_decode($_REQUEST['error_msg']);
				}
				echo '<div class="errMsg" style="color:red; font-weight:bold; font-size:12px">'.$error_msg.'</div>';
				?>
    <!--<p id="days"> </p>-->
</div>
<!-- pulled right: nav area -->
			<div class="pull-right">
            	<!-- clock -->
                <div id="clock" style="margin-top:12px;float:left;">
                	
                </div>
				<!-- collapse menu button -->
				<div id="hide-menu" class="btn-header pull-right">
					<span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>				</div>
				<!-- end collapse menu -->
				
				<!-- #MOBILE -->
				<!-- Top menu profile link : this shows only when top menu is active -->
				<ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
					<li class="">
						<a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown"> 
							<img src="img/avatars/<?php echo getAvatarImage($mysqli,$_SESSION['userSession']);?>" alt="<?php echo $_SESSION['userSession']; ?>" class="online" /> Welcome, <?php echo $_SESSION['userSession']; ?> 
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
				<!-- multiple lang dropdown : find all flags in the flags page -->
				
                <!--<ul class="header-dropdown-list hidden-xs">
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img src="img/blank.gif" class="flag flag-us" alt="United States"> <span> English (US) </span> <i class="fa fa-angle-down"></i> </a>
						<ul class="dropdown-menu pull-right">
							<li class="active">
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-us" alt="United States"> English (US)</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-fr" alt="France"> Français</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-es" alt="Spanish"> Español</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-de" alt="German"> Deutsch</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-jp" alt="Japan"> 日本語</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-cn" alt="China"> 中文</a>
							</li>	
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-it" alt="Italy"> Italiano</a>
							</li>	
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-pt" alt="Portugal"> Portugal</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-ru" alt="Russia"> Русский язык</a>
							</li>
							<li>
								<a href="javascript:void(0);"><img src="img/blank.gif" class="flag flag-kr" alt="Korea"> 한국어</a>
							</li>						
							
						</ul>
					</li>
				</ul>-->
				<!-- end multiple lang -->

			</div>
			<!-- end pulled right: nav area -->
            <?php if(basename($_SERVER['PHP_SELF']) != 'scheduleMain.php'){ ?>
            <!--<div class="christmasAnimation">
                <div class="snow" />
                <style>
                    .editor-stage .snow {
                        height:50px;
                        background: #fff;
                    }
                    .snow{
                        position:fixed;
                        pointer-events:none;
                        top:0;
                        left:0;
                        right:0;
                        bottom:0;
                        height:100vh;
                        background: none;
                        /*background-image: url('https://s3-eu-west-1.amazonaws.com/static-ressources/s1.png'), url('https://s3-eu-west-1.amazonaws.com/static-ressources/s2.png'), url('https://s3-eu-west-1.amazonaws.com/static-ressources/s3.png');*/
                        background-image: url('img/s1.png'), url('img/s2.png'), url('img/s3.png');
                        z-index:100;
                        -webkit-animation: snow 10s linear infinite;
                        -moz-animation: snow 10s linear infinite;
                        -ms-animation: snow 10s linear infinite;
                        animation: snow 10s linear infinite;
                    }
                    @keyframes snow {
                        0% {background-position: 0px 0px, 0px 0px, 0px 0px;}
                        50% {background-position: 500px 500px, 100px 200px, -100px 150px;}
                        100% {background-position: 500px 1000px, 200px 400px, -100px 300px;}
                    }
                    @-moz-keyframes snow {
                        0% {background-position: 0px 0px, 0px 0px, 0px 0px;}
                        50% {background-position: 500px 500px, 100px 200px, -100px 150px;}
                        100% {background-position: 400px 1000px, 200px 400px, 100px 300px;}
                    }
                    @-webkit-keyframes snow {
                        0% {background-position: 0px 0px, 0px 0px, 0px 0px;}
                        50% {background-position: 500px 500px, 100px 200px, -100px 150px;}
                        100% {background-position: 500px 1000px, 200px 400px, -100px 300px;}
                    }
                    @-ms-keyframes snow {
                        0% {background-position: 0px 0px, 0px 0px, 0px 0px;}
                        50% {background-position: 500px 500px, 100px 200px, -100px 150px;}
                        100% {background-position: 500px 1000px, 200px 400px, -100px 300px;}
                    }
                </style>
                <p>
            </div>-->
<?php } ?>