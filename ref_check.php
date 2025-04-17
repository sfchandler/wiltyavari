<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' && $_SESSION['userType'] != 'CONSULTANT') {
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$consId = getConsultantId($mysqli, $_SESSION['userSession']);
?>

<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php"; ?>
    <style>

        .error{
            color: red;
        }
        .invalid{
            color: red;
        }
        label{
            font-weight: normal;
            font-size: 11pt;
            color: #0c7cd5;
        }
        .outer-panel{
            margin: 0 auto;
            padding: 10px 50px 10px 50px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background: #FFFFFF;
            width: 80%;
        }
        /*body{
            background-image: url("img/subtle-stripes-pattern-2273.png");
            background-repeat: repeat;
        }*/
        .table th, .table td {
            border-top: none !important;
        }
        .h3box {
            margin:50px 0;
            padding:10px 10px;
            border:1px solid #eee;
            background:#f9f9f9;
        }

        * {
            -webkit-box-sizing:border-box;
            -moz-box-sizing:border-box;
            box-sizing:border-box;
        }

        *:before, *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .clearfix {
            clear:both;
        }

        .text-center {text-align:center;}

        .question{
            text-align: justify;
            padding-left: 0px;
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
            padding:20px 0;
            position:relative;
            margin-bottom:10px;

        }
        .header:after {
            content:"";
            display:block;
            height:1px;
            background:#eee;
            position:absolute;
            left:30%; right:30%;
        }
        .header h2 {
            font-size:3em;
            font-weight:300;
            margin-bottom:0.2em;
        }
        .header p {
            font-size:14px;
        }
        #a-footer {
            margin: 20px 0;
        }
        .new-react-version {
            padding: 20px 20px;
            border: 1px solid #eee;
            border-radius: 20px;
            box-shadow: 0 2px 12px 0 rgba(0,0,0,0.1);

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
        p.selection{
            padding-left: 20px;
        }
        .jb_desc{
            border: 1px solid black;
            width: 100%;
            height: 400px;
            overflow: scroll;
        }
        fieldset{
            border: none;

        }
        legend{
            color: #0c7cd5;
        }
        .compulsory{
            color: red;
        }
    </style>
</head>
<body>
<header id="header">
    <?php include "template/top_menu.php";
    if ($_REQUEST['error_msg'] <> '') {
        echo base64_decode($_REQUEST['error_msg']);
    } ?>
</header>
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
    </div>
    <!-- END RIBBON -->
    <!-- MAIN CONTENT -->
    <div id="content">
        <br><br>
        <div class="outer-panel">
            <br>
            <span style="text-align: center">
                <div class="h3box">
                    <img src="img/user-check.svg" width="25" height="25" alt="">
                    <span style="font-size: 16pt;padding-top: 5px;">&nbsp;REFERENCE CHECK</span>
                </div>
            </span>
            <div id="msg" class="msg error"></div>
            <div class="container-lg">
                <form name="frmRefChk" id="frmRefChk" action="" method="post" class="smart-form">
                    <div class="row">
                        <section class="col-lg-12">
                            <table class="table">
                                <tbody>
                                  <tr>
                                    <td>
                                        <label>Candidate Name: <?php echo getCandidateFullName($mysqli,$_REQUEST['canId']); ?></label>
                                        <input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo $_REQUEST['canId']; ?>">
                                        <input type="hidden" name="cons_id" id="cons_id" value="<?php echo $consId; ?>">
                                    </td>
                                    <td>
                                        <label for=""><span class="compulsory">*</span> Referee Name:</label>
                                        <input type="text" name="referee_name" id="referee_name" value="" class="form-control"/>
                                    </td>
                                  </tr>
                                  <tr>
                                      <td>
                                          <label><span class="compulsory">*</span> Company: </label>
                                          <input type="text" name="company_name" id="company_name" class="form-control"/>
                                      </td>
                                      <td>
                                          <label><span class="compulsory">*</span> Position Held(Important to Get):</label>
                                          <input type="text" name="position_held" id="position_held" value="" class="form-control"/>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td>
                                      </td>
                                      <td>
                                          <label for=""><span class="compulsory">*</span> Telephone Number:</label>
                                              <input type="text" name="phone_number" id="phone_number" value="" class="form-control compulsory"/>

                                      </td>
                                  </tr>
                                  <tr>
                                      <td>
                                      </td>
                                      <td>
                                          <label for=""><span class="compulsory">*</span> Referee Email:</label>
                                          <input type="text" name="referee_email" id="referee_email" value="" class="form-control compulsory"/>

                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2"><label for="">Questions</label></td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          1.	What was the name of the company that you worked with the applicant and what was your role?  What was the role of the candidate?
                                          <br>
                                          <textarea name="q1" id="q1" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          2.	Are you still working for that company?
                                          <br>
                                          <textarea name="q2" id="q2" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          3.	How long did you work with the applicant for and how long has the applicant worked in the company (Period of employment)?
                                          <br>
                                          <textarea name="q3" id="q3" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          4.	What were the main duties and responsibilities carried out by (him/her) in their role and please describe a typical day of work for the candidate?
                                          <br>
                                          <textarea name="q4" id="q4" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          5.	How would you describe (his/her) initiative/attitude on the job?
                                          <br>
                                          <textarea name="q5" id="q5" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          6.	How would you describe (his/her) attendance record, reliability, and honesty?
                                          <br>
                                          <textarea name="q6" id="q6" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          7.	Can you please describe (his/her) ability to work independently, as well as in a team?
                                          <br>
                                          <textarea name="q7" id="q7" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          8.	Are you aware of them having been involved in any conflict in the workplace?
                                          <br>
                                          <textarea name="q8" id="q8" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          9.	How does (he/she) handle fast-paced work/stressful situations?
                                          <br>
                                          <textarea name="q9" id="q9" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          10.	How would you assess (his/her) performance in the job? Consider:
                                          Strengths?
                                          Weaknesses?
                                          Key accomplishments?
                                          <br>
                                          <textarea name="q10" id="q10" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          11.	Why did (he/she) leave the company?
                                          <br>
                                          <textarea name="q11" id="q11" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          12.	Did he /she get injured at work? <br>
                                          If yes what was the nature of the injury??  Did the candidate return to work afterwards and if so how long was the candidate out of work
                                          <br>
                                          <textarea name="q12" id="q12" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          13. Are you currently using labour hire or are you looking for help with your current staffing requirements?
                                          <br>
                                          <textarea name="q13" id="q13" cols="30" rows="5" class="form-control"></textarea>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="2">
                                          <button type="submit" name="refCheckBtn" id="refCheckBtn" class="btn btn-lg btn-info">Submit</button>
                                      </td>
                                  </tr>
                                </tbody>
                              </table>
                        </section>
                        <section class="col-lg-12">

                        </section>
                    </div>
                </form>
                <div class="row">
                    <section class="col col-lg-12">
                        <br><br>
                    </section>
                </div>
            </div>
        </div>
        <br><br><br><br><br>
    </div>
    <!-- end content -->
</div>
<!-- PAGE FOOTER -->
<div class="page-footer">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <span class="txt-color-white"><?php echo DOMAIN_NAME; ?> <span class="hidden-xs"> - Employee Recruitment System</span> © <?php echo date('Y'); ?></span>
        </div>

        <div class="col-xs-6 col-sm-6 text-right hidden-xs">
            <div class="txt-color-white inline-block">

            </div>
        </div>
    </div>
</div>
<!-- END PAGE FOOTER -->

<?php include "./template/scr_scripts.php"; ?>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/additional-methods.js"></script>
<!-- JQUERY MASKED INPUT -->
<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
<!-- JQUERY FORM PLUGIN -->
<script src="js/jqueryform/jquery.form.js"></script>
<script type="text/javascript">
    $(function () {
        $body = $("body");
        $(document).on({
            ajaxStart: function () {
                $body.addClass("loading");
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });
        $(document).on('click', '#refCheckBtn', function (e) {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmRefChk = $("#frmRefChk").validate({
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
                    referee_name: {
                        required:true
                    },
                    referee_email:{
                        required:true
                    },
                    company_name: {
                        required:true
                    },
                    position_held:{
                        required:true
                    },
                    phone_number: {
                        required:true
                    },
                    q1: {
                        required: true
                    },
                    q2: {
                        required: true
                    },
                    q3: {
                        required: true,
                    },
                    q4: {
                        required: true
                    },
                    q5: {
                        required: true
                    },
                    q6: {
                        required: true
                    },
                    q7: {
                        required: true
                    },
                    q8: {
                        required: true
                    },
                    q9:{
                        required: true
                    },
                    q10:{
                        required: true
                    },
                    q11:{
                        required: true
                    },
                    q12:{
                        required: true
                    },
                    q13:{
                        required: true
                    }
                },
                messages: {

                },
                submitHandler: function (form) {
                    let candidate_id = $('#candidate_id').val();
                    let cons_id = $('#cons_id').val();
                    let referee_name = $('#referee_name').val();
                    let referee_email = $('#referee_email').val();
                    let company_name = $('#company_name').val();
                    let position_held = $('#position_held').val();
                    let phone_number = $('#phone_number').val();
                    let q1 = $('textarea#q1').val();
                    let q2 = $('textarea#q2').val();
                    let q3 = $('textarea#q3').val();
                    let q4 = $('textarea#q4').val();
                    let q5 = $('textarea#q5').val();
                    let q6 = $('textarea#q6').val();
                    let q7 = $('textarea#q7').val();
                    let q8 = $('textarea#q8').val();
                    let q9 = $('textarea#q9').val();
                    let q10 = $('textarea#q10').val();
                    let q11 = $('textarea#q11').val();
                    let q12 = $('textarea#q12').val();
                    let q13 = $('textarea#q13').val();
                    $.ajax({
                        type: "POST",
                        url: "./gen_ref_check_pdf.php",
                        data: {
                            candidate_id:candidate_id,
                            cons_id:cons_id,
                            referee_name:referee_name,
                            referee_email:referee_email,
                            company_name:company_name,
                            position_held:position_held,
                            phone_number:phone_number,
                            q1:q1,
                            q2:q2,
                            q3:q3,
                            q4:q4,
                            q5:q5,
                            q6:q6,
                            q7:q7,
                            q8:q8,
                            q9:q9,
                            q10:q10,
                            q11:q11,
                            q12:q12,
                            q13:q13
                        },
                        dataType: "text",
                        success: function (data) {
                            if(data === 'SUCCESS'){
                                $('.msg').html('');
                                $('.msg').html('Reference Check Submission successful');
                                $('html, body').animate({scrollTop: '0px'}, 300);
                                $('#refCheckBtn').hide();
                            }else{
                                $('.msg').html('');
                                $('.msg').html(data);
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
                },
                errorPlacement: function (error, element) {
                    //error.insertAfter(element.parent());
                    error.insertAfter(element);
                }
            });
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>