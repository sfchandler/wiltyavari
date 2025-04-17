<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
$useragent=$_SERVER['HTTP_USER_AGENT'];
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if(empty($_REQUEST['id'])){
    $msg = "Access Denied";
    header("Location:error.php?error=$msg");
}elseif(validateDocumentTypeSigned($mysqli,base64_decode($_REQUEST['id']),85)){
    $msg = "Employee Variation Agreement Signed submitted";
    header("Location:error.php?error=$msg");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CHANDLER RECRUITMENT DAVIES BAKERY EMPLOYEE VARIATION AGREEMENT</title>
    <script src="js/jquery/2.1.1/jquery.min.js"></script>
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">
    <!-- BOOTSTRAP JS -->
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <!-- this, preferably, goes inside head element: -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="js/jSignature/flashcanvas.js"></script>
    <![endif]-->
    <script src="js/jSignature/jSignature.min.js"></script>
    <!-- JQUERY VALIDATE -->
    <script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
    <!-- JQUERY VALIDATE -->
    <script src="js/plugin/jquery-validate/additional-methods.js"></script>
    <script type="text/javascript" src="js/jquery.base64.js"></script>
    <!-- JQUERY FORM PLUGIN -->
    <script src="js/jqueryform/jquery.form.js"></script>
</head>
<body>
<div class="container">
    <br><br>
    <div class="panel">
        <br><br>
        <div>
            <img src="img/chandler_personnel.jpg" width="350" height="30">
        </div>
        <span style="text-align: center"><div class="h3box" style="background: black"><h3 style="color: white">EMPLOYEE VARIATION AGREEMENT</h3></div></span>
        <div id="msg" class="msg error"></div>
        <div id="adobe-dc-view" style="height: 500px; width: 100%;"></div>
        <script src="https://documentcloud.adobe.com/view-sdk/main.js"></script>
        <script type="text/javascript">
            const urlParams = new URLSearchParams(window.location.search);
            const filePath = 'docform/Davies Bakery Employment Variation Agreement.pdf';
            const previewConfig = {
                showDownloadPDF: false,
                showPageControls: true,
                showAnnotationTools: false,
                embedMode: "SIZED_CONTAINER",
                showFullScreen: true
            }
            document.addEventListener("adobe_dc_view_sdk.ready", function(){
                var adobeDCView = new AdobeDC.View({clientId: "7a8d6647b50240f3842818d162ff537a", divId: "adobe-dc-view"});
                adobeDCView.previewFile({
                    content:{ location:{ url: filePath }},
                    metaData:{ fileName: '/Davies Bakery Employment Variation Agreement' }
                },previewConfig);
            });
        </script>
        <div>
            <div style="width: 600px;padding-left: 50px;padding-top: 10px;">
                <div class="row">
                    <section class="col-lg-12"><a class="btn btn-info" href="docform/Davies Bakery Employment Variation Agreement.pdf" target="_blank">View Davies Bakery Variation Agreement</a></section>
                </div>
                <br><br>
                <div class="msg" style="color: #0aa66e; font-weight: bold"></div>
                <form name="frmEMPVARForm" id="frmEMPVARForm" method="post" class="mch_form">
                    <label>Employee Signature</label>
                    <table style="width: 450px;">
                        <tbody>
                        <tr>
                            <td><div id="signature" style="width: 100%; background: lightgrey"></div></td>
                        </tr>
                        <tr>
                            <td><button type="button" id="reset">Clear</button></td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
                    <?php echo getCandidateFullName($mysqli,base64_decode($_REQUEST['id'])); ?>
                    <br>
                    <?php echo date('d/m/Y'); ?>
                    <br>
                    <br><br>
                    <div class="divSubmit">
                        <input type="hidden" name="canId" id="canId" value="<?php echo $_REQUEST['id']; ?>">
                        <input type="hidden" name="conEmail" id="conEmail" value="<?php echo $_REQUEST['conEmail']; ?>">
                        <button type="submit" id="empVariationSubmitBtn" class="btn-success btn-lg">Sign & Submit</button>
                    </div>
                </form>
            </div>
            <br>
        </div>

    </div>
    <br><br>
</div>
<br><br><br>
<style>
    .form-control {
        border-radius: 0.5rem;
    }

    .input-group-addon {
        border-radius: 0.5rem;
    }

    .error {
        color: red;
    }

    .invalid {
        color: red;
    }

    label {
        font-weight: normal;
    }
    .panel {
        margin: 0 auto;
        padding: 10px 100px 10px 100px;
        border-radius: 10px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        background: #FFFFFF;
        width: 90%;
    }
    body {
        background-image: url("img/subtle-stripes-pattern-2273.png");
        background-repeat: repeat;
    }
    .table th, .table td {
        border-top: none !important;
    }
    .h3box {
        margin: 50px 0;
        padding: 10px 10px;
        border: 1px solid #eee;
        background: #f9f9f9;
    }
    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    *:before, *:after {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }
    .clearfix {
        clear: both;
    }
    .text-center {
        text-align: center;
    }
    a {
        color: tomato;
        text-decoration: none;
    }
    a:hover {
        color: #2196f3;
    }
    pre {
        display: block;
        padding: 9.5px;
        margin: 0 0 10px;
        font-size: 13px;
        line-height: 1.42857143;
        color: #333;
        word-break: break-all;
        word-wrap: break-word;
        background-color: #F5F5F5;
        border: 1px solid #CCC;
        border-radius: 4px;
    }
    .header {
        padding: 20px 0;
        position: relative;
        margin-bottom: 10px;
    }
    .header:after {
        content: "";
        display: block;
        height: 1px;
        background: #eee;
        position: absolute;
        left: 30%;
        right: 30%;
    }
    .header h2 {
        font-size: 3em;
        font-weight: 300;
        margin-bottom: 0.2em;
    }
    .header p {
        font-size: 14px;
    }
    #a-footer {
        margin: 20px 0;
    }
    .new-react-version {
        padding: 20px 20px;
        border: 1px solid #eee;
        border-radius: 20px;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);

        text-align: center;
        font-size: 14px;
        line-height: 1.7;
    }
    .new-react-version .react-svg-logo {
        text-align: center;
        max-width: 60px;
        margin: 20px auto;
        margin-top: 0;
    }
    .success-box1 {
        margin: 50px 0;
        padding: 10px 10px;
        border: 1px solid #eee;
        background: #f9f9f9;
    }
    .success-box1 img {
        margin-right: 10px;
        display: inline-block;
        vertical-align: top;
    }
    .success-box1 > div {
        vertical-align: top;
        display: inline-block;
        color: #888;
    }
    .success-box2 {
        margin: 50px 0;
        padding: 10px 10px;
        border: 1px solid #eee;
        background: #f9f9f9;
    }
    .success-box2 img {
        margin-right: 10px;
        display: inline-block;
        vertical-align: top;
    }
    .success-box2 > div {
        vertical-align: top;
        display: inline-block;
        color: #888;
    }
    .success-box3 {
        margin: 50px 0;
        padding: 10px 10px;
        border: 1px solid #eee;
        background: #f9f9f9;
    }
    .success-box3 img {
        margin-right: 10px;
        display: inline-block;
        vertical-align: top;
    }
    .success-box3 > div {
        vertical-align: top;
        display: inline-block;
        color: #888;
    }
    .success-box4 {
        margin: 50px 0;
        padding: 10px 10px;
        border: 1px solid #eee;
        background: #f9f9f9;
    }
    .success-box4 img {
        margin-right: 10px;
        display: inline-block;
        vertical-align: top;
    }
    .success-box4 > div {
        vertical-align: top;
        display: inline-block;
        color: #888;
    }
    .answers{
        padding-left: 30px;
    }
