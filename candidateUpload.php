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
	<body>
		<!-- HEADER -->
		<header id="header">
			<?php include "template/top_menu.php";
			if($_REQUEST['error_msg']<>''){echo base64_decode($_REQUEST['error_msg']);}?>

		</header>
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
					<li>Candidate </li>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
				
			    <div id="candidate-list" class="inbox-body no-content-padding">
					
                    <div class="table-wrap custom-scroll animated fast fadeInRight">
						<!-- ajax will fill this area -->
                        <!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
									</div>
									<!-- end widget edit box -->
									<!-- widget content -->
									<div class="widget-body">
                                    <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                      <thead>	
                                        <tr>		                
                                            <th>MATCH</th>
                                            <th data-hide="phone">CANDIDATEID</th>
                                            <th data-class="phone,tablet"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i> NAME</th>
                                            <th data-hide="phone"><i class="fa fa-fw fa-phone txt-color-blue hidden-md hidden-sm hidden-xs"></i> MOBILE NO</th>
                                            <th data-hide="phone,tablet"><i class="fa fa-fw fa-envelope txt-color-blue hidden-md hidden-sm hidden-xs"></i> EMAIL</th>
                                        </tr>
                                      </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
									

									</div>
									<!-- end widget content -->
				
								</div>
								<!-- end widget div -->
				
							</div>
							<!-- end widget -->
                            <br>
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
					<span class="txt-color-white"><?php echo DOMAIN_NAME; ?>?> <span class="hidden-xs"> - Employee Recruitment System</span> © 2024</span>
				</div>

				<div class="col-xs-6 col-sm-6 text-right hidden-xs">
					<div class="txt-color-white inline-block">
						
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
		<?php include "template/scripts.php"; ?>

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
			/*loadCandidateInfo();
			function loadCandidateInfo() {
				loadURL("ajax/candidate/candidateMatch.php", $('#candidate-list > .table-wrap'));
			}*/
			
		});	
		</script>
		<script src="js/chandlerQuery.js"></script>
		
        <script type="text/javascript">
		  var responsiveHelper_dt_basic = undefined;
		  var responsiveHelper_datatable_fixed_column = undefined;
		  var responsiveHelper_datatable_col_reorder = undefined;
		  var responsiveHelper_datatable_tabletools = undefined;
		  
		  var breakpointDefinition = {
			  tablet : 1024,
			  phone : 480
		  };
		  
		  $('#dt_basic').dataTable({
			  "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				  "t"+
				  "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			  "autoWidth" : true,
			  "oLanguage": {
				  "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
			  },
			  "preDrawCallback" : function() {
				  // Initialize the responsive datatables helper once.
				  if (!responsiveHelper_dt_basic) {
					  responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
				  }
			  },
			  "rowCallback" : function(nRow) {
				  responsiveHelper_dt_basic.createExpandIcon(nRow);
			  },
			  "drawCallback" : function(oSettings) {
				  responsiveHelper_dt_basic.respond();
			  },
			  "bPaginate": false,
			  "bInfo" : false
		  });
	</script>
	</body>

</html>