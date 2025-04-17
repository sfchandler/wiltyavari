<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
if($_SESSION['userType']!=='ADMIN'){
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
                <h2  class="semi-bold">Company Information</h2>
            </div>
            <div style="width:100%">
                <div style="padding-left:20px;padding-bottom:45px; width:45%; float: left;">
                    <form name="companyFrm" id="companyFrm" class="smart-form" method="post">
                        <header>
                            Add Company
                        </header>
                        <fieldset>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="input"> <i class="icon-append fa fa-briefcase"></i>
                                        <input type="hidden" name="companyId" id="companyId" value=""/>
                                        <input type="text" name="companyName" id="companyName" placeholder="Company Name" class="clientGroup">
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="input"><i class="icon-append fa fa-tag"></i>
                                        <input name="abn" id="abn" value="" placeholder="ABN" class="clientGroup"/>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="input"><i class="icon-append fa fa-tag"></i>
                                        <input name="acn" id="acn" value="" placeholder="ACN" class="clientGroup"/>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="input">
                                        <textarea name="companyAddress" id="companyAddress" placeholder="Address" class="clientGroup"></textarea>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="input"><i class="icon-append fa fa-phone"></i>
                                        <input name="telephone" id="telephone" value="" placeholder="Telephone" class="clientGroup"/>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="input"><i class="icon-append fa fa-phone"></i>
                                        <input name="fax" id="fax" value="" placeholder="Fax" class="clientGroup"/>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="input"><i class="icon-append fa fa-info"></i>
                                        <textarea name="companyDesc" id="companyDesc" placeholder="Description" class="clientGroup"></textarea>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="input"><i class="icon-append fa fa-home"></i>
                                        <input name="website" id="website" value="" placeholder="Website" class="clientGroup"/>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="input"><i class="icon-append fa fa-home"></i>
                                        <input name="remittanceEmail" id="remittanceEmail" value="" placeholder="Remittance Email" class="clientGroup"/>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-12">
                                    <label class="input"><i class="icon-append fa fa-sticky-note"></i>
                                        <textarea name="companyNote" id="companyNote" placeholder="Note" class="clientGroup" cols="50" rows="5"></textarea>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-12">
                                    <label class="input">
                                        <button type="submit" name="saveBtn" id="saveBtn" class="btn btn-primary btn-square btn-lg">Save</button>
                                        <button type="submit" name="updateBtn" id="updateBtn" class="btn btn-primary btn-square btn-lg">Update</button>
                                    </label>
                                </section>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div style="padding-left:20px; padding-bottom:50px; width:55%; float: left; overflow-y: scroll; height: 500px;">
                    <form id="frmFile" action="companyLogoUpload.php" class="smart-form" enctype="multipart/form-data" method="post">									<div class="row">
                            <fieldset>
                                <section class="col col-4">
                                    <select name="companyId">
                                    <?php echo getCompanyDropdown($mysqli);?>
                                    </select>
                                    <label for="file">File input:</label>
                                    <div class="input input-file">
                                        <span class="button"><input class="input" type="file" id="file" name="file" onchange="this.parentNode.nextSibling.value = this.value">Browse</span><input type="text" placeholder="" readonly>
                                        <button class="btn btn-primary btn-sm" type="submit" value="Upload"><i class="glyphicon glyphicon-upload"></i>Upload</button>
                                    </div>
                                    <div id="progress">
                                        <div id="bar"></div>
                                        <div id="percent">0%</div>
                                    </div>
                                    <div id="message"></div>
                                </section>
                            </fieldset>
                        </div>
                    </form>
                    <div class="companyList">
                        <table width="100%" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>CompanyID</th>
                                <th>CompanyName</th>
                                <th>Logo</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="companyBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div style="clear: both;"></div><br><br><br>
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
    $(document).ready(function(){
        $('#updateBtn').hide();
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
                    //$('.documentsList').html(response.responseText);
                    //console.log('Response text'+response.responseText);
                    location.reload();
                }else{
                    $("#message").html("<font color='green'>"+response.responseText+"</font>");
                }
            },
            error: function()
            {
                $("#message").html("<font color='red'> ERROR: unable to upload files</font>");
            }
        };
        $("#frmFile").ajaxForm(options);
        getCompanyInfo();
        function getCompanyInfo(){
            var action = 'GETINFO';
            $.ajax({
                url:"cmpInfoProcess.php",
                type:"POST",
                data:{action:action},
                dataType:"html",
                success: function(data){
                    $('.companyBody').html('');
                    $('.companyBody').html(data);
                }
            });
        }
        $(document).on('click','#removeBtn', function(){
            var companyId = $(this).closest('td').attr('data-companyid');
            var logoPath = $(this).closest('td').attr('data-logopath');
            var action = 'REMOVE';
            $.ajax({
                url:"cmpInfoProcess.php",
                type:"POST",
                data:{action:action,companyId:companyId,logoPath:logoPath},
                dataType:"html",
                success: function(data){
                    if(data == 'SUCCESS'){
                        getCompanyInfo();
                    }else{
                        console.log('Remove Unsuccessful');
                    }
                }
            });
        });
        $(document).on('click','#editBtn', function(){
            var companyId = $(this).closest('td').attr('data-companyid');
            var action = 'EDIT';
            $.ajax({
                url:"cmpInfoProcess.php",
                type:"POST",
                data:{action:action,companyId:companyId},
                dataType:"json",
                success: function(data){
                    if(data.length != 0){
                        $.each(data,function (index,value) {
                            $('#companyId').val('');
                            $('#companyId').val(value['companyId']);
                            $('#companyName').val(value['companyName']);
                            $('#abn').val(value['abn']);
                            $('#acn').val(value['acn']);
                            $('#companyAddress').text(value['companyAddress']);
                            $('#telephone').val(value['telephone']);
                            $('#fax').val(value['fax']);
                            $('#companyDesc').val(value['companyDesc']);
                            $('#website').val(value['website']);
                            $('#remittanceEmail').val(value['remittanceEmail']);
                            $('#companyNote').text(value['companyNote']);
                        });
                        $('#saveBtn').hide();
                        $('#updateBtn').show();
                    }
                }
            });
        });
        $(document).on('click', '#updateBtn', function() {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var companyFrm = $("#companyFrm").validate({
                errorClass	: errorClass,
                errorElement : errorElement,
                highlight: function(element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function(element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    companyName: {
                        required:true
                    },
                    abn:{
                        required:true
                    },
                    companyAddress: {
                        required:true
                    },
                    telephone:{
                        required:true
                    },
                    fax:{
                        required:true
                    },
                    website:{
                        required:true
                    },
                    remittanceEmail:{
                        required:true
                    }
                },
                messages: {
                    companyName: {
                        required: "Please enter Company Name"
                    },
                    abn:{
                        required: "Please enter ABN"
                    },
                    companyAddress: {
                        required: "Please enter company address"
                    },
                    telephone:{
                        required:"Please enter telephone"
                    },
                    fax:{
                        required:"Please enter fax"
                    },
                    website:{
                        required:"Please enter the web url"
                    },
                    remittanceEmail:{
                        required:"Please enter remittance email address"
                    }
                },
                submitHandler: function (form) {
                    var companyId = $('#companyId').val();
                    var companyName = $('#companyName').val();
                    var abn = $('#abn').val();
                    var acn = $('#acn').val();
                    var companyAddress = $('textarea#companyAddress').val();
                    var phone = $('#telephone').val();
                    var fax = $('#fax').val();
                    var companyDesc = $('#companyDesc').val();
                    var website = $('#website').val();
                    var remittanceEmail = $('#remittanceEmail').val();
                    var companyNote = $('textarea#companyNote').val();
                    var action = 'UPDATE';
                    $.ajax({
                        url:"cmpInfoProcess.php",
                        type:"POST",
                        data:{companyId:companyId,companyName:companyName,abn:abn,acn:acn,companyAddress:companyAddress,phone:phone,fax:fax,companyDesc:companyDesc,website:website,remittanceEmail:remittanceEmail,companyNote:companyNote,action:action},
                        dataType:"text",
                        success: function(data){
                            console.log('response data'+data);
                            if(data == 'updated'){
                                location.reload();
                            }
                        }
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        $(document).on('click', '#saveBtn', function() {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var companyFrm = $("#companyFrm").validate({
                errorClass	: errorClass,
                errorElement : errorElement,
                highlight: function(element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function(element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    companyName: {
                        required:true
                    },
                    abn:{
                        required:true
                    },
                    companyAddress: {
                        required:true
                    },
                    telephone:{
                        required:true
                    },
                    fax:{
                        required:true
                    },
                    website:{
                        required:true
                    },
                    remittanceEmail:{
                        required:true
                    }
                },
                messages: {
                    companyName: {
                        required: "Please enter Company Name"
                    },
                    abn:{
                        required: "Please enter ABN"
                    },
                    companyAddress: {
                        required: "Please enter company address"
                    },
                    telephone:{
                        required:"Please enter telephone"
                    },
                    fax:{
                        required:"Please enter fax"
                    },
                    website:{
                        required:"Please enter the web url"
                    },
                    remittanceEmail:{
                        required:"Please enter remittance email address"
                    }
                },
                submitHandler: function (form) {
                    var companyName = $('#companyName').val();
                    var abn = $('#abn').val();
                    var acn = $('#acn').val();
                    var companyAddress = $('textarea#companyAddress').val();
                    var phone = $('#telephone').val();
                    var fax = $('#fax').val();
                    var companyDesc = $('#companyDesc').val();
                    var website = $('#website').val();
                    var remittanceEmail = $('#remittanceEmail').val();
                    var companyNote = $('textarea#companyNote').val();
                    var action = 'SAVE';
                    $.ajax({
                        url:"cmpInfoProcess.php",
                        type:"POST",
                        data:{companyName:companyName,abn:abn,acn:acn,companyAddress:companyAddress,phone:phone,fax:fax,companyDesc:companyDesc,website:website,remittanceEmail:remittanceEmail,companyNote:companyNote,action:action},
                        dataType:"html",
                        success: function(data){
                            console.log('response data'+data);
                            if(data == 'inserted'){
                                location.reload();
                            }
                        }
                    });
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