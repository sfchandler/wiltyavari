<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
$useragent=$_SERVER['HTTP_USER_AGENT'];
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if(validateHandbookSigned($mysqli,base64_decode($_REQUEST['candidateId']))){
    $msg = "Handbook Signed submitted";
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
    <title><?php echo DOMAIN_NAME; ?>EMPLOYEE HANDBOOK</title>
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
            <img src="img/logo.png" width="220" height="50">
        </div>
        <br><br>
        <div id="msg" class="msg error"></div>
        <div id="adobe-dc-view" style="height: 500px; width: 100%;"></div>
        <script src="https://documentcloud.adobe.com/view-sdk/main.js"></script>
        <script type="text/javascript">
            const urlParams = new URLSearchParams(window.location.search);
            const filePath = 'docform/EmployeeHandbook.pdf';
            const previewConfig = {
                showDownloadPDF: false,
                showPageControls: true,
                showAnnotationTools: false,
                embedMode: "SIZED_CONTAINER",
                showFullScreen: true
            }
            document.addEventListener("adobe_dc_view_sdk.ready", function(){
                var adobeDCView = new AdobeDC.View({clientId: "82e2acebec804744904e8d0fc6760e10", divId: "adobe-dc-view"});
                adobeDCView.previewFile({
                    content:{ location:{ url: filePath }},
                    metaData:{ fileName: 'Employee Handbook' }
                },previewConfig);
            });
        </script>
        <br><br>
        <div>
            <div style="width: 600px;padding-left: 50px">
                <div class="row">
                    <section class="col-lg-4"></section>
                    <section class="col-lg-4"><a class="btn btn-info" href="docform/EmployeeHandbook.pdf" target="_blank">View Employee Handbook</a></section>
                    <section class="col-lg-4"></section>
                </div>
                <br><br>
                <form name="frmConfForm" id="frmConfForm" method="post">
                    <br><br>
                    <label>Signature</label>
                    <table style="width: 350px;">
                        <tbody>
                        <tr>
                            <td><div id="signature" style="width: 100%; background: lightgrey"></div></td>
                        </tr>
                        </tbody>
                    </table>
                    <?php echo getCandidateFullName($mysqli,$_REQUEST['candidateId']); ?>
                    <br>
                    <?php echo date('d/m/Y'); ?>
                    <br>
                    <input type="hidden" name="canId" id="canId" value="<?php echo $_REQUEST['candidateId']; ?>">
                    <input type="hidden" name="conEmail" id="conEmail" value="<?php echo $_REQUEST['conEmail']; ?>">
                    <button type="submit" id="confSubmitBtn" class="confSubmitBtn btn-success btn-lg">SUBMIT</button>
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
        $(document).on('click', '#confSubmitBtn', function (e) {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmConfForm = $('#frmConfForm').validate({
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
                rules: {

                },
                messages: {

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
                            url: "./signEmployeeHandbook.php",
                            data: {
                                imageSrc: imageSrc,
                                canId: canId,
                                conEmail: conEmail,
                                action: action
                            },
                            dataType: "text",
                            success: function (data) {
                                if (data == 'SUCCESS') {
                                    $('.msg').html('');
                                    $('.msg').html('Handbook Signed Successfully');
                                    $('html, body').animate({scrollTop: '0px'}, 300);
                                    location.reload();
                                } else {
                                    $('.msg').html('');
                                    $('.msg').html('Error signing handbook');
                                    $('html, body').animate({scrollTop: '0px'}, 300);
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
                    }else { // This is the default behavior of the script for all fields
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
