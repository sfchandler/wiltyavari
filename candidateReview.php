<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}

if($_REQUEST['action'] == 'JOBBOARD'){
    $firstName = $_REQUEST['fN'];
    $lastName = $_REQUEST['lN'];
    $jbId = $_REQUEST['jb_id'];
    $email = $_REQUEST['eml'];
    $phone = $_REQUEST['mbl'];
}else {
    $email = $_REQUEST['eml'];
    $phone = $_REQUEST['mbl'];
    $firstName = $_REQUEST['fN'];
    $lastName = $_REQUEST['lN'];
    $candidateMailFrom = retrieveCandidateName($mysqli, htmlentities($_REQUEST['messageid']), $_SESSION['accountName']);
    if (empty($email)) {
        $email = $candidateMailFrom;
    }
    if (strpos($candidateMailFrom, 'via') !== false) {
        $str = explode('via', $candidateMailFrom);
        $fullName = explode(' ', $str[0]);
        $firstName = trim($fullName[0]);
        $lastName = $fullName[1] . ' ' . $fullName[2];
    }elseif (strpos($candidateMailFrom, '<') !== false){
        $str = explode('<', $candidateMailFrom);
        $fullName = explode(' ', $str[0]);
        $firstName = trim($fullName[0]);
        $lastName = $fullName[1] . ' ' . $fullName[2];
    }

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
			?>
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
		<div id="main" role="main">
			<!-- RIBBON -->
			<div id="ribbon">
				<span class="ribbon-button-alignment"> 
				</span>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
                <div>
                            <div>
                                    <input type="hidden" name="msg_id" id="msg_id" value="<?php echo $_REQUEST['messageid']; ?>"/>
                                    <input type="hidden" name="ref_code" id="ref_code" value="<?php echo $_REQUEST['refcode']; ?>"/>
                                    <input type="hidden" name="first_name" id="first_name" value="<?php echo $firstName; ?>"/>
                                    <input type="hidden" name="last_name" id="last_name" value="<?php echo $lastName; ?>"/>
                                <span class="error"><?php if($_REQUEST['error_msg']<>''){echo $_REQUEST['error_msg'];} ?></span>
                                <h2>Search System for candidate:</h2>
                                <div>
                                    <form class="smart-form" name="frmUpload" id="frmUpload" action="processUpload.php" method="post">
                                        <div class="row">
                                            <section class="col col-1">
                                                <label for="fN" class="label">First Name</label>
                                                <label class="input"><i class="icon-append fa fa-user"></i>
                                                <input class="input" type="text" name="fN" id="fN" class="form-control" value="<?php echo $firstName; ?>">
                                                </label>
                                            </section>
                                            <section class="col col-1">
                                                <label for="lN" class="label">Last Name</label>
                                                <label class="input"><i class="icon-append fa fa-user"></i>
                                                <input class="input" type="text" name="lN" id="lN" class="form-control" value="<?php echo $lastName; ?>">
                                                </label>
                                            </section>
                                            <section class="col col-1">
                                                <label for="em" class="label">Email:</label>
                                                <label class="input"><i class="icon-append fa fa-envelope"></i>
                                                <input class="input" type="email" name="em" id="em" value="<?php echo $email; ?>" class="form-control" required>
                                                <span class="error" id="emErr"></span>
                                                </label>
                                            </section>
                                            <section class="col col-1">
                                                <label for="em" class="label">Job Ref Code:</label>
                                                <label class="input"><i class="icon-append fa fa-envelope"></i>
                                                    <input class="input" type="text" name="rc" id="rc" value="<?php echo $_REQUEST['refcode'];?>" class="form-control" readonly>
                                                </label>
                                            </section>
                                            <section class="col col-1">
                                                <label for="ph" class="label">Phone Number</label>
                                                <label class="input"><i class="icon-append fa fa-phone"></i>
                                                    <input class="input" type="text" name="ph" id="ph" value="<?php echo $phone; ?>" class="form-control">
                                                    <span class="error" id="phErr"></span>
                                                </label>
                                            </section>
                                            <section class="col col-2">
                                                <label for="reason_for_suitability" class="label">Reason For suitable </label>
                                                <label class="input"><i class="icon-append fa fa-check"></i>
                                                    <textarea name="reason_for_suitability" id="reason_for_suitability" cols="30" rows="10" class="form-control"></textarea>
                                                    <span class="error" id="rsErr"></span>
                                                </label>
                                            </section>
                                            <section class="col col-2">
                                                <label for="ph_screen_time" class="label">Phone Screen book time</label>
                                                <label class="input"><i class="icon-append fa fa-times"></i>
                                                    <input name="ph_screen_time" id="ph_screen_time" class="ph_screen_time form-control" readonly/>
                                                    <span class="error" id="phBookErr"></span>
                                                </label>
                                            </section>
                                            <section class="col col-1">
                                                <br>
                                                <input type="hidden" name="msgId" id="msgId" value="<?php echo $_REQUEST['messageid']; ?>">
                                                <input type="hidden" name="autoId" id="autoId" value="<?php echo $_REQUEST['autoId']; ?>">
                                                <input type="hidden" name="jbId" id="jbId" value="<?php echo $_REQUEST['jb_id']; ?>">
                                                <input type="hidden" name="consId" id="consId" value="<?php echo getConsultantId($mysqli,$_SESSION['userSession']); ?>">
                                                <button name="doubleCheckBtn" type="button" class="doubleCheckBtn btn btn-primary btn-sm"><i class="glyphicon glyphicon-check"></i>Check</button>
                                                <button name="uploadBtn" type="submit" class="uploadBtn btn btn-primary btn-sm" style="display: none"><i class="glyphicon glyphicon-upload"></i>New/Update</button>
                                             </section>
                                         </div>
                                    </form>
                                </div>
                                <br>
                            </div>
                            <!-- end widget div -->

                        </div>
                 <br>
                <div id="searchResults"></div>
            </div>
        </div>
			<!-- END MAIN CONTENT -->
		<!-- PAGE FOOTER -->
		<div class="page-footer">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<span class="txt-color-white"><?php echo DOMAIN_NAME; ?><span class="hidden-xs"> - Employee Recruitment System</span></span>
				</div>

				<div class="col-xs-6 col-sm-6 text-right hidden-xs">
					<div class="txt-color-white inline-block">

					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE FOOTER -->
		<?php include "template/scripts.php"; ?>
         <script>
	 		 runAllForms();
			 $(function() {
                 loadSearchResults();
                 function loadSearchResults() {
                     let messageid = $('#msg_id').val();
                     let refcode = $('#ref_code').val();
                     let firstName = $('#first_name').val();
                     let lastName = $('#last_name').val();
                     $.ajax({
                         url: "candidateMatch.php",
                         type: "POST",
                         data: {
                             firstName: firstName,
                             lastName: lastName,
                             refcode: refcode,
                             messageid: messageid
                         },
                         dataType: "html",
                         success: function (data) {
                             $('#searchResults').html('');
                             $('#searchResults').html(data);
                         }
                     });
                 }
                 $('.ph_screen_time').datetimepicker({
                     controlType: 'select',
                     timeFormat: 'HH:mm',
                     dateFormat: 'yy-mm-dd'
                 });
                 $(document).on('click', '.ph_screen_time', function () {
                     $('.ph_screen_time').datetimepicker();
                 });
                 $(document).on('click','.doubleCheckBtn', function (){
                     console.log('time '+$('#ph_screen_time').val());
                    let messageid = $('#msg_id').val();
                    let refcode = $('#rc').val();
                    let firstName = $('#fN').val();
                    let lastName = $('#lN').val();
                    let email = $('#em').val();
                    let phone = $('#ph').val();
                    let reason_for_suitability = $('#reason_for_suitability').val();
                    if((email == '')){
                        $('#emErr').html('');
                        $('#emErr').html('Please enter email address');
                    }else if((phone == '')){
                        $('#phErr').html('');
                        $('#phErr').html('Please enter phone number');
                    }else if((reason_for_suitability == '')){
                        $('#rsErr').html('');
                        $('#rsErr').html('Please enter reason for suitability');
                    }else {
                        $('#phErr').html('');
                        $('#emErr').html('');
                        $('#rsErr').html('');
                        $.ajax({
                            url: "candidateMatch.php",
                            type: "POST",
                            data: {
                                firstName: firstName,
                                lastName: lastName,
                                refcode: refcode,
                                messageid: messageid,
                                email: email,
                                phone: phone,
                                reason_for_suitability:reason_for_suitability
                            },
                            dataType: "html",
                            success: function (data) {
                                $('.uploadBtn').show();
                                $('#searchResults').html('');
                                $('#searchResults').html(data);
                            }
                        });
                    }
                });
				$(document).on('click', '.uploadBtn', function(evt) {
				  var errorClass = 'invalid';
				  var errorElement = 'em';
				  var $screenFrm = $("#frmUpload").validate({
					  errorClass	: errorClass,
					  errorElement	: errorElement,
					  highlight: function(element) {
						  $(element).parent().removeClass('state-success').addClass("state-error");
						  $(element).removeClass('valid');
					  },
					  unhighlight: function(element) {
						  $(element).parent().removeClass("state-error").addClass('state-success');
						  $(element).addClass('valid');
					  },
					  rules: {
						 fN: {
							required: true
							   },
						 lN: {
							required: true
							   },
						 em: {
							required: true,
							email : true
							   },
						 ph: {
							required: true
						 },
                         reason_for_suitability:{
                             required:true
                         },
                         ph_screen_time:{
                             required:true
                         }
					 },
					 messages: {
						fN:{
							required: "Please enter candidate first name"
						},
						lN:{
							required: "Please enter candidate last name"
						},
						em:{
							required: "Please enter candidate email",
							email : "Please enter a VALID email address"
						},
						ph:{
							required: "Please enter candidate mobile"
						},
                        reason_for_suitability:{
                            required: "Please enter reason for suitability"
                        },
                        ph_screen_time:{
                            required: "Please select phone screen schedule"
                        }
					 },
					 submitHandler: function (form) {
						form.submit();
					 },
                     errorPlacement : function(error, element) {
                        error.insertAfter(element.parent());
                     }
					});
				});
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