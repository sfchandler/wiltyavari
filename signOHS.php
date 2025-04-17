<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
$useragent=$_SERVER['HTTP_USER_AGENT'];
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
//echo base64_decode($_REQUEST['id']).' CL '.base64_decode($_REQUEST['clientId']).' ST '.base64_decode($_REQUEST['stateId']).' DP '.base64_decode($_REQUEST['deptId']).' POS'.base64_decode($_REQUEST['positionId']);
if(validateDocumentSubmission($mysqli,base64_decode($_REQUEST['id']),base64_decode($_REQUEST['clientId']),base64_decode($_REQUEST['stateId']),base64_decode($_REQUEST['deptId']),base64_decode($_REQUEST['positionId']),60)){
    $msg = "OH and S Questionnaire Signed submitted";
    header("Location:error.php?error=$msg");
}
$client = getClientNameByClientId($mysqli,base64_decode($_REQUEST['clientId']));
$position = getPositionByPositionId($mysqli,base64_decode($_REQUEST['positionId']));
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
        <h3>OCCUPATIONAL HEALTH AND SAFETY QUESTIONNAIRE</h3>
        <div id="msg" class="msg error"></div>
        <form id="frmOHSForm" name="frmOHSForm" method="post" class="smart-form">
            <div class="row">
                <section class="col col sm-6">
                    <br><br>
                    <table class="table table-responsive" style="width: 100%">
                        <tbody>
                        <tr>
                            <td><b>Candidate Name: </b><input type="text" name="candidate_name" id="candidate_name" value="<?php echo getCandidateFullName($mysqli,base64_decode($_REQUEST['id'])); ?>" class="form-control" readonly/></td>
                            <td>
                                <input type="hidden" name="clientId" id="clientId" value="<?php echo base64_decode($_REQUEST['clientId']);?>">
                                <input type="hidden" name="stateId" id="stateId" value="<?php echo base64_decode($_REQUEST['stateId']);?>">
                                <input type="hidden" name="deptId" id="deptId" value="<?php echo base64_decode($_REQUEST['deptId']);?>">
                                <input type="hidden" name="positionId" id="positionId" value="<?php echo base64_decode($_REQUEST['positionId']);?>">
                            </td>
                        </tr>
                        <tr>
                            <td><b>Company: </b><input type="text" name="company_name" id="company_name" value="<?php echo $client; ?>" class="form-control" readonly/></td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td><b>Position: </b><input type="text" name="position" id="position" value="<?php echo $position; ?>" class="form-control" readonly/></td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>FIRST DAY PROCESS</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">1.	Do you remember what process you went through on your first day</td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <ul>
                                    <li>What sort of induction/training did you receive? </li>
                                    <textarea name="induction" id="induction" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea>
                                </ul>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <ul>
                                    <li>What job tasks were you required to do on your first day?( please write as much details as you can) </li>
                                    <textarea name="first_day_tasks" id="first_day_tasks" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea>
                                </ul>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <ul>
                                    <li>What sort of training do you receive if you move to a different section/role/task?</li>
                                    <textarea name="training_info" id="training_info" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea>
                                </ul>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <ul>
                                    <li>Do you feel like you were given adequate information? </li>
                                    <input type="radio" name="adequate" id="adequate1" value="YES" class="adequate form-check-input"/>
                                    <label for="adequate1">YES</label><br>
                                    <input type="radio" name="adequate" id="adequate2" value="NO" class="adequate form-check-input"/>
                                    <label for="adequate2">NO</label><br>
                                    (If NO please explain)
                                    <textarea name="adequate_info" id="adequate_info" cols="20" rows="5" class="form-control" style="display: none" onpaste="return false;" ondrop="return false;" autocomplete="off"></textarea>
                                </ul>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2"><b>WORK ENVIRONMENT</b></td>
                        </tr>
                        <tr>
                            <td colspan="2">2.	Describe the environment you are working in</td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <ul>
                                    <li>How are they treated by co-workers/supervisors? </li>
                                    <textarea name="co_worker" id="co_worker" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea>
                                </ul>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <ul>
                                    <li>Work culture? </li>
                                    <textarea name="work_culture" id="work_culture" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea>
                                </ul>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <ul>
                                    <li>Physical environment? </li>
                                    <textarea name="physical_env" id="physical_env" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" required></textarea>
                                </ul>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="">3.	Have you noticed anything or been asked to do anything that you don’t think is safe?
                                <br>
                                <input type="radio" name="safety" id="safety1" value="YES" class="safety form-check-input"/>
                                <label for="safety1">YES</label><br>
                                <input type="radio" name="safety" id="safety2" value="NO" class="safety form-check-input"/>
                                <label for="safety2">NO</label><br>
                                (If YES please explain)
                                <textarea name="safety_info" id="safety_info" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" style="display: none"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="">4.	If you saw something that you believe is unsafe, what would you do?
                                <br>
                                <textarea name="unsafe" id="unsafe" cols="20" rows="5" class="form-control" required></textarea>
                                <br>
                                <ul>
                                    <li>Do you think your concern would be taken seriously?</li>
                                    <input type="radio" name="concern" id="concern1" value="YES" class="concern form-check-input"/>
                                    <label for="concern1">YES</label><br>
                                    <input type="radio" name="concern" id="concern2" value="NO" class="concern form-check-input"/>
                                    <label for="concern2">NO</label><br>
                                    (If NO please explain)
                                    <textarea name="concern_info" id="concern_info" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" style="display: none"></textarea>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="">5.	On a scale of 1 to 10 how safety conscious do you think this company is?
                                <br>
                                ( 1 being unsafe and 10 being extremely safe)
                                <br>
                                    <input type="radio" name="safety_scale" id="safety_scale1" value="1" class="form-check-input"/>
                                    <label for="safety_scale1">1</label>&nbsp;
                                    <input type="radio" name="safety_scale" id="safety_scale2" value="2" class="form-check-input"/>
                                    <label for="safety_scale2">2</label>&nbsp;
                                    <input type="radio" name="safety_scale" id="safety_scale3" value="3" class="form-check-input"/>
                                    <label for="safety_scale3">3</label>&nbsp;
                                    <input type="radio" name="safety_scale" id="safety_scale4" value="4" class="form-check-input"/>
                                    <label for="safety_scale4">4</label>&nbsp;
                                    <input type="radio" name="safety_scale" id="safety_scale5" value="5" class="form-check-input"/>
                                    <label for="safety_scale5">5</label>&nbsp;
                                    <input type="radio" name="safety_scale" id="safety_scale6" value="6" class="form-check-input"/>
                                    <label for="safety_scale6">6</label>&nbsp;
                                    <input type="radio" name="safety_scale" id="safety_scale7" value="7" class="form-check-input"/>
                                    <label for="safety_scale7">7</label>&nbsp;
                                    <input type="radio" name="safety_scale" id="safety_scale8" value="8" class="form-check-input"/>
                                    <label for="safety_scale8">8</label>&nbsp;
                                    <input type="radio" name="safety_scale" id="safety_scale9" value="9" class="form-check-input"/>
                                    <label for="safety_scale9">9</label>&nbsp;
                                    <input type="radio" name="safety_scale" id="safety_scale10" value="10" class="form-check-input"/>
                                    <label for="safety_scale10">10</label>&nbsp;
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2"><b>SUPERVISION</b></td>
                        </tr>
                        <tr>
                            <td colspan="">6.	What sort of supervision do you receive?
                                <ul>
                                    <li>Do you see your supervisor often? </li>
                                    <input type="radio" name="supervision" id="supervision1" value="YES" class="supervision form-check-input"/>
                                    <label for="supervision1">YES</label><br>
                                    <input type="radio" name="supervision" id="supervision2" value="NO" class="supervision form-check-input"/>
                                    <label for="supervision2">NO</label><br>
                                    (If NO please explain)
                                    <textarea name="supervision_info" id="supervision_info" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" style="display: none"></textarea>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="">7.	Do you have experienced workers around to help you if required?
                                <br>
                                    <input type="radio" name="experienced_workers" id="experienced_workers1" value="YES" class="experienced_workers form-check-input"/>
                                    <label for="experienced_workers1">YES</label><br>
                                    <input type="radio" name="experienced_workers" id="experienced_workers2" value="NO" class="experienced_workers form-check-input"/>
                                    <label for="experienced_workers2">NO</label><br>
                                    (If NO please explain)
                                    <textarea name="experienced_workers_info" id="experienced_workers_info" cols="20" rows="5" class="form-control" onpaste="return false;" ondrop="return false;" autocomplete="off" style="display: none"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="">8.	Is there any concerns you would like to discuss with your consultant?
                                <br>
                                <input type="radio" name="discuss_concern" id="discuss_concern1" value="YES" class="form-check-input"/>
                                <label for="discuss_concern1">YES</label><br>
                                <input type="radio" name="discuss_concern" id="discuss_concern2" value="NO" class="form-check-input"/>
                                <label for="discuss_concern2">NO</label><br>
                                <div class="consultant_discuss"></div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type="hidden" name="id" id="id" value="<?php echo base64_decode($_REQUEST['id']); ?>"/>
                                <input type="hidden" name="cons_id" id="cons_id" value="<?php echo base64_decode($_REQUEST['cons_id']); ?>"/>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>
                </section>

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
                    <button id="ohsBtn" class="ohsBtn btn-success btn-lg">Submit</button>
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
        $(document).on('click','.safety', function (evt) {
            if($("input[name='safety']:checked").val() === 'YES'){
                $('#safety_info').show();
            }else{
                $('#safety_info').html('');
                $('#safety_info').hide();
            }
        });
        $(document).on('click','.adequate', function (evt) {
            if($("input[name='adequate']:checked").val() === 'NO'){
                $('#adequate_info').show();
            }else{
                $('#adequate_info').html('');
                $('#adequate_info').hide();
            }
        });
        $(document).on('click','.concern', function (evt) {
            if($("input[name='concern']:checked").val() === 'NO'){
                $('#concern_info').show();
            }else{
                $('#concern_info').html('');
                $('#concern_info').hide();
            }
        });
        $(document).on('click','.supervision', function (evt) {
            if($("input[name='supervision']:checked").val() === 'NO'){
                $('#supervision_info').show();
            }else{
                $('#supervision_info').html('');
                $('#supervision_info').hide();
            }
        });
        $(document).on('click','.experienced_workers', function (evt) {
            if($("input[name='experienced_workers']:checked").val() === 'NO'){
                $('#experienced_workers_info').show();
            }else{
                $('#experienced_workers_info').html('');
                $('#experienced_workers_info').hide();
            }
        });
        $("input[name='discuss_concern']").change(function () {
            if ($("input[name='discuss_concern']:checked").val() == 'YES') {
                $('.consultant_discuss').html('Your consultant will contact you for a confidential discussion');
            }
        });
        jQuery.validator.addMethod("alphanumeric", function(value, element) {
            return this.optional(element) || /^[\w.]+$/i.test(value);
        }, "Letters, numbers, and underscores only please");

        $(document).on('click','#ohsBtn',function (e) {
            var errorClass = 'invalid';
            var errorElement = 'div';
            var frmOHSForm = $('#frmOHSForm').validate({
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
                    candidate_name:{
                        required:true
                    },
                    company_name:{
                        required:true
                    },
                    position:{
                        required:true
                    },
                    induction:{
                        required:true
                    },
                    first_day_tasks:{
                        required:true
                    },
                    adequate:{
                        required:true
                    },
                    adequate_info:{
                        required: function (element) {
                            return $("input[name='adequate']:checked").val() == 'NO';
                        }
                    },
                    co_worker:{
                        required:true
                    },
                    work_culture:{
                        required:true
                    },
                    physical_env:{
                        required:true
                    },
                    safety:{
                        required:true
                    },
                    safety_info:{
                        required: function (element) {
                            return $("input[name='safety']:checked").val() == 'YES';
                        }
                    },
                    concern:{
                        required:true
                    },
                    unsafe:{
                        required:true
                    },
                    concern_info:{
                        required: function (element) {
                            return $("input[name='concern']:checked").val() == 'NO';
                        }
                    },
                    safety_scale:{
                        required:true
                    },
                    training_info:{
                        required:true
                    },
                    supervision:{
                        required:true
                    },
                    supervision_info:{
                        required: function (element) {
                            return $("input[name='supervision']:checked").val() == 'NO';
                        }
                    },
                    experienced_workers:{
                        required:true
                    },
                    experienced_workers_info:{
                        required: function (element) {
                            return $("input[name='experienced_workers']:checked").val() == 'NO';
                        }
                    },
                    discuss_concern:{
                        required:true
                    }
                },
                messages:{
                    induction:{
                        required: "Please answer this question"
                    },
                    first_day_tasks:{
                        require: "Please answer this question"
                    },
                    training_info:{
                        required: "Please answer this question"
                    },
                    co_worker:{
                        required: "Please answer this question"
                    },
                    work_culture:{
                        required: "Please answer this question"
                    },
                    physical_env:{
                        required: "Please answer this question"
                    }
                },
                submitHandler: function (form) {
                    if ($sigdiv.jSignature('getData', 'native').length == 0) {
                        alert('Please Enter Signature!');
                    }else{
                        var stateId = $.base64.encode($('#stateId').val());
                        var clientId = $.base64.encode($('#clientId').val());
                        var positionId = $.base64.encode($('#positionId').val());
                        var deptId = $.base64.encode($('#deptId').val());
                        var candidate_name = $.base64.encode($('#candidate_name').val());
                        var company_name  = $.base64.encode($('#company_name').val());
                        var position  = $.base64.encode($('#position').val());
                        var induction = $.base64.encode($('textarea#induction').val());
                        var first_day_tasks = $.base64.encode($('textarea#first_day_tasks').val());
                        var adequate = $.base64.encode($('input[name=adequate]:checked', '#frmOHSForm').val());
                        var adequate_info = $.base64.encode($('textarea#adequate_info').val());
                        var co_worker = $.base64.encode($('textarea#co_worker').val());
                        var work_culture = $.base64.encode($('textarea#work_culture').val());
                        var physical_env = $.base64.encode($('textarea#physical_env').val());
                        var safety = $.base64.encode($('input[name=safety]:checked', '#frmOHSForm').val());
                        var safety_info = $.base64.encode($('textarea#safety_info').val());
                        var concern = $.base64.encode($('input[name=concern]:checked', '#frmOHSForm').val());
                        var unsafe = $.base64.encode($('textarea#unsafe').val());
                        var concern_info = $.base64.encode($('textarea#concern_info').val());
                        var safety_scale = $.base64.encode($('input[name=safety_scale]:checked', '#frmOHSForm').val());
                        var training_info = $.base64.encode($('textarea#training_info').val());
                        var supervision = $.base64.encode($('input[name=supervision]:checked', '#frmOHSForm').val());
                        var supervision_info = $.base64.encode($('textarea#supervision_info').val());
                        var experienced_workers = $.base64.encode($('input[name=experienced_workers]:checked', '#frmOHSForm').val());
                        var experienced_workers_info = $.base64.encode($('textarea#experienced_workers_info').val());
                        var discuss_concern = $.base64.encode($('input[name=discuss_concern]:checked', '#frmOHSForm').val());
                        var id = $.base64.encode($('#id').val());
                        var cons_id = $.base64.encode($('#cons_id').val());
                        var imageSrc = $("#signatureImg").attr('src');
                        $.ajax({
                            url:"./processOHS.php",
                            type:'POST',
                            dataType:'text',
                            data:{
                                stateId:stateId,
                                clientId:clientId,
                                deptId:deptId,
                                positionId:positionId,
                                candidate_name:candidate_name,
                                company_name:company_name,
                                position:position,
                                induction:induction,
                                first_day_tasks:first_day_tasks,
                                adequate:adequate,
                                adequate_info:adequate_info,
                                co_worker:co_worker,
                                work_culture:work_culture,
                                physical_env:physical_env,
                                safety:safety,
                                safety_info:safety_info,
                                concern:concern,
                                unsafe:unsafe,
                                concern_info:concern_info,
                                safety_scale:safety_scale,
                                training_info:training_info,
                                supervision:supervision,
                                supervision_info:supervision_info,
                                experienced_workers:experienced_workers,
                                experienced_workers_info:experienced_workers_info,
                                discuss_concern:discuss_concern,
                                id:id,
                                cons_id:cons_id,
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
                                    $('.msg').html('!! Submission Unsuccessful or Already submitted');
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