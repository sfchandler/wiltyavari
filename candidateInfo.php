<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<?php include "template/header.php";?>
	</head>
	<body class="">

		<!-- HEADER -->
		<header id="header">
			<div id="logo-group">

				<!-- PLACE YOUR LOGO HERE -->
				<span id="logo"> <img src="img/logo.png" alt="<?php echo DOMAIN_NAME; ?> Admin"> </span>
				<!-- END LOGO PLACEHOLDER -->
				<!-- Note: The activity badge color changes when clicked and resets the number to 0
				Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
				<!--<span id="activity" class="activity-dropdown"> <i class="fa fa-user"></i> <b class="badge"> 21 </b> </span>-->

				<!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
				<!--<div class="ajax-dropdown">-->

					<!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
					<!--<div class="btn-group btn-group-justified" data-toggle="buttons">
						<label class="btn btn-default">
							<input type="radio" name="activity" id="ajax/notify/mail.html">
							Msgs (14) </label>
						<label class="btn btn-default">
							<input type="radio" name="activity" id="ajax/notify/notifications.html">
							notify (3) </label>
						<label class="btn btn-default">
							<input type="radio" name="activity" id="ajax/notify/tasks.html">
							Tasks (4) </label>
					</div>-->

					<!-- notification content -->
					<!--<div class="ajax-notifications custom-scroll">

						<div class="alert alert-transparent">
							<h4>Click a button to show messages here</h4>
							This blank page message helps protect your privacy, or you can show the first message here automatically.
						</div>

						<i class="fa fa-lock fa-4x fa-border"></i>

					</div>-->
					<!-- end notification content -->

					<!-- footer: refresh area -->
					<!--<span> Last updated on: 12/12/2013 9:43AM
						<button type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
							<i class="fa fa-refresh"></i>
						</button> </span>-->
					<!-- end footer -->
				</div>
				<!-- END AJAX-DROPDOWN -->
			</div>

			<?php include "template/top_menu.php"; //echo $_SESSION['accountName'];
			if($_REQUEST['error_msg']<>''){echo base64_decode($_REQUEST['error_msg']);}?>

		</header>
		<!-- END HEADER -->

		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<aside id="left-panel">

			<!-- User info -->
			<div class="login-info">
				<?php include "template/user_info.php"; ?>
			</div>
			<!-- end user info -->
				<?php include "template/navigation.php"; ?>
			<span class="minifyme" data-action="minifyMenu"> 
				<i class="fa fa-arrow-circle-left hit"></i> 
			</span>

		</aside>
		<!-- END NAVIGATION -->

		<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">
				<span class="ribbon-button-alignment"> 
				</span>
				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li>Home</li><li>Inbox</li><li>Candidate Info</li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
				<!--<div class="inbox-nav-bar no-content-padding">
					<h1 class="page-title txt-color-blueDark hidden-tablet"><i class="fa fa-fw fa-inbox"></i> Inbox &nbsp;
					</h1>
				</div>-->
			    <div id="candidate-list" class="inbox-body no-content-padding">
					<!--<div class="inbox-side-bar">
						<ul class="inbox-menu-lg">
							<li class="active">
								<a class="inbox-load" href="javascript:void(0);"> Inbox (<span class="numRows"></span>) </a>
							</li>
						</ul>
					</div>-->
                    <div class="table-wrap custom-scroll animated fast fadeInRight">
						<!-- ajax will fill this area -->
						LOADING...
					</div>
			    </div>


			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->

		<!-- PAGE FOOTER -->
		<div class="page-footer">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<span class="txt-color-white"><?php echo DOMAIN_NAME; ?> <span class="hidden-xs"> - Employee Recruitment System</span> © <?php echo date('Y'); ?></span>
				</div>

				<div class="col-xs-6 col-sm-6 text-right hidden-xs">
					<div class="txt-color-white inline-block">
						<!--<i class="txt-color-blueLight hidden-mobile">Last account activity <i class="fa fa-clock-o"></i> <strong>52 mins ago &nbsp;</strong> </i>
						<div class="btn-group dropup">
							<button class="btn btn-xs dropdown-toggle bg-color-blue txt-color-white" data-toggle="dropdown">
								<i class="fa fa-link"></i> <span class="caret"></span>
							</button>
							<ul class="dropdown-menu pull-right text-left">
								<li>
									<div class="padding-5">
										<p class="txt-color-darken font-sm no-margin">Download Progress</p>
										<div class="progress progress-micro no-margin">
											<div class="progress-bar progress-bar-success" style="width: 50%;"></div>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li>
									<div class="padding-5">
										<p class="txt-color-darken font-sm no-margin">Server Load</p>
										<div class="progress progress-micro no-margin">
											<div class="progress-bar progress-bar-success" style="width: 20%;"></div>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li>
									<div class="padding-5">
										<p class="txt-color-darken font-sm no-margin">Memory Load <span class="text-danger">*critical*</span></p>
										<div class="progress progress-micro no-margin">
											<div class="progress-bar progress-bar-danger" style="width: 70%;"></div>
										</div>
									</div>
								</li>
								<li class="divider"></li>
								<li>
									<div class="padding-5">
										<button class="btn btn-block btn-default">refresh</button>
									</div>
								</li>
							</ul>
						</div>-->
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE FOOTER -->

		<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
		Note: These tiles are completely responsive,
		you can add as many as you like
		-->
		<div id="shortcut">
			<ul>
				<li>
					<a href="inbox.php" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a>
				</li>
				<li>
					<a href="calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
				</li>
				<li>
					<a href="gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
				</li>
				<li>
					<a href="invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Invoice <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
				</li>
				<li>
					<a href="gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
				</li>
				<li>
					<a href="profile.html" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
				</li>
			</ul>
		</div>
		<!-- END SHORTCUT AREA -->

		<!--================================================== -->

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script data-pace-options='{"restartOnRequestAfter": true}' src="js/plugin/pace/pace.min.js"></script>

		<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script>
			if (!window.jQuery) {
				document.write('<script src="js/libs/jquery-2.1.1.min.js"><\/script>');
			}
		</script>

		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>
        <!-- IMPORTANT: APP CONFIG -->
		<script src="js/app.config.js"></script>
        <!-- BOOTSTRAP JS -->
		<script src="js/bootstrap/bootstrap.min.js"></script>
		<!-- Jquery Form Validator -->
        <script type="text/javascript" src="js/jquery.validate.js"></script>
        <!-- Jquery time picker -->
        <script type="text/javascript" src="js/timepicker/jquery-ui-sliderAccess.js"></script>
        <script type="text/javascript" src="js/timepicker/jquery-ui-timepicker-addon.js"></script>
        <!-- Chandler Scripts -->
		<script type="text/javascript" src="js/candidateQuery.js"></script>
		
		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
		<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 
		
		<!-- CUSTOM NOTIFICATION -->
		<script src="js/notification/SmartNotification.min.js"></script>
		<!-- JARVIS WIDGETS -->
		<script src="js/smartwidgets/jarvis.widget.min.js"></script>
		<!-- FastClick: For mobile devices -->
		<script src="js/plugin/fastclick/fastclick.min.js"></script>
		<!--[if IE 8]>
		<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
		<![endif]-->
		<!-- Demo purpose only -->
		<script src="js/demo.js"></script>
		<!-- MAIN APP JS FILE -->
		<script src="js/app.js"></script>
		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="js/speech/voicecommand.min.js"></script>

		<!-- PAGE RELATED PLUGIN(S) -->
		
		<script src="js/plugin/delete-table-row/delete-table-row.min.js"></script>
		
		<script src="js/plugin/summernote/summernote.min.js"></script>
		
		<script src="js/plugin/select2/select2.min.js"></script>
		
        <!-- PAGE RELATED PLUGIN(S) -->
		<script src="js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
        
		<script type="text/javascript">
		
		$(document).ready(function() {
			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			pageSetUp();
			// PAGE RELATED SCRIPTS
			/*
			 * Fixed table height
			 */
			//tableHeightSize()
			//$(window).resize(function() {
			//	tableHeightSize()
			//})
			
			function tableHeightSize() {
				if ($('body').hasClass('menu-on-top')) {
					var menuHeight = 68;
					// nav height
	
					var tableHeight = ($(window).height() - 224) - menuHeight;
					if (tableHeight < (320 - menuHeight)) {
						$('.table-wrap').css('height', (320 - menuHeight) + 'px');
					} else {
						$('.table-wrap').css('height', tableHeight + 'px');
					}
				} else {
					var tableHeight = $(window).height() - 224;
					if (tableHeight < 320) {
						$('.table-wrap').css('height', 320 + 'px');
					} else {
						$('.table-wrap').css('height', tableHeight + 'px');
					}
				}
			}
			loadCandidateInfo();
			function loadCandidateInfo() {
				loadURL("ajax/candidate/candidateList.php", $('#candidate-list > .table-wrap'));
			}
			
		});	
			
		
		</script>

	</body>

</html>