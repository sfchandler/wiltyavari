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
    <style>
        .table-scroll{
            /*width:100%; */
            display: block;
            empty-cells: show;

            /* Decoration
            border-spacing: 0;
            border: 1px solid;*/
        }

        .table-scroll thead{
            background-color: #f1f1f1;
            position:relative;
            display: block;
            width:100%;
            /*overflow-y: scroll;*/
        }

        .table-scroll tbody{
            /* Position */
            display: block; position:relative;
            width:100%; overflow-y:scroll;
            /* Decoration */
            border-top: 1px solid rgba(0,0,0,0.2);
        }

        .table-scroll tr{
            width: 100%;
            display:flex;
        }

        .table-scroll td,.table-scroll th{
            flex-basis:100%;
            flex-grow:2;
            display: block;
            padding: 1rem;
            text-align:left;
        }

        /* Other options */

        .table-scroll.small-first-col td:first-child,
        .table-scroll.small-first-col th:first-child{
            flex-basis:20%;
            flex-grow:1;
        }

        .table-scroll tbody tr:nth-child(2n){
            background-color: rgba(130,130,170,0.1);
        }

        .body-half-screen{
            max-height: 100vh;
        }

        .small-col{flex-basis:10%;}
    </style>
</head>
<body>
<!-- HEADER -->
<header id="header">
    <?php include "template/top_menu.php"; ?>
</header>
<!-- END HEADER -->
<aside id="left-panel">
    <div class="login-info">
        <?php include "template/user_info.php"; ?>
    </div>
    <?php include "template/navigation.php"; ?>
    <span class="minifyme" data-action="minifyMenu">
		<i class="fa fa-arrow-circle-left hit"></i>
	</span>
