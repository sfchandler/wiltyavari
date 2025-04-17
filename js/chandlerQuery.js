$(function() {
	/* AJAX loading animation */
	/*$body = $("body");

	$(document).on({
		ajaxStart: function() { $body.addClass("loading");    },
		ajaxStop: function() { $body.removeClass("loading"); }    
	});*/
	/* -  end  -*/
	/* disable back button */
	/*history.pushState(null, null, document.URL);
	window.addEventListener('popstate', function () {
		history.pushState(null, null, document.URL);
	});*/

	$(document).tooltip({
		position: {
			my: "center bottom-20",
			at: "center top",
			using: function( position, feedback ) {
				$( this ).css( position );
				$( "<div>" )
					.addClass( "arrow" )
					.addClass( feedback.vertical )
					.addClass( feedback.horizontal )
					.appendTo(this);
			}
		}
	});

	var addCommentDialog;
	var addTalentCommentDialog;
	var jotEmailDialog;
	var taxEmailDialog;

	/* prevent users pressing browser back */
	window.history.forward();
    function disableBack() { 
		window.history.forward(); 
	}	
    window.onload = disableBack();
    window.onpageshow = function(evt) { 
		if (evt.persisted) disableBack(); 
	}
	
	$(document).bind('keypress', function(e) {
		if(e.target.tagName != 'TEXTAREA') {
		  if(e.keyCode==13){
			  e.preventDefault();
			  $('#searchBtn').trigger('click');
		  }
		}
	});
		
	/* Default Mail List Request */
	//getEmailList();
	/* End  Default Mail List Request */
	/*$(document).on('click', '.inbox-load', function(e) {
	    //getEmailList();
		$('#tblMailView').show();
		$('.formDisplay').html('');
	});*/
	
	$(document).on('click', '.checkBtn', function(e) {
		var $row = $(this).closest("tr");
		var messageid = $row.find('.messageid').data('messageid');
		var subject = $row.find('.messageid').data('subj');
		console.log('subject'+subject);
		window.open('./candidateReview.php?messageid='+messageid+'&subject='+subject,'_blank');
	});
	$(document).on('click', '.jbCheckBtn', function(e) {
		var $row = $(this).closest("tr");
		var messageid = $row.find('.jbmessageid').data('messageid');
		var subject = $row.find('.messageid').data('subj');
		window.open('./jbCandidateReview.php?messageid='+messageid+'&subject='+subject,'_blank');
	});
	$(document).on('click', '.jotBtn', function(e) {
		var $row = $(this).closest("tr");
		var autoId = $(this).closest("tr").attr('id');
		var messageid = $row.find('.messageid').data('messageid');
		var conEmail = $('#consultantEmail').val();
		$.ajax({
			url: "jotEmail.php",
			type: "POST",
			dataType: "text",
			data: {messageid: messageid},
			success: function(data) {
				console.log('JOT');
				if(data != 'N/A') {
					$('#jotEmailAddress').val(data);
					var mailbody = 'Please fill the registration form required and submit online, in-order to fulfill your recruitment by clicking, <a href="https://www.outapay.com.au/jotForm.php?conEmail='+conEmail+'" target="_blank">Click here to submit jot form</a>';
					$('#jotEmailBody').text(mailbody);
					$('#jotEmailText').html(mailbody);
					jotEmailDialog.data('jotEmailAddress', data);
					jotEmailDialog.dialog("open");
					jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
				}else{
					//alert('Please enter an email address');
					$('#jotEmailAddress').val('');
					var mailbody = 'Please fill the registration form required and submit online, in-order to fulfill your recruitment by clicking, <a href="https://www.outapay.com.au/jotForm.php?conEmail='+conEmail+'" target="_blank">Click here to submit jot form</a>';
					$('#jotEmailBody').text(mailbody);
					$('#jotEmailText').html(mailbody);
					jotEmailDialog.data('jotEmailAddress', '');
					jotEmailDialog.dialog("open");
					jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
				}
			}
		});
	});
	$(document).on('click', '.formsBtn', function(e) {
		var $row = $(this).closest("tr");
		var autoId = $(this).closest("tr").attr('id');
		var messageid = $row.find('.messageid').data('messageid');
		var conEmail = $('#taxConsultantEmail').val();
		$.ajax({
			url: "formsEmail.php",
			type: "POST",
			dataType: "text",
			data: {messageid: messageid},
			success: function(data) {
				if(data != 'N/A') {
					$('#taxEmailAddress').val(data);
					var mailbody = 'Please fill the forms required by outapay and submit online, in-order to fulfill your recruitment by clicking, <a href="https://www.outapay.com.au/forms.php?conEmail='+conEmail+'" target="_blank">Click here to submit forms</a>';
					$('#taxEmailBody').text(mailbody);
					$('#taxEmailText').html(mailbody);
					taxEmailDialog.data('taxEmailAddress', data);
					taxEmailDialog.dialog("open");
					jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
				}else{
					$('#taxEmailAddress').val('');
					var mailbody = 'Please fill the forms required by outapay and submit online, in-order to fulfill your recruitment by clicking, <a href="https://www.outapay.com.au/forms.php?conEmail='+conEmail+'" target="_blank">Click here to submit forms</a>';
					$('#taxEmailBody').text(mailbody);
					$('#taxEmailText').html(mailbody);
					taxEmailDialog.data('taxEmailAddress', '');
					taxEmailDialog.dialog("open");
					jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
				}
			}
		});
	});

    /* ********************* mail Comment ******************* */
	function getMailComment(autoId){
		$.ajax({
            url: "getMailComment.php",
            type: "POST",
            dataType: "html",
            data: {
                autoId: autoId
            },
            success: function(data) {
                $("textarea#mComment").val(data);
            }
		});
	}
	function saveMailComment(mAutoId,mComment){
        $.ajax({
            url: "saveMailComment.php",
            type: "POST",
            dataType: "text",
            data: {
                mAutoId: mAutoId,
                mComment:mComment,
            },
            success: function(data) {
                addCommentDialog.dialog("close");
            }
        });
	}
	addCommentDialog = $("#commentPopup").dialog({
		autoOpen: false,
		height: 500,
		width: 500,
		modal: true,
		title:"Email Comments",
		open: function(event, ui) {
			$("#commentPopup").css({'overflow':'hidden'});
			$('#mAutoId').val(addCommentDialog.data('mAutoId'));
			$('textarea#mComment').val(addCommentDialog.data('mComment'));
		},
		buttons: {
			Save: function(){
				var mAutoId = $('#mAutoId').val();
				var mComment = $('textarea#mComment').val();
				saveMailComment(mAutoId,mComment);
			},
			Cancel: function() {
				addCommentDialog.dialog("close");
			}
		}
	});
	$(document).on('click','.commentLink',function () {
		let autoId = $(this).closest("tr").attr('id');
		let refcode = $(this).closest('td').find('.messageid').attr('data-refcode');
		$('#mAutoId').val('');
		$('#mAutoId').val(autoId);
		addCommentDialog.data('mAutoId',autoId);
		addCommentDialog.data('mComment',getMailComment(autoId));
		addCommentDialog.dialog("open");
		jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
	});
	/* Talent email comment */

	function getTalentMailComment(autoId){
		$.ajax({
			url: "getTalentMailComment.php",
			type: "POST",
			dataType: "html",
			data: {
				autoId: autoId
			},
			success: function(data) {
				$("textarea#mComment").val(data);
			}
		});
	}
	function saveTalentMailComment(mAutoId,mComment){
		$.ajax({
			url: "saveTalentMailComment.php",
			type: "POST",
			dataType: "text",
			data: {
				mAutoId: mAutoId,
				mComment:mComment,
			},
			success: function(data) {
				addTalentCommentDialog.dialog("close");
				location.reload();
			}
		});
	}
	addTalentCommentDialog = $("#talentCommentPopup").dialog({
		autoOpen: false,
		height: 500,
		width: 500,
		modal: true,
		title:"Email Comments",
		open: function(event, ui) {
			$("#talentCommentPopup").css({'overflow':'hidden'});
			$('#mAutoId').val(addTalentCommentDialog.data('mAutoId'));
			$('textarea#mComment').val(addTalentCommentDialog.data('mComment'));
		},
		buttons: {
			Save: function(){
				var mAutoId = $('#mAutoId').val();
				var mComment = $('textarea#mComment').val();
				saveTalentMailComment(mAutoId,mComment);
			},
			Cancel: function() {
				addTalentCommentDialog.dialog("close");
			}
		}
	});
	$(document).on('click','.talentCommentLink',function () {
		var autoId = $(this).closest("tr").attr('id');
		$('#mAutoId').val('');
		$('#mAutoId').val(autoId);
		addTalentCommentDialog.data('mAutoId',autoId);
		addTalentCommentDialog.data('mComment',getTalentMailComment(autoId));
		addTalentCommentDialog.dialog("open");
		jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');
	});
	$(document).on('click','.talentBtn', function(){
		var autoId = $(this).closest("tr").attr('id');
		var consultant = $(this).closest("td").find("input[name='consultant']").val();
		saveTalentNote(autoId,consultant);
	});
	function saveTalentNote(autoId,consultant) {
		var action = 'UPDATE';
		console.log('data........'+autoId+consultant);
		$.ajax({
			url: "updateTalentConsultant.php",
			type: "POST",
			dataType: "text",
			data: {
				autoId: autoId,
				consultant:consultant,
				action:action
			},
			success: function(data) {
			}
		});
	}


	/*$('.talentConsultant').keydown(function(){
		var autoId = $(this).closest("tr").attr('id');
		var consultant = $(this).val();
		var action = 'UPDATE';
		console.log('data........'+autoId+consultant);
		$.ajax({
			url: "updateTalentConsultant.php",
			type: "POST",
			dataType: "text",
			data: {
				autoId: autoId,
				consultant:consultant,
				action:action
			},
			success: function(data) {

			}
		});

	});*/

	function sendJotMail(email,mailbody){
		var action = 'sendMail';
		$.ajax({
			url: "jotEmail.php",
			type: "POST",
			dataType: "text",
			data: {
				action:action,
				email: email,
				mailbody:mailbody,
			},
			success: function(data) {
				jotEmailDialog.dialog("close");
			}
		});
	}
	function sendTaxMail(email,mailbody){
		var action = 'sendMail';
		$.ajax({
			url: "formsEmail.php",
			type: "POST",
			dataType: "text",
			data: {
				action:action,
				email: email,
				mailbody:mailbody,
			},
			success: function(data) {
				if(data == 'N/P'){
					alert('No Profile Found');
				}else {
					taxEmailDialog.dialog("close");
				}
			}
		});
	}

	jotEmailDialog = $("#jotEmailPopup").dialog({
		autoOpen: false,
		height: 300,
		width: 300,
		modal: true,
		title:"Jot Email send",
		open: function(event, ui) {
			$("#jotEmailPopup").css({'overflow':'hidden'});
		},
		buttons: {
			Send: function(){
				var email = $('#jotEmailAddress').val();
				var mailbody = $('#jotEmailBody').text();
				sendJotMail(email,mailbody)
			},
			Cancel: function() {
				jotEmailDialog.dialog("close");
			}
		}
	});
	taxEmailDialog = $("#taxEmailPopup").dialog({
		autoOpen: false,
		height: 300,
		width: 300,
		modal: true,
		title:"Forms Email send",
		open: function(event, ui) {
			$("#taxEmailPopup").css({'overflow':'hidden'});
		},
		buttons: {
			Send: function(){
				var email = $('#taxEmailAddress').val();
				var mailbody = $('#taxEmailBody').text();
				sendTaxMail(email,mailbody)
			},
			Cancel: function() {
				taxEmailDialog.dialog("close");
			}
		}
	});


    /************************ end *****************************/


    /*$(document).on('click', '.diaryNotesBtn', function(e){
        var $row = $(this).closest("tr");
        var messageid = $row.find('.messageid').data('messageid');
        window.open('./diaryNotes.php?messageid='+messageid+'','_blank');
    });*/
	/* ***************** EMail Data Loading ********************* */
	/*loadResults(0);
    $('.inboxList').on('scroll',function() {
      if($("#inbLoading").css('display') == 'none') {
          var limitStart = $('#inbody tr').length;
          var searchTxt = $('#searchTxt').val();
          var subjectSearchTxt = $('#subjectSearchTxt').val();
          var fromSearchTxt = $('#fromSearchTxt').val();
          if(searchTxt == '' && subjectSearchTxt =='' && fromSearchTxt == '') {
              loadResults(limitStart);
          }else{
              loadSearchResults(limitStart);
          }
        /!*
        $('.inboxList').scroll(function() {
      		if($("#inbLoading").css('display') == 'none') {
				if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
				   var limitStart = $("#inbody tr").length;
				   loadResults(limitStart);
				}
      		}
		});
        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) { // checking whether bottom of div reached
            var limitStart = $('#inbody tr').length;
            var searchTxt = $('#searchTxt').val();
            var subjectSearchTxt = $('#subjectSearchTxt').val();
            var fromSearchTxt = $('#fromSearchTxt').val();
            if(searchTxt == '' && subjectSearchTxt =='' && fromSearchTxt == '') {
                loadResults(limitStart);
            }else{
                loadSearchResults(limitStart);
			}
        }*!/
      }
	});
	function loadResults(limitStart) {
		$("#inbLoading").show();
		$.ajax({
			url: "loadMails.php",
			type: "POST",
			dataType: "html",
			data: {
				limitStart: limitStart
			},
			success: function(data) {
				 $("#inbody").append(data);
				 $("#inbLoading").hide();     
			}
		});
	};*/
	/* ******************* end Email Data Loading ***************** */
	/*------------------ job board mails ------------------- */
	loadJobBoardResults(0);
	$("#jbLoading").hide();
	$(document).on('click', '.jb-load', function(e) {
		//getEmailList();
		$('#tblJBMailView').show();
		$('.formJBDisplay').html('');
	});

	$('.jbInboxList').scroll(function() {
		if($("#jbLoading").css('display') == 'none') {
			var limitStart = $('#jb-body tr').length;
			loadJobBoardResults(limitStart);
		}
	});
	function loadJobBoardResults(limitStart) {
		$("#jbLoading").show();
		$.ajax({
			url: "loadJobBoard.php",
			type: "POST",
			dataType: "html",
			data: {
				limitStart: limitStart
			},
			success: function(data) {
				$("#jb-body").append(data);
				$("#jbLoading").hide();
			}
		});
	};
	$(document).on('click', '.jbmessageid', function(e){
		getJBMailView($(this));
		getJBAttachments($(this));

	});
	function getJBMailView(instance){
		var $row = instance.closest("tr");
		var messageid = $row.find('.jbmessageid').data('messageid');
		var mailString;
		$.ajax({
			type: "POST",
			url: "./JbMailView.php",
			data: { messageid : messageid },
			dataType: 'json',
			success: function (data) {
				$.each(data, function(index, element) {
					$('.mailSubject').html();
					$('.mailSubject').html(element.subject);
					$('.mailFrom').html();
					$('.mailFrom').html(element.mailfrom);
					$('.sentDate').html();
					$('.sentDate').html(element.date);
					$('.mailTo').html();
					$('.mailTo').html(element.mailto);
					$('.mailAttachments').html();
					$('.mailAttachments').html();
					$('.mailBody').html('');
					mailString = element.msgbody;
					mailString.replace(/(\r\n|\n|\r)/gm,"");
				});
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
			$(".mailBody").html(mailString);
			var decoded = $("<textarea/>").html(mailString).text();
			$(".mailBody").contents().find('html').html(decoded.replace(/\\n/g, "").replace(/\\r/g, ""));
			$(".mailBody").contents().find('a').click(function(event) {
				event.preventDefault();
			});
		});
	}
	function getJBAttachments(instance){
		var $row = instance.closest("tr");
		var messageid = $row.find('.jbmessageid').data('messageid');
		var fileico;
		$.ajax({
			type: "POST",
			url: "./getJBAttachments.php",
			data: { messageid : messageid },
			dataType: 'json',
			success: function (data) {
				$('.mailAttachments').html('');
				$.each(data, function(index, element) {
					if(element.filetype == 'pdf'){
						fileico = 'pdf-adobe-portable-document-format.ico';
					}else if(element.filetype == 'docx'){
						fileico = 'docx-microsoft-word-open-xml-document.png';
					}else if(element.filetype == 'doc'){
						fileico = 'doc-microsoft-word-97-to-2003-document.png';
					}else if(element.filetype == 'txt'){
						fileico = 'text_document.ico';
					}
					$('.mailAttachments').append('<img src="../filetypes/'+fileico+'" /><a href="'+element.filepath+'" target="_blank">'+element.filename+'</a><br>');
				});
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
		});
	}

	/*----------------------------------------------------------*/



	/* ******************* mail search **************************** */
    function loadJBSearchResults(limitStart){
        $("#inbLoading").show();
        var searchTxt = $('#searchTxt').val();
        var subjectSearchTxt = $('#subjectSearchTxt').val();
        var fromSearchTxt = $('#fromSearchTxt').val();
		var accountName = 'jobboard';
        $.ajax({
            url: "searchMail.php",
            type: "POST",
            dataType: "html",
            data: {
                searchTxt : searchTxt, subjectSearchTxt : subjectSearchTxt, fromSearchTxt : fromSearchTxt,limitStart: limitStart,accountName:accountName
            },
            success: function(data) {
                $("#jb-body").append(data);
                $("#jbLoading").hide();
            }
        }).done(function(){
            $('.numRows').html($('#rowCount').val());
			$('.messageid').on('click', function(e){
				$('#tblMailView').show();
				$('.formDisplay').html('');
				getJBMailView($(this));
				getJBAttachments($(this));
				$("#jbLoading").hide();
			});
        });
	}
	$(document).on('click', '.searchBtn', function(e) {
        var limitStart = 0;
        $("#jb-body").html('');
        loadJBSearchResults(limitStart);
    });
    /*$(document).on('click', '.searchBtn', function(e) {
        var rows='';
        var numRows;
        var searchTxt = $('#searchTxt').val();
        var subjectSearchTxt = $('#subjectSearchTxt').val();
        var fromSearchTxt = $('#fromSearchTxt').val();
		var accountName = 'jobboard';
        $.ajax({
            type: "POST",
            url: "./searchMail.php",
            data: { searchTxt : searchTxt, subjectSearchTxt : subjectSearchTxt, fromSearchTxt : fromSearchTxt,accountName:accountName},
            dataType: 'html',
            success: function (data) {
                if(data.length>0){
					/!*$.each(data, function(index, element) {
					 numRows = element.numRows;
					 rows += '<tr><td class="messageid" data-messageid="'+element.messageid+'"><div>'+element.mailfrom+'</div><div>'+element.subject+'</div><div>'+element.mailto+'</div></td><td align="right">'+element.date+'</td><td><input type="hidden" name="messageid" id="messageid" value="'+element.messageid+'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td><td><button id="deleteBtn" class="deleteBtn btn btn-xs btn-danger" type="button"><i class="fa fa-trash"></i> Delete</button></td></tr>';
					 });*!/
                    $('.mailListBody').html('');
                    $('.mailListBody').html(data);
                    $('.numRows').html('');
                    $('.numRows').html($('#rowCount').val());
                    //console.log('DATA=>'+data);
                }else{
                    $('.mailListBody').html('');
                    $('.mailListBody').html('No Matching Results');
                    $('.numRows').html('');
                    $('.numRows').html('0');
                }
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
            $('.messageid').on('click', function(e){
                $('#tblMailView').show();
                $('.formDisplay').html('');
                getJBMailView($(this));
                getJBAttachments($(this));
            });

        });
    });*/
	/* **************** status color category start ****************** */
	/*var colorList = [];
	$.ajax({
		 type:"POST",
		 url: "./catColors.php",
		 dataType: 'json',
		 success: function (data) {
			$.each(data, function(index, element) {
				//console.log(element.catid+element.catcolor+element.category);
				colorList = data;
			});
		 }
	});
	
	var $menu = [];
	
	function buildMenu(){
		var html = [
			"<div class='custom-menu'>",
			"<b>Categorize</b><br />"
		];
		for(var i = 0; i < colorList.length; i++){
			html.push("<div style=\"width:200px;padding:2px 2px 2px 2px;\"><div id=" + colorList[i].catid +" style=\"float:left;display:block;width:20px;height:20px;border:1px solid #000; background-color:" + colorList[i].catcolor + ";\" class=\"color\"></div><div style=\"padding-left:5px;float:left;\">" + colorList[i].category + "</div><div style=\"clear:both;\"></div></div>");
		}
		$menu = $(html.join(''))
		.appendTo("body")
		.on("click", ".color", handleClick);   
	}
	
	function handleClick(e){
		var autoid = $menu.data('target').autoid;
		var catid = this.id;
		$.ajax({
			 type:"POST",
			 url: "./catColorsUpdate.php",
			 data: { autoid : autoid, catid : catid },
			 dataType: 'html',
			 success: function (data) {
			 }
		}).done(function(data){
            $("div[id="+autoid+"]").html(data);
		});
		$menu.hide();
	}
	
	$('#rowInbox').on('contextmenu', '.category', function (event) {
		event.preventDefault();
		if ($menu.length == 0){
			buildMenu();
		}
		$menu
			.show()
			.data("target",{autoid : this.id })
			.css({
				top: event.pageY + "px",
				left: event.pageX + "px"
			});
	});
	$(document).on("click", function (event) {
		if ($(event.target).closest(".custom-menu").length == 0)
			$(".custom-menu").hide();
	});*/
	
	/* **************** status color category end ****************** */

	/* **************** status jobboard color category start ****************** */
	var jbcolorList = [];
	$.ajax({
		type:"POST",
		url: "./catColors.php",
		dataType: 'json',
		success: function (data) {
			$.each(data, function(index, element) {
				//console.log(element.catid+element.catcolor+element.category);
				jbcolorList = data;
			});
		}
	});

	var $jbmenu = [];

	function jbBuildMenu(){
		var html = [
			"<div class='custom-menu'>",
			"<b>Categorize</b><br />"
		];
		for(var i = 0; i < jbcolorList.length; i++){
			html.push("<div style=\"width:200px;padding:2px 2px 2px 2px;\"><div id=" + jbcolorList[i].catid +" style=\"float:left;display:block;width:20px;height:20px;border:1px solid #000; background-color:" + jbcolorList[i].catcolor + ";\" class=\"color\"></div><div style=\"padding-left:5px;float:left;\">" + jbcolorList[i].category + "</div><div style=\"clear:both;\"></div></div>");
		}
		$jbmenu = $(html.join(''))
			.appendTo("body")
			.on("click", ".color", jbHandleClick);
	}

	function jbHandleClick(e){
		var autoid = $jbmenu.data('target').autoid;
		var catid = this.id;
		$.ajax({
			type:"POST",
			url: "./jbCatColorsUpdate.php",
			data: { autoid : autoid, catid : catid },
			dataType: 'html',
			success: function (data) {
			}
		}).done(function(data){
			$("div[id="+autoid+"]").html(data);
		});
		$jbmenu.hide();
	}

	$('#jbRowInbox').on('contextmenu', '.category', function (event) {
		event.preventDefault();
		if ($jbmenu.length == 0){
			jbBuildMenu();
		}
		$jbmenu
			.show()
			.data("target",{autoid : this.id })
			.css({
				top: event.pageY + "px",
				left: event.pageX + "px"
			});
	});
	$(document).on("click", function (event) {
		if ($(event.target).closest(".custom-menu").length == 0)
			$(".custom-menu").hide();
	});

	/* **************** status jobboard color category end ****************** */

	/*------------------ Talent Request mails ------------------- */
	loadTalentRequestResults(0);
	$("#tlLoading").hide();
	$(document).on('click', '.tl-load', function(e) {
		//getEmailList();
		$('#tblTLMailView').show();
		$('.formTLDisplay').html('');
	});

	$('.tlInboxList').scroll(function() {
		if($("#jbLoading").css('display') == 'none') {
			var limitStart = $('#jb-body tr').length;
			loadTalentRequestResults(limitStart);
		}
	});
	function loadTalentRequestResults(limitStart) {
		$("#jbLoading").show();
		$.ajax({
			url: "loadTalentRequest.php",
			type: "POST",
			dataType: "html",
			data: {
				limitStart: limitStart
			},
			success: function(data) {
				$("#tl-body").append(data);
				$("#tlLoading").hide();
			}
		});
	};
	$(document).on('click', '.tlmessageid', function(e){
		getTLMailView($(this));
		getTLAttachments($(this));

	});
	function getTLMailView(instance){
		var $row = instance.closest("tr");
		var messageid = $row.find('.tlmessageid').data('messageid');
		var mailString;
		$.ajax({
			type: "POST",
			url: "./tlMailView.php",
			data: { messageid : messageid },
			dataType: 'json',
			success: function (data) {
				$.each(data, function(index, element) {
					$('.mailSubject').html();
					$('.mailSubject').html(element.subject);
					$('.mailFrom').html();
					$('.mailFrom').html(element.mailfrom);
					$('.sentDate').html();
					$('.sentDate').html(element.date);
					$('.mailTo').html();
					$('.mailTo').html(element.mailto);
					$('.mailAttachments').html();
					$('.mailAttachments').html();
					$('.mailBody').html('');
					mailString = element.msgbody;
					mailString.replace(/(\r\n|\n|\r)/gm,"");
				});
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
			$(".mailBody").html(mailString);
			var decoded = $("<textarea/>").html(mailString).text();
			$(".mailBody").contents().find('html').html(decoded.replace(/\\n/g, "").replace(/\\r/g, ""));
			$(".mailBody").contents().find('a').click(function(event) {
				event.preventDefault();
			});
		});
	}
	function getTLAttachments(instance){
		var $row = instance.closest("tr");
		var messageid = $row.find('.tlmessageid').data('messageid');
		var fileico;
		$.ajax({
			type: "POST",
			url: "./getTLAttachments.php",
			data: { messageid : messageid },
			dataType: 'json',
			success: function (data) {
				$('.mailAttachments').html('');
				$.each(data, function(index, element) {
					if(element.filetype == 'pdf'){
						fileico = 'pdf-adobe-portable-document-format.ico';
					}else if(element.filetype == 'docx'){
						fileico = 'docx-microsoft-word-open-xml-document.png';
					}else if(element.filetype == 'doc'){
						fileico = 'doc-microsoft-word-97-to-2003-document.png';
					}else if(element.filetype == 'txt'){
						fileico = 'text_document.ico';
					}
					$('.mailAttachments').append('<img src="../filetypes/'+fileico+'" /><a href="'+element.filepath+'" target="_blank">'+element.filename+'</a><br>');
				});
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
		});
	}

	/*----------------------------------------------------------*/
	/* **************** talent request color category start ****************** */
	/*var jbcolorList = [];
	$.ajax({
		type:"POST",
		url: "./catColors.php",
		dataType: 'json',
		success: function (data) {
			$.each(data, function(index, element) {
				//console.log(element.catid+element.catcolor+element.category);
				jbcolorList = data;
			});
		}
	});

	var $jbmenu = [];

	function jbBuildMenu(){
		var html = [
			"<div class='custom-menu'>",
			"<b>Categorize</b><br />"
		];
		for(var i = 0; i < jbcolorList.length; i++){
			html.push("<div style=\"width:200px;padding:2px 2px 2px 2px;\"><div id=" + jbcolorList[i].catid +" style=\"float:left;display:block;width:20px;height:20px;border:1px solid #000; background-color:" + jbcolorList[i].catcolor + ";\" class=\"color\"></div><div style=\"padding-left:5px;float:left;\">" + jbcolorList[i].category + "</div><div style=\"clear:both;\"></div></div>");
		}
		$jbmenu = $(html.join(''))
			.appendTo("body")
			.on("click", ".color", jbHandleClick);
	}

	function jbHandleClick(e){
		var autoid = $jbmenu.data('target').autoid;
		var catid = this.id;
		$.ajax({
			type:"POST",
			url: "./jbCatColorsUpdate.php",
			data: { autoid : autoid, catid : catid },
			dataType: 'html',
			success: function (data) {
			}
		}).done(function(data){
			$("div[id="+autoid+"]").html(data);
		});
		$jbmenu.hide();
	}

	$('#jbRowInbox').on('contextmenu', '.category', function (event) {
		event.preventDefault();
		if ($jbmenu.length == 0){
			jbBuildMenu();
		}
		$jbmenu
			.show()
			.data("target",{autoid : this.id })
			.css({
				top: event.pageY + "px",
				left: event.pageX + "px"
			});
	});
	$(document).on("click", function (event) {
		if ($(event.target).closest(".custom-menu").length == 0)
			$(".custom-menu").hide();
	});*/

	/* **************** talent request color category end ****************** */





	//-------------------------------------
	if($("#pm").val()=='25%'){
		$('#matchPercentage').attr('class', 'red-percentage');
	}else if($("#pm").val()=='50%'){
		$('#matchPercentage').attr('class', 'orange-percentage');
	}else if($("#pm").val()=='75%'){
		$('#matchPercentage').attr('class', 'yellow-percentage');
	}else if($("#pm").val()=='100%'){
		$('#matchPercentage').attr('class', 'green-percentage');
	}
	//-------------------------------------
	$(document).on('click', '.callBtn', function(e) {
		var $row = $(this).closest("tr"); 
		var messageid = $row.find('.messageid').data('messageid');
		if(messageid === undefined){
			messageid = $row.find('.jbmessageid').data('messageid');
			window.open('./screening.php?messageid='+messageid+'&jb=jcall','_blank');
		}else{
			window.open('./screening.php?messageid='+messageid+'','_blank');
		}

		/*$.ajax({
			   type: "POST",
			   url: "./questionare.php",
			   data: { messageid : messageid },
			   dataType: 'html',
			   success: function (data) {
				  $('#tblMailView').hide();
				  $('.formDisplay').html('');
				  $('.formDisplay').html(data);
				  $('.screenDate').datetimepicker({dateFormat: 'dd-mm-yy'});
				  $('.screenDate').datetimepicker('setDate', (new Date()));
				  $('.intvwTime').datetimepicker({
					  dateFormat: 'dd-mm-yy',  
					  timeFormat: 'hh:mm tt z'
				  });
				  $('#convictionDescription').hide();
				  $('#medicalConditionDesc').hide();
				  $('#consultantId').hide();
				  $('#intvwTime').hide(); 
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
				$('.messageid').on('click', function(e){
					  $('#tblMailView').show();
					  $('.formDisplay').html('');
				});
		  });*/
	});
	//-------------------------
	$(document).on('click', '.screenDate', function(){
		$('.screenDate').datetimepicker({dateFormat: 'dd-mm-yy'});
	});
	$(document).on('click', '.intvwTime', function(){
		$('.intvwTime').datetimepicker({
			dateFormat: 'dd-mm-yy',  
			timeFormat: 'hh:mm tt z'
		});
	});
	$(document).on('click', '#medicalCondition', function(){
		if($('input[name=medicalCondition]:checked', '#screenFrm').val() == 'Yes'){
			$('#medicalConditionDesc').show();
		}else{
			$('#medicalConditionDesc').hide();
		}
	});
	$(document).on('click', '#criminalConviction', function(){
		if($('input[name=criminalConviction]:checked', '#screenFrm').val() == 'Yes'){
			$('#convictionDescription').show();
		}else{
			$('#convictionDescription').hide();
		}
	});
	$(document).on('click', '#bookInterview', function(){
		if($('input[name=bookInterview]:checked', '#screenFrm').val() == 'Yes'){
			$('#consultantId').show();
			$('#intvwTime').show();
		}else if($('input[name=bookInterview]:checked', '#screenFrm').val() == 'No'){
			$('#consultantId').hide();
			$('#intvwTime').hide();
		}
	});
	$(document).on('click', '.messageid', function(e){
		getMailView($(this));
		getAttachments($(this));
		/*
		var win=window.open('about:blank');
		with(win.document)
		{
		  open();
		  write(data);
		  close();
		}
		*/
	});
	/*$(document).on('click', '.screenSubmit', function(evt) {
		
		$("#screenFrm").validate({
		  rules: {
			 firstName: {
				required: true
				   },
			 lastName: {
				required: true
				   },	   
			 candidateEmail: {
				required: true
				   },
			 candidateMobile: {
				required: true			 
			 },
			 convictionDescription: {
			 	required: function(element) {
				  return $("input:radio[name='criminalConviction']:checked").val() == 'Yes';
				}
			 },
			 candidateSex: {
			 	required : true
			 },
			 currentWrk: {
			 	required: true   
				   },
			 howfar: {
			 	required: true
			 },
			 residentStatus:{
			 	required: true
			 },
			 shiftAvailable:{
			 	required: true
			 }
		 },
		 messages: {
			firstName:{
				required: "Please enter candidate first name"
			},
			lastName:{
				required: "Please enter candidate last name"
			},
			candidateEmail:{
				required: "Please enter candidate email"
			},
			convictionDescription:{
				required: "Please Describe Criminal Conviction"
			},
			candidateSex:{
				required: "Please select candidate sex"
			},
			candidateMobile:{
				required: "Please enter candidate mobile"
			},
			currentWrk:{
				required: "Please enter work status"
			},
			howfar:{
				required: "Please enter how far are you willing to travel for work"
			},
			residentStatus: {
				required: "Please select residential status"
			},
			shiftAvailable:{
				required: "Please select shift available"
			}
		 },
		 submitHandler: function (form) {
			var messageid = $('#messageid').val();
			var firstName = $('#firstName').val();
			var lastName = $('#lastName').val();
			var candidateEmail = $('#candidateEmail').val();
			console.log('email'+candidateEmail);
			var candidateSex = $('#candidateSex :selected').val();
			var screenDate = $('#screenDate').val();
			var suburb = $('#candidateSuburb').val();
			var candidatePhone = $('#candidatePhone').val();
			var candidateMobile = $('#candidateMobile').val();
			var currentWrk = $('textarea#currentWrk').val();
			var howfar = $('#howfar').val();
			var genLabourPay = $('textarea#genLabourPay').val();
			var criminalConviction = $('input[name=criminalConviction]:checked', '#screenFrm').val();
			var convictionDescription = $('#convictionDescription').val();
			var medicalCondition = $('input[name=medicalCondition]:checked', '#screenFrm').val();
			var medicalConditionDesc = $('#medicalConditionDesc').val();
			var hasCar = $('input[name=hasCar]:checked', '#screenFrm').val();
			var residentStatus = $('input[name=residentStatus]:checked', '#screenFrm').val();
			var otherLicence = [];
            $.each($("input[name='otherLicence']:checked"), function(){            
                otherLicence.push($(this).val());
            });
			var safetyGear = [];
			$.each($("input[name='safetyGear']:checked"), function(){            
                safetyGear.push($(this).val());
            });
			var expOperating = [];
			$.each($("input[name='expOperating']:checked"), function(){            
                expOperating.push($(this).val());
            });
			var workType = $('textarea#workType').val();
			var shiftAvailable = [];
			$.each($("input[name='shiftAvailable']:checked"), function(){            
                shiftAvailable.push($(this).val());
            });
			var overtime = $('input[name=overtime]:checked', '#screenFrm').val();
			var bookInterview = $('input[name=bookInterview]:checked', '#screenFrm').val();
			var intvwTime = $('#intvwTime').val();
			var consultantId = $('#consultantId option:selected').val();
			$(".screenSubmit").attr("disabled", true);
				$.ajax({
				   type: "POST",
				   url: "./updateCandidate.php",
				   data: {messageid : messageid , firstName : firstName, lastName : lastName , candidateEmail : candidateEmail, candidateSex : candidateSex , screenDate : screenDate , suburb : suburb, candidatePhone : candidatePhone , candidateMobile : candidateMobile , currentWrk : currentWrk , howfar : howfar , genLabourPay : genLabourPay , criminalConviction : criminalConviction , convictionDescription : convictionDescription, medicalCondition : medicalCondition , medicalConditionDesc : medicalConditionDesc , hasCar : hasCar , residentStatus : residentStatus , otherLicence : otherLicence , safetyGear : safetyGear , expOperating : expOperating , workType : workType , shiftAvailable : shiftAvailable , overtime : overtime , bookInterview : bookInterview,intvwTime : intvwTime , consultantId : consultantId},
				   dataType: "json",
				   success: function (data) {
					   	  console.log('DATA'+data);
						  if(data.email == 'SUCCESS'){
							alert("email generated");
							location.reload(true);
						  }else if(data.email == 'ERROR'){
							alert("error generating email");
							location.reload(true);
						  }else if(data.status == 'ARCHIVED'){
							alert("candidate details archived");
							location.reload(true);
						  }else if(data.status == 'EXISTS'){
							alert("existing candidate updated");
							location.reload(true);
						  }else if(data.status == 'DBERROR'){
							alert("data insert error");
							location.reload(true);  
						  }else{
						  	alert(data);//"This Candidate may be existing/Database Error"
							location.reload(true);
						  }
						  
						  $(".screenSubmit").attr("disabled", false);
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
				});
         }
		});
	});*/

/* ----------------------- search by subject ------------------------------ */
/*$(document).on('click', '.subjectSearchBtn', function(e) {		
		var rows='';
		var numRows;
		var subjectSearchTxt = $('#subjectSearchTxt').val();
		$.ajax({
			 type: "POST",
			 url: "./searchSubjectMail.php",
			 data: { subjectSearchTxt : subjectSearchTxt },
			 dataType: 'html',
			 success: function (data) {
				 if(data.length>0){
					$('.mailListBody').html('');
					$('.mailListBody').html(data);
					$('.numRows').html('');
					$('.numRows').html();	
				 }else{
					$('.mailListBody').html('');
					$('.mailListBody').html('No Matching Results');
					$('.numRows').html('');
					$('.numRows').html('0');
				 }
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
			$('.numRows').html($('#rowCount').val());	
			$('.messageid').on('click', function(e){
				$('#tblMailView').show();
				$('.formDisplay').html('');
				getMailView($(this));
				getAttachments($(this));
			});
			
		});
	});*/
	//snowfall
	/*$('#header').snowfall({round: true,minSize : 3, maxSize : 4, flakeCount : 300, maxSpeed : 1});
	$('body').snowfall({round: true,minSize : 3, maxSize : 4, flakeCount : 300, maxSpeed : 1});*/


/* --------------------------- end -----------------------------------------*/	
});
/*function getEmailList(){
var items;
var numRows;
	$.ajax({
			 type: "GET",
			 url: "./mailList.php",
			 dataType: 'html',
			 success: function (data) {
				/!*$.each(data, function(index, element) {
					numRows = element.numRows;
					items += '<tr><td class="messageid" data-messageid="'+element.messageid+'"><div>'+element.mailfrom+'</div><div>'+element.subject+'</div><div>'+element.mailto+'</div></td><td align="right">'+element.date+'</td><td><input type="hidden" name="messageid" id="messageid" value="'+element.messageid+'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td><td><button id="deleteBtn" class="deleteBtn btn btn-xs btn-danger" type="button"><i class="fa fa-trash"></i> Delete</button></td></tr>';
				});*!/
			 	$('.mailListBody').html('');
				$('.mailListBody').html(data);
				//$('.mailListBody').html(items);	
				$('.numRows').html($('#rCount').val());
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
			$('.messageid').on('click', function(e){
				getMailView($(this));
				getAttachments($(this));
			});
		});
}

function getAttachments(instance){
	var $row = instance.closest("tr"); 
	var messageid = $row.find('.messageid').data('messageid');
	var fileico;
	$.ajax({
			 type: "POST",
			 url: "./getAttachments.php",
			 data: { messageid : messageid },
			 dataType: 'json',
			 success: function (data) {
				$('.mailAttachments').html('');
				$.each(data, function(index, element) {
					if(element.filetype == 'pdf'){
						fileico = 'pdf-adobe-portable-document-format.ico';	
					}else if(element.filetype == 'docx'){
						fileico = 'docx-microsoft-word-open-xml-document.png';
					}else if(element.filetype == 'doc'){
						fileico = 'doc-microsoft-word-97-to-2003-document.png';
					}else if(element.filetype == 'txt'){
						fileico = 'text_document.ico';
					}
					$('.mailAttachments').append('<img src="./filetypes/'+fileico+'" /><a href="'+element.filepath+'" target="_blank">'+element.filename+'</a><br>');
				});
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
		});	
}
function getMailView(instance){
	var $row = instance.closest("tr"); 
	var messageid = $row.find('.messageid').data('messageid');
	var mailString;
		$.ajax({
			 type: "POST",
			 url: "./mailView.php",
			 data: { messageid : messageid },
			 dataType: 'json',
			 success: function (data) {
				$.each(data, function(index, element) {
					$('.mailSubject').html();
					$('.mailSubject').html(element.subject);
					$('.mailFrom').html();
					$('.mailFrom').html(element.mailfrom);
					$('.sentDate').html();
					$('.sentDate').html(element.date);
					$('.mailTo').html();
					$('.mailTo').html(element.mailto);
					$('.mailAttachments').html();
					$('.mailAttachments').html();
					$('.mailBody').html('');
					mailString = element.msgbody;
					mailString.replace(/(\r\n|\n|\r)/gm,"");								
				});
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
			$(".mailBody").html(mailString);
			var decoded = $("<textarea/>").html(mailString).text();
			$(".mailBody").contents().find('html').html(decoded.replace(/\\n/g, "").replace(/\\r/g, ""));
			$(".mailBody").contents().find('a').click(function(event) {
            	event.preventDefault();
        	}); 
		});
}*/
// filter out some nasties
function filterData(data){
data = data.replace(/<?\/body[^>]*>/g,'');
data = data.replace(/[\r|\n]+/g,'');
data = data.replace(/<--[\S\s]*?-->/g,'');
data = data.replace(/<noscript[^>]*>[\S\s]*?<\/noscript>/g,'');
data = data.replace(/<script[^>]*>[\S\s]*?<\/script>/g,'');
data = data.replace(/<script.*\/>/,'');
return data;
}
function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
/*
*  Converts \n newline chars into <br> chars s.t. they are visible
*  inside HTML
*/
function convertToHTMLVisibleNewline(value) {
    if (value != null && value != "") {
        return value.replace(/\n/g, "<br/>");
    } else {
        return value;
    }
}

/*
*  Converts <br> chars into \n newline chars s.t. they are visible
*  inside text edit boxes
*/
function convertToTextVisibleNewLine(value) {
    if (value != null && value != "") {
        return value.replace(/\<br\>/g, "\n");
    } else {
        return value;
    }
}
function escapeNewLineChars(valueToEscape) {
    if (valueToEscape != null && valueToEscape != "") {
        return valueToEscape.replace(/\n/g, "\\n");
    } else {
        return valueToEscape;
    }
}