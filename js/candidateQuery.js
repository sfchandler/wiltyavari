$(document).ready(function () {
	var DOMAIN_URL = 'https://www.wiltyavari.outapay.com.au';

	$(document).on('click', '.intBtn', function(e) {
		var $row = $(this).closest("tr"); 
		var candidateid = $row.find('.candidateid').data('candidateid');
		$.ajax({
			   type: "POST",
			   url: "./interviewProcedure.php",
			   data: { candidateid : candidateid },
			   dataType: 'html',
			   success: function (data) {
				  $('.regList').hide(); 
				  $('.intwProc').html(data);
			   },
			   error: function(jqXHR, exception) {
					if (jqXHR.status === 0) {
						alert('Not connect.\n Verify Network.');
					} else if (jqXHR.status == 404) {
						alert('Requested page not found. [404]');
					} else if (jqXHR.status == 500) {
						alert('Internal Server Error [500].');
					} else if (exception === 'parsererror') {
						alert('Requested JSON parse failed.');
					} else if (exception === 'timeout') {
						alert('Time out error.');
					} else if (exception === 'abort') {
						alert('Ajax request aborted.');
					} else {
						alert('Uncaught Error.\n' + jqXHR.responseText);
					}
				}
		  }).done(function(){
				
		  });
	});
	//----------------------------------
	$(document).on('click', '.intvwBtn', function() {
		var $row = $(this).closest("tr"); 
		var candidateid = $row.find('.candidateid').data('candidateid');
		$("#intvwFrm").validate({
		  rules: {
			 intro:{
			 	required:true
			 },
			 formsCompleted: {
			 	required:true
			 },
			 wrkhistory: {
				required: true
			 },
			 forklift: {
			 	required: true
			 },
			 rf: {
			 	required: true
			 },
			 powertools: {
			 	required: true
			 },
			 containers: {
			 	required: true
			 },
			 yleaving: {
			 	required: true
			 },
			 ohsrules: {
			 	required: true
			 },
			 perftask: {
			 	required: true
			 },
			 strengths: {
			 	required: true
			 },
			 teamwork: {
			 	required: true
			 },
			 eyetest:{
			 	required: true
			 },
			 readtest: {
			 	required: true
			 },
			 rating: {
			 	required: true
			 }
		 },
		 messages: {
			intro:{
			 	required:"Please select introduction status"
			 },
			 formsCompleted: {
			 	required: "Please select whether the forms are complete or not"
			 },
			 wrkhistory: {
				required: "Please enter work history comments"
			 },
			 forklift: {
			 	required: "Select confident on forklift or not"
			 },
			 rf: {
			 	required: "Select rf scanning experience"
			 },
			 powertools: {
			 	required: "Select experience using power tools"
			 },
			 containers: {
			 	required: "Select whether ok to do container lifting"
			 },
			 yleaving: {
			 	required: "Enter reasons for leaving"
			 },
			 ohsrules: {
			 	required: "Enter response on OH&S rules"
			 },
			 perftask: {
			 	required: "Enter response on comfortable to perform supervisor tasks"
			 },
			 strengths: {
			 	required: "Enter response on his/her strengths"
			 },
			 teamwork: {
			 	required: "Enter response on working independently and within a team"
			 },
			 eyetest:{
			 	required: "Select eye site test status"
			 },
			 readtest: {
			 	required: "Select reading test status"
			 },
			 rating: {
			 	required: "Select overall rating for the candidate"
			 }
		 },
		 submitHandler: function (form) {
			var intro = $('input[name=intro]:checked', '#intvwFrm').val();
			var formsCompleted = $('input[name=formsCompleted]:checked', '#intvwFrm').val();
			var fit2work = $('input[name=fit2work]:checked', '#intvwFrm').val();
			var policeHistory = $('input[name=policeHistory]:checked', '#intvwFrm').val();
			var wrkhistory = $('textarea#wrkhistory').val();
			var forklift = $('input[name=forklift]:checked', '#intvwFrm').val();
			var rf = $('input[name=rf]:checked', '#intvwFrm').val();
			var powertools = $('input[name=powertools]:checked', '#intvwFrm').val();
			var containers = $('input[name=containers]:checked', '#intvwFrm').val();
			var yleaving = $('textarea#yleaving').val();
			var ohsrules = $('textarea#ohsrules').val();
			var perftask = $('textarea#perftask').val();
			var strengths = $('textarea#strengths').val();
			var teamwork = $('textarea#teamwork').val();
			var eyetest = $('input[name=eyetest]:checked', '#intvwFrm').val();
			var readtest = $('input[name=readtest]:checked', '#intvwFrm').val();
			var consultantId = $('#consultantId option:selected').val();
			
			$.ajax({
			   type: "POST",
			   url: "./updateIntvwNotes.php",
			   data: { candidateid : candidateid, intro : intro, formsCompleted : formsCompleted, fit2work : fit2work, policeHistory : policeHistory, wrkhistory : wrkhistory,forklift : forklift,rf : rf,powertools : powertools, containers :containers, yleaving : yleaving, ohsrules : ohsrules, perftask : perftask, strengths : strengths, teamwork: teamwork, eyetest : eyetest, readtest : readtest, rating : rating, consultantId : consultantId},
			   success: function (data) {
				 
			   },
			   error: function(jqXHR, exception) {
					if (jqXHR.status === 0) {
						alert('Not connect.\n Verify Network.');
					} else if (jqXHR.status == 404) {
						alert('Requested page not found. [404]');
					} else if (jqXHR.status == 500) {
						alert('Internal Server Error [500].');
					} else if (exception === 'parsererror') {
						alert('Requested JSON parse failed.');
					} else if (exception === 'timeout') {
						alert('Time out error.');
					} else if (exception === 'abort') {
						alert('Ajax request aborted.');
					} else {
						alert('Uncaught Error.\n' + jqXHR.responseText);
					}
				}
		  	}).done(function (response) {
				alert('RESPONSE'+resonse);
				/*if (response.success == 'success') {               
					alert('success');                       
				} else {
					alert('fail');
				}*/
			});
         },
		 // Do not change code below
					errorPlacement : function(error, element) {
						error.insertAfter(element.parent());
		 }
		});
	});

	var employmentContractDialog;
    var inductionDialog;
	var covidDialog;
	var jotFormDialog;
	var refFormDialog;
	var surveyDialog;
	var handbookDialog;
	$(document).on('click', '.empContractBtn', function(e) {
		var conEmail = $('#empConsultantEmail').val();
		var firstName = $('#empFirstName').val();
		var lastName = $('#empLastName').val();
		var candidateId = $('#empCanId').val();
		var email = $('#empEmail').val();
		var mailbody = 'Please sign the employment contract to fulfill your recruitment by clicking, <a href="'+DOMAIN_URL+'/empTerms.php?conEmail='+conEmail+'&firstName='+firstName+'&lastName='+lastName+'&candidateId='+candidateId+'&email='+email+'" target="_blank">Click here to submit Employment Contract</a>';
		$('#empContractBody').text(mailbody);
		$('#empEmailText').html(mailbody);
		employmentContractDialog.dialog("open");
		jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
	});

	$(document).on('click', '.inductionBtn', function(e) {
		console.log('induction ....')
		var conEmail = $('#inductionConEmail').val();
		var candidateId = $('#canId').val();
		var mailbody = 'Please sign the induction to fulfill your recruitment by clicking, <a href="'+DOMAIN_URL+'/induction.php?conEmail='+encodeURIComponent(window.btoa(conEmail))+'&candidateId='+encodeURIComponent(window.btoa(candidateId))+'" target="_blank">Click here to submit Induction</a>';
		$('#inductionBody').text(mailbody);
		$('#inductionEmailText').html(mailbody);
		inductionDialog.dialog("open");
		jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
	});

	$(document).on('click', '.covidBtn', function(e) {
		var conEmail = $('#covidConEmail').val();
		var candidateId = $('#canId').val();
		var mailbody = 'Please sign the  COVID19 Policy to fulfill your recruitment by clicking, <a href="'+DOMAIN_URL+'/covid19.php?conEmail='+conEmail+'&candidateId='+candidateId+'" target="_blank">Click here to submit COVID19 Policy</a>';
		$('#covidBody').text(mailbody);
		$('#covidEmailText').html(mailbody);
		covidDialog.dialog("open");
		jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
	});
	$(document).on('click', '.jotFormBtn', function(e) {
		var conEmail = $('#jotFormConEmail').val();
		var empId = $('#jotFormCanId').val();
		var empEmail = $('#jotFormCanEmail').val();
		var fullName = $('#jotFormFullName').val();
		var firstName = $('#jotFormFirstName').val();
		var lastName = $('#jotFormLastName').val();
		var dob = $('#jotFormDOB').val();
		var mobile = $('#jotFormMobile').val();
		var unit_no = $('#jotForm_unit_no').val();
		var street_no = $('#jotForm_street_number_1').val();
		var street_name = $('#jotForm_street_name').val();
		var suburb = $('#jotForm_suburb').val();
		var state = $('#jotForm_state').val();
		var postcode = $('#jotForm_postcode').val();
		var address = $('#jotForm_address').val();
		var mailbody = 'Hi  '+fullName+', \n<br><br>' +
			'Please use below link to complete your online registration.\n<br><br><b>Please use a COMPUTER/LAPTOP to fill this form.</b>\n<br><br><table width="100%" cellspacing="0" cellpadding="0">\n' +
			'  <tr>\n' +
			'      <td>\n' +
			'          <table cellspacing="0" cellpadding="0">\n' +
			'              <tr>\n' +
			'                  <td style="border-radius: 2px;" bgcolor="black"><a href="'+DOMAIN_URL+'/jotForm.php?conEmail='+conEmail+
			'&empId='+empId+
			'&empEmail='+empEmail+
			'&empFirstName='+firstName+
			'&empLastName='+lastName+
			'&empDOB='+dob+
			'&empMobile='+mobile+
			'&emp_unit_no='+unit_no+
			'&emp_street_no='+street_no+
			'&emp_street_name='+street_name+
			'&emp_suburb='+suburb+
			'&emp_state='+state+
			'&emp_postcode='+postcode+
			'&emp_address='+address+
			'" target="_blank" style="padding: 8px 12px; border: 1px solid black;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">Click here to submit Registraion form</a></td>\n' +
			'              </tr>\n' +
			'          </table>\n' +
			'      </td>\n' +
			'  </tr>\n' +
			'</table>' +
			'<br>' +
			'The below documents are required to complete the registration. Please ensure they are saved on your computer before attempting the registration.\n<br><br>' +
			'•\tRecent Photograph \n<br>' +
			'•\tPassport/Birth Certificate/ Citizenship Certificate\n<br>' +
			'•\tDriving Licence/Medicare card \n<br>' +
			'•\tProof of age/ Student id \n<br>' +
			'•\tPolice Check \n<br>' +
			'•\tWhite Card/ Forklift License (If Applicable)\n<br><br>Any issues, please feel free to contact us on (03) 7777 7777.';
		$('#jotFormBody').text(mailbody);
		$('#jotFormEmailText').html(mailbody);
		jotFormDialog.dialog("open");
		jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
	});
	$(document).on('click', '.formsBtn', function(e) {
		var conEmail = $('#formsConEmail').val();
		var empId = $('#formsCanId').val();
		var empEmail = $('#formsCanEmail').val();
		var mailbody = ' Please fill the forms required and submit online, in-order to fulfill your recruitment by clicking, <a href="'+DOMAIN_URL+'/forms.php?conEmail='+conEmail+'&empId='+empId+'&empEmail='+empEmail+'" target="_blank">Click here to submit forms</a>';
		$('#formsEmailBody').text(mailbody);
		$('#formsEmailText').html(mailbody);
		formsDialog.dialog("open");
		jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
	});

	$(document).on('click', '.refFormBtn', function(e) {
		var conEmail = $('#refFormConEmail').val();
		var empId = $('#refFormCanId').val();
		var refEmail = $('#refFormEmail').val();
		var refFormFullName = $('#refFormFullName').val();
		var mailbody = 'Hi  '+refFormFullName+', \n<br><br>'+
			'Please use below link to complete reference.\n<br><br><b>Please use a COMPUTER/LAPTOP to fill this form.</b>\n<br><br><table width="100%" cellspacing="0" cellpadding="0">\n' +
			'  <tr>\n' +
			'      <td>\n' +
			'          <table cellspacing="0" cellpadding="0">\n' +
			'              <tr>\n' +
			'                  <td style="border-radius: 2px;" bgcolor="black"><a href="'+DOMAIN_URL+'/referenceCheck.php?conEmail='+conEmail+'&id='+empId+'&refEmail='+refEmail+'&refName='+refFormFullName+'" target="_blank" style="padding: 8px 12px; border: 1px solid black;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">Click here to submit Reference Check form</a></td>\n' +
			'              </tr>\n' +
			'          </table>\n' +
			'      </td>\n' +
			'  </tr>\n' +
			'</table>' +
			'<br>' +
			'<br>Any issues, please feel free to contact us on (03) 7777 7777.';
		$('#refFormBody').text(mailbody);
		$('#refFormEmailText').html(mailbody);
		refFormDialog.dialog("open");
		jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
	});

	$(document).on('click', '.surveyBtn', function(e) {
		var cons_id = $('#surveyFormConId').val();
		var candidateId = $('#surveyFormCanId').val();
		var surveyCanEmail = $('#surveyCanEmail').val();
		var surveyFormFullName = $('#surveyFormFullName').val();
		var mailbody = 'Hi  '+surveyFormFullName+', \n<br><br>'+
			'Thank you for choosing to work with . Your feedback matters to us.<br>\n' +
			'We place great value on your time, so this survey should take less than 5 minutes to complete <br>\n' +
			'Start survey, click here\n<br><br><table width="100%" cellspacing="0" cellpadding="0">\n' +
			'  <tr>\n' +
			'      <td>\n' +
			'          <table cellspacing="0" cellpadding="0">\n' +
			'              <tr>\n' +
			'                  <td style="border-radius: 2px;" bgcolor="black"><a href="'+DOMAIN_URL+'/customerSurvey.php?cons_id='+cons_id+'&id='+candidateId+'&surveyCanEmail='+surveyCanEmail+'&surveyName='+surveyFormFullName+'" target="_blank" style="padding: 8px 12px; border: 1px solid black;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">Click here to submit Customer Satisfaction Survey</a></td>\n' +
			'              </tr>\n' +
			'          </table>\n' +
			'      </td>\n' +
			'  </tr>\n' +
			'</table>' +
			'<br>' +
			'<br>Any issues, please feel free to contact us on (03) 7777 7777.';
		$('#surveyFormBody').text(mailbody);
		$('#surveyFormEmailText').html(mailbody);
		surveyDialog.dialog("open");
		jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
	});
	surveyDialog = $("#surveyEmailPopup").dialog({
		autoOpen: false,
		height: 450,
		width: 550,
		modal: true,
		title:"Survey Form",
		open: function(event, ui) {
			$("#surveyEmailPopup").css({'overflow':'hidden'});
		},
		buttons: {
			Send: function(){
				var mailbody = $('#surveyFormBody').text();
				var conEmail = $('#surveyFormConEmail').val();
				var candidateId = $('#surveyFormCanId').val();
				sendSurveyMail(conEmail,candidateId,mailbody);
			},
			Cancel: function() {
				surveyDialog.dialog("close");
			}
		}
	});
	function sendSurveyMail(conEmail,candidateId,mailbody){
		var action = 'sendMail';
		$.ajax({
			url: "surveyFormEmail.php",
			type: "POST",
			dataType: "text",
			data: {
				action:action,
				mailbody:mailbody,
				conEmail:conEmail,
				candidateId:candidateId
			},
			success: function(data) {
				surveyDialog.dialog("close");
				location.reload();
			}
		});
	}

	function sendEmpContractMail(email,conEmail,firstName,lastName,candidateId,mailbody){
		var action = 'sendMail';
		$.ajax({
			url: "empContractEmail.php",
			type: "POST",
			dataType: "text",
			data: {
				action:action,
				email: email,
				mailbody:mailbody,
				conEmail:conEmail,
				firstName:firstName,
				lastName:lastName,
				candidateId:candidateId
			},
			success: function(data) {
				employmentContractDialog.dialog("close");
			}
		});
	}
	employmentContractDialog = $("#empConEmailPopup").dialog({
		autoOpen: false,
		height: 200,
		width: 400,
		modal: true,
		title:"Employment Contract Link",
		open: function(event, ui) {
			$("#empConEmailPopup").css({'overflow':'hidden'});
		},
		buttons: {
			Send: function(){
				var mailbody = $('#empContractBody').text();
				var conEmail = $('#empConsultantEmail').val();
				var firstName = $('#empFirstName').val();
				var lastName = $('#empLastName').val();
				var candidateId = $('#canId').val();
				var email = $('#empEmail').val();
				sendEmpContractMail(email,conEmail,firstName,lastName,candidateId,mailbody)
			},
			Cancel: function() {
				employmentContractDialog.dialog("close");
			}
		}
	});
	function sendInductionMail(conEmail,candidateId,mailbody){
		var action = 'sendMail';
		$.ajax({
			url: "inductionEmail.php",
			type: "POST",
			dataType: "text",
			data: {
				action:action,
				mailbody:mailbody,
				conEmail:conEmail,
				candidateId:candidateId
			},
			success: function(data) {
				inductionDialog.dialog("close");
			}
		});
	}
	inductionDialog = $("#inductionEmailPopup").dialog({
		autoOpen: false,
		height: 200,
		width: 400,
		modal: true,
		title:"Casual Induction Link",
		open: function(event, ui) {
			$("#inductionEmailPopup").css({'overflow':'hidden'});
		},
		buttons: {
			Send: function(){
				var mailbody = $('#inductionBody').text();
				var conEmail = $('#inductionConEmail').val();
				var candidateId = $('#canId').val();
				sendInductionMail(conEmail,candidateId,mailbody)
			},
			Cancel: function() {
				inductionDialog.dialog("close");
			}
		}
	});
	function sendCovidMail(conEmail,candidateId,mailbody){
		var action = 'sendMail';
		$.ajax({
			url: "covidEmail.php",
			type: "POST",
			dataType: "text",
			data: {
				action:action,
				mailbody:mailbody,
				conEmail:conEmail,
				candidateId:candidateId
			},
			success: function(data) {
				covidDialog.dialog("close");
			}
		});
	}
	covidDialog = $("#covidEmailPopup").dialog({
		autoOpen: false,
		height: 200,
		width: 400,
		modal: true,
		title:"Casual COVID Policy Link",
		open: function(event, ui) {
			$("#covidEmailPopup").css({'overflow':'hidden'});
		},
		buttons: {
			Send: function(){
				var mailbody = $('#covidBody').text();
				var conEmail = $('#covidConEmail').val();
				var candidateId = $('#canId').val();
				sendCovidMail(conEmail,candidateId,mailbody)
			},
			Cancel: function() {
				covidDialog.dialog("close");
			}
		}
	});

	jotFormDialog = $("#jotFormEmailPopup").dialog({
		autoOpen: false,
		height: 700,
		width: 700,
		modal: true,
		title:"Reg Form Link",
		open: function(event, ui) {
			$("#jotFormEmailPopup").css({'overflow':'hidden'});
		},
		buttons: {
			Send: function(){
				var mailbody = $('#jotFormBody').text();
				var reg_instructions = $('textarea#reg_instructions').val();
				var conEmail = $('#jotFormConEmail').val();
				var candidateId = $('#jotFormCanId').val();
				sendJotFormMail(conEmail,candidateId,mailbody,reg_instructions)
			},
			Cancel: function() {
				jotFormDialog.dialog("close");
			}
		}
	});
	function sendJotFormMail(conEmail,candidateId,mailbody,reg_instructions){
		var action = 'sendMail';
		$.ajax({
			url: "jotFormEmail.php",
			type: "POST",
			dataType: "text",
			data: {
				action:action,
				mailbody:mailbody,
				reg_instructions:reg_instructions,
				conEmail:conEmail,
				candidateId:candidateId
			},
			success: function(data) {
				jotFormDialog.dialog("close");
			}
		});
	}

	$('#refFormEmailFrm').validate({
		rules: {
			refFormFullName: {
				required: true
			},
			refFormEmail: {
				required: true
			}
		}
	});
	refFormDialog = $("#refFormEmailPopup").dialog({
		autoOpen: false,
		height: 400,
		width: 400,
		modal: true,
		title:"Reference Check Form Link",
		open: function(event, ui) {
			$("#refFormEmailPopup").css({'overflow':'hidden'});
		},
		buttons: {
			Send: function(){
			/*	var mailbody = $('#refFormBody').text();
				var conEmail = $('#refFormConEmail').val();
				var candidateId = $('#canId').val();
				var refFormFullName = $('#refFormFullName').val();
				var refFormEmail = $('#refFormEmail').val();*/
				var candidateId = $('#canId').val();
				var conEmail = $('#refFormConEmail').val();
				var empId = $('#refFormCanId').val();
				var refEmail = $('#refFormEmail').val();
				var refFormFullName = $('#refFormFullName').val();
				var mailbody = 'Hi  '+refFormFullName+', \n<br><br>'+
					'Please use below link to complete reference check.\n<br><br><b>Please use a COMPUTER/LAPTOP to fill this form.</b>\n<br><br><table width="100%" cellspacing="0" cellpadding="0">\n' +
					'  <tr>\n' +
					'      <td>\n' +
					'          <table cellspacing="0" cellpadding="0">\n' +
					'              <tr>\n' +
					'                  <td style="border-radius: 2px;" bgcolor="black"><a href="'+DOMAIN_URL+'/referenceCheck.php?conEmail='+conEmail+'&id='+empId+'&refEmail='+refEmail+'&refName='+refFormFullName+'" target="_blank" style="padding: 8px 12px; border: 1px solid black;border-radius: 2px;font-family: Helvetica, Arial, sans-serif;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block;">Click here to submit Reference Check form</a></td>\n' +
					'              </tr>\n' +
					'          </table>\n' +
					'      </td>\n' +
					'  </tr>\n' +
					'</table>' +
					'<br>' +
					'<br>Any issues, please feel free to contact us on (03) 7777 7777.';
				$('#refFormBody').text(mailbody);
				$('#refFormEmailText').html(mailbody);

				if ( $( '#refFormEmailFrm' ).valid()  ) {
					$( this ).dialog( 'close' );
					sendReferenceFormMail(conEmail, candidateId,mailbody, refEmail);
				}else{

				}
			},
			Cancel: function() {
				refFormDialog.dialog("close");
			}
		}
	});
	function sendReferenceFormMail(conEmail,candidateId,mailbody,refFormEmail){
		var action = 'sendMail';
		$.ajax({
			url: "refFormEmail.php",
			type: "POST",
			dataType: "text",
			data: {
				action:action,
				mailbody:mailbody,
				refFormEmail:refFormEmail,
				conEmail:conEmail,
				candidateId:candidateId
			},
			success: function(data) {
				refFormDialog.dialog("close");
			}
		});
	}

	formsDialog = $("#formsEmailPopup").dialog({
		autoOpen: false,
		height: 200,
		width: 400,
		modal: true,
		title:"Forms Link",
		open: function(event, ui) {
			$("#formsEmailPopup").css({'overflow':'hidden'});
		},
		buttons: {
			Send: function(){
				var mailbody = $('#formsEmailBody').text();
				var conEmail = $('#formsConEmail').val();
				var candidateId = $('#canId').val();
				sendFormsMail(conEmail,candidateId,mailbody);
			},
			Cancel: function() {
				formsDialog.dialog("close");
			}
		}
	});
	function sendFormsMail(conEmail,candidateId,mailbody){
		var action = 'sendMail';
		$.ajax({
			url: "formsEmail.php",
			type: "POST",
			dataType: "text",
			data: {
				action:action,
				mailbody:mailbody,
				conEmail:conEmail,
				candidateId:candidateId
			},
			success: function(data) {
				formsDialog.dialog("close");
			}
		});
	}

	$(document).on('click', '.empHandbookBtn', function(e) {
		var conEmail = $('#handbookConEmail').val();
		var candidateId = $('#handbookCanId').val();
		var mailbody = 'Please sign and submit the Employee Handbook to fulfill your recruitment by clicking, <a href="'+DOMAIN_URL+'/empHandbook.php?conEmail='+conEmail+'&candidateId='+candidateId+'" target="_blank">Click here to Sign and Submit Handbook</a>';
		$('#handbookBody').text(mailbody);
		$('#handbookEmailText').html(mailbody);
		handbookDialog.dialog("open");
		jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
	});
	function sendEmployeeHandbookMail(conEmail,candidateId,mailbody){
		var action = 'handbookSendMail';
		$.ajax({
			url: "handbookEmail.php",
			type: "POST",
			dataType: "text",
			data: {
				action:action,
				mailbody:mailbody,
				conEmail:conEmail,
				candidateId:candidateId
			},
			success: function(data) {
				handbookDialog.dialog("close");
			}
		});
	}
	handbookDialog = $('#handbookEmailPopup').dialog({
		autoOpen: false,
		height: 200,
		width: 400,
		modal: true,
		title:"Handbook Link",
		open: function(event, ui) {
			$("#handbookEmailPopup").css({'overflow':'hidden'});
		},
		buttons: {
			Send: function(){
				var mailbody = $('#handbookBody').text();
				var conEmail = $('#handbookConEmail').val();
				var candidateId = $('#canId').val();
				sendEmployeeHandbookMail(conEmail,candidateId,mailbody)
			},
			Cancel: function() {
				handbookDialog.dialog("close");
			}
		}
	});


});