</aside>
<!-- END NAVIGATION -->
<!-- MAIN PANEL -->
<div id="main" role="main">
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body" style="min-height: 1080px; overflow: scroll">
        <div class="error" style="padding-left: 25px;"></div>
        <div style="width: 100%">
            <div style="float: left; width:60%;">
                <form id="frmHireRates" method="post" class="smart-form">
                    <fieldset>
                        <legend><h2>Labour Hire Rates</h2></legend>
                        <div class="row">
                            <section class="col col-3">
                                <label for="client"><b>CLIENT</b>
                                    <input type="text" name="client" id="client" size="100" class="form-control">
                                </label>
                            </section>
                            <section class="col col-6">
                                <input type="hidden" name="clientLogo" id="clientLogo">
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-3">
                                <label for="award"><b>AWARD</b>
                                    <select name="award" id="award" style="background: #fff; cursor: pointer;" class="form-control">
                                        <?php echo getAwardsList($mysqli); ?>
                                    </select>
                                </label>
                            </section>
                            <section class="col col-6">
                                <label for="breakdown"><b>BREAKDOWN</b>
                                    <select name="breakdown" id="breakdown" style="background: #fff; cursor: pointer;" class="form-control">
                                        <option value="FULL BREAKDOWN">FULL BREAKDOWN</option>
                                        <option value="CHARGE RATE">CHARGE RATE</option>
                                    </select>
                                </label>
                            </section>
                        </div>
                        <div class="row" style="padding-left: 5px;">
                            <table class="table table-striped table-bordered table-responsive" style="width: 60%">
                                <thead>
                                    <th></th>
                                    <th>
                                        <label for="position"><b>POSITION</b>
                                            <input type="text" name="position" id="position" class="form-control">
                                        </label>
                                    </th>
                                    <th>
                                        <label for="position2"><b>POSITION</b>
                                            <input type="text" name="position2" id="position2" class="form-control">
                                        </label>
                                    </th>
                                    <th>
                                        <label for="position3"><b>POSITION</b>
                                            <input type="text" name="position3" id="position3" class="form-control">
                                        </label>
                                    </th>
                                    <th>
                                        <label for="position4"><b>POSITION</b>
                                            <input type="text" name="position4" id="position4" class="form-control">
                                        </label>
                                    </th>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td class="col-sm-1"><label for="hourly_rate">Hourly Rate</label></td>
                                    <td class="col-sm-2"><input type="text" name="hourly_rate" id="hourly_rate" value="" class="form-check-input" size="5" required/></td>
                                    <td class="col-sm-2"><input type="text" name="hourly_rate2" id="hourly_rate2" value="" class="form-check-input" size="5" /></td>
                                    <td class="col-sm-2"><input type="text" name="hourly_rate3" id="hourly_rate3" value="" class="form-check-input" size="5" /></td>
                                    <td class="col-sm-2"><input type="text" name="hourly_rate4" id="hourly_rate4" value="" class="form-check-input" size="5" /></td>
                                  </tr>
                                  <tr>
                                    <td class="col-sm-1"><label for="superannuation">Superannuation  e.g. 0.10 - 10%</label></td>
                                    <td class="col-sm-2"><input type="text" name="superannuation" id="superannuation" value="" class="form-check-input" size="5" required/></td>
                                    <td class="col-sm-2"><input type="text" name="superannuation2" id="superannuation2" value="" class="form-check-input" size="5" /></td>
                                    <td class="col-sm-2"><input type="text" name="superannuation3" id="superannuation3" value="" class="form-check-input" size="5" /></td>
                                    <td class="col-sm-2"><input type="text" name="superannuation4" id="superannuation4" value="" class="form-check-input" size="5" /></td>
                                  </tr>
                                  <tr>
                                    <td class="col-sm-1"><label for="payroll_tax">Payroll Tax  e.g. 0.0485 - 4.85%</label></td>
                                    <td class="col-sm-2"><input type="text" name="payroll_tax" id="payroll_tax" value="" class="form-check-input" size="5" required/></td>
                                    <td class="col-sm-2"><input type="text" name="payroll_tax2" id="payroll_tax2" value="" class="form-check-input" size="5" /></td>
                                    <td class="col-sm-2"><input type="text" name="payroll_tax3" id="payroll_tax3" value="" class="form-check-input" size="5" /></td>
                                    <td class="col-sm-2"><input type="text" name="payroll_tax4" id="payroll_tax4" value="" class="form-check-input" size="5" /></td>
                                  </tr>
                                  <tr>
                                      <td class="col-sm-1"><label for="mhws">MHWS</label></td>
                                      <td class="col-sm-2"><input type="text" name="mhws" id="mhws" value="" class="form-check-input" size="5" required/></td>
                                      <td class="col-sm-2"><input type="text" name="mhws2" id="mhws2" value="" class="form-check-input" size="5" /></td>
                                      <td class="col-sm-2"><input type="text" name="mhws3" id="mhws3" value="" class="form-check-input" size="5" /></td>
                                      <td class="col-sm-2"><input type="text" name="mhws4" id="mhws4" value="" class="form-check-input" size="5" /></td>
                                  </tr>
                                  <tr>
                                      <td class="col-sm-1"><label for="workcover">WorkCover</label></td>
                                      <td class="col-sm-2"><input type="text" name="workcover" id="workcover" value="" class="form-check-input" size="5" required/></td>
                                      <td class="col-sm-2"><input type="text" name="workcover2" id="workcover2" value="" class="form-check-input" size="5"/></td>
                                      <td class="col-sm-2"><input type="text" name="workcover3" id="workcover3" value="" class="form-check-input" size="5"/></td>
                                      <td class="col-sm-2"><input type="text" name="workcover4" id="workcover4" value="" class="form-check-input" size="5"/></td>
                                  </tr>
                                  <tr>
                                      <td class="col-sm-1"><label for="margin">Margin/Placement Fee</label></td>
                                      <td class="col-sm-2"><input type="text" name="margin" id="margin" value="3.50" class="form-check-input" size="5" required/></td>
                                      <td class="col-sm-2"><input type="text" name="margin2" id="margin2" value="3.50" class="form-check-input" size="5" /></td>
                                      <td class="col-sm-2"><input type="text" name="margin3" id="margin3" value="3.50" class="form-check-input" size="5" /></td>
                                      <td class="col-sm-2"><input type="text" name="margin4" id="margin4" value="3.50" class="form-check-input" size="5" /></td>
                                  </tr>
                                  <tr>
                                      <td class="col-sm-1"><label for="payment_terms">Payment Terms</label></td>
                                      <td colspan="4"><select name="payment_terms" id="payment_terms" class="form-control">
                                              <option value="7">7 Days from Invoice date</option>
                                              <option value="14">14 Days from Invoice date</option>
                                              <option value="21">21 Days from Invoice date</option>
                                              <option value="30">30 Days from Invoice date</option>
                                          </select>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan=5" align="right">
                                          <button name="viewBtn" id="viewBtn" class="btn btn-lg btn-primary">View/Generate Rates</button>
                                      </td>
                                  </tr>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div style="float: right; width: 40%;padding-right: 5px;">
                <form name="frmSend" id="frmSend" action="" method="post" enctype="multipart/form-data">
                <table class="table table-bordered">
                    <tbody>
                      <tr>
                          <td style="width: 25%">
                              Client Logo:
                              <form name="frmLogo" id="frmLogo" method="post" enctype="multipart/form-data">
                                  <input type="file" name="client_logo" id="client_logo" class="btn btn btn-default" style="display: block">
                                  <br>
                                  <button name="logoBtn" id="logoBtn" class="btn btn-lg btn-success"><i class="glyphicon glyphicon-open-file"></i> Attach Logo</button>
                              </form>
                          </td>
                          <td><img id="logoImg" src="" alt=""></td>
                      </tr>
                      <tr>
                        <td>Client Email Address<span style="color: red; font-weight: bold"> * </span></td>
                        <td>
                            <input type="email" name="client_email" id="client_email" class="form-control" required>
                            <input type="hidden" name="ratesFile" id="ratesFile"><div id="ratesFileLink"></div>
                        </td>
                      </tr>
                      <tr>
                          <td>Attachments</td>
                          <td><input type="file" name="proof1" class="form-control-file"/>&nbsp;<input type="file" name="proof2" class="form-control-file"/>&nbsp;<input type="file" name="proof3" class="form-control-file"/></td>
                      </tr>
                      <tr>
                          <td colspan="2">Email Body <span style="color: red; font-weight: bold"> * </span>
                              <textarea name="email_body" id="email_body" class="form-control" required></textarea><span class="email_body_error" style="color: red"></span>
                          </td>
                      </tr>
                      <tr>
                          <td></td>
                          <td><button class="btn btn-lg btn-primary" name="sendRatesBtn" id="sendRatesBtn"><i class="glyphicon glyphicon-send"></i>&nbsp;&nbsp;Send Client Rates</button></td>
                      </tr>
                    </tbody>
                  </table>
                </form>
            </div>
        </div>
        <div style="clear: both"></div>
        <div style="height: 450px; overflow-y: scroll">
            <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Client</th>
                    <th>Positions</th>
                    <th>Award</th>
                    <th>Email</th>
                    <th>Rates File</th>
                    <th>Date Sent</th>
                    <th>IP/Location</th>
                    <th>Signed Hire Rates File</th>
                    <th>Signed/Submit Date</th>
                  </tr>
                </thead>
                <tbody class="display_hire_rates body-half-screen">

                </tbody>
              </table>
        </div>
        <br><br>
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<?php include "template/footer.php"; ?>
<?php include "template/scripts.php"; ?>
<!-- DATE RANGE PICKER -->
<script type="text/javascript" src="js/daterangepicker/moment.js"></script>
<script type="text/javascript" src="js/daterangepicker/daterangepicker.js"></script>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/additional-methods.js"></script>
<!-- JQUERY MASKED INPUT -->
<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
<!-- JQUERY FORM PLUGIN -->
<script src="js/jqueryform/jquery.form.js"></script>
<!-- TINYMCE PLUGIN -->
<script src='js/tinymce/js/tinymce/tinymce.min.js'></script>
<script>
    $(document).ready(function(){
        $body = $("body");
        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
        /*$('input').on('keypress', function (event) {
            var regex = new RegExp("^$|^[a-zA-Z0-9]+$");
            var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });*/
        tinymce.init({
            selector: '#email_body',
            height: 200,
            theme: 'modern',
            plugins: '',
            toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
            image_advtab: true,
            templates: [
                { title: 'Test template 1', content: 'Test 1' },
                { title: 'Test template 2', content: 'Test 2' }
            ],
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ],
            branding: false
        });
        $('#viewBtn').hide();
        $('#sendRateBtn').hide();
        displayHireRates();
        function displayHireRates(){
            let action = 'DISPLAY';
            $.ajax({
                type: 'post',
                crossDomain: true,
                url: 'hireRateProcessing.php',
                dataType: "html",
                data:{action:action},
                success: function (data) {
                    $('.display_hire_rates').html('');
                    $('.display_hire_rates').html(data);
                }
            });
        }
        $(document).on('click','#viewBtn',function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmHireRates = $("#frmHireRates").validate({
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
                    client: {
                        required:true
                    },
                    position: {
                        required:true
                    },
                    award:{
                        required:true
                    },
                    breakdown:{
                        required:true
                    },
                    hourly_rate:{
                        required:true
                    },
                    superannuation:{
                        required:true
                    },
                    payroll_tax:{
                        required:true
                    },
                    mhws:{
                        required:true
                    },
                    workcover:{
                        required:true
                    },
                    margin:{
                        required:true
                    },
                    payment_terms:{
                        required:true
                    },
                    clientLogo:{
                        required:true
                    },
                },
                messages: {
                    client:{
                        required: "Please select client"
                    },
                    position:{
                        required: "Please select position"
                    },
                    award:{
                        required: "Please select Award"
                    },
                    breakdown:{
                        required: "Please select breakdown method"
                    },
                    hourly_rate:{
                        required: "Please enter hourly rate"
                    },
                    superannuation:{
                        required: "Please enter superannuation percentage"
                    },
                    payroll_tax:{
                        required: "Please enter payroll tax percentage"
                    },
                    mhws:{
                        required: "Please enter Mental health & Wellbeing Support percentage"
                    },
                    workcover:{
                        required: "Please enter work cover percentage"
                    },
                    margin:{
                        required: "Please enter Placement Fee"
                    },
                    payment_terms:{
                        required: "Please select payment terms"
                    },
                    clientLogo: {
                        required: "Please attach the client logo"
                    }
                },
                submitHandler: function (form) {
                    let client = $('#client').val();
                    let position = $('#position').val();
                    let position2 = $('#position2').val();
                    let position3 = $('#position3').val();
                    let position4 = $('#position4').val();
                    let award = $('#award :selected').val();
                    let breakdown = $('#breakdown :selected').val();
                    let hourly_rate = $('#hourly_rate').val();
                    let superannuation = $('#superannuation').val();
                    let payroll_tax = $('#payroll_tax').val();
                    let mhws = $('#mhws').val();
                    let workcover = $('#workcover').val();
                    let margin = $('#margin').val();

                    let hourly_rate2 = $('#hourly_rate2').val();
                    let superannuation2 = $('#superannuation2').val();
                    let payroll_tax2 = $('#payroll_tax2').val();
                    let mhws2 = $('#mhws2').val();
                    let workcover2 = $('#workcover2').val();
                    let margin2 = $('#margin2').val();

                    let hourly_rate3 = $('#hourly_rate3').val();
                    let superannuation3 = $('#superannuation3').val();
                    let payroll_tax3 = $('#payroll_tax3').val();
                    let mhws3 = $('#mhws3').val();
                    let workcover3 = $('#workcover3').val();
                    let margin3 = $('#margin3').val();

                    let hourly_rate4 = $('#hourly_rate4').val();
                    let superannuation4 = $('#superannuation4').val();
                    let payroll_tax4 = $('#payroll_tax4').val();
                    let mhws4 = $('#mhws4').val();
                    let workcover4 = $('#workcover4').val();
                    let margin4 = $('#margin4').val();

                    let payment_terms = $('#payment_terms :selected').val();
                    let client_logo = $('#clientLogo').val();
                    $.ajax({
                        type: 'post',
                        crossDomain: true,
                        url: 'generateHireRate.php',
                        dataType: "html",
                        data: {client:client,
                            position:position,
                            position2:position2,
                            position3:position3,
                            position4:position4,
                            award:award,
                            breakdown:breakdown,
                            hourly_rate:hourly_rate,
                            superannuation:superannuation,
                            payroll_tax:payroll_tax,
                            mhws:mhws,
                            workcover:workcover,
                            margin:margin,
                            hourly_rate2:hourly_rate2,
                            superannuation2:superannuation2,
                            payroll_tax2:payroll_tax2,
                            mhws2:mhws2,
                            workcover2:workcover2,
                            margin2:margin2,
                            hourly_rate3:hourly_rate3,
                            superannuation3:superannuation3,
                            payroll_tax3:payroll_tax3,
                            mhws3:mhws3,
                            workcover3:workcover3,
                            margin3:margin3,
                            hourly_rate4:hourly_rate4,
                            superannuation4:superannuation4,
                            payroll_tax4:payroll_tax4,
                            mhws4:mhws4,
                            workcover4:workcover4,
                            margin4:margin4,
                            payment_terms:payment_terms,
                            client_logo:client_logo},
                        success: function (data) {
                            $('#sendRatesBtn').show();
                            $('#ratesFile').val(data);
                            $('#ratesFileLink').html('<a href="'+data+'" class="btn btn-danger btn-link" style="text-decoration: none; cursor: pointer" target="_blank"><i class="fa fa-file-pdf-o"></i>&nbsp; Attached rates File</a>');
                            window.open(data);
                        }
                    });
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        $(document).on('click','#logoBtn',function (e){
            e.preventDefault();
            var fd = new FormData();
            var files = $('#client_logo')[0].files[0];
            var action = 'LOGO';
            fd.append('file', files);
            fd.append('action',action);
            $.ajax({
                url: 'hireRateProcessing.php',
                type: 'post',
                data: fd,
                contentType: false,
                processData: false,
                success: function(response){
                    if(response != 0){
                        console.log(response);
                        $('#logoImg').attr('src',response);
                        $('#clientLogo').val(response);
                        $('#viewBtn').show();
                    }
                    else{
                        console.log(response);
                    }
                },
            });
        });
        function generateFullRatesToArchive(){
            let client = $('#client').val();
            let position = $('#position').val();
            let position2 = $('#position2').val();
            let position3 = $('#position3').val();
            let position4 = $('#position4').val();
            let award = $('#award :selected').val();
            let breakdown = 'FULL BREAKDOWN';
            let hourly_rate = $('#hourly_rate').val();
            let superannuation = $('#superannuation').val();
            let payroll_tax = $('#payroll_tax').val();
            let mhws = $('#mhws').val();
            let workcover = $('#workcover').val();
            let margin = $('#margin').val();

            let hourly_rate2 = $('#hourly_rate2').val();
            let superannuation2 = $('#superannuation2').val();
            let payroll_tax2 = $('#payroll_tax2').val();
            let mhws2 = $('#mhws2').val();
            let workcover2 = $('#workcover2').val();
            let margin2 = $('#margin2').val();

            let hourly_rate3 = $('#hourly_rate3').val();
            let superannuation3 = $('#superannuation3').val();
            let payroll_tax3 = $('#payroll_tax3').val();
            let mhws3 = $('#mhws3').val();
            let workcover3 = $('#workcover3').val();
            let margin3 = $('#margin3').val();

            let hourly_rate4 = $('#hourly_rate4').val();
            let superannuation4 = $('#superannuation4').val();
            let payroll_tax4 = $('#payroll_tax4').val();
            let mhws4 = $('#mhws4').val();
            let workcover4 = $('#workcover4').val();
            let margin4 = $('#margin4').val();

            let payment_terms = $('#payment_terms :selected').val();
            let client_logo = $('#clientLogo').val();
            $.ajax({
                type: 'post',
                crossDomain: true,
                url: 'generateHireRate.php',
                dataType: "html",
                data: {client:client,
                    position:position,
                    position2:position2,
                    position3:position3,
                    position4:position4,
                    award:award,
                    breakdown:breakdown,
                    hourly_rate:hourly_rate,
                    superannuation:superannuation,
                    payroll_tax:payroll_tax,
                    mhws:mhws,
                    workcover:workcover,
                    margin:margin,
                    hourly_rate2:hourly_rate2,
                    superannuation2:superannuation2,
                    payroll_tax2:payroll_tax2,
                    mhws2:mhws2,
                    workcover2:workcover2,
                    margin2:margin2,
                    hourly_rate3:hourly_rate3,
                    superannuation3:superannuation3,
                    payroll_tax3:payroll_tax3,
                    mhws3:mhws3,
                    workcover3:workcover3,
                    margin3:margin3,
                    hourly_rate4:hourly_rate4,
                    superannuation4:superannuation4,
                    payroll_tax4:payroll_tax4,
                    mhws4:mhws4,
                    workcover4:workcover4,
                    margin4:margin4,
                    payment_terms:payment_terms,
                    client_logo:client_logo},
                success: function (data) {

                }
            });
        }
        $(document).on('click','#sendRatesBtn',function (){
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmSend = $("#frmSend").validate({
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
                    client_email: {
                        required: true
                    },
                    email_body: {
                        required: true
                    },
                },
                messages: {
                    client_email: {
                        required: "Please enter client email address"
                    },
                    email_body: {
                        required: "Please enter email body text"
                    },
                },
                submitHandler: function (form) {
                    let client_email = $('#client_email').val();
                    let rates_file = $('#ratesFile').val();
                    let client = $('#client').val();
                    let position = $('#position').val();
                    let position2 = $('#position2').val();
                    let position3 = $('#position3').val();
                    let position4 = $('#position4').val();
                    let award = $('#award :selected').val();
                    let proof1 = $('#proof1').val();
                    let proof2 = $('#proof2').val();
                    let proof3 = $('#proof3').val();
                    let payment_terms = $('#payment_terms :selected').val();
                    let email_body = tinyMCE.get('email_body').getContent();
                    let action = 'SENDRATES';

                    var formData = new FormData(form);
                    formData.append('client_email', client_email);
                    formData.append('email_body', email_body);
                    formData.append('rates_file', rates_file);
                    formData.append('client', client);
                    formData.append('position', position);
                    formData.append('position2', position2);
                    formData.append('position3', position3);
                    formData.append('position4', position4);
                    formData.append('award', award);
                    formData.append('action', action);
                    formData.append('proof1', proof1);
                    formData.append('proof2', proof2);
                    formData.append('proof3', proof3);
                    formData.append('payment_terms',payment_terms);

                    if($('#email_body_ifr').contents().find('body').text().trim().length == 0){
                        $('.email_body_error').html('Please enter email body text');
                    }else {
                        $('.email_body_error').html('');
                        $.ajax({
                            url: 'hireRateProcessing.php',
                            type: 'post',
                            dataType: "html",
                            data: formData,
                            mimeType: "multipart/form-data",
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                if (response != 0) {
                                    if (response == 'MAILSENT') {
                                        $('.error').html('Email Sent Successfully');
                                    } else {
                                        $('.error').html('Error Sending email');
                                    }
                                } else {
                                    console.log(response);
                                }
                                displayHireRates();
                            },
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