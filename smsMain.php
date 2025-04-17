<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}
$canId = $_REQUEST['canId'];
$defaultText = 'Hi '.getCandidateFullName($mysqli,$canId).'

Thank you for applying for the role of XXXXXXXXXXXXXXXXXXXXXX with '.DOMAIN_NAME.'

Your application stood out to us and we would like to conduct a phone interview at the below time – XXXXXXXXXXXXX with one of our consultants '.getConsultantName($mysqli,getConsultantIdByCandidateId($mysqli,$canId)).'.

Your interview will last 10-15 minutes, and you will have the chance to discuss the position and clarify any queries.

If you are unable to attend the interview during this time please call us on  to reschedule.

DO NOT REPLY BACK TO THIS TEXT MESSAGE';
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
				<div style="width:100%">
                	<div style="float:left; padding-left:20px; padding-top:5px; width:40%;">
                    	<form name="frmNewSMS" id="frmNewSMS" class="smart-form" method="post" action="">
                        <div>
                        	<fieldset>
                            	<div class="creditBalanceLabel">WHOLESALE SMS CREDIT BALANCE&nbsp;<span class="wholesaleBalance"></span></div>
                                <div class="creditBalanceLabel">CELLCAST CREDIT BALANCE&nbsp;<span class="cellCastBalance"></span></div>
                            	<div class="row"> 
                                    <section class="col col-6">
                                        <label class="label">Activity:</label>
                                        <label class="select">
                                            <select id="act" name="act">
                                                <option value="SMS">SMS</option>
                                            </select> <i></i>
                                        </label>  
                                    </section>
                                    <section class="col col-6">
                                    	<div class="input-group-btn" style="padding-top:25px">
                                             <button class="sendBtn btn btn-primary btn-sm" type="submit" value="Search"><i class="glyphicon glyphicon-envelope"></i>&nbsp;Send</button>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">    
                                	<section class="col col-6">
                                       <label class="checkbox">
                                       <input type="checkbox" id="alertMe" name="alertMe" value="Yes" checked>
                                       <i></i>Alert Me:</label>
                                    </section>
                                    <section class="col col-6">
                                    	<div class="input-group-btn">
                                             <button class="cancelBtn btn btn-default btn-sm" value="Cancel"><i class="glyphicon glyphicon-remove"></i>&nbsp;Cancel</button>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">    
                                	<section class="col col-6">
                                        <label class="label">SMS Account:</label>
                                        <label class="select">
                                            <select id="smsAccount" name="smsAccount">
                                                <?php echo getSMSAccounts($mysqli); ?>
                                            </select><i></i>
                                        </label> 
                                    </section>
                                    <section class="col col-6">
                                        <label class="supportInfo fa-support"></label>
                                    	<input type="hidden" id="rCanId" value="<?php echo $canId; ?>"/>	
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label for="smsTemplate">SMS Template:</label><select name="smsTemplate" id="smsTemplate">
                                            <option value="DEFAULT" data-smstemp="<?php echo $defaultText;?>">DEFAULT</option>
                                        </select>
                                    </section>
                                </div>
                                <div class="row">
                                	<section class="col col-12" style="width:100%;">
                                    	<label class="textarea textarea-resizable">
                                              <textarea rows="20" class="custom-scroll" name="smsText" id="smsText" placeholder="SMS Text ....."></textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                        </div>
                        <div>
                        	<div><i class="fa fa-fw fa-users txt-color-blue hidden-md hidden-sm hidden-xs"></i>Recipients(<span class="nRecipients" style="color:rgba(37,124,179,1.00); font-weight:bold"></span>)</div>
                        	<table id="smsRecipients" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                                        <thead>	
                                            <tr>		                
                                                <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                                <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>MOBILE</th>
                                                <th data-hide="phone"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>     
                                            </tr>
                                        </thead>
                                        <tbody class="recipients">
                                           
                                        </tbody>
                                      </table>
                        </div>
                        </form>
                        
                    </div>            
                    <div style="float:left; padding-left:20px;padding-left:20px;padding-bottom:50px; width:50%">
                                <div class="row">
                                <form name="frmSMS" id="frmSMS" class="smart-form" method="post" action="">
                                         <fieldset>
                                         	<div class="row"> 
                                                <section class="col col-4">
                                                  <label for="canId" class="label">Candidate ID:</label>
                                                      <label class="input">
                                                          <input type="text" id="canId" name="canId" placeholder="Candidate ID" value="" class="searchGroup"><b class="tooltip tooltip-bottom-right">Please enter Candidate ID</b></label>
                                                </section>
                                                <section class="col col-4">
                                                  <!--<label for="axiomno" class="label">Axiom ID:</label>
                                                      <label class="input">
                                                          <input type="text" id="axiomno" name="axiomno" placeholder="Axiom ID" value="" class="searchGroup"><b class="tooltip tooltip-bottom-right">Please enter Axiom ID</b></label>-->
                                                </section>
                                                <section class="col col-4" style="padding-left:0px;padding-top:25px">
                                                     <div class="input-group-btn">
                                                        <button class="srchCandidateBtn btn btn-primary btn-sm" type="submit" value="Search"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</button>
                                                </section>  
                                            </div>
                                            <div class="row"> 
                                                <section class="col col-6">
                                                  <label for="searchFirstName" class="label">First Name:</label>
                                                      <label class="input">
                                                          <input type="text" id="searchFirstName" name="searchFirstName" placeholder="First Name" value="" class="searchGroup"><b class="tooltip tooltip-bottom-right">Please enter first name text</b></label>
                                                </section>
                                                <section class="col col-6" style="padding-left:0px;padding-top:25px">
                                                     
                                                </section>  
                                            </div>
                                            <div class="row"> 
                                                <section class="col col-6">
                                                  <label for="searchLastName" class="label">Last Name:</label>
                                                      <label class="input">
                                                          <input type="text" id="searchLastName" name="searchLastName" placeholder="Last Name" value="" class="searchGroup"><b class="tooltip tooltip-bottom-right">Please enter last name text</b></label>
                                                </section>
                                                <section class="col col-6" style="padding-left:0px;padding-top:25px">
                                                     
                                                </section>  
                                            </div>
                                            <div class="row"> 
                                                <section class="col col-6">
                                                  <label for="searchMobile" class="label">Mobile Number:</label>
                                                      <label class="input">
                                                          <input type="text" id="searchMobile" name="searchMobile" placeholder="Mobile No" value="" class="searchGroup">
                                                          <b class="tooltip tooltip-bottom-right">Please enter mobile no text</b></label>
                                                </section>
                                                <section class="col col-6" style="padding-left:0px;padding-top:25px">
                                                     
                                                </section>  
                                            </div>
                                            <div class="row"> 
                                                <section class="col col-6">
                                                  <label for="searchEmail" class="label">Email:</label>
                                                      <label class="input">
                                                          <input type="text" id="searchEmail" name="searchEmail" placeholder="Email Address" value="" class="searchGroup">
                                                          <b class="tooltip tooltip-bottom-right">Please enter Email address</b></label>
                                                </section>
                                                <section class="col col-6" style="padding-left:0px;padding-top:25px">
                                                     
                                                </section>  
                                            </div>            
                                        </fieldset>
                                </form>
                                </div>
                                <div align="center"><span id="inbLoading">Loading Please wait...</span></div>
                                <div class="personList">
                                    <table id="tblSMS" border="1" cellpadding="2" cellspacing="2"  class="table table-striped table-bordered table-hover" width="100%">
                                        <thead>	
                                            <tr>		                
                                                <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                                <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>CANDIDATEID</th>
                                                 <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>AXIOMID</th>
                                                <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>MOBILE</th>
                                                <th data-class="phone,tablet"><i class="fa fa-fw fa-home txt-color-blue hidden-md hidden-sm hidden-xs"></i> ADDRESS</th>
                                                <th data-hide="phone"><i class="fa fa-fw fa-envelope txt-color-blue hidden-md hidden-sm hidden-xs"></i> EMAIL</th>
                                           </tr>
                                        </thead>
                                        <tbody class="personInfo">
                                           
                                        </tbody>
                                      </table>
                                </div>
                        </div>
                    </div>
                    <div style="clear:both; width:1%; padding-bottom:50px;"></div>
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
				 /* AJAX loading animation
				$body = $("body");

				$(document).on({
					ajaxStart: function() { $body.addClass("smsprocessing");    },
					 ajaxStop: function() { $body.removeClass("smsprocessing"); }    
				});*/
				/* -  end  -*/
                 $(document).on('click', '#smsTemplate', function(){
                     console.log('....click');
                     if ($(this).val() == 'DEFAULT') {
                         $('textarea#smsText').val('');
                         $('textarea#smsText').val($('#smsTemplate :selected').attr('data-smstemp'));
                     }
                 });
                 var rCanId = $('#rCanId').val();
				 loadRecipients(rCanId,0);
				 function loadRecipients(cid,attempt){
				 	$.ajax({
						  url: "smsList.php",
						  type: "POST",
						  dataType: "html",
						  data: { cid : cid,attempt : attempt},
						  success: function(data) {
							 $('.recipients').html('');
							 $('.recipients').html(data);
							 $('.nRecipients').html('');
							 $('.nRecipients').html($('#recipientCount').val());
						  }
					  });
				 }
				 function removeRecipient(cid,sessId){
				 	$.ajax({
						  url: "removeRecipients.php",
						  type: "POST",
						  dataType: "html",
						  data: { cid : cid, sessId : sessId },
						  success: function(data) {
							 $('.recipients').html('');
							 $('.recipients').html(data);
							 $('.nRecipients').html('');
							 $('.nRecipients').html($('#recipientCount').val());
						  }
					  });
				 }
				 $(document).on('click', '.addRecipient', function(){
				 	var $row = $(this).closest("tr"); 
					var cid = $row.find('.cid').data('cid');
					loadRecipients(cid,1);
					$('.nRecipients').html('');
					$('.nRecipients').html($('#recipientCount').val());
				 });
				 $(document).on('click', '.recipientRemove', function(){
				 	var $row = $(this).closest("tr");
					var cand = $row.find('.cand').data('cand'); 
					var sessid = $row.find('.sessid').data('sessid');
					removeRecipient(cand,sessid);
					$('.nRecipients').html('');
					$('.nRecipients').html($('#recipientCount').val());
				 });

                 var wholesale  = 'WHOLESALE';
                 var cellcast = 'CELLCAST';
                 loadSMSCreditBalance(wholesale);
                 loadSMSCreditBalance(cellcast);
                 function loadSMSCreditBalance(action){
                     $.ajax({
                         url: "balanceCheck.php",
                         type: "POST",
                         data: {action:action},
                         dataType: "text",
                         success: function(data) {
                             if(action == 'WHOLESALE') {
                                 $('.wholesaleBalance').html('');
                                 $('.wholesaleBalance').html(data);
                             }else if(action == 'CELLCAST'){
                                 $('.cellCastBalance').html('');
                                 $('.cellCastBalance').html(data);
                             }
                         }
                     });
                 }
				 $(document).on('click', '.sendBtn', function(){
					  var errorClass = 'invalid';
					  var errorElement = 'em';
					  var screenFrm = $("#frmNewSMS").validate({
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
							 smsText: {
								required: true,
								rangelength:[1,600]
								   } 
						  },
						  messages: {
								smsText: {
									rangelength: function(range, input) {
										return [
											'You are only allowed between ',
											range[0],
											'and ',
											range[1],
											' characters. ',
											' You have typed ',
											$('#smsText').val().length,
											' characters'                                
										].join('');
									}
                        		}	 
						  },
						  submitHandler: function (form) {
							  var act = $('#act').val();
							  var alertMe = $('input[name=alertMe]:checked', '#frmNewSMS').val();
							  var smsAccount = $('#smsAccount option:selected').val(); 
							  var smsText = $('textarea#smsText').val();
							  $.ajax({
								  url: "sendSMS.php",
								  type: "POST",
								  dataType: "text",
								  data: { act : act, alertMe : alertMe, smsAccount : smsAccount, smsText : smsText},
								  success: function(data) {
									 if(data == 'MSGSENT'){
                                         console.log(data);
										 window.close();
									 }else if(data == 'NORECIPIENTS'){
									 	$('.errMsg').html(data);
									 }else{
                                         $('.errMsg').html(data);
									 }
								  }
							  });
						  },
						  errorPlacement : function(error, element) {
							error.insertAfter(element.parent());
						  }
						  });
				 	
				 });
                 var smsAccount = $('#smsAccount option:selected').val();
                 smsSupportInfo(smsAccount);
                 function smsSupportInfo(smsAccount){
                     $.ajax({
                         url: "getSMSAccountInfo.php",
                         type: "POST",
                         dataType: "html",
                         data: {smsAccount : smsAccount},
                         success: function(data) {
                             $(".supportInfo").html('');
                             $(".supportInfo").html(data);
                         }
                     });
                 }
				 $(document).on('change','#smsAccount',function () {
                     var smsAccount = $('#smsAccount option:selected').val();
                     smsSupportInfo(smsAccount);
                 });
				 /*$(document).bind('keypress', function(e) {
					  if(e.keyCode==13){
						  e.preventDefault();
						  $('.srchCandidateBtn').trigger('click');
					  }
				 });*/
				 $('.personList').scroll(function() {
					if($("#inbLoading").css('display') == 'none') {
					  if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
						 var limitStart = $(".personInfo tr").length;
						 loadResults(limitStart,$('#canId').val(),$('#axiomno').val(),$('#searchFirstName').val(),$('#searchLastName').val(),$('#searchMobile').val(),$('#searchEmail').val()); 
					  }
					}
				  });
				  function loadResults(limitStart,canId,axiomno,searchFirstName,searchLastName, searchMobile,searchEmail) {
					  $("#inbLoading").show();
					  $.ajax({
						  url: "searchSMSCandidate.php",
						  type: "POST",
						  dataType: "html",
						  data: {
							  limitStart : limitStart, canId : canId, axiomno : axiomno, searchFirstName : searchFirstName, searchLastName : searchLastName, searchMobile : searchMobile, searchEmail : searchEmail},
						  success: function(data) {
							   $(".personInfo").append(data);
							   $("#inbLoading").hide();     
						  }
					  });
				  };
				 $(document).on('click', '.cancelBtn', function(){
					window.close();
				 });
				 $(document).on('click', '.srchCandidateBtn', function(evt) {
					  var errorClass = 'invalid';
					  var errorElement = 'em';
					  var screenFrm = $("#frmSMS").validate({
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
							searchMobile: {
								require_from_group: [1, ".searchGroup"]
								   },
							searchEmail: {
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
							searchMobile:{
								required: "Please enter mobile no"
							},
							searchEmail:{
								required: "Please enter email"
							}
						 },
						 submitHandler: function (form) {
							  $(".personInfo").html('');
							  loadResults(0,$('#canId').val(),$('#axiomno').val(),$('#searchFirstName').val(),$('#searchLastName').val(),$('#searchMobile').val(),$('#searchEmail').val());
							  /*$.ajax({
								   type:"POST",
								   url: "./searchSMSCandidate.php",
								   data: { srchTerm : srchTerm},
								   dataType: 'html',
								   success: function (data) {
									  console.log('Data...'+data);
									  $('.personInfo').html('');
									  $('.personInfo').html(data);
							  },
							   error: function(jqXHR, exception) {
									if (jqXHR.status === 0) {
										console.log('Not connect.\n Verify Network.');
									} else if (jqXHR.status == 404) {
										console.log('Requested page not found. [404]');
									} else if (jqXHR.status == 500) {
										console.log('Internal Server Error [500].');
									} else if (exception === 'parsererror') {
										console.log('Requested JSON parse failed.');
									} else if (exception === 'timeout') {
										console.log('Time out error.');
									} else if (exception === 'abort') {
										console.log('Ajax request aborted.');
									} else {
										console.log('Uncaught Error.\n' + jqXHR.responseText);
									}
								}
							  }).done(function(){	
							  		smsTable.fnClearTable(this);
									smsTable.fnDraw();					  
							  });*/
						 },
						 errorPlacement : function(error, element) {
							error.insertAfter(element.parent());
						 }
						});
					});


			 });
		</script>
        <div class="modal"><!-- Place at bottom of page --></div>
        
	</body>

</html>