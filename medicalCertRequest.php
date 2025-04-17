<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
$useragent=$_SERVER['HTTP_USER_AGENT'];
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
        .invalid{
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
            margin: 0 auto;
            padding: 10px 100px 10px 100px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background: #FFFFFF;
            width: 90%;
        }
        body{
            background-image: url("img/subtle-stripes-pattern-2273.png");
            background-repeat: repeat;
        }
        .table th, .table td {
            border-top: none !important;
        }
    </style>
</head>
<body>
<div class="container">
    <br><br>
    <div class="sign-panel">
        <br><br>
        <div>
            <img src="img/logo.png" width="216" height="53">
        </div>
        <br>
        <h3>SICK LEAVE - MEDICAL CERTIFICATE UPLOAD</h3>
        <div id="msg" class="msg error"></div>
        <form id="frmMedForm" name="frmMedForm" method="post" class="smart-form" enctype="multipart/form-data">
            <div class="row">
                <section class="col col sm-6">
                    <br><br>
                    <table class="table table-responsive" style="width: 50%">
                        <tbody>
                        <tr>
                            <td>
                                <b>Candidate Name: </b>
                                <input type="text" name="candidate_name" id="candidate_name" value="<?php echo base64_decode($_REQUEST['fullName']); ?>" class="form-control" readonly/>
                            </td>
                            <td>
                                <input type="hidden" name="shId" id="shId" value="<?php echo $_REQUEST['shid']; ?>">
                                <input type="hidden" name="canId" id="canId" value="<?php echo $_REQUEST['canId']; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="file" name="medCertFile" id="medCertFile" class="medicalCert form-control"/>
                                <br>
                                <button id="medCertBtn" type="submit" class="medCertBtn btn-success btn-lg">Submit</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </section>
        </form>
    </div>
    <br><br>
</div>
<br><br><br>
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

        $(document).on('click','.medCertBtn',function (e) {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmMedForm = $('#frmMedForm').validate({
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
                        medicalCert:{
                            required:true
                        }
                    },
                    messages:{
                        medicalCert:{
                            required: "Please select file to upload"
                        }
                    },
                    submitHandler: function (form) {
                        var formData = new FormData(form);
                        formData.append('file', $('#medCertFile')[0].files[0]);
                        formData.append('canId', $('#canId').val());
                        formData.append('notes', $('#shId').val());
                        $.ajax({
                            url:"./medCertUpload.php",
                            type:'POST',
                            dataType:'text',
                            data: formData,
                            mimeType: "multipart/form-data",
                            contentType: false,
                            processData: false,
                            success: function (data) {
                                if(data == 'SUCCESS'){
                                    $('#medCertBtn').hide();
                                    $('.msg').html('');
                                    $('.msg').html('Submission Successful');
                                    $('html, body').animate({scrollTop: '0px'}, 300);
                                }else{
                                    $('.msg').html('');
                                    $('.msg').html('!! Submission Unsuccessful');
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
                    },
                    errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                    }
            });
        });
    });
</script>
<div class="modal"></div>
</body>
</html>