</style>
<script>
    jQuery.extend(jQuery.validator.messages, {
        required: "Please answer this question",
        remote: "Please fix this field.",
        email: "Please enter a valid email address.",
        url: "Please enter a valid URL.",
        date: "Please enter a valid date.",
        dateISO: "Please enter a valid date (ISO).",
        number: "Please enter a valid number.",
        digits: "Please enter only digits.",
        creditcard: "Please enter a valid credit card number.",
        equalTo: "Please enter the same value again.",
        accept: "Please enter a value with a valid extension.",
        maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
        minlength: jQuery.validator.format("Please enter at least {0} characters."),
        rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
        range: jQuery.validator.format("Please enter a value between {0} and {1}."),
        max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
        min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
    });
    $(document).ready(function () {
        $body = $("body");
        $(document).on({
            ajaxStart: function () {
                $body.addClass("loading");
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });

        var count = 0;
        $(document).on('click', '#empVariationSubmitBtn', function (e) {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmEMPVARForm = $('#frmEMPVARForm').validate({
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
                submitHandler: function (form) {
                    if ($sigdiv.jSignature('getData', 'native').length == 0) {
                        alert('Please Enter Signature!!!');
                    } else {
                        var imageSrc = $('#signatureImg').attr('src');
                        let canId = $('#canId').val();
                        let conEmail = $('#conEmail').val();
                        let action = 'SUBMIT';
                        $.ajax({
                            type: "POST",
                            url: "./processEmployeeVariation.php",
                            data: {
                                imageSrc: imageSrc,
                                canId: canId,
                                conEmail: conEmail,
                                action: action
                            },
                            dataType: "text",
                            success: function (data) {
                                if (data == 'SUCCESS') {
                                    $('.divSubmit').hide();
                                    $('.error').html('');
                                    $('.msg').html('');
                                    $('.msg').html('Employee Variation submitted successfully');
                                    $('html, body').animate({scrollTop: '0px'}, 300);
                                } else {
                                    $('.msg').html('');
                                    $('.msg').html('Employee Variation submission unsuccessful');
                                }
                            },
                            error: function (jqXHR, exception) {
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
                    if ( element.is(':radio') ) {
                        error.insertAfter(element.parent().parent().parent());
                    }else {
                        error.insertAfter(element.parent());
                    }
                }
            });
        });
        var $sigdiv = $("#signature");
        $(document).on('click','#reset',function () {
            $sigdiv.jSignature("reset");
        });
        $sigdiv.jSignature({'background-color': 'transparent',
            'decor-color': 'transparent' });
        $sigdiv.jSignature("reset");
        $("#signature").on('change', function(e) {
            $("#imgSig").html('');
            var datapair = $sigdiv.jSignature("getData", "image");
            var i = new Image();
            i.id = 'signatureImg';
            i.src = "data:" + datapair[0] + "," + datapair[1];
            $(i).appendTo($("#imgSig"));
        });



    });
</script>
<div id="imgSig" style="display: none;"></div>
<img id="dataImg" src="" style="border: 1px solid green;">
<div class="modal"></div>
</body>
</html>
