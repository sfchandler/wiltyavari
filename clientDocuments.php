<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
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
                <h3>Client/Company Documents</h3>
            </div>
                <div style="width:40%; float: left">
                    <div style="padding-left:30px;padding-bottom:10px;">
                        <form id="frmFile" action="clientDocUpload.php" class="smart-form" enctype="multipart/form-data" method="post">
                            <div class="row">
                                    <section class="col col-4">
                                        <label>Client :</label>
                                        <label class="select"><i class="icon-append fa fa-user"></i>
                                            <select id="clientId" name="clientId" class="select">
                                                <?php echo getClientsForDocumentUpload($mysqli); ?>
                                            </select><i class="icon-append fa fa-industry"></i>
                                        </label>
                                        <label for="docDesc">Document Description</label>
                                        <label class="input"><i class="icon-append fa fa-file-text"></i>
                                            <input type="text" name="docDesc" class="input"/>
                                        </label>
                                    </section>
                                    <section class="col col-4">
                                        <label for="docName">Document Name:</label>
                                        <label class="input"><i class="icon-append fa fa-file-text"></i>
                                            <input type="text" name="docName" id="docName" readonly value="">
                                        </label>
                                        <label for="file">File input:</label>
                                        <div class="input input-file"><span class="button">
                                            <input class="input" type="file" id="file" name="file" onchange="this.parentNode.nextSibling.value = this.value">Browse</span><input type="text" placeholder="" readonly>
                                            <button class="btn btn-primary btn-sm" type="submit" value="Upload"><i class="glyphicon glyphicon-upload"></i>Upload</button>
                                        </div>
                                        <div id="progress">
                                            <div id="bar"></div>
                                            <div id="percent">0%</div>
                                        </div>
                                        <div id="message"></div>
                                        <label for="notes">Notes :</label>
                                        <label class="textarea"><i class="icon-append fa fa-info"></i>
                                            <textarea class="textarea" name="notes" placeholder="Notes" required><?php echo $notes;?></textarea>
                                    </section>
                            </div>
                        </form>
                    </div>
                </div>
                <div style="padding-left:10px; padding-bottom:10px; width:60%; float: left">
                    <table id="documents" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                        <tr>
                            <th data-class="expand"><i class="fa fa-fw fa-file txt-color-blue hidden-md hidden-sm hidden-xs"></i>DOCUMENTS</th>
                            <th data-class="expand"><i class="fa fa-fw fa-file txt-color-blue hidden-md hidden-sm hidden-xs"></i>DOCUMENT DESCRIPTION</th>
                            <th data-class="expand"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>
                        </tr>
                        </thead>
                        <tbody class="documentsList">
                        <?php echo getClientDocuments($mysqli,$clientId);?>
                        </tbody>
                    </table>
                </div>
                <div style="padding-left: 30px; clear: both"><h4>Document Audit list</h4></div>
                <div id="clientChk" style="padding-left:20px; padding-bottom:50px;">

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
        /*********** file upload ***************/
        var options = {
            beforeSend: function()
            {
                $("#progress").show();
                //clear everything
                $("#bar").width('0%');
                $("#message").html("");
                $("#percent").html("0%");
            },
            uploadProgress: function(event, position, total, percentComplete)
            {
                $("#bar").width(percentComplete+'%');
                $("#percent").html(percentComplete+'%');

            },
            success: function()
            {
                $("#bar").width('100%');
                $("#percent").html('100%');
            },
            complete: function(response)
            {
                if(response.responseText != 'Error Uploading'){
                    $('.documentsList').html(response.responseText);
                }else{
                    $("#message").html("<font color='green'>"+response.responseText+"</font>");
                }
            },
            error: function()
            {
                $("#message").html("<font color='red'>ERROR: unable to upload files</font>");
            }
        };

        $(document).on('change', '#file', function(){
            $('#docName').val($('input[type=file]').val());
        });
        $("#frmFile").ajaxForm(options);
        /*********** end file upload ***********/
        $(document).on('click', '.docRemove', function(){
            var $row = $(this).closest("tr");
            var clientId =  $row.find('.clientid').data('clientid');
            var fpath = $row.find('.fpath').data('fpath');
            $.ajax({
                type:"POST",
                url: "./clientDocRemove.php",
                data: { clientId : clientId, fpath : fpath },
                dataType: 'html',
                success: function (data) {
                    $('.documentsList').html('');
                    $('.documentsList').html(data);
                }
            }).done(function(data){
            });
        });
        $(document).on('change','#clientId',function () {
            var clientId = $('#clientId :selected').val();
            $.ajax({
                type:"POST",
                url: "./getClientDocuments.php",
                data: { clientId : clientId},
                dataType: 'html',
                success: function (data) {
                    $('.documentsList').html('');
                    $('.documentsList').html(data);
                    loadClientAuditCheck();
                }
            });
        });
        $(document).on('click','#clientId',function () {
            var clientId = $('#clientId :selected').val();
            $.ajax({
                type:"POST",
                url: "./getClientDocuments.php",
                data: { clientId : clientId},
                dataType: 'html',
                success: function (data) {
                    $('.documentsList').html('');
                    $('.documentsList').html(data);
                    loadClientAuditCheck();
                }
            });
        });

        function loadClientAuditCheck(){
            var clientId = $('#clientId :selected').val();
            var action = 'GET';
            $.ajax({
                type:"POST",
                url: "./loadClientAuditList.php",
                data: { clientId : clientId, action:action },
                dataType: 'html',
                success: function (data) {
                    console.log('.......'+data);
                    $('#clientChk').html('');
                    $('#clientChk').html(data);
                }
            }).done(function(data){
            });
        }

        $(document).on('click','.consRadio',function(){
            var chkTypeSelection = $(this).val();
            var chkTypeName = $(this).attr('name');
            var clientId = $('#clientId :selected').val();
            var action ='UPDATE';
            $.ajax({
                type:"POST",
                url: "./loadClientAuditList.php",
                data: { clientId : clientId, action:action,chkTypeSelection:chkTypeSelection,chkTypeName:chkTypeName },
                dataType: 'html',
                success: function (data) {
                    if((data == 'added') || (data == 'updated')){
                        loadClientAuditCheck();
                    }
                }
            }).done(function(data){

            });
        });
        $(document).on('click','.accRadio',function(){
            var chkTypeSelection = $(this).val();
            var chkTypeName = $(this).attr('name');
            var clientId = $('#clientId :selected').val();
            var action ='PAYROLL';
            $.ajax({
                type:"POST",
                url: "./loadClientAuditList.php",
                data: { clientId : clientId, action:action,chkTypeSelection:chkTypeSelection,chkTypeName:chkTypeName},
                dataType: 'html',
                success: function (data) {
                    console.log('....'+chkTypeSelection);
                    if((data == 'added') || (data == 'updated')){
                        loadClientAuditCheck();
                    }
                }
            }).done(function(data){

            });
        });
        $(document).on('click','#consAuditBtn',function(evt){
            var frmAccCheck = $('#frmAccCheck').validate({
                rules: {
                    1:{
                        required:true
                    },
                    2:{
                        required:true
                    },
                    3:{
                        required:true
                    },
                    4:{
                        required:true
                    },
                    5:{
                        required:true
                    },
                    6:{
                        required:true
                    },
                    7:{
                        required:true
                    },
                    8:{
                        required:true
                    },
                    9:{
                        required:true
                    },
                    10:{
                        required:true
                    },
                    11:{
                        required:true
                    },
                    12:{
                        required:true
                    },
                    13:{
                        required:true
                    },
                    14:{
                        required:true
                    },
                    15:{
                        required:true
                    }
                },
                messages: {
                    1:{
                        required:'required'
                    },
                    2:{
                        required:'required'
                    },
                    3:{
                        required:'required'
                    },
                    4:{
                        required:'required'
                    },
                    5:{
                        required:'required'
                    },
                    6:{
                        required:'required'
                    },
                    7:{
                        required:'required'
                    },
                    8:{
                        required:'required'
                    },
                    9:{
                        required:'required'
                    },
                    10:{
                        required:'required'
                    },
                    11:{
                        required:'required'
                    },
                    12:{
                        required:'required'
                    },
                    13:{
                        required:'required'
                    },
                    14:{
                        required:'required'
                    },
                    15:{
                        required:'required'
                    }
                },
                submitHandler: function (form) {
                    if ($('#jobOrderNotify').prop('checked') == true) {
                        var jobOrderNotify = 1;
                        var clientId = $('#clientId :selected').val();
                        var canId = $('#canId').val();
                        var action = 'MAIL';
                        $.ajax({
                            type: "POST",
                            url: "./loadClientAuditList.php",
                            data: {
                                canId: canId,
                                action: action,
                                clientId: clientId,
                                jobOrderNotify: jobOrderNotify
                            },
                            dataType: 'text',
                            success: function (data) {
                                $('.auditChkError').html('');
                                $('.auditChkError').html(data);
                            }
                        });
                    } else {
                        $('.auditChkError').html('');
                        $('.auditChkError').html('Please tick to agree selection');
                        alert('Please tick to agree selection');
                    }
                }
            });
        });


        var clientId = $('#clientId :selected').val();
        getClientPersonAuditedBy(clientId);
        function getClientPersonAuditedBy(clientId){
            $.ajax({
                type: "POST",
                url: "./getClientAuditedPerson.php",
                data: {clientId : clientId},
                dataType: "text",
                success: function (data) {
                    $('.auditedPerson').html('');
                    $('.auditedPerson').html(data);
                }
            });
        }
        $(document).on('click','#accAuditBtn', function(){
            var frmAccCheck = $('#frmAccCheck').validate({
                rules: {
                    '1-P':{
                        required:true
                    },
                    '2-P':{
                        required:true
                    },
                    '3-P':{
                        required:true
                    },
                    '4-P':{
                        required:true
                    },
                    '5-P':{
                        required:true
                    },
                    '6-P':{
                        required:true
                    },
                    '7-P':{
                        required:true
                    },
                    '8-P':{
                        required:true
                    },
                    '9-P':{
                        required:true
                    },
                    '10-P':{
                        required:true
                    },
                    '11-P':{
                        required:true
                    },
                    '12-P':{
                        required:true
                    },
                    '13-P':{
                        required:true
                    },
                    '14-P':{
                        required:true
                    },
                    '15-P':{
                        required:true
                    },
                    '16-P':{
                        required:true
                    },
                    '17-P':{
                        required:true
                    },
                    '18-P':{
                        required:true
                    }
                },
                messages: {
                    '1-P':{
                        required:'required'
                    },
                    '2-P':{
                        required:'required'
                    },
                    '3-P':{
                        required:'required'
                    },
                    '4-P':{
                        required:'required'
                    },
                    '5-P':{
                        required:'required'
                    },
                    '6-P':{
                        required:'required'
                    },
                    '7-P':{
                        required:'required'
                    },
                    '8-P':{
                        required:'required'
                    },
                    '9-P':{
                        required:'required'
                    },
                    '10-P':{
                        required:'required'
                    },
                    '11-P':{
                        required:'required'
                    },
                    '12-P':{
                        required:'required'
                    },
                    '13-P':{
                        required:'required'
                    },
                    '14-P':{
                        required:'required'
                    },
                    '15-P':{
                        required:'required'
                    },
                    '15-P':{
                        required:'required'
                    },
                    '16-P':{
                        required:'required'
                    },
                    '17-P':{
                        required:'required'
                    },
                    '18-P':{
                        required:'required'
                    }
                },
                submitHandler: function (form) {
                    var auditStatus = '';
                    var btnStatus = $('#accAuditBtn').val();
                    if(btnStatus == 'AUDIT COMPLETE'){
                        auditStatus = 'AUDIT INCOMPLETE';
                    }else{
                        auditStatus = 'AUDIT COMPLETE';
                    }
                    var clientId = $('#clientId').val();
                    $.ajax({
                        type: "POST",
                        url: "./activateClient.php",
                        data: {auditStatus : auditStatus, clientId : clientId},
                        dataType: "text",
                        success: function (data) {
                            $('#accAuditBtn').val(data);
                            $('#accAuditBtn').html(data);
                            getClientPersonAuditedBy(clientId);
                        }
                    });
                }
            });
        });
        $(document).on('click','.activateBtn', function(){
            var clientId = $('#clientId').val();
            var status = '';
            var btnStatus = $(this).val();
            if(btnStatus == 'ACTIVE'){
                status = 'INACTIVE';
            }else{
                status = 'ACTIVE';
            }
            $.ajax({
                type: "POST",
                url: "./activateClient.php",
                data: {status : status, clientId : clientId},
                dataType: "text",
                success: function (data) {
                    console.log('STATUS'+status+'data'+data);
                    $('#activateBtn').val(data);
                    $('#activateBtn').html('');
                    $('#activateBtn').html(data);
                }
            });
        });
    });
</script>
</body>

</html>