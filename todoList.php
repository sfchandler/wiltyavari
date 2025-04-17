<?php
session_start();

require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}
$consultants = getConsultants($mysqli);
$sessionId = session_id(); 
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
                <div class="inbox-body no-content-padding">
				    <div style="padding-left:20px; padding-top:5px; padding-bottom: 10px;">
                         <h3 class="alert-heading"><i class="glyphicon glyphicon-book"></i>&nbsp;My ToDo List</h3>
                         <input type="hidden" name="consId" id="consId" value="<?php echo getConsultantId($mysqli,$_SESSION['userSession']); ?>"/>
                         	<form name="frmSort" id="frmSort">
                            	<input type="radio" name="sortType" id="sortType" value="Overdue">Overdue
                            	<input type="radio" name="sortType" id="sortType" value="ToDo">To Do
                                <select name="duration" id="duration">
                                	<option value="None">Select Duration</option>
                                	<option value="Week">Week</option>
                                    <option value="Fortnight">Fortnight</option>
                                    <option value="Month">Month</option>
                                    <option value="Today">Today</option>
                                    <option value="Tomorrow">Tomorrow</option>
                                </select>
                                <select name="consultantId" id="consultantId">
								  <?php foreach($consultants as $cT){ ?>														
                                      <option value="<?php echo $cT['consultantId']; ?>" <?php if($cT['consultantId'] == getConsultantId($mysqli,$_SESSION['userSession'])){?> selected <?php } ?>><?php echo $cT['name']; ?></option>
                                  <?php } ?>
                                </select><i></i> 
                            </form>
                            <div align="center"><span id="dashNotesLoading">Loading Please wait...</span></div>
                            <div class="dashNotesList">
                            <?php $activities = getActivityList($mysqli);?>
                            <table id="datatable_tabletools" class="table table-striped table-bordered table-hover" width="100%">
                              <thead>	
                                  <tr>		                
                                      <th data-class="expand"><i class="fa fa-fw fa-flag txt-color-blue hidden-md hidden-sm hidden-xs"></i>PRIORITY</th>
                                      <th data-hide="phone"><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>TODO DATE</th>
                                      <th data-class="phone,tablet"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i> TIME</th>
                                      <th data-hide="phone"><i class="fa fa-fw fa-hand-o-down txt-color-blue hidden-md hidden-sm hidden-xs"></i> ACTIVITY<br><select name="activityId" id="activityId" class="select">
                                              <option value="0">None</option>
                                              <?php
                                              foreach($activities as $act){
                                                  ?>
                                                  <option value="<?php echo $act['activityId']; ?>" <?php if($activityId == $act['activityId']){?> selected <?php } ?>><?php echo $act['activityType']; ?></option>
                                              <?php } ?>
                                          </select></th>
                                      <th data-hide="phone"><i class="fa fa-fw fa-asterisk txt-color-blue hidden-md hidden-sm hidden-xs"></i> SUBJECT</th>
                                      <th data-hide="phone"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i> REGARDING</th>
                                      <th data-hide="phone"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i> CONSULTANT</th>
                                  </tr>
                              </thead>
                              <tbody class="dashNotes">
                              </tbody>
                            </table>
                            </div>
                    </div>
                    <div style="padding-bottom: 50px">&nbsp;</div>
                </div>
            </div>
			<!-- END MAIN CONTENT -->
			</div>
		<!-- END MAIN PANEL -->

		<!-- PAGE FOOTER -->
		<?php include "template/footer.php"; ?>
		<!-- END PAGE FOOTER -->		
		<!-- END SHORTCUT AREA -->
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
	 		 runAllForms();
			 $(function(){
                 $(document).on('click','.attrRemove',function(){
					  var $row = $(this).closest("tr");
					  var sid = $row.find('.sid').data('sid');
					  var oid = $row.find('.oid').data('oid');
					  var limitStart = 0;
					  $("#attrLoading").show();
					  $.ajax({
						 type:"POST",
						 url: "./removeSearchAttribute.php",
						 data: { limitStart : limitStart, sid : sid, oid : oid},
						 dataType: 'html',
						 success: function (data) {
							$('.attributesList').html('');
							loadAttributes(limitStart, sid);
						 }
					  }).done(function(data){

					  });
				 });
                 loadOtherLicences();
                 function loadOtherLicences(){
                     $('.ui-autocomplete-input').css('width','240px')
                     $('#otherLicenceId').autocomplete({
                         source: <?php include "./getAttributes.php"; ?>,
                         select: function(event, ui) {
                             var otherLicenceType = ui.item.value;
                             var otherLicenceId = ui.item.id;
                             $('#attrId').val(otherLicenceId);
                             $('#otherLicenceId').addClass('searchGroup');
                         }
                     });
                     $('#otherLicenceId').addClass('searchGroup');
                 }
				  $(document).bind('keypress', function(e) {
					  if(e.keyCode==13){
						  e.preventDefault();
						  $('.srchCandidateBtn').trigger('click');
                          $('#otherLicenceId').prop('size',30);
					  }
				 });

				 $(document).on('click','#status', function(){
						if($('input[name=status]:checked', '#frmFilter').val() !== ''){
							var status = $(this).val();
							if(status != ''){
								$.ajax({
								   type: "POST",
								   url: "./notesList.php",
								   data: {status : status},
								   dataType: "html",
								   success: function (data) {
									   $('.notesList').html('');
									   $('.notesList').html(data);
								   }
								});
							}
						}
					});

				  $(document).on('click', '#sortType', function(){
					if($('input[name=sortType]:checked', '#frmSort').val() == 'ToDo'){
						$('#duration').attr('disabled',false);
						$(".dashNotes").html('');
						loadDashResults(0,$('#consId').val(),$('input[name=sortType]:checked', '#frmSort').val(),$('#duration :selected').val(),$('#consultantId :selected').val(),$('#activityId :selected').val());
					}else{
						$('#duration').attr('disabled',true);
						$(".dashNotes").html('');
				  		loadDashResults(0,$('#consId').val(),$('input[name=sortType]:checked', '#frmSort').val(),'None',$('#consultantId :selected').val(),$('#activityId :selected').val());
					}
				  });
				  $(document).on('click', '#consultantId', function(){
						$(".dashNotes").html('');
						loadDashResults(0,$('#consId').val(),$('input[name=sortType]:checked', '#frmSort').val(),$('#duration :selected').val(),$('#consultantId :selected').val(),$('#activityId :selected').val());
				  });
				  $(document).on('click', '#duration', function(){
				  	$(".dashNotes").html('');
				  	loadDashResults(0,$('#consId').val(),$('input[name=sortType]:checked', '#frmSort').val(),$('#duration :selected').val(),$('#consultantId :selected').val(),$('#activityId :selected').val());
				  });

                  $(document).on('click','#activityId',function () {
                      $(".dashNotes").html('');
                      loadDashResults(0,$('#consId').val(),$('input[name=sortType]:checked', '#frmSort').val(),$('#duration :selected').val(),$('#consultantId :selected').val(),$('#activityId :selected').val());
                  });

				  loadDashResults(0,$('#consId').val(),$('input[name=sortType]:checked', '#frmSort').val(),$('#duration :selected').val(),$('#consultantId :selected').val(),$('#activityId :selected').val());

				  $('.dashNotesList').scroll(function() {
					if($("#dashNotesLoading").css('display') == 'none') {
					  if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
						 var limitStart = $(".dashNotes tr").length;
						 loadDashResults(limitStart,$('#consId').val(),$('input[name=sortType]:checked', '#frmSort').val(),$('#duration :selected').val(),$('#consultantId :selected').val(),$('#activityId :selected').val());
					  }
					}
				  });

                 var on = false;
                 window.setInterval(function() {
                     on = !on;
                     if (on) {
                         $('.vevo').toggle( "pulsate" );//.addClass('vevo-blink');
                         //$('.vevo').animate({right: "100px"}, 500, 'swing');
                     } else {
                         $('.vevo-blink').toggle( "pulsate" );//$('.vevo-blink').removeClass('vevo-blink');
                         //$('.vevo').animate({right: "100px"}, 500, 'swing');
                     }
                 }, 2000);
				  function loadDashResults(limitStart, consId, sortType, duration,consultantId,actSort) {
					  $("#dashNotesLoading").show();
					  $.ajax({
						  url: "dashboardNotes.php",
						  type: "POST",
						  dataType: "html",
						  data: {
							  limitStart : limitStart, consId : consId, sortType : sortType, duration : duration,consultantId : consultantId,actSort:actSort},
						  success: function(data) {
							   $(".dashNotes").append(data);
							   $("#dashNotesLoading").hide();     
						  }
					  }).done(function(){
                      });
				  };
					
				  $('.personList').scroll(function() {
					if($("#inbLoading").css('display') == 'none') {
					  if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
						 var limitStart = $(".personInfo tr").length;
						 loadResults(limitStart,$('#canId').val(),$('#axiomno').val(),$('#searchFirstName').val(),$('#searchLastName').val(),$('#searchMobile').val(),$('#searchEmail').val(),$('#attrId').val());
					  }
					}
				  });
				  function empSuggestion(){
                      $('.ui-autocomplete-input').css('width','240px');
                      $('#employeeName').autocomplete({
                          source: <?php include "./employeeList.php"; ?>,
                          select: function(event, ui) {
                              var empName = ui.item.value;
                              var candidateId = ui.item.id;
                              $('#empSelected').val('');
                              $('#empSelected').val(candidateId);
                          }
                      });
                      return true;
                  }
				  function loadResults(limitStart,canId,axiomno,searchFirstName,searchLastName,searchNickName, searchMobile,searchLamattinaId,searchEmail,attrId) {
					  $("#inbLoading").show();
					  $.ajax({
						  url: "searchCandidate.php",
						  type: "POST",
						  dataType: "html",
						  data: {
							  limitStart : limitStart, canId : canId, axiomno : axiomno, searchFirstName : searchFirstName, searchLastName : searchLastName,searchNickName:searchNickName, searchMobile : searchMobile, searchLamattinaId : searchLamattinaId, searchEmail : searchEmail,attrId:attrId},
						  success: function(data) {
							   $(".personInfo").append(data);
							   $("#inbLoading").hide();
						  }
					  }).done(function(){
                          empSuggestion();
                          $('#otherLicenceId').prop('size',30);
                      });
				  };

				  $(document).on('click','.assignBtn', function () {
				       var supervisor = $('#empSelected').val();
				       var srchCanId = $('.srchCanId').closest('td').attr('data-srchcanId');//$(this).closest('td').attr('data-srchcanId');
                       var type = 'ASSIGN';
                      // console.log('....>'+supervisor+srchCanId+type);
				       $.ajax({
                           url: "assignSupervisor.php",
                           type: "POST",
                           dataType: "html",
                           data: { srchCanId : srchCanId, supervisor : supervisor,type:type},
                           success: function(data) {
                               //console.log('data'+data);
                               location.reload();
                           }
                       });
                  });
                  $(document).on('click','.makeSupervisorBtn', function () {
                         var srchCanId = $(this).closest('td').attr('data-srchCanId');//$('.srchCanId').closest('td').attr('data-srchCanId');
                         var type = 'MAKE';
                         $.ajax({
                             url: "assignSupervisor.php",
                             type: "POST",
                             dataType: "html",
                             data: { srchCanId : srchCanId,type:type},
                             success: function(data) {
                                 location.reload();
                             }
                         });
                  });
                  $(document).on('click','.genPassWord',function () {
                      var srchCanId = $(this).closest('td').attr('data-srchCanId');
                      //var srchCanId = $(this).closest("tr").closest('td').find('.srchCanId').attr('data-srchCanId');
                      $.ajax({
                          url: "generateAppCredentials.php",
                          type: "POST",
                          dataType: "html",
                          data: { srchCanId : srchCanId},
                          success: function(data) {
                              if(data == 'SUCCESS'){
                                  $('.erMsg').html('generated Password has been emailed');
                              }else {
                                  $('.erMsg').html(data);
                              }
                          }
                      });
                  });


                 $(document).on('click', '.srchCandidateBtn', function(evt) {
                      $('#otherLicenceId').prop('size',30);
					  var errorClass = 'invalid';
					  var errorElement = 'em';
					  var dashboardFrm = $("#frmDash").validate({
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
							 canId: {
								require_from_group: [1, ".searchGroup"]
								   },
							 axiomno: {
								require_from_group: [1, ".searchGroup"]
								   },	   
							 searchFirstName: {
								require_from_group: [1, ".searchGroup"]
								   },
							 searchLastName: {
								require_from_group: [1, ".searchGroup"]
								   },
                            searchNickName: {
							    require_from_group: [1, ".searchGroup"]
                            },
							searchMobile: {
								require_from_group: [1, ".searchGroup"]
								   },
                            searchLamattinaId:{
							     require_from_group: [1,".searchGroup"]
                            },
							searchEmail: {
								require_from_group: [1, ".searchGroup"]
								   },
                            otherLicenceId: {
                                require_from_group: [1, ".searchGroup"]
                                   }
						 },
						 messages: {
							canId:{
								required: "Please enter Candidate ID"
							},
							axiomno:{
								required: "Please enter Axiom ID"
							},
							searchFirstName:{
								required: "Please enter first name"
							},
							searchLastName:{
								required: "Please enter last name"
							},
                            searchNickName:{
							    required: "Please enter nickname"
                            },
							searchMobile:{
								required: "Please enter mobile no"
							},
                            searchLamattinaId:{
                                 required: "Please enter Lamattina ID"
                            },
							searchEmail:{
								required: "Please enter email"
							},
                            otherLicenceId:{
							    required: "Please select an attribute"
                            }
						 },
						 submitHandler: function (form) {
                              $(".personInfo").html('');
							  loadResults(0,$('#canId').val(),$('#axiomno').val(),$('#searchFirstName').val(),$('#searchLastName').val(),$('#searchNickName').val(),$('#searchMobile').val(),$('#searchLamattinaId').val(),$('#searchEmail').val(),$('#attrId').val());
                              loadOtherLicences();
                              $('#otherLicenceId').prop('size',30);
                         },
						 errorPlacement : function(error, element) {
							error.insertAfter(element.parent());
						 }
						});
					});
			 	
			 });
		</script>
        
        
	</body>

</html>