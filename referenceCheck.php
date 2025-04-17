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
            <img src="img/logo.png" width="220" height="50">
        </div>
        <br>
        <h3>
            <div>REFERENCE CHECK QUESTIONNAIRE</div>
            <div>LABOUR HIRE</div>
        </h3>
        <div id="msg" class="msg error"></div>
        <form id="frmRefForm" name="frmRefForm" method="post" class="smart-form">
            <div class="row">
                <section class="col col sm-6">
                    <br><br>
                    <table class="table table-responsive" style="width: 100%">
                        <tbody>
                        <tr>
                            <td>
                                <div><b>Candidate Name: </b><input type="text" name="candidate_name" id="candidate_name" value="<?php echo getCandidateFullName($mysqli,base64_decode($_REQUEST['id'])); ?>" class="form-control" readonly/></div></td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div><b>Company: </b><input type="text" name="company_name" id="company_name" value="" class="form-control" /></div></td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div><b>Referee Name: </b><input type="text" name="referee_name" id="referee_name" value="<?php echo $_REQUEST['refName']; ?>" class="form-control" readonly/></div></td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div><b>Position Held: </b><input type="text" name="position" id="position" value="" class="form-control" /></div></td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div><b>Telephone Number: </b><input type="text" name="phone_number" id="phone_number" value="" class="form-control" /></div>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                                <td colspan="2"><b>QUESTIONS</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">1.	In what capacity have you worked with the applicant? What was your role?  What was the role of the candidate?</td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <div><textarea name="work_capacity" id="work_capacity" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">2.	What was your role?</td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <div><textarea name="your_work_role" id="your_work_role" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">3.  What was the role of the candidate?</td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <div><textarea name="candidate_role" id="candidate_role" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">4.	How long have you worked with the applicant?
                                <div><textarea name="work_period" id="work_period" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">5.	What were the main duties and responsibilities carried out by (him/her) in their role?
                                <div><textarea name="duties" id="duties" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">6.	How would you describe (his/her) initiative on the job?
                                <div><textarea name="initiative" id="initiative" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">7.	Can you please describe (his/her) ability to work independently, as well as in a team?
                                <div><textarea name="work_independent" id="work_independent" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">8.	Are you aware of them having been involved in any conflict in the workplace?
                                <div><textarea name="conflict" id="conflict" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">9.	How does (he/she) handle fast-paced work, stress and conflicts?
                                <div><textarea name="fast_paced_work" id="fast_paced_work" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">10.	How would you assess (his/her) performance in the job? Consider:
                                <ul>
                                    <li>Strengths</li>
                                    <li>Weaknesses?</li>
                                    <li>Key accomplishments?</li>
                                </ul>
                                <div><textarea name="performance" id="performance" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">11.	Why did (he/she) leave the company?
                                <div><textarea name="leave_company" id="leave_company" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">12.	How would you describe (his/her) overall attitude to work?
                                <div><textarea name="overall_attitude" id="overall_attitude" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">13. How would you describe (his/her) attendance record, reliability, and honesty?
                                <div><textarea name="attendance_record" id="attendance_record" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">14.	Did (he/she) ever claim WorkCover while employed with you? Did they have any medical conditions which restricted their ability to work?
                                <div><textarea name="work_cover" id="work_cover" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea></div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="hidden" name="id" id="id" value="<?php echo base64_decode($_REQUEST['id']); ?>"/>
                                <input type="hidden" name="conEmail" id="conEmail" value="<?php echo base64_decode($_REQUEST['conEmail']); ?>"/>
                            </td>
                        </tr>
                        </tbody>
                    </table>
            </section>
            </div>
            <br>

            <div class="row">
                <section class="col col-sm-8">
                    I hereby declare that the information provided here is correct and accurate.
                    <br>
                    <br>
                    <b>Signature</b><span style="padding-left: 330px;"><b>Date:</b>&nbsp;<?php echo date('d/m/Y');?></span>
                    <div id="signature"></div>
                </section>
            </div>
            <div class="row">
                <section class="col col-sm-8">
                    <br>
                    <button id="refBtn" class="refBtn btn-success btn-lg">Submit</button>
                    <br>
                    <div id="msg" class="msg error"></div>
                    <br>
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

        $(document).on('click','#refBtn',function (e) {
            var errorClass = 'invalid';
            var errorElement = 'div';
            var frmRefForm = $('#frmRefForm').validate({
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
                    company_name:{
                        required:true
                    },
                    referee_name:{
                        required: true
                    },
                    position:{
                        required:true
                    },
                    phone_number:{
                        required:true
                    },
                    work_capacity:{
                        required:true
                    },
                    work_period:{
                        required:true
                    },
                    duties:{
                        required:true
                    },
                    initiative:{
                        required:true
                    },
                    work_independent:{
                        required:true
                    },
                    conflict:{
                        required:true
                    },
                    fast_paced_work:{
                        required:true
                    },
                    performance:{
                        required:true
                    },
                    leave_company:{
                        required:true
                    },
                    overall_attitude:{
                        required:true
                    },
                    attendance_record:{
                        required:true
                    },
                    work_cover:{
                        required:true
                    },
                },
                messages:{
                    company_name:{
                        required:"Please enter company name"
                    },
                    referee_name:{
                        required:"Please enter referee name"
                    },
                    position:{
                        required:"Please enter position name"
                    },
                    phone_number:{
                        required:"Please enter phone number"
                    }
                },
                submitHandler: function (form) {
                    if ($sigdiv.jSignature('getData', 'native').length == 0) {
                        alert('Please Enter Signature!');
                    }else{
                        var candidate_name = $.base64.encode($('#candidate_name').val());
                        var referee_name = $.base64.encode($('#referee_name').val());
                        var company_name  = $.base64.encode($('#company_name').val());
                        var position  = $.base64.encode($('#position').val());
                        var phone_number = $.base64.encode($('#phone_number').val());
                        var work_capacity = $.base64.encode($('textarea#work_capacity').val());
                        var work_period = $.base64.encode($('textarea#work_period').val());
                        var duties = $.base64.encode($('textarea#duties').val());
                        var initiative = $.base64.encode($('textarea#initiative').val());
                        var work_independent = $.base64.encode($('textarea#work_independent').val());
                        var conflict = $.base64.encode($('textarea#conflict').val());
                        var fast_paced_work = $.base64.encode($('textarea#fast_paced_work').val());
                        var performance = $.base64.encode($('textarea#performance').val());
                        var leave_company = $.base64.encode($('textarea#leave_company').val());
                        var overall_attitude = $.base64.encode($('textarea#overall_attitude').val());
                        var attendance_record = $.base64.encode($('textarea#attendance_record').val());
                        var work_cover = $.base64.encode($('textarea#work_cover').val());
                        var id = $.base64.encode($('#id').val());
                        var conEmail = $.base64.encode($('#conEmail').val());
                        var imageSrc = $("#signatureImg").attr('src');
                        $.ajax({
                            url:"./processReferenceCheck.php",
                            type:'POST',
                            dataType:'text',
                            data:{
                                candidate_name:candidate_name,
                                company_name:company_name,
                                referee_name:referee_name,
                                position:position,
                                phone_number:phone_number,
                                work_capacity:work_capacity,
                                work_period:work_period,
                                duties:duties,
                                initiative:initiative,
                                work_independent:work_independent,
                                conflict:conflict,
                                fast_paced_work:fast_paced_work,
                                performance:performance,
                                leave_company:leave_company,
                                overall_attitude:overall_attitude,
                                attendance_record:attendance_record,
                                work_cover:work_cover,
                                id:id,
                                conEmail:conEmail,
                                imageSrc:imageSrc
                            },
                            success: function (data) {
                                console.log('data.....'+data);
                                if(data == 'SUCCESS'){
                                    $('#ohsBtn').hide();
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
                    }
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