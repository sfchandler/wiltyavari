<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
$useragent=$_SERVER['HTTP_USER_AGENT'];
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
/*if(empty($_REQUEST['candidateId'])){
    $msg = "Access Denied";
    header("Location:error.php?error=$msg");
}elseif(validateEmploymentTermDocumentSigned($mysqli,$_REQUEST['candidateId'])){
    $msg = "Employment Terms Signed submitted";
    header("Location:error.php?error=$msg");
}*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <style>
        .error{
            color: red;
        }
        label{
            font-weight: normal;
        }
        #signature {
            border: 1px dotted black;
            background-color:lightgrey;
        }
        .sign-panel{
            padding: 10px 10px 10px 30px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background: #FFFFFF;
        }
        body{
            background-image: url("img/subtle-stripes-pattern-2273.png");
            background-repeat: repeat;
        }
    </style>
</head>
<body>
    <div class="container">
        <br><br>
        <div class="sign-panel">
        <br><br>
        <div>
            <img src="img/logo.png" width="220" height="50">
        </div>
        <br>
        <form id="frmHireRatesForm" name="frmHireRatesForm" method="post" class="smart-form">
            <div class="row">
                <section class="col col sm-12">
                    <div id="adobe-dc-view" style="height: 500px; width: 860px;"></div>
                    <script src="https://documentcloud.adobe.com/view-sdk/main.js"></script>
                    <script type="text/javascript">
                        const urlParams = new URLSearchParams(window.location.search);
                        const filePath = atob(urlParams.get('file'));
                        const previewConfig = {
                            showDownloadPDF: false,
                            showPageControls: true
                        }
                        document.addEventListener("adobe_dc_view_sdk.ready", function(){
                            var adobeDCView = new AdobeDC.View({clientId: "7a8d6647b50240f3842818d162ff537a", divId: "adobe-dc-view"});
                            adobeDCView.previewFile({
                                    content:{ location:{ url: filePath }},
                                    metaData:{ fileName: filePath }
                                },previewConfig);
                        });
                    </script>
                </section>
            </div>
            <div class="row">
                <section class="col col sm-6">
                    <br><br><br>
                    <table class="table table-responsive table-striped" style="width: 60%">
                        <tbody>
                          <tr>
                            <td><b>Name:</b></td>
                            <td><input type="text" name="client_name" id="client_name" value="" class="form-control"></td>
                            <td></td>
                          </tr>
                          <tr>
                              <td><b>Title:</b></td>
                              <td><input type="text" name="client_title" id="client_title" value="" class="form-control"></td>
                              <td> </td>
                          </tr>
                          <tr>
                              <td><b>Company:</b></td>
                              <td><input type="hidden" name="client" id="client" value="<?php echo base64_decode($_REQUEST['client']);?>"><?php echo base64_decode($_REQUEST['client']);?>
                                  <input type="hidden" name="r_file" id="r_file" value="<?php echo base64_decode($_REQUEST['file']); ?>"/>
                              </td>
                              <td> </td>
                          </tr>
                          <tr>
                              <td><b>ABN:</b></td>
                              <td><input type="text" name="client_abn" id="client_abn" class="form-control"></td>
                              <td> </td>
                          </tr>
                          <tr>
                              <td><b>Address:</b></td>
                              <td><textarea name="client_address" id="client_address" cols="20" rows="5" class="form-control"></textarea></td>
                              <td> </td>
                          </tr>
                          <tr>
                              <td><b>Phone:</b></td>
                              <td><input type="text" name="client_phone" id="client_phone" class="form-control"/></td>
                              <td> </td>
                          </tr>
                          <tr>
                              <td><b>Fax:</b></td>
                              <td>
                                  <input type="text" name="client_fax" id="client_fax" class="form-control"/>
                                  <input type="hidden" name="id" id="id" value="<?php echo base64_decode($_REQUEST['id']); ?>"/>
                                  <input type="hidden" name="sign_in_ip" id="sign_in_ip" value="<?php echo base64_encode($_SERVER['REMOTE_ADDR']); ?>"/>
                              </td>
                              <td> </td>
                          </tr>
                        </tbody>
                      </table>
                </section>
            </div>
            <br>
            <br>
            <div>
                <section class="col col-sm-12">
                <b>I agree to the rates proposed by <?php echo DOMAIN_NAME; ?>  and understand the rules and regulations according to the <?php echo getAwardById($mysqli,base64_decode($_REQUEST['award'])); ?></b>
                </section>
            </div>
            <div class="row">
                <section class="col col-sm-6">
                    <br>
                    <br>
                    <b>Signature</b><span style="padding-left: 330px;"><b>Date:</b>&nbsp;<?php echo date('d/m/Y');?></span>
                    <div id="signature"></div>
                </section>
            </div>
            <div class="row">
                <section class="col col-sm-6">
                    <br>
                    <button id="hireRatesBtn" class="hireRatesBtn btn-success btn-lg">Submit</button>
                </section>
            </div>
        </form>
        </div>
        <br><br>
    </div>
    <br><br><br>
    <div id="imgSig" style="display: none;"></div>
    <img id="dataImg" src="" style="border: 1px solid green;">
    <script src="js/jquery/2.1.1/jquery.min.js"></script>
    <!-- this, preferably, goes inside head element: -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="js/jSignature/flashcanvas.js"></script>
    <![endif]-->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">
    <!-- BOOTSTRAP JS -->
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <!-- JQUERY VALIDATE -->
    <script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
    <!-- JQUERY VALIDATE -->
    <script src="js/plugin/jquery-validate/additional-methods.js"></script>
    <!-- you load jquery somewhere before jSignature...-->
    <script src="js/jSignature/jSignature.min.js"></script>

    <script type="text/javascript" src="js/jquery.base64.js"></script>
    <!-- JQUERY FORM PLUGIN -->
    <script src="js/jqueryform/jquery.form.js"></script>
<script>
    $(document).ready(function(){
        $body = $("body");
        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
        $.ajaxSetup({
            headers : {
                'CsrfToken': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var $sigdiv = $("#signature");
        $(document).on('click','#reset',function () {
            $sigdiv.jSignature("reset");
        });
        $sigdiv.jSignature({'UndoButton':true,'background-color': 'transparent',
            'decor-color': 'transparent'
        });

        $sigdiv.jSignature("reset");
        $("#signature").on('change', function(e) {
            $("#imgSig").html('');
            var datapair = $sigdiv.jSignature("getData", "image");
            var i = new Image();
            i.id = 'signatureImg';
            i.src = "data:" + datapair[0] + "," + datapair[1];
            $(i).appendTo($("#imgSig"));
        });

        $(document).on('click','#hireRatesBtn',function (e) {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var hireRateForm = $('#frmHireRatesForm').validate({
                errorClass: errorClass,
                errorElement: errorElement,
                highlight: function (element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function (element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules:{
                    client_name:{
                        required:true
                    },
                    client_title:{
                        required:true
                    },
                    client_abn:{
                        required:true
                    },
                    client_address:{
                        required:true
                    },
                    client_phone:{
                        required:true
                    }
                },
                messages:{
                    client_name:{
                        required: "Please enter full name"
                    },
                    client_title:{
                        required: "Please enter your title"
                    },
                    client_abn:{
                        required: "Please enter ABN No"
                    },
                    client_address:{
                        required: "Please enter address"
                    },
                    client_phone:{
                        required: "Please enter phone number"
                    }
                },
                submitHandler: function (form) {
                    if ($sigdiv.jSignature('getData', 'native').length == 0) {
                        alert('Please Enter Signature!!!');
                    } else
                    {
                        var client_name = $.base64.encode($('#client_name').val());
                        var client_title  = $.base64.encode($('#client_title').val());
                        var client = $.base64.encode($('#client').val());
                        var client_abn = $.base64.encode($('#client_abn').val());
                        var client_address = $.base64.encode($('textarea#client_address').val());
                        var client_phone = $.base64.encode($('#client_phone').val());
                        var client_fax = $.base64.encode($('#client_fax').val());
                        var r_file = $.base64.encode($('#r_file').val());
                        var id = $.base64.encode($('#id').val());
                        var ip = $('#sign_in_ip').val();
                        var imageSrc = $("#signatureImg").attr('src');
                        $.ajax({
                            url:"./processHireRateSigning.php",
                            type:'POST',
                            dataType:'text',
                            data:{
                                client_name:client_name,
                                client_title:client_title,
                                client:client,
                                client_abn:client_abn,
                                client_address:client_address,
                                client_phone:client_phone,
                                client_fax:client_fax,
                                r_file:r_file,
                                id:id,
                                ip:ip,
                                imageSrc:imageSrc
                            },
                            success: function (data) {
                                if($.trim(data) === 'MAILSENT'){
                                    alert('Labour Hire Rates successfully submitted!');
                                    $('#hireRatesBtn').hide();
                                    location.reload();
                                    window.close();
                                }else{
                                    alert('! Submission Unsuccessful');
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
                        });
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>