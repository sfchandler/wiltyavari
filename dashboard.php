<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}
echo $_REQUEST['canId'];
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
					<li>Dashboard</li>
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
    				
                        <table width="100%" border="0">
                          <tbody>
                            <tr>
                              <td>
                              		<form name="frmSearch" action="" method="post">
                                    <div class="row">
                                    	<div class="col-sm-12">
                                    		<div class="row">
                                                <section class="col-sm-4">	
                                                    <input class="form-control input-md" type="text" name="searchTxt" id="searchTxt" placeholder="Search People by first name..." value=""/>
                                                    
                                                </section>
                                                <section class="col-sm-4">	                    
                                                    <div class="input-group-btn">
                                                    <button id="srchCandidateBtn" name="srchCandidateBtn" class="srchCandidateBtn btn btn-default btn-primary" type="button" tabindex="0">
                                                                &nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-search fa-lg"></i>Search&nbsp;&nbsp;&nbsp;
                                                    </button>
                                                    </div>
                                                </section>
                                                <section class="col-sm-4">
                                                        <table  border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover">
                                                          <tbody>
                                                            <tr>
                                                              <td>&nbsp;</td>
                                                              <td>&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                              <td>&nbsp;</td>
                                                              <td>&nbsp;</td>
                                                            </tr>
                                                          </tbody>
                                                        </table>
        
                                                    </section>  
                                        	</div>
                                        	<div class="row">
                                            	<section class="col-sm-6">
                                                	
                                                    	<table  border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover">
                                                          <tbody>
                                                            <div class="personInfo"></div>
                                                          </tbody>
                                                        </table>
                                                    <br><br>
                                            	</section>
                                        	</div>      
                                        </div>
                                    </div>
                                    </form>
                              </td>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td><div class="canResult"></div></td>
                              <td>&nbsp;</td>
                            </tr>
                          </tbody>
                        </table>

                       
                </div>


			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->

		<!-- PAGE FOOTER -->
		<div class="page-footer">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<span class="txt-color-white"> <?php echo DOMAIN_NAME; ?> <span class="hidden-xs"> -  Employee Recruitment System</span></span>
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
    <script type="text/javascript">
		$(function() {
			$(document).on('click','#srchCandidateBtn', function(){
				var srchTerm = $('#searchTxt').val();
				$.ajax({
					 type:"POST",
					 url: "./searchCandidate.php",
					 data: { srchTerm : srchTerm},
					 dataType: 'html',
					 success: function (data) {
						console.log('Data...'+data);
						/*$.each(data, function(index, element) {
							$('.personInfo').html(element.lastName+' '+element.firstName+' ('+element.consultantName+')'+' ');
						});*/
						$('.personInfo').html(data);
					 }
				});
			});
		});
    </script>
	</body>

</html>