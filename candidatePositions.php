<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}
if($_SESSION['userType']!=='ACCOUNTS'){
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
			<?php include "template/top_menu.php"; ?>
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
					<?php include "template/breadcrumblinks.php"; ?>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
            	<div class="content-body no-content-padding">
					<div style="padding-left:30px;">
                    	<h2  class="semi-bold">Candidate Positions</h2>
                    </div>
                    <div style="width:100%">            
                        <div style="float:left; padding-left:20px;padding-bottom:50px; width:50%">
                            <div class="error"></div>
                            <form id="positionFrm" class="smart-form" method="post">
                                <header>
                                        Add Candidate Positions
                                </header>
                                    <fieldset>
                                        <div class="row">
                                            <section class="col col-4">
                                                <label class="input"> <i class="icon-append fa fa-certificate"></i>
                                                    <input type="text" name="position" id="position" placeholder="Candidate Position">
                                                </label>
                                            </section>
                                            <section class="col col-4">
                                            	<div class="input-group-btn">
                                                    <button class="addPositionBtn btn btn-primary btn-sm" type="submit" value="AddPosition"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add Position</button>
                                                </div>
                                            </section>
                                        </div>
                                    </fieldset>
                            </form>
                            <form id="editpositionFrm" class="smart-form" method="post">
                                <header>
                                    Edit Candidate Position
                                </header>
                                <fieldset>
                                    <div class="row">
                                        <section class="col col-4">
                                            <label class="input"> <i class="icon-append fa fa-certificate"></i>
                                                <input type="hidden" id="epositionid" name="epositionid" value=""/>
                                                <input type="text" name="eposition" id="eposition" placeholder="Candidate Position" value="">
                                            </label>
                                        </section>
                                        <section class="col col-4">
                                            <div class="input-group-btn">
                                                <button class="updatePositionBtn btn btn-primary btn-sm" type="submit" value="UpdatePosition"><i class="glyphicon glyphicon-plus"></i>&nbsp;Update Position</button>
                                            </div>
                                        </section>
                                    </div>
                                </fieldset>
                            </form>
                            <div class="positionsList">
                            	<table width="100%" class="table table-striped table-bordered table-hover">
                                    <thead>
                                      <tr><th>Position ID</th>
                                          <th>Position</th>
                                          <th>Action</th>
                                      </tr>
                                    </thead>	
                                    <tbody class="positionsBody">

                                    </tbody>
                                </table>
                            </div>
                      </div>
                   </div>   
                </div>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN PANEL -->
		<?php include "template/footer.php"; ?>
		<?php include "template/scripts.php"; ?>
		<!-- JQUERY VALIDATE -->
        <script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
        <!-- JQUERY VALIDATE -->
        <script src="js/plugin/jquery-validate/additional-methods.js"></script>
        <!-- JQUERY MASKED INPUT -->
        <script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
        <!-- JQUERY FORM PLUGIN -->
        <script src="js/jqueryform/jquery.form.js"></script>
        <script>
			$(function(){
                 $('#editpositionFrm').hide();
                 $('#positionFrm').show();
				 function addCandidatePositions(position) {
					  $.ajax({
							  url: "addCandidatePosition.php",
							  type: "POST",
							  dataType: "html",
							  data: {position : position},
							  success: function(data) {
							      if(data == 'exists'){
                                      $('.error').html('');
                                      $('.error').html('Position Already Exists');
                                  }else{
                                      $('.error').html('');
                                      $('.positionsBody').html('');
                                      $('.positionsBody').html(data);
                                  }
							  }
                      });
				 }
				 function editCandidatePositions(positionid,position){
                     $.ajax({
                         url:"editCandidatePosition.php",
                         type:"POST",
                         dataType:"text",
                         data: {positionid : positionid, position : position},
                         success: function(data){
                             getCandidatePositions();
                         }
                     });
                 }
                 getCandidatePositions();

				 function getCandidatePositions(){
					  $.ajax({
							  url:"getCandidatePositions.php",
							  type:"POST",
							  dataType:"html",
							  success: function(data){
								  $('.positionsBody').html('');
								  $('.positionsBody').html(data);
                                  $('#editpositionFrm').hide();
                                  $('#positionFrm').show();
							  }
						  });
				 }

				 $(document).on('click', '.addPositionBtn', function(evt) {
                     $('.error').html('');
					  var errorClass = 'invalid';
					  var errorElement = 'em';
					  var positionFrm = $("#positionFrm").validate({
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
							 position: {
								required: true
								   }
						 },
						 messages: {
							position: {
								required: "Please enter Candidate Position"
							}
						 },
						 submitHandler: function (form) {
							  var position = $('#position').val();
                              addCandidatePositions(position);
						 },
						 errorPlacement : function(error, element) {
							error.insertAfter(element.parent());
						 }
						});
					});
                    $(document).on('click','.editPositionBtn', function(){
                        $('.error').html('');
                        $('#positionFrm').hide();
                        $('#editpositionFrm').show();
                        var $row = $(this).closest("tr");
                        var positionid = $row.find('.positionid').data('positionid');
                        var position  = $row.find('.positionid').data('position');
                        $('#epositionid').val(positionid);
                        $('#eposition').val(position);
                    });
				    $(document).on('click','.updatePositionBtn', function(){
                        var errorClass = 'invalid';
                        var errorElement = 'em';
                        var editpositionFrm = $("#editpositionFrm").validate({
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
                                eposition: {
                                    required: true
                                }
                            },
                            messages: {
                                eposition: {
                                    required: "Please enter Candidate Position"
                                }
                            },
                            submitHandler: function (form) {
                                var positionid = $('#epositionid').val();
                                var position = $('#eposition').val();
                                editCandidatePositions(positionid,position)
                            },
                            errorPlacement : function(error, element) {
                                error.insertAfter(element.parent());
                            }
                        });

                    });
					$(document).on('click','.removePositionBtn', function(){
                        $('.error').html('');
						var $row = $(this).closest("tr");
						var positionid = $row.find('.positionid').data('positionid');
						$.ajax({
							  url:"removeCandidatePosition.php",
							  type:"POST",
							  dataType:"text",
							  data: {positionid : positionid},
							  success: function(data){
                                  getCandidatePositions();
							  }
                        });
					});

			});
		</script>
	</body>

</html>