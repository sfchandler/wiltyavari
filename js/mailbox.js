$(function() {
    $body = $("body");
    $(document).on({
        ajaxStart: function() { $body.addClass("loading");    },
        ajaxStop: function() { $body.removeClass("loading"); }
    });
    var addCommentDialog;
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
    /* End  Default Mail List Request */
    $(document).on('click', '.inbox-load', function(e) {
        $('#tblMailView').show();
        $('.formDisplay').html('');
    });

    $(document).on('click', '.checkBtn', function(e) {
        var $row = $(this).closest("tr");
        var autoId = $(this).closest("tr").attr('id');
        var messageid = $row.find('.messageid').data('messageid');
        var refcode = $row.find('.messageid').data('refcode');
        var email = $row.find('.messageid').data('email');
        window.open('./candidateReview.php?messageid='+messageid+'&autoId='+autoId+'&refcode='+refcode+'&email='+email,'_blank');
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
                    var mailbody = 'Please fill the jotform required by labourbank and submit online, in-order to fulfill your recruitment by clicking, <a href="http://www.labourbank.com.au/jotForm.php?conEmail='+conEmail+'" target="_blank">Click here to submit jot form</a>';
                    $('#jotEmailBody').text(mailbody);
                    $('#jotEmailText').html(mailbody);
                    jotEmailDialog.data('jotEmailAddress', data);
                    jotEmailDialog.dialog("open");
                }else{
                    //alert('Please enter an email address');
                    $('#jotEmailAddress').val('');
                    var mailbody = 'Please fill the jotform required by labourbank and submit online, in-order to fulfill your recruitment by clicking, <a href="http://www.labourbank.com.au/jotForm.php?conEmail='+conEmail+'" target="_blank">Click here to submit jot form</a>';
                    $('#jotEmailBody').text(mailbody);
                    $('#jotEmailText').html(mailbody);
                    jotEmailDialog.data('jotEmailAddress', '');
                    jotEmailDialog.dialog("open");
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
                    var mailbody = 'Please fill the forms required by labourbank and submit online, in-order to fulfill your recruitment by clicking, <a href="http://www.labourbank.com.au/forms.php?conEmail='+conEmail+'" target="_blank">Click here to submit forms</a>';
                    $('#taxEmailBody').text(mailbody);
                    $('#taxEmailText').html(mailbody);
                    taxEmailDialog.data('taxEmailAddress', data);
                    taxEmailDialog.dialog("open");
                }else{
                    $('#taxEmailAddress').val('');
                    var mailbody = 'Please fill the forms required by labourbank and submit online, in-order to fulfill your recruitment by clicking, <a href="http://www.labourbank.com.au/forms.php?conEmail='+conEmail+'" target="_blank">Click here to submit forms</a>';
                    $('#taxEmailBody').text(mailbody);
                    $('#taxEmailText').html(mailbody);
                    taxEmailDialog.data('taxEmailAddress', '');
                    taxEmailDialog.dialog("open");
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
    $(document).on('click','.commentLink',function (e) {
        var autoId = $(this).closest('tr').attr('id');
        var refcode = $(this).closest('td').prev().attr('data-refcode');
        $('#mAutoId').val('');
        $('#mAutoId').val(autoId);
        addCommentDialog.data('mAutoId',autoId);
        addCommentDialog.data('mComment',getMailComment(autoId));
       /* if($('textarea#mComment').val() === ''){
            addCommentDialog.data('mComment',refcode);
            $('textarea#mComment').val(addCommentDialog.data('mComment'));
            console.log('refcode >> '+$('#mComment').val()+' mcomment '+addCommentDialog.data('mComment'));
        }*/
        addCommentDialog.dialog("open");
        $(this).scrollTop(e.pageY);
        return false;
    });
    /************************ end *****************************/
    var status = 1;
        /* ***************** EMail Data Loading ********************* */
    loadInboxReferenceMails(1);
    $(document).on('click','.viewBody', function (){
        $(this).closest('td').find('.mailBodyText').toggle();
    });
    /* ******************* end Email Data Loading ***************** */
    /*------------------ job board mails ------------------- */
    //loadJBInboxReferenceMails();
   /* $(document).on('click', '.jb-load', function(e) {
        $('#tblJBMailView').show();
        $('.formJBDisplay').html('');
    });
    loadJobBoardResults(0);
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

    });*/
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


    /* **************** status color category start ****************** */
    var colorList = [];
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
    });

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
        //getMailView($(this));
        //getAttachments($(this));
    });
    /* ******************* mail search **************************** */
    $(document).on('click', '.srchTxtBtn', function(e) {
        let ref_code = $(this).closest('td').data('ref-code');
        let srchTxt = $(this).closest('td').find('input[name="srchTxt"]').val();
        let subjectSrchTxt = $(this).closest('td').find('input[name="subjectSrchTxt"]').val();
        let fromSrchTxt = $(this).closest('td').find('input[name="fromSrchTxt"]').val();
        console.log('...........'+ref_code+'  '+srchTxt+' '+fromSrchTxt);
        $.ajax({
            type:"POST",
            url: "./searchReference.php",
            dataType: 'html',
            data:{ref_code:ref_code,srchTxt:srchTxt,subjectSrchTxt:subjectSrchTxt,fromSrchTxt:fromSrchTxt},
            success: function (data) {
                $('#S'+ref_code).html('');
                $('#S'+ref_code).html(data);
            }
        });

    });
    /*$(document).on('click', '.srchJBTxtBtn', function(e) {
        let ref_code = $(this).closest('td').data('ref-code');
        let srchTxt = $(this).closest('td').find('input[name="srchTxt"]').val();
        let subjectSrchTxt = $(this).closest('td').find('input[name="subjectSrchTxt"]').val();
        let fromSrchTxt = $(this).closest('td').find('input[name="fromSrchTxt"]').val();
        console.log('...........'+ref_code+'  '+srchTxt+' '+fromSrchTxt);
        $.ajax({
            type:"POST",
            url: "./searchJBReference.php",
            dataType: 'html',
            data:{ref_code:ref_code,srchTxt:srchTxt,subjectSrchTxt:subjectSrchTxt,fromSrchTxt:fromSrchTxt},
            success: function (data) {
                $('#JS'+ref_code).html('');
                $('#JS'+ref_code).html(data);
            }
        });

    });*/
    $(document).on('click','.clpse',function (){
        let ref_code = $(this).data('ref-code');
        $.ajax({
            type:"POST",
            url: "./mailResults.php",
            dataType: 'html',
            data:{ref_code:ref_code,status:status},
            success: function (data) {
                $('#S'+ref_code).html('');
                $('#S'+ref_code).html(data);
            }
        });
    });
    $('.viewActiveBtn').hide();
    $('.table#tblHead').addClass('tblHeadGreen');
    $(document).on('click','.viewInactiveBtn', function (){
        loadInboxReferenceMails(0);
        status = 0;
        $('.viewInactiveBtn').hide();
        $('.viewActiveBtn').show();
        $('.table#tblHead').removeClass('tblHeadGreen');
        $('.table#tblHead').addClass('tblHeadGrey');
    });
    $(document).on('click','.viewActiveBtn', function (){
        loadInboxReferenceMails(1);
        status = 1;
        $('.viewActiveBtn').hide();
        $('.viewInactiveBtn').show();
        $('.table#tblHead').removeClass('tblHeadGrey');
        $('.table#tblHead').addClass('tblHeadGreen');
    });
    /*$(document).on('click','.jbclpse',function (){
        let ref_code = $(this).data('ref-code');
        $.ajax({
            type:"POST",
            url: "./jbMailResults.php",
            dataType: 'html',
            data:{ref_code:ref_code},
            success: function (data) {
                $('#JS'+ref_code).html('');
                $('#JS'+ref_code).html(data);
            }
        });
    });*/
    //snowfall
    /*$('#header').snowfall({round: true,minSize : 3, maxSize : 4, flakeCount : 300, maxSpeed : 1});
    $('body').snowfall({round: true,minSize : 3, maxSize : 4, flakeCount : 300, maxSpeed : 1});*/
    /* --------------------------- end -----------------------------------------*/
    $(document).on('click','.shortlistBtn', function (){
        let arr = [];
        $(this).closest('td').prev().prev().find("input[name='positionChk']:checked").each(function (){
           arr.push($(this).val());
        });
        let auto_id = $(this).closest('tr').attr('id');
        let state_id = $(this).closest('td').prev().prev().prev().prev().find('#state_id :selected').val();
        let region = $(this).closest('td').prev().prev().prev().find('#region :selected').val();
        let gender = $(this).closest('td').prev().find('#gender :selected').val();
        let applied_date = $(this).closest('td').find('#applied_date').val();
        let ref_code = $(this).closest('td').find('#ref_code').val();
        let msg_id = $(this).closest('td').find('#msg_id').val();
        let account_name = $(this).closest('td').find('#account_name').val();
        let inbox_type = 'INBOX';
        //console.log('============>>>>>> '+'auto_id'+auto_id+'state'+state_id+'region'+region+'gender'+gender+'appdate'+applied_date+'refc'+ref_code+'msgid'+msg_id+'acname'+account_name+'inb'+inbox_type);
        $.ajax({
            type:"POST",
            url: "./updateShortList.php",
            dataType: 'text',
            data:{
                auto_id: auto_id,
                state_id: state_id,
                positions: arr,
                region: region,
                gender: gender,
                applied_date: applied_date,
                ref_code: ref_code,
                msg_id: msg_id,
                account_name: account_name,
                inbox_type:inbox_type
            },
            success: function (data) {
                console.log('DATA '+data);
            }
        });
    });
});
function getEmailList(){
    var items;
    var numRows;
    $.ajax({
        type: "GET",
        url: "./mailList.php",
        dataType: 'html',
        success: function (data) {
            /*$.each(data, function(index, element) {
                numRows = element.numRows;
                items += '<tr><td class="messageid" data-messageid="'+element.messageid+'"><div>'+element.mailfrom+'</div><div>'+element.subject+'</div><div>'+element.mailto+'</div></td><td align="right">'+element.date+'</td><td><input type="hidden" name="messageid" id="messageid" value="'+element.messageid+'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td><td><button id="deleteBtn" class="deleteBtn btn btn-xs btn-danger" type="button"><i class="fa fa-trash"></i> Delete</button></td></tr>';
            });*/
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
}
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

function loadInboxReferenceMails(status){
    $.ajax({
        url: "loadReferenceMails.php",
        type: "POST",
        dataType: "html",
        data:{status:status},
        success: function(data) {
            $("#inboxBody").html('');
            $("#inboxBody").html(data);
            //$("#inboxBody").append(data);
        }
    });
}
/*function loadJBInboxReferenceMails(){
    $.ajax({
        url: "loadJBReferenceMails.php",
        type: "POST",
        dataType: "html",
        success: function(data) {
            $("#inboxJBBody").append(data);
        }
    });
}*